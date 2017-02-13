<?php
class Photonic_SmugMug_Processor extends Photonic_Processor {
	function __construct() {
		parent::__construct();
		global $photonic_smug_api_key, $photonic_smug_api_secret;
		$this->api_key = $photonic_smug_api_key;
		$this->api_secret = $photonic_smug_api_secret;
		$this->provider = 'smug';
	}

	/**
	 * The main gallery builder for SmugMug. SmugMug takes the following parameters:
	 * 	- nick_name = The nickname of the user. This is mandatory for SmugMug.
	 * 	- view = tree | albums | album | images. If left blank, a value of 'tree' is assumed.
	 * 	- columns = The number of columns to show the output in
	 *	- album = The album slug, which is the AlbumID_AlbumKey. Either this parameter is needed or the individual album_id and album_key are needed if view='album' or 'images'.
	 * 	- album_id, album_key = The ID and key of the album. Either both of these are needed, or the combination "album" is needed if view='album' or 'images'.
	 *	- empty = true | false. If true, empty albums and categories are returned in the response, otherwise they are ignored.
	 *	- columns = The number of columns to return the output in. Optional.
	 *
	 * @param array $attr
	 * @return string|void
	 */
	function get_gallery_images($attr = array()) {
		global $photonic_smug_api_key, $photonic_smug_thumb_size, $photonic_smug_main_size;

		if (!isset($photonic_smug_api_key) || trim($photonic_smug_api_key) == '') {
			return __("SmugMug API Key not defined", 'photonic');
		}

		$attr = array_merge(array(
			'style' => 'default',
			'columns'    => 'auto',
			'empty' => 'false',
		), $attr);
		extract($attr);

		$args = array(
			'APIKey' => $photonic_smug_api_key,
			'Empty' => $empty,
		);

		$chained_calls = array();
		if (isset($view)) {
			$view = trim($view);
		}
		else {
			$view = 'tree';
		}

		switch ($view) {
			case 'albums':
				$chained_calls[] = 'smugmug.albums.get';
				$args['Extras'] = 'URL,ImageCount,Passworded,Password,NiceName';
				break;

			case 'album':
			case 'images':
				$chained_calls[] = 'smugmug.albums.getInfo';
				$chained_calls[] = 'smugmug.images.get';

				if (isset($album_id) && trim($album_id) != '' && isset($album_key) && trim($album_key) != '') {
					$args['AlbumID'] = $album_id;
					$args['AlbumKey'] = $album_key;
					$args['Extras'] = "{$photonic_smug_thumb_size}URL,{$photonic_smug_main_size}URL,Caption,Title,Passworded,Password";
				}
				else if (isset($album) && trim($album) != '') {
					$args['AlbumID'] = substr($album, 0, stripos($album, '_'));
					$args['AlbumKey'] = substr($album, stripos($album, '_') + 1);
					$args['Extras'] = "{$photonic_smug_thumb_size}URL,{$photonic_smug_main_size}URL,Caption,Title,Passworded,Password";
				}

				if (isset($password) && trim($password) != '') {
					$args['Password'] = $password;
				}
				break;

			case 'tree':
			default:
				$chained_calls[] = 'smugmug.users.getTree';
				$args['Extras'] = "URL,ImageCount,Passworded";
				break;
		}

		if ($view == 'tree' || $view == 'albums') {
			if (!isset($nick_name) || (isset($nick_name) && trim($nick_name) == '')) {
				return "";
			}
		}

		if (isset($nick_name) && trim($nick_name) != '') {
			$args['NickName'] = $nick_name;
		}

		$ret = '';
		global $photonic_smug_login_shown, $photonic_smug_allow_oauth, $photonic_smug_oauth_done;
		if (!$photonic_smug_login_shown && $photonic_smug_allow_oauth && is_single() && !$photonic_smug_oauth_done) {
			$post_id = get_the_ID();
			$ret .= $this->get_login_box($post_id);
			$photonic_smug_login_shown = true;
		}

		return $ret.$this->make_chained_calls($chained_calls, $args, $attr);
	}

	/**
	 * Runs a sequence of web-service calls to get information. Most often a single web-service call with the "Extras" parameter suffices for SmugMug.
	 * But there are some scenarios, e.g. clicking on an album to get a popup of all images in that album, where you need to chain the calls for the header.
	 *
	 * @param $chained_calls
	 * @param $smug_args
	 * @param $shortcode_attr
	 * @return string
	 */
	function make_chained_calls($chained_calls, $smug_args, $shortcode_attr) {
		global $photonic_smug_position;

		if (is_array($chained_calls) && count($chained_calls) > 0) {
			$photonic_smug_position++;
			extract($shortcode_attr);

			$ret = '';
			global $photonic_smug_oauth_done;
			$passworded = false;
			foreach ($chained_calls as $call) {
				$smug_args['method'] = $call;
				if ($photonic_smug_oauth_done) {
					$signed_args = $this->sign_call('https://secure.smugmug.com/services/api/json/1.3.0/', 'POST', $smug_args);
					$response = Photonic::http('https://secure.smugmug.com/services/api/json/1.3.0/', 'POST', $signed_args);
				}
				else {
					$response = Photonic::http('https://secure.smugmug.com/services/api/json/1.3.0/', 'POST', $smug_args);
				}

				if ($call == 'smugmug.albums.get') {
					$body = $response['body'];
					$body = json_decode($body);
					if ($body->stat == 'ok') {
						$albums = $body->Albums;
						if (is_array($albums) && count($albums) > 0) {
							$ret .= "<div class='photonic-smug-stream' id='photonic-smug-stream-$photonic_smug_position'>";
							$ret .= $this->process_albums($albums, $columns);
							$ret .= "</div>";
						}
					}
				}
				else if ($call == 'smugmug.albums.getInfo') {
					$body = $response['body'];
					$body = json_decode($body);
					if ($body->stat == 'ok') {
						$album = $body->Album;
						if (isset($album->Passworded) && $album->Passworded && !isset($album->Password) && !isset($signed_args['Password'])) {
							$passworded = true;
						}
						$rand = rand(1000, 9999);
						$insert = '';
						$insert .= "<div class='photonic-smug-stream'>";
						$insert .= "<div class='photonic-smug-album'>";
						$insert .= "<a class='photonic-header-thumb photonic-smug-album-solo-thumb' href='{$album->URL}'><img class='random-image' src='https://secure.smugmug.com/photos/random.mg?AlbumID={$album->id}&AlbumKey={$album->Key}&Size=75x75&rand=$rand' /></a>";
						$insert .= "<div class='photonic-header-details photonic-smug-album-details'>";
						$insert .= "<div class='photonic-header-title photonic-smug-album-title'>";
						$insert .= "<a href='{$album->URL}'>".$album->Title."</a>";
						$insert .= "</div>";
						$insert .= "<span class='photonic-header-info photonic-set-pop-info'>".sprintf(__('%s photos', 'photonic'), $album->ImageCount)."</span>";
						$insert .= "</div>";
						$insert .= "</div>";
						$insert .= "</div>";
						if (isset($shortcode_attr['display']) && $shortcode_attr['display'] == 'popup') {
							// Do nothing. We will insert this into the popup.
						}
						else {
							$ret .= $insert;
						}
					}
					else if ($body->stat == 'fail' && $body->code == 31) {
						$passworded = false;
					}
				}
				else if ($call == 'smugmug.images.get') {
					if (!$passworded) {
						if (isset($insert)) {
							$ret .= $this->process_images($response, $columns, $shortcode_attr, $insert);
						}
						else {
							$ret .= $this->process_images($response, $columns, $shortcode_attr);
						}
					}
				}
				else if ($call == 'smugmug.users.getTree') {
					$body = $response['body'];
					$body = json_decode($body);
					if ($body->stat == 'ok') {
						$categories = $body->Categories;
						if (is_array($categories) && count($categories) > 0) {
							$ret .= "<ul class='photonic-tree'>";
							foreach ($categories as $category) {
								if (isset($category->Albums)) {
									$albums = $category->Albums;
									$ret .= "<li>";
									$ret .= "<div class='photonic-smug-category'><span class='photonic-header-title photonic-category-title'>{$category->Name}</span></div>";
									$ret .= $this->process_albums($albums, $columns);
									$ret .= "</li>";
								}

								if (isset($category->SubCategories)) {
									$sub_categories = $category->SubCategories;
									$ret .= "<li>";
									if (is_array($sub_categories) && count($sub_categories) > 0) {
										$ret .= "<ul class='photonic-sub-tree'>";
										foreach ($sub_categories as $sub_category) {
											$albums = $sub_category->Albums;
											$ret .= "<li>";
											$ret .= "<div class='photonic-smug-sub-category'><span class='photonic-header-title photonic-sub-category-title'>{$sub_category->Name}</span></div>";
											$ret .= $this->process_albums($albums, $columns);
											$ret .= "</li>";
										}
										$ret .= "</ul>";
									}
									$ret .= "</li>";
								}
							}
							$ret .= "</ul>";
						}
					}
				}
			}
			return $ret;
		}
		return '';
	}

	/**
	 * Parse an array of album objects returned by the SmugMug API, then return an appropriate response. For every album a random thumbnail
	 * is generated using a call to https://secure.smugmug.com/photos/random.mg, because SmugMug doesn't return a thumbnail for an album.
	 *
	 * @param $albums
	 * @param $columns
	 * @return string
	 */
	function process_albums($albums, $columns) {
		global $photonic_smug_position, $photonic_smug_albums_album_per_row_constraint, $photonic_smug_albums_album_constrain_by_count, $photonic_smug_thumb_size, $photonic_smug_albums_album_title_display, $photonic_smug_hide_albums_album_photos_count_display;
		if (is_array($albums) && count($albums) > 0) {
			if ($columns == 'auto') {
				if ($photonic_smug_albums_album_per_row_constraint == 'padding') {
					$pad_class = 'photonic-pad-albums';
				}
				else {
					$pad_class = 'photonic-gallery-'.$photonic_smug_albums_album_constrain_by_count.'c';
				}
			}
			else {
				$pad_class = 'photonic-gallery-'.$columns.'c';
			}

			$ret = "<ul>";
			$rand = rand(1000, 9999);
			foreach ($albums as $album) {
				$album_li = '';
				if ($album->ImageCount != 0) {
					if (isset($album->Passworded) && $album->Passworded && !isset($album->Password)) {
						$passworded = 'photonic-smug-passworded';
					}
					else {
						$passworded = '';
					}

					$album_li .= "<li class='photonic-smug-image photonic-smug-album-thumb $pad_class' id='photonic-smug-album-{$album->id}-{$album->Key}-$photonic_smug_position'>";
					$album_li .= "<a href='{$album->URL}' title='" . esc_attr($album->Title) . "' class='photonic-smug-album-thumb {$passworded}' id='photonic-smug-album-thumb-{$album->id}-{$album->Key}-$photonic_smug_position'>";
					$album_li .= "<img class='random-image' src='https://secure.smugmug.com/photos/random.mg?AlbumID={$album->id}&AlbumKey={$album->Key}&Size=$photonic_smug_thumb_size&rand=$rand' alt='" . esc_attr($album->Title) . "' />";
					$album_li .= "</a>";

					if ($photonic_smug_albums_album_title_display == 'below') {
						$album_li .= "<span class='photonic-album-title'><a href='{$album->URL}' title='" . esc_attr($album->Title) . "' >".esc_attr($album->Title)."</a></span>";
						if ('on' != $photonic_smug_hide_albums_album_photos_count_display) {
							$album_li .= "<span class='photonic-album-photo-count'>".sprintf(__('%s photos', 'photonic'), $album->ImageCount)."</span>";
						}
					}

					$album_li .= "</li>";
				}

				if ($album_li != '') {
					$ret .= $album_li;
				}
			}
			$ret .= "</ul>";

			if ($ret == '<ul></ul>') {
				$ret = '';
			}
			return $ret;
		}
		return '';
	}

	/**
	 * Takes a response, then parses out the images from that response and returns a set of thumbnails for it. This method handles
	 * both, in-page images as well as images in a popup panel.
	 *
	 * @param $response
	 * @param string $columns
	 * @param array $attr
	 * @param null $insert
	 * @return string
	 */
	function process_images($response, $columns = 'auto', $attr = array(), $insert = null) {
		global $photonic_smug_photos_per_row_constraint, $photonic_smug_photos_constrain_by_count, $photonic_smug_position, $photonic_slideshow_library;
		global $photonic_smug_photos_pop_per_row_constraint, $photonic_smug_photos_pop_constrain_by_count, $photonic_gallery_panel_items, $photonic_smug_photo_pop_title_display;
		$body = $response['body'];
		$body = json_decode($body);
		if ($body->stat == 'ok') {
			$album = $body->Album;
			$images = $album->Images;
			$ret = "";
			if (is_array($images) && count($images) > 0) {
				$ul_class = '';
				if (isset($attr['display']) && $attr['display'] == 'popup') {
					$ret .= "<div class='photonic-smug-panel photonic-panel'>";
					$ul_class = "class='slideshow-grid-panel lib-$photonic_slideshow_library'";
					$ret .= $insert;
					$ret .= "<div class='photonic-smug-panel-content photonic-panel-content'>";
				}
				else {
					$ret .= "<div class='photonic-smug-stream' id='photonic-smug-stream-$photonic_smug_position'>";
				}
				$ret .= "<ul $ul_class>";
				if (isset($attr['display']) && $attr['display'] == 'popup') {
					if ($photonic_smug_photos_pop_per_row_constraint == 'padding') {
						$a_pad_class = 'photonic-pad-photos';
					}
					else {
						$a_pad_class = 'photonic-gallery-'.$photonic_smug_photos_pop_constrain_by_count.'c';
					}
					if ($photonic_slideshow_library != 'none') {
						$library = 'launch-gallery-'.$photonic_slideshow_library.' '.$photonic_slideshow_library;
					}
					else {
						$library = '';
					}
					if ($photonic_slideshow_library != 'prettyphoto') {
						$rel = "rel='photonic-smug-stream-$photonic_smug_position'";
					}
					else if ($photonic_slideshow_library == 'prettyphoto') {
						if (isset($attr['panel']) && $attr['panel'] != null) {
							$panel = $attr['panel'];
							$rel = "rel='photonic-prettyPhoto[$panel]'";
						}
						else {
							$rel = "rel='photonic-prettyPhoto[photonic-smug-stream-$photonic_smug_position]'";
						}
					}
					else {
						$rel = '';
					}

					$count = 0;
					foreach ($images as $image) {
						$count++;
						if ($count % $photonic_gallery_panel_items == 1) {
							$ret .= "<li class='photonic-smug-image'>";
						}
						$ret .= $this->process_images_info($image, $rel, $a_pad_class, $library, 'popup');
						if ($count % $photonic_gallery_panel_items == 0 || $count == count($images)) {
							$ret .= "</li>";
						}
					}

					if ($photonic_smug_photo_pop_title_display == 'tooltip') {
						$ret .= "<script type='text/javascript'>\$j('.photonic-smug-panel a').each(function() { \$j(this).data('title', \$j(this).attr('title')); }); \$j('.photonic-smug-panel a').each(function() { if (!(\$j(this).parent().hasClass('photonic-header-title'))) { var iTitle = \$j(this).find('img').attr('alt'); \$j(this).tooltip({ bodyHandler: function() { return iTitle; }, showURL: false });}})</script>";
					}

					if ($photonic_slideshow_library == 'fancybox') {
						$ret .= "<script type='text/javascript'>\$j('a.launch-gallery-fancybox').each(function() { \$j(this).fancybox({ transitionIn:'elastic', transitionOut:'elastic',speedIn:600,speedOut:200,overlayShow:true,overlayOpacity:0.8,overlayColor:\"#000\",titleShow:Photonic_JS.fbox_show_title,titlePosition:Photonic_JS.fbox_title_position});});</script>";
					}
					else if ($photonic_slideshow_library == 'colorbox') {
						$ret .= "<script type='text/javascript'>\$j('a.launch-gallery-colorbox').each(function() { \$j(this).colorbox({ opacity: 0.8, maxWidth: '95%', maxHeight: '95%', slideshow: Photonic_JS.slideshow_mode, slideshowSpeed: Photonic_JS.slideshow_interval });});</script>";
					}
					else if ($photonic_slideshow_library == 'prettyphoto') {
						$ret .= "<script type='text/javascript'>\$j(\"a[rel^='photonic-prettyPhoto']\").prettyPhoto({ theme: Photonic_JS.pphoto_theme, autoplay_slideshow: Photonic_JS.slideshow_mode, slideshow: parseInt(Photonic_JS.slideshow_interval), show_title: false, social_tools: '', deeplinking: false });</script>";
					}
				}
				else {
					if (!isset($columns)) {
						$columns = 'auto';
					}
					if ($columns == 'auto') {
						if ($photonic_smug_photos_per_row_constraint == 'padding') {
							$pad_class = 'photonic-pad-photos';
						}
						else {
							$pad_class = 'photonic-gallery-'.$photonic_smug_photos_constrain_by_count.'c';
						}
					}
					else {
						$pad_class = 'photonic-gallery-'.$columns.'c';
					}
/*					if (isset($attr['max_images']) && trim($attr['max_images']) != '') {
						$max_images = trim($attr['max_images']);
						if (!Photonic::check_integer($max_images)) {
							unset($max_images);
						}
						else {
							$max_images = (int)$max_images;
						}
					}
					$counter = 0;*/
					foreach ($images as $image) {
/*						$counter++;
						if (isset($max_images) && $counter > $max_images) {
							break;
						}*/
						$ret .= "<li class='photonic-smug-image $pad_class'>".$this->process_images_info($image)."</li>";
					}
				}

				$ret .= "</ul>";
				if (isset($attr['display']) && $attr['display'] == 'popup') {
					$ret .= "</div>";
				}
				$ret .= "</div>";
				return $ret;
			}
		}
		return "";
	}

	/**
	 * Generates the markup for a single SmugMug image. This is used both, in-page as well as in a popup panel.
	 *
	 * @param $image
	 * @param null $rel
	 * @param string $a_pad_class
	 * @param null $library
	 * @param string $display
	 * @return string
	 */
	function process_images_info($image, $rel = null, $a_pad_class = '', $library = null, $display = 'page') {
		global $photonic_slideshow_library, $photonic_smug_position, $photonic_smug_photo_title_display, $photonic_smug_photo_pop_title_display, $photonic_smug_thumb_size, $photonic_smug_main_size;
		if ($rel == null) {
			if ($photonic_slideshow_library == 'prettyphoto') {
				$rel = "rel='photonic-prettyPhoto[photonic-smug-stream-$photonic_smug_position]'";
			}
			else {
				$rel = "rel='photonic-smug-stream-$photonic_smug_position'";
			}
		}
		if ($library == null) {
			$library = "launch-gallery-$photonic_slideshow_library $photonic_slideshow_library";
		}
		$caption = esc_attr($image->Caption);
		$thumb = "{$photonic_smug_thumb_size}URL";
		$main = "{$photonic_smug_main_size}URL";
		$ret = "<a href='{$image->{$main}}' title='".$caption."' class='$library $a_pad_class' $rel>";
		$ret .= "<img src='{$image->{$thumb}}' alt='".$caption."'/>";
		if ($display == 'page' && $photonic_smug_photo_title_display == 'below') {
			$ret .= "<span class='photonic-photo-title'>$caption</span>";
		}
		else if ($display == 'popup' && $photonic_smug_photo_pop_title_display == 'below') {
			$ret .= "<span class='photonic-photo-title'>$caption</span>";
		}
		$ret .= "</a>";
		return $ret;
	}

	/**
	 * If a SmugMug album thumbnail is being displayed on a page, clicking on the thumbnail should launch a popup displaying all
	 * album photos. This function handles the click event and the subsequent invocation of the popup.
	 *
	 * @return void
	 */
	function display_album() {
		$panel = $_POST['panel'];
		$panel = substr($panel, 26);
		$album_id = substr($panel, 0, strpos($panel, '-'));
		$album_key = substr($panel, strpos($panel, '-') + 1);
		$album_key = substr($album_key, 0, strpos($album_key, '-'));
		echo $this->get_gallery_images(array('album_id' => $album_id, 'album_key' => $album_key, 'view' => 'album', 'display' => 'popup', 'panel' => $panel));
		die();
	}

	/**
	 * Access Token URL
	 *
	 * @return string
	 */
	public function access_token_URL() {
		return 'smugmug.auth.getAccessToken';
	}

	/**
	 * Authenticate URL
	 *
	 * @return string
	 */
	public function authenticate_URL() {
		return 'http://api.smugmug.com/services/oauth/authorize.mg';
	}

	/**
	 * Authorize URL
	 *
	 * @return string
	 */
	public function authorize_URL() {
		return 'http://api.smugmug.com/services/oauth/authorize.mg';
	}

	/**
	 * Request Token URL
	 *
	 * @return string
	 */
	public function request_token_URL() {
		return 'smugmug.auth.getRequestToken';
	}

	public function end_point() {
		return 'https://secure.smugmug.com/services/api/json/1.3.0/';
	}

	function parse_token($response) {
		$body = $response['body'];
		$body = json_decode($body);

		if ($body->stat == 'ok') {
			$auth = $body->Auth;
			$token = $auth->Token;
			return array('oauth_token' => $token->id, 'oauth_token_secret' => $token->Secret);
		}
		return array();
	}

	public function check_access_token_method() {
		return 'smugmug.auth.checkAccessToken';
	}
}
