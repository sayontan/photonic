<?php
/**
 * Processor for Picasa. This extends the Photonic_Processor class and defines methods local to Picasa.
 *
 * @package Photonic
 * @subpackage Extensions
 */

class Photonic_Picasa_Processor extends Photonic_Processor {
	/**
	 *
	 * user_id
	 * kind
	 * album
	 * max_results
	 *
	 * thumb_size
	 * columns
	 * shorten caption
	 * show caption
	 *
	 * @param array $attr
	 * @return string
	 */
	function get_gallery_images($attr = array()) {
		global $photonic_picasa_position;
		$attr = array_merge(array(
			'style' => 'default',
			'show_captions' => false,
			'crop' => true,
			'display' => 'page',
		), $attr);
		extract($attr);

		if (!isset($user_id) || (isset($user_id) && trim($user_id) == '')) {
			return '';
		}

		if (!isset($view)) {
			$view = null;
		}

		$query_url = 'http://picasaweb.google.com/data/feed/api/user/'.$user_id;
		if (isset($album) && trim($album) != '') {
			$query_url .= '/album/'.urlencode($album);
		}

		if (isset($albumid) && trim($albumid) != '') {
			$query_url .= '/albumid/'.urlencode($albumid);
		}

		if (isset($kind) && trim($kind) != '' && in_array(trim($kind), array('album', 'photo', 'tag'))) {
			$kind = trim($kind);
			$query_url .= "?kind=".$kind."&";
		}
		else {
			$kind = '';
			$query_url .= "?".$kind;
		}

		if (!isset($view) || $view == null) {
			if ($kind == 'album') {
				$view = 'album';
			}
			else if ($kind == '') {
				if (!isset($album) && !isset($albumid)) {
					$view = 'album';
				}
			}
		}

		if (isset($max_results) && trim($max_results) != '') {
			$query_url .= 'max-results='.trim($max_results).'&';
		}

		if (isset($thumbsize) && trim($thumbsize) != '') {
			$query_url .= 'thumbsize='.trim($thumbsize).'&';
		}
		else {
			$query_url .= 'thumbsize=75&';
		}

		$query_url .= 'imgmax=1600u';
		//$query_url .= $crop_str;

		$response = wp_remote_request($query_url);
		if (is_wp_error($response)) {
			$rss = '';
		}
		else if (200 != $response['response']['code']) {
			$rss = '';
		}
		else {
			$rss = $response['body'];
		}

		$photonic_picasa_position++;
		if ($display != 'popup') {
			$out = "<div class='photonic-picasa-stream' id='photonic-picasa-stream-$photonic_picasa_position'>";
		}
		else {
			$out = "<div class='photonic-picasa-panel photonic-panel'>";
		}
		if (!isset($columns)) {
			$columns = null;
		}

		if (!isset($panel)) {
			$panel = null;
		}

		$out .= $this->picasa_parse_feed($rss, $view, $display, $columns, $panel);
		$out .= "</div>";
		return $out;
	}

	/**
	 * Reads the output from Picasa and parses it to generate the front-end output.
	 * In a later release this will be streamlined to use DOM-based parsing instead of event-based parsing.
	 *
	 * @param $rss
	 * @param null $view
	 * @param string $display
	 * @param null $columns
	 * @param null $panel
	 * @return string
	 */
	function picasa_parse_feed($rss, $view = null, $display = 'page', $columns = null, $panel = null) {
		global $photonic_picasa_position, $photonic_slideshow_library, $photonic_picasa_photo_title_display, $photonic_gallery_panel_items, $photonic_picasa_photo_pop_title_display;
		global $photonic_picasa_photos_per_row_constraint, $photonic_picasa_photos_constrain_by_count, $photonic_picasa_photos_pop_per_row_constraint, $photonic_picasa_photos_pop_constrain_by_count;
		if (!isset($photonic_gallery_panel_items) || $photonic_gallery_panel_items == '0' || $photonic_gallery_panel_items == 0) {
			$photonic_gallery_panel_items = 20;
		}

		$p = xml_parser_create();
		xml_parse_into_struct($p, $rss, $vals, $index);
		xml_parser_free($p);

		$opened = false;
		$picasa_title = "NULL";
		$count=0;

		$ul_class = '';
		$out = '';
		if ($display == 'popup') {
			$ul_class = "class='slideshow-grid-panel lib-$photonic_slideshow_library'";
			$out .= "<div class='photonic-picasa-panel-content photonic-panel-content fix'>";
		}
		$out .= "<ul $ul_class>";

		foreach ($vals as $val) {
			if (!$opened) {
				switch ($val["tag"]) {
					case "ENTRY":
						if ($val["type"] == "open") {
							$opened = true;
						}
						break;

					case "TITLE":
						if ($picasa_title == "NULL") {
							$picasa_title = $val["value"];
						}

					case "GPHOTO:NUMPHOTOS":
						if (!isset($numphotos) || (isset($numphotos) && !is_numeric($numphotos))) {
							$numphotos = $val["value"];
						}
						break;

					case "GPHOTO:ID":
						$albumid = $val["value"];
						break;

					case "OPENSEARCH:TOTALRESULTS":
						$result_count = $val["value"];
						break;

					case "GPHOTO:USER":
						$gphotouser = trim($val["value"]);
						break;
				}
			}
			else {
				switch ($val["tag"]) {
					case "ENTRY":
						if ($val["type"] == "close") {
							$opened = false;
						}
						break;

					case "MEDIA:THUMBNAIL":
						$thumb = trim($val["attributes"]["URL"] . "\n");
						break;

					case "MEDIA:CONTENT":
						$href = $val["attributes"]["URL"];
						$filename = basename($href);
						break;

					case "SUMMARY":
						$caption = isset($val["value"]) ? $val["value"] : '';
						break;

					case "GPHOTO:ID":
						$gphotoid = trim($val["value"]);
						break;

					case "GPHOTO:USER":
						$gphotouser = trim($val["value"]);
						break;
				}
			}

			if (isset($thumb) && isset($href) && isset($gphotoid)) {
				// Set image caption
/*				if (!isset($caption) || (isset($caption) && trim($caption) == "")) {
					$caption = $filename;
				}*/
				if (!isset($caption)) {
					$caption = '';
				}

				// Keep count of images
				$count++;

				$display_caption = apply_filters('photonic_image_display_caption', $caption);

				// Hide Videos
				$vidpos = stripos($href, "googlevideo");

				if (($vidpos == "")) {
					$li_id = $view == 'album' ? "id='photonic-picasa-album-$gphotouser-$photonic_picasa_position-$gphotoid'" : '';

					if ($display == 'page') {
						if ($columns == null) {
							if ($photonic_picasa_photos_per_row_constraint == 'padding') {
								$pad_class = 'photonic-pad-photos';
							}
							else {
								$pad_class = 'photonic-gallery-'.$photonic_picasa_photos_constrain_by_count.'c';
							}
						}
						else {
							$pad_class = 'photonic-gallery-'.$columns.'c';
						}
						$out .= "<li class='photonic-picasa-image $pad_class' $li_id>";
					}
					else {
						if ($count % $photonic_gallery_panel_items == 1) {
							$out .= "<li class='photonic-picasa-image'>";
						}

						if ($photonic_picasa_photos_pop_per_row_constraint == 'padding') {
							$pad_class = 'photonic-pad-photos';
						}
						else {
							$pad_class = 'photonic-gallery-'.$photonic_picasa_photos_pop_constrain_by_count.'c';
						}
					}
					$library = '';
					$id = '';
					if ($photonic_slideshow_library != 'none') {
						if ($view != 'album' || $display == 'popup') {
							$library = 'launch-gallery-'.$photonic_slideshow_library.' '.$photonic_slideshow_library;
						}
						else {
							$library = 'photonic-picasa-album-thumb';
							$id = "id='photonic-picasa-album-thumb-$gphotouser-$photonic_picasa_position-$gphotoid'";
						}
					}

					$rel = '';
					if (($view != 'album' || $display == 'popup') && $photonic_slideshow_library != 'prettyphoto') {
						$rel = "rel='photonic-picasa-stream-$photonic_picasa_position'";
					}
					else if (($view != 'album' || $display == 'popup') && $photonic_slideshow_library == 'prettyphoto') {
						if ($panel == null) {
							$rel = "rel='photonic-prettyPhoto[photonic-picasa-stream-$photonic_picasa_position]'";
						}
						else {
							$rel = "rel='photonic-prettyPhoto[$panel]'";
						}
					}

					$a_pad_class = $display == 'popup' ? $pad_class : '';
					$out .= "<a class='$library $a_pad_class' title=\"".esc_attr($display_caption)."\" href='$href' $rel $id>";
					$out .= "<img src='$thumb' alt=\"".esc_attr($display_caption)."\"/>";
					if ($display == 'page' && $photonic_picasa_photo_title_display == 'below') {
						$out .= "<span class='photonic-photo-title'>$display_caption</span>";
					}
					else if ($display == 'popup' && $photonic_picasa_photo_pop_title_display == 'below') {
						$out .= "<span class='photonic-photo-title'>$display_caption</span>";
					}
					$out .= "</a>";
					if ($display == 'page') {
						$out .= "</li>";
					}
					else {
						if ($count % $photonic_gallery_panel_items == 0) {
							$out .= "</li>";
						}
					}
				}

				//----------------------------------
				//Reset the variables
				//----------------------------------
				unset($thumb);
				unset($picasa_title);
				unset($href);
				unset($path);
				unset($url);
				unset($text);
				unset($gphotoid);
			}
		}

		if ($out != '<ul>') {
			if (substr($out, -5) != "</li>") {
				$out .= "</li>";
			}
			$out .= '</ul>';
			if ($photonic_picasa_photo_pop_title_display == 'tooltip') {
				$out .= "<script type='text/javascript'>\$j('.photonic-picasa-panel a').each(function() { \$j(this).data('title', \$j(this).attr('title')); }); \$j('.photonic-picasa-panel a').each(function() { if (!(\$j(this).parent().hasClass('photonic-header-title'))) { var iTitle = \$j(this).find('img').attr('alt'); \$j(this).tooltip({ bodyHandler: function() { return iTitle; }, showURL: false });}})</script>";
			}

			if ($display == 'popup') {
				if ($photonic_slideshow_library == 'fancybox') {
					$out .= "<script type='text/javascript'>\$j('a.launch-gallery-fancybox').each(function() { \$j(this).fancybox({ transitionIn:'elastic', transitionOut:'elastic',speedIn:600,speedOut:200,overlayShow:true,overlayOpacity:0.8,overlayColor:\"#000\",titleShow:Photonic_JS.fbox_show_title,titlePosition:Photonic_JS.fbox_title_position});});</script>";
				}
				else if ($photonic_slideshow_library == 'colorbox') {
					$out .= "<script type='text/javascript'>\$j('a.launch-gallery-colorbox').each(function() { \$j(this).colorbox({ opacity: 0.8, maxWidth: '95%', maxHeight: '95%', slideshow: Photonic_JS.slideshow_mode, slideshowSpeed: Photonic_JS.slideshow_interval });});</script>";
				}
				else if ($photonic_slideshow_library == 'prettyphoto') {
					$out .= "<script type='text/javascript'>\$j(\"a[rel^='photonic-prettyPhoto']\").prettyPhoto({ theme: Photonic_JS.pphoto_theme, autoplay_slideshow: Photonic_JS.slideshow_mode, slideshow: parseInt(Photonic_JS.slideshow_interval), show_title: false, social_tools: '', deeplinking: false });</script>";
				}
				$out .= "</div>";
			}
		}
		else {
			$out = '';
		}
		return $out;
	}

	/**
	 * If a Picasa album thumbnail is being displayed on a page, clicking on the thumbnail should launch a popup displaying all
	 * album photos. This function handles the click event and the subsequent invocation of the popup.
	 *
	 * @return void
	 */
	function display_album() {
		$panel = $_POST['panel'];
		$panel = substr($panel, 28);
		$user = substr($panel, 0, strpos($panel, '-'));
		$album = substr($panel, strpos($panel, '-') + 1);
		$album = substr($album, strpos($album, '-') + 1);
		echo $this->get_gallery_images(array('user_id' => $user, 'albumid' => $album, 'view' => 'album', 'display' => 'popup', 'panel' => $panel));
		die();
	}

	/**
	 * Access Token URL
	 *
	 * @return string
	 */
	public function access_token_URL() {
		// TODO: Implement access_token_URL() method.
	}

	/**
	 * Authenticate URL
	 *
	 * @return string
	 */
	public function authenticate_URL() {
		// TODO: Implement authenticate_URL() method.
	}

	/**
	 * Authorize URL
	 *
	 * @return string
	 */
	public function authorize_URL() {
		// TODO: Implement authorize_URL() method.
	}

	/**
	 * Request Token URL
	 *
	 * @return string
	 */
	public function request_token_URL() {
		// TODO: Implement request_Token_URL() method.
	}

	public function end_point() {
		// TODO: Implement end_point() method.
	}

	function parse_token($response) {
		// TODO: Implement parse_token() method.
	}

	public function check_access_token_method() {
		// TODO: Implement check_access_token_method() method.
	}
}
?>