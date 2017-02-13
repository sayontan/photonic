<?php
/**
 * Processor for 500px. This extends the Photonic_Processor class and defines methods local to 500px.
 *
 * @package Photonic
 * @subpackage Extensions
 */

class Photonic_500px_Processor extends Photonic_Processor {
	function __construct() {
		parent::__construct();
		global $photonic_500px_api_key, $photonic_500px_api_secret;
		$this->api_key = $photonic_500px_api_key;
		$this->api_secret = $photonic_500px_api_secret;
		$this->provider = '500px';
	}

	/**
	 * A very flexible function to display photos from 500px. This makes use of the 500px API, hence it requires the user's Consumer API key.
	 * The API key is defined in the options. The function makes use of one API call:
	 *  <a href='http://developer.500px.com/docs/photos-index'>GET Photos</a> - for retrieving photos based on search critiera
	 *
	 * The following short-code parameters are supported:
	 * - feature: popular | upcoming | editors | fresh_today | fresh_yesterday | fresh_week | user | user_friends | user_favorites
	 * - user_id, username: Any one of them is required if feature = user | user_friends | user_favorites
	 * - only: 	Abstract | Animals | Black and White | Celebrities | City and Architecture | Commercial | Concert | Family | Fashion | Street | Travel |
	 * 			Film | Fine Art | Food | Journalism | Landscapes | Macro | Nature | Nude | People | Performing Arts | Sport | Still Life | Underwater
	 * - rpp: Number of photos
	 * - thumb_size: Size of the thumbnail. Can be 1 | 2 | 3, which correspond to 75 &times; 75 px, 140 &times; 140 px and 280 &times; 280 px respectively.
	 * - main_size: Size of the opened main photo. Can be 3 | 4, which correspond to 280 &times; 280 px and the full size respectively.
	 * - sort: created_at | rating | times_viewed | taken_at
	 *
	 * @param array $attr
	 * @return string|void
	 */
	function get_gallery_images($attr = array()) {
		global $photonic_500px_api_key, $photonic_500px_position;

		$attr = array_merge(array(
			'style' => 'default',
	//		'feature' => ''  // popular | upcoming | editors | fresh_today | fresh_yesterday | fresh_week
			// Defaults from WP ...
			'columns'    => 'auto',
			'thumb_size'       => '1',
			'main_size'       => '4',
		), $attr);
		extract($attr);

		if (!isset($photonic_500px_api_key) || trim($photonic_500px_api_key) == '') {
			return __("500px Consumer Key not defined", 'photonic');
		}

		$user_feature = false;
		$query_url = 'https://api.500px.com/v1/photos?consumer_key='.$photonic_500px_api_key;
		if (isset($feature) && trim($feature) != '') {
			$feature = esc_html(trim($feature));
			$query_url .= '&feature='.$feature;
			if (in_array(trim($feature), array('user', 'user_friends', 'user_favorites'))) {
				$user_feature = true;
			}
		}

		$user_set = false;
		if (isset($user_id) && trim($user_id) != '') {
			$user_id = trim($user_id);
			$query_url .= '&user_id='.$user_id;
			$user_set = true;
		}
		else if (isset($username) && trim($username) != '') {
			$username = trim($username);
			$query_url .= '&username='.$username;
			$user_set = true;
		}

		if ($user_feature && !$user_set) {
			return __("A user-specific feature has been requested, but the username or user_id is missing", 'photonic');
		}

		if (isset($only) && trim($only) != '') {
			$only = urlencode(trim($only));
			$query_url .= '&only='.trim($only);
		}

		if (isset($rpp) && trim($rpp) != '') {
			$rpp = trim($rpp);
			$query_url .= '&rpp='.trim($rpp);
		}

		if (isset($sort) && trim($sort) != '') {
			$sort = trim($sort);
			$query_url .= '&sort='.trim($sort);
		}

		// Allow users to define additional query parameters
		$query_url = apply_filters('photonic_500px_query', $query_url, $attr);

		global $photonic_500px_oauth_done;
		if ($photonic_500px_oauth_done) {
			$end_point = Photonic_Processor::get_normalized_http_url($query_url);
			if (strstr($query_url, $end_point) > -1) {
				$params = substr($query_url, strlen($end_point));
				if (strlen($params) > 1) {
					$params = substr($params, 1);
				}
				$params = Photonic_Processor::parse_parameters($params);
				$signed_args = $this->sign_call($end_point, 'GET', $params);
				$query_url = $end_point.'?'.Photonic_Processor::build_query($signed_args);
			}
		}

		$ret = '';

		global $photonic_500px_login_shown, $photonic_500px_allow_oauth, $photonic_500px_oauth_done;
		if (!$photonic_500px_login_shown && $photonic_500px_allow_oauth && is_single() && !$photonic_500px_oauth_done) {
			$post_id = get_the_ID();
			$ret .= $this->get_login_box($post_id);
			$photonic_500px_login_shown = true;
		}

		$photonic_500px_position++;
		$ret .= "<div class='photonic-500px-stream' id='photonic-500px-stream-$photonic_500px_position'>";
		$ret .= $this->process_response($query_url, $thumb_size, $main_size, $columns);
		$ret .= "</div>";
		return $ret;
	}

	function process_response($url, $thumb_size = '1', $main_size = '4', $columns = 'auto') {
		global $photonic_slideshow_library, $photonic_500px_position, $photonic_500px_photos_per_row_constraint, $photonic_500px_photos_constrain_by_count, $photonic_500px_disable_title_link, $photonic_500px_photo_title_display;

		$response = wp_remote_request($url, array('sslverify' => false));

		if (is_wp_error($response)) {
			return "";
		}
		else if ($response['response']['code'] != 200 && $response['response']['code'] != '200') { // Something went wrong
			return "<!-- Currently there is an error with the server. Code: ".$response['response']['code'].", Message: ".$response['response']['message']."-->";
		}
		else {
			$content = $response['body'];
			$content = json_decode($content);
			$photos = $content->photos;
			$ret = "<ul>";
			if (!isset($columns)) {
				$columns = 'auto';
			}
			if ($columns == 'auto') {
				if ($photonic_500px_photos_per_row_constraint == 'padding') {
					$pad_class = 'photonic-pad-photos';
				}
				else {
					$pad_class = 'photonic-gallery-'.$photonic_500px_photos_constrain_by_count.'c';
				}
			}
			else {
				$pad_class = 'photonic-gallery-'.$columns.'c';
			}

			foreach ($photos as $photo) {
				$image = $photo->image_url;
				$first = substr($image, 0, strrpos($image, '/'));
				$last = substr($image, strrpos($image, '/'));
				$extension = substr($last, stripos($last, '.'));
				$ret .= "<li class='photonic-500px-image $pad_class'>";
				$a_start = $photonic_500px_disable_title_link == 'on' ? "" : "<a href=\"http://500px.com/photo/".$photo->id."\">";
				$a_end = $photonic_500px_disable_title_link == 'on' ? "" : "</a>";
				if ($photonic_slideshow_library == 'prettyphoto') {
					$rel = "photonic-prettyPhoto[photonic-500px-stream-$photonic_500px_position]";
				}
				else {
					$rel = "photonic-500px-stream-$photonic_500px_position";
				}
				$ret .= "<a href='$first/$main_size$extension' class='launch-gallery-$photonic_slideshow_library $photonic_slideshow_library' rel='$rel' title='$a_start".esc_attr($photo->name)."$a_end'>";
				$ret .= "<img src='$first/$thumb_size$extension' alt='".esc_attr($photo->name)."' />";
				$ret .= "</a>";

				if ($photonic_500px_photo_title_display == 'below') {
					$ret .= "<span class='photonic-photo-title'>".$photo->name."</span>";
				}

				$ret .= "</li>";
			}
			if ($ret != "<ul>") {
				$ret .= "</ul>";
			}
			else {
				$ret = "";
			}
			return $ret;
		}
	}

	/**
	 * Access Token URL
	 *
	 * @return string
	 */
	public function access_token_URL() {
		return 'https://api.500px.com/v1/oauth/access_token';
	}

	/**
	 * Authenticate URL
	 *
	 * @return string
	 */
	public function authenticate_URL() {
		return 'https://api.500px.com/v1/oauth/authorize';
	}

	/**
	 * Authorize URL
	 *
	 * @return string
	 */
	public function authorize_URL() {
		return 'https://api.500px.com/v1/oauth/authorize';
	}

	/**
	 * Request Token URL
	 *
	 * @return string
	 */
	public function request_token_URL() {
		return 'https://api.500px.com/v1/oauth/request_token';
	}

	public function end_point() {
		return 'https://api.500px.com/v1/photos';
	}

	function parse_token($response) {
		$body = $response['body'];
		$token = Photonic_Processor::parse_parameters($body);
		return $token;
	}

	public function check_access_token_method() {
		// TODO: Implement check_access_token_method() method.
	}

	/**
	 * Method to validate that the stored token is indeed authenticated.
	 *
	 * @param $request_token
	 * @return array|WP_Error
	 */
	function check_access_token($request_token) {
		global $photonic_500px_api_key;
		$signed_parameters = $this->sign_call('https://api.500px.com/v1/users', 'GET', array('consumer_key' => $photonic_500px_api_key));

		$end_point = $this->end_point();
		$end_point .= '?'.Photonic_Processor::build_query($signed_parameters);

		$response = Photonic::http($end_point, 'GET', null);
		return $response;
	}

	public function is_access_token_valid($response) {
		$response = $response['response'];

		if ($response['code'] == 200) {
			return true;
		}
		return false;
	}
}