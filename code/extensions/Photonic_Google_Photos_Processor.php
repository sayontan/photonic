<?php
/**
 * Fetches photos from a user's Google Photos account. This is different from the Picasa Extension. Picasa's shutdown was announced in February 2016,
 * but the Google Photos API was only introduced in May 2018. This API has better support for shared albums, and is generally more streamlined.
 *
 * However, it lacks support for dual title / description fields, doesn't provide download URLs, and video support is ambiguous.
 */

class Photonic_Google_Photos_Processor extends Photonic_OAuth2_Processor {
	var $error_date_format;

	function __construct() {
		parent::__construct();
		global $photonic_google_client_id, $photonic_google_client_secret, $photonic_google_disable_title_link, $photonic_google_refresh_token;
		$this->client_id = trim($photonic_google_client_id);
		$this->client_secret = trim($photonic_google_client_secret);
		$this->provider = 'google';
		$this->oauth_version = '2.0';
		$this->response_type = 'code';
		$this->scope = 'https://www.googleapis.com/auth/photoslibrary.readonly https://www.googleapis.com/auth/photoslibrary.sharing https://picasaweb.google.com/data/';
		$this->link_lightbox_title = empty($photonic_google_disable_title_link);

		// Documentation
		$this->doc_links = array(
			'general' => 'https://aquoid.com/plugins/photonic/google-photos/',
			'photos' => 'https://aquoid.com/plugins/photonic/google-photos/photos/',
			'albums' => 'https://aquoid.com/plugins/photonic/google-photos/albums/',
		);

		$this->error_date_format = __('Dates must be entered in the format Y/M/D where Y is from 0 to 9999, M is from 0 to 12 and D is from 0 to 31. You entered %s.', 'photonic');
		$this->oauth_done = false;
		$this->perform_back_end_authentication($photonic_google_refresh_token);
	}

	/**
	 * Main function that fetches the images associated with the shortcode.
	 *
	 * @param array $attr
	 * @return string
	 */
	function get_gallery_images($attr = array()) {
		global $photonic_google_refresh_token, $photonic_google_media;
		$this->gallery_index++;
		$this->push_to_stack('Get Gallery Images');
		$attr = array_merge(
			$this->common_parameters,
			array(
				'caption' => 'title',
				'thumb_size' => '150',
				'main_size' => '1600',
				'tile_size' => '1600',
				'crop_thumb' => 'crop',

				// Google ...
				'count' => 100,
				'media' => $photonic_google_media,
				'video_size' => 'm37',
				'date_filters' => '',
				'content_filters' => '',
				'access' => 'all',
			),
			$attr);
		$attr = array_map('trim', $attr);

		if (empty($this->client_id)) {
			$this->pop_from_stack();
			return $this->error(sprintf(__("Google Photos Client ID not defined. See <a href='%s'>here</a> for documentation.", 'photonic'), $this->doc_links['general']));
		}
		if (empty($this->client_secret)) {
			$this->pop_from_stack();
			return $this->error(sprintf(__("Google Photos Client Secret not defined. See <a href='%s'>here</a> for documentation.", 'photonic'), $this->doc_links['general']));
		}
		if (empty($photonic_google_refresh_token)) {
			$this->pop_from_stack();
			return $this->error(sprintf(__("Google Photos Refresh Token not defined. Please authenticate from <em>Photonic &rarr; Authentication</em> See <a href='%s'>here</a> for documentation.", 'photonic'), $this->doc_links['general']));
		}

		if (empty($attr['view'])) {
			$this->pop_from_stack();
			return $this->error(__('The <code>view</code> parameter is mandatory for the shortcode.', 'photonic'));
		}

		$query_urls = array();
		if ($attr['view'] == 'albums') {
			$additional = array();
			if (!empty($attr['count'])) {
				$additional['pageSize'] = intval($attr['count']) > 50 ? 50 : intval($attr['count']);
			}

			if (!empty($attr['next_token'])) {
				$additional['pageToken'] = $attr['next_token'];
			}

			$access = $this->access_all_or_shared($attr);
			if ($access['shared']) {
				$query_urls['https://photoslibrary.googleapis.com/v1/sharedAlbums'] = array('GET' => $additional);
			}
			if ($access['all']) {
				$query_urls['https://photoslibrary.googleapis.com/v1/albums'] = array('GET' => $additional);
			}
		}
		else if ($attr['view'] == 'photos') {
			$additional = array();
			if (!empty($attr['album_id'])) {
//				$query_urls['https://photoslibrary.googleapis.com/v1/albums/'.$attr['album_id']] = array('GET' => array());
				$additional['albumId'] = $attr['album_id'];
			}
			else {
				$filters = array();

				$date_parameter = array();
				$range_parameter = array();
				if (!empty($attr['date_filters'])) {
					/*
					 * Structure of $attr['date_filters']: comma-separated list of dates or date ranges.
					 * Each date is represented by Y/M/D, where 0 <= Y <= 9999, 0 <= M <= 12, 0 <= D < 31
					 * Each range is represented as Y/M/D-Y/M/D
					 */
					$date_filters = explode(',', trim($attr['date_filters']));
					foreach($date_filters as $date_filter) {
						$dates = explode('-', trim($date_filter));
						if (count($dates) > 2) {
							$dates = array_slice($dates, 0, 2);
						}
						$range = array();
						foreach ($dates as $idx => $date) {
							$date_parts = explode('/', trim($date));
							if (count($date_parts) != 3) {
								$this->pop_from_stack();
								return $this->error(sprintf($this->error_date_format, $date));
							}

							if (!is_numeric($date_parts[0]) || $date_parts[0] > 9999 || $date_parts[0] < 0 ||
								!is_numeric($date_parts[1]) || $date_parts[1] > 12 || $date_parts[1] < 0 ||
								!is_numeric($date_parts[2]) || $date_parts[2] > 31 || $date_parts[2] < 0) {
								$this->pop_from_stack();
								return $this->error(sprintf($this->error_date_format, $date));
							}

							$date_object = array(
								'year' => intval($date_parts[0]),
								'month' => intval($date_parts[1]),
								'day' => intval($date_parts[2]),
							);

							if (count($dates) == 1) {
								$date_parameter[] = $date_object;
							}
							else if ($idx == 0) {
								$range['startDate'] = $date_object;
							}
							else {
								$range['endDate'] = $date_object;
							}
						}
						if (!empty($range)) {
							$range_parameter[] = $range;
						}
					}

					$date_filter_parameter = array();
					if (!empty($date_parameter)) {
						$date_filter_parameter['dates'] = $date_parameter;
					}
					if (!empty($range_parameter)) {
						$date_filter_parameter['ranges'] = $range_parameter;
					}
					if (!empty($date_filter_parameter)) {
						$filters['dateFilter'] = $date_filter_parameter;
					}
				}

				if (!empty($attr['content_filters'])) {
					$valid_filters = array(
						'NONE' => 'Default content category. This category is ignored if any other category is also listed.',
						'LANDSCAPES' => 'Media items containing landscapes.',
						'RECEIPTS' => 'Media items containing receipts.',
						'CITYSCAPES' =>'Media items containing cityscapes.',
						'LANDMARKS' => 'Media items containing landmarks.',
						'SELFIES' => 'Media items that are selfies.',
						'PEOPLE' => 'Media items containing people.',
						'PETS' => 'Media items containing pets.',
						'WEDDINGS' => 'Media items from weddings.',
						'BIRTHDAYS' => 'Media items from birthdays.',
						'DOCUMENTS' => 'Media items containing documents.',
						'TRAVEL' => 'Media items taken during travel.',
						'ANIMALS' => 'Media items containing animals.',
						'FOOD' => 'Media items containing food.',
						'SPORT' => 'Media items from sporting events.',
						'NIGHT' => 'Media items taken at night.',
						'PERFORMANCES' => 'Media items from performances.',
						'WHITEBOARDS' => 'Media items containing whiteboards.',
						'SCREENSHOTS' => 'Media items that are screenshots.',
						'UTILITY' => 'Media items that are considered to be utility. These include, but are not limited to documents, screenshots, whiteboards etc.',
					);

					/*
					 * Structure of content_filters: C1,C2,-C3,C4,-C5.
					 * The filters are specified as a comma-separated list.
					 * A "-" before the filter's name indicates that the filter should be excluded rather than included.
					 */
					$content_filters = explode(',', $attr['content_filters']);
					$include = $exclude = array();
					foreach ($content_filters as $content_filter) {
						$content_filter = strtoupper($content_filter);
						if (stripos($content_filter, '-') == 0 && array_key_exists(substr($content_filter, 1), $valid_filters)) {
							$exclude[] = substr($content_filter, 1);
						}
						else if (array_key_exists($content_filter, $valid_filters)){
							$include[] = $content_filter;
						}
					}

					$content_filter_parameter = array();
					if (!empty($include)) {
						$content_filter_parameter['includedContentCategories'] = $include;
					}
					if (!empty($exclude)) {
						$content_filter_parameter['excludedContentCategories'] = $exclude;
					}
					if (!empty($content_filter_parameter)) {
						$filters['contentFilter'] = $content_filter_parameter;
					}
				}

				$media_filters = explode(',', $attr['media']);
				$media_filter_parameter = array();
				if (in_array('all', $media_filters)) {
					$media_filter_parameter[] = 'ALL_MEDIA';
				}
				else if (in_array('photos', $media_filters)) {
					$media_filter_parameter[] = 'PHOTO';
				}
				else if (in_array('videos', $media_filters)) {
					$media_filter_parameter[] = 'VIDEO';
				}

				if (!empty($media_filter_parameter)) {
					$filters['mediaTypeFilter'] = array('mediaTypes' => $media_filter_parameter);
				}

				if (!empty($filters)) {
					$additional['filters'] = $filters;
				}
			}

			if (!empty($attr['count']) || !empty($attr['photo_count'])) {
				$additional['pageSize'] = !empty($attr['photo_count']) ? $attr['photo_count'] : $attr['count'];
				$additional['pageSize'] = intval($additional['pageSize']) > 500 ? 500 : intval($additional['pageSize']);
			}
			if (!empty($attr['next_token'])) {
				$additional['pageToken'] = $attr['next_token'];
			}

			$query_urls['https://photoslibrary.googleapis.com/v1/mediaItems:search'] = array('POST' => $additional);
		}

		$out = $this->make_call($query_urls, $attr);
		$out = $this->finalize_markup($out, $attr);
		$this->pop_from_stack();

		return $out.$this->get_stack_markup();
	}


	private function make_call($query_urls, $attr) {
		global $photonic_google_refresh_token;
		$this->push_to_stack('Making calls');

		$incremented = false;
		$out = '';
		$access = $this->access_all_or_shared($attr);

		$all_ids = array();
		foreach ($query_urls as $query_url => $method_and_args) {
			$this->push_to_stack("Query $query_url");
			if ($access['all'] && $query_url == 'https://photoslibrary.googleapis.com/v1/sharedAlbums') {
				$defer = true;
			}
			else {
				$defer = false;
			}

			if (!empty($photonic_google_refresh_token) && !empty($this->access_token)) {
				$query_url = add_query_arg('access_token', $this->access_token, $query_url);
			}

			foreach ($method_and_args as $method => $args) {
				$this->push_to_stack('Sending request');
				if (empty($args['filters'])) {
					$call_args = array();
					$call_args['method'] = $method;
					$call_args['body'] = $args;
					$call_args['sslverify'] = PHOTONIC_SSL_VERIFY;
					$response = wp_remote_request($query_url, $call_args);
				}
				else {
					$headers = array();
					$headers[] = 'Accept: application/json';
					$headers[] = 'Content-Type: application/json';

					// This doesn't work for some reason while using 'filters'. Google always responds with an invalid JSON format error.
					// Various options have been tried, including sending 'body' without a json_encode, enclosing the json_encode in '[]' etc.
					// Falling back on cURL for cases where $args have 'filters'
					/*					$response = wp_remote_request($query_url, array(
											'method' => 'POST',
											'headers' => $headers,
											'httpversion' => '1.0',
											'body' => json_encode($args),
										));
					*/

					$ch = curl_init($query_url);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, PHOTONIC_SSL_VERIFY);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // If not set, this prints the output
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));

					$curl_response = curl_exec($ch);
					curl_close($ch);
					// The following mimics a structure that can be handled by is_wp_error and wp_remote_retrieve_body
					$response = array();
					$response['body'] = $curl_response;
				}
				$this->pop_from_stack();

				if (!is_wp_error($response)) {
					$this->push_to_stack('Processing Response');
					$body = wp_remote_retrieve_body($response);

					if (!$incremented) {
						$incremented = true;
					}

					$output = $this->process_response($body, $attr, $defer, $all_ids);
					if ($defer && is_array($output)/* && empty($all_ids) && $access['all']*/) {
						foreach ($output as $object) {
							$all_ids[] = $object['id_1'];
						}
					}
					else {
						$out .= $output;
					}
					$this->pop_from_stack();
				}
				else {
					$this->pop_from_stack(); // "Query $query_url"
					$this->pop_from_stack(); // 'Making calls'
					return $this->error($response->get_error_message());
				}
			}
			$this->pop_from_stack();
		}

		$this->pop_from_stack();
		return $out;
	}

	/**
	 * @param $body
	 * @param $short_code
	 * @param bool|false $deferred
	 * @param array $remove
	 * @return mixed
	 */
	private function process_response($body, $short_code, $deferred = false, $remove = array()) {
		global $photonic_google_photo_title_display, $photonic_google_photos_per_row_constraint, $photonic_google_photos_constrain_by_padding,
		       $photonic_google_photos_constrain_by_count, $photonic_google_photos_pop_per_row_constraint, $photonic_google_photos_pop_constrain_by_padding,
		       $photonic_google_photos_pop_constrain_by_count, $photonic_google_photo_pop_title_display, $photonic_google_hide_album_photo_count_display;
		$out = '';
		if (!empty($body)) {
			$body = json_decode($body);
			$row_constraints = array('constraint-type' => $photonic_google_photos_per_row_constraint, 'padding' => $photonic_google_photos_constrain_by_padding, 'count' => $photonic_google_photos_constrain_by_count);
			$display = $short_code['display'];
			if (isset($body->albums) || isset($body->sharedAlbums)) {
				$albums = isset($body->albums) ? $body->albums : $body->sharedAlbums;

				$pagination = array();
				if (!empty($body->nextPageToken)) {
					$pagination = array(
						'total' => 10,
						'start' => 0,
						'end' => 1,
						'per-page' => $short_code['count'],
						'next-token' => $body->nextPageToken,
					);
				}

				$albums = $this->build_level_2_objects($albums, $short_code, $remove);
				if ($deferred) {
					return $albums;
				}

				$out .= $this->display_level_2_gallery($albums,
					array(
						'row_constraints' => $row_constraints,
						'type' => 'albums',
						'singular_type' => 'album',
						'title_position' => $photonic_google_photo_title_display,
						'level_1_count_display' => !empty($photonic_google_hide_album_photo_count_display),
						'pagination' => $pagination,
					),
					$short_code
				);
			}
			else if (isset($body->mediaItems)){
				if ($display == 'in-page') {
					$title_position = $photonic_google_photo_title_display;
				}
				else {
					$row_constraints = array('constraint-type' => $photonic_google_photos_pop_per_row_constraint, 'padding' => $photonic_google_photos_pop_constrain_by_padding, 'count' => $photonic_google_photos_pop_constrain_by_count);
					$title_position = $photonic_google_photo_pop_title_display;
				}

				$level_2_meta = array();
				if (!empty($body->nextPageToken)) {
					$level_2_meta = array(
						'total' => 10,
						'start' => 0,
						'end' => 1,
						'per-page' => $short_code['count'],
						'next-token' => $body->nextPageToken,
					);
				}
				$photos = $body->mediaItems;
				$photos = $this->build_level_1_objects($photos, $short_code);
				$out .= $this->display_level_1_gallery($photos,
					array(
						'title_position' => $title_position,
						'row_constraints' => $row_constraints,
						'parent' => 'album',
						'level_2_meta' => $level_2_meta,
					),
					$short_code);
			}
			else if (isset($body->error)) {
				$out .= __('Failed to get data. Error:', 'photonic')."<br/><code>\n";
				$out .= $body->error->message;
				$out .= "</code><br/>\n";
				return $this->error($out);
			}
		}
		else {
			$out .= __('Failed to get data. Error:', 'photonic')."<br/><code>\n";
			$out .= $body;
			$out .= "</code><br/>\n";
			return $this->error($out);
		}

		return $out;
	}

	private function build_level_1_objects($photos, $short_code) {
		$objects = array();
		foreach ($photos as $photo) {
			$object = array();
			$is_video = false;
//			if (empty($photo->mimeType) || stripos($photo->mimeType, 'image') !== 0) {
			if (!empty($photo->mediaMetadata) && !empty($photo->mediaMetadata->video)) {
				$is_video = true;
			}

			$media = explode(',', $short_code['media']);
			$videos_ok = in_array('videos', $media) || in_array('all', $media);
			$photos_ok = in_array('photos', $media) || in_array('all', $media);
			if (($is_video && !$videos_ok) || (!$is_video && !$photos_ok)) {
				continue;
			}

			$object['id'] = $photo->id;

			$object['thumbnail'] = $photo->baseUrl . "=w{$short_code['thumb_size']}-h{$short_code['thumb_size']}" . ($short_code['crop_thumb'] == 'crop' ? '-c' : '');
			$object['tile_image'] = $photo->baseUrl . "=w{$short_code['tile_size']}-h{$short_code['tile_size']}";
			$object['main_image'] = $photo->baseUrl . "=w{$short_code['main_size']}-h{$short_code['main_size']}";

			if ($is_video) {
				$object['video'] = $photo->baseUrl."={$short_code['video_size']}";
				$object['mime'] = empty($photo->mimeType) ? 'video/mp4' : $photo->mimeType;
			}
			else {
				$object['download'] = $object['main_image'].'-d';
			}

			$object['main_page'] = $photo->productUrl;

			if (!isset($object['main_page'])) {
				$object['main_page'] = $object['main_image'];
			}

			if (!empty($photo->description)) {
				$object['title'] = $photo->description;
			}
			else {
				$object['title'] = '';
			}
			$object['alt_title'] = $object['title'];
			$object['description'] = $object['title'];

			$objects[] = $object;
		}
		return $objects;
	}

	private function build_level_2_objects($albums, $short_code, $remove) {
		$filter = $short_code['filter'];
		$filters = empty($filter) ? array() : explode(',', $filter);

		$objects = array();
		foreach ($albums as $album) {
			if (!empty($filters) && ((!in_array($album->id, $filters) && strtolower($short_code['filter_type']) !== 'exclude') ||
					(in_array($album->id, $filters) && strtolower($short_code['filter_type']) === 'exclude'))) {
				continue;
			}

			if (in_array($album->id, $remove)) {
				continue;
			}

			$object = $this->process_album($album, $short_code);
			$objects[] = $object;
		}
		return $objects;
	}

	/**
	 * @param $album
	 * @param $short_code
	 * @return array
	 */
	private function process_album($album, $short_code) {
		$object = array();
		$object['id_1'] = "{$album->id}";

		$object['thumbnail'] = $album->coverPhotoBaseUrl . "=w{$short_code['thumb_size']}-h{$short_code['thumb_size']}" . ($short_code['crop_thumb'] == 'crop' ? '-c' : '');
		$object['tile_image'] = $album->coverPhotoBaseUrl . "=w{$short_code['tile_size']}-h{$short_code['tile_size']}";
		if (isset($album->productUrl)) {
			$object['main_page'] = $album->productUrl;
		}

		$object['title'] = esc_attr($album->title);
		$object['counter'] = empty($album->totalMediaItems) ? $album->mediaItemsCount : $album->totalMediaItems;
		$object['data_attributes'] = array(
			'thumb-size' => $short_code['thumb_size'],
			'photo-count' => empty($short_code['photo_count']) ? $short_code['count'] : $short_code['photo_count'],
			'photo-more' => empty($short_code['photo_more']) ? '' : $short_code['photo_more']
		);
		return $object;
	}

	private function access_all_or_shared($short_code) {
		$all_or_shared = array();
		$access = explode(',', $short_code['access']);
		if (empty($access) || (in_array('shared', $access) && in_array('not-shared', $access)) || in_array('all', $access)) {
			$all_or_shared['all'] = true;
			$all_or_shared['shared'] = false;
		}
		else if (count($access) == 1 && in_array('not-shared', $access)) {
			$all_or_shared['all'] = true;
			$all_or_shared['shared'] = true;
		}
		else if (count($access) == 1 && in_array('shared', $access)) {
			$all_or_shared['all'] = false;
			$all_or_shared['shared'] = true;
		}
		return $all_or_shared;
	}

	public function authentication_url() {
		return 'https://accounts.google.com/o/oauth2/auth';
	}

	public function access_token_url() {
		return 'https://accounts.google.com/o/oauth2/token';
	}

	/**
	 * Takes a token response from a request token call, then puts it in an appropriate array.
	 *
	 * @param $response
	 * @return array
	 */
	public function parse_token($response) {
		$token = array();
		if (!is_wp_error($response) && is_array($response)) {
			$body = $response['body'];
			$body = json_decode($body);
			$token['oauth_token'] = $body->access_token;
			$token['oauth_token_type'] = $body->token_type;
			$token['oauth_token_created'] = time();
			$token['oauth_token_expires'] = $body->expires_in;
		}
		return $token;
	}
}