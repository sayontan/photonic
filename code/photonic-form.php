<?php
/**
 * Creates a form in the "Add Media" screen under the new "Photonic" tab. This form lets you insert the gallery shortcode with
 * the right arguments for native WP galleries, Flickr, Picasa, SmugMug and 500px.
 *
 * @package Photonic
 * @subpackage UI
 */

$selected_tab = isset($_GET['photonic-tab']) ? esc_attr($_GET['photonic-tab']) : 'default';
if (!in_array($selected_tab, array('default', 'flickr', 'picasa', 'smugmug', '500px'))) {
	$selected_tab = 'default';
}

if (isset($_POST['photonic-submit'])) {
	$shortcode =  stripslashes($_POST['photonic-shortcode']);
	return media_send_to_editor($shortcode);
}
else if (isset($_POST['photonic-cancel'])) {
	return media_send_to_editor('');
}
?>
<script type="text/javascript">
	$j = jQuery.noConflict();

	function photonicAdminHtmlEncode(value){
		return $j('<div/>').text(value).html();
	}

	$j(document).ready(function() {
		$j('#photonic-shortcode-form input[type="text"], #photonic-shortcode-form select').change(function(event) {
			var comboValues = $j('#photonic-shortcode-form').serializeArray();
			var newValues = new Array();
			var len = comboValues.length;
			for (var i=0; i<len; i++) {
				var individual = comboValues[i];
				if (individual['name'].trim() != 'photonic-shortcode' && individual['name'].trim() != 'photonic-submit' &&
						individual['name'].trim() != 'photonic-cancel' && individual['value'].trim() != '') {
					newValues.push(individual['name'] + "='" + photonicAdminHtmlEncode(decodeURIComponent(individual['value'].trim())) + "'");
				}
			}

			var shortcode = "[gallery type='<?php echo $selected_tab; ?>' ";
			len = newValues.length;
			for (var i=0; i<len; i++) {
				shortcode += newValues[i] + ' ';
			}
			shortcode += ']';
			$j('#photonic-preview').text(shortcode);
			$j('#photonic-shortcode').val(shortcode);
		});
		$j('#photonic-shortcode-form select').change();
	});
</script>
<?php
$fields = array(
	'default' => array(
		'name' => __('WP Galleries', 'photonic'),
		'fields' => array(
			array(
				'id' => 'id',
				'name' => __('Gallery ID', 'photonic'),
				'type' => 'text',
				'req' => true,
			),

			array(
				'id' => 'style',
				'name' => __('Display Style', 'photonic'),
				'type' => 'select',
				'options' => array(
					'strip-below' => __('Thumbnails below slideshow', 'photonic'),
					'strip-above' => __('Thumbnails above slideshow', 'photonic'),
					'no-strip' => __('No thumbnails - only slideshow', 'photonic'),
					'launch' => __('Only thumbnails - click for slideshow', 'photonic')
				),
			),

			array(
				'id' => 'fx',
				'name' => __('Slideshow Effects', 'photonic'),
				'type' => 'select',
				'options' => array(
					'fade' => __('Fade', 'photonic'),
					'scrollUp' => __('Scroll Up', 'photonic'),
					'scrollDown' => __('Scroll Down', 'photonic'),
					'scrollLeft' => __('Scroll Left', 'photonic'),
					'scrollRight' => __('Scroll Right', 'photonic'),
					'scrollHorz' => __('Scroll Horizontal', 'photonic'),
					'scrollVert' => __('Scroll Vertical', 'photonic'),
					'slideX' => __('Slide X', 'photonic'),
					'slideY' => __('Slide Y', 'photonic'),
					'turnUp' => __('Turn Up', 'photonic'),
					'turnDown' => __('Turn Down', 'photonic'),
					'turnLeft' => __('Turn Left', 'photonic'),
					'turnRight' => __('Turn Right', 'photonic'),
					'zoom' => __('Zoom', 'photonic'),
					'fadeZoom' => __('Fade Zoom', 'photonic'),
					'blindX' => __('Blind X', 'photonic'),
					'blindY' => __('Blind Y', 'photonic'),
					'blindZ' => __('Blind Z', 'photonic'),
					'growX' => __('Grow X', 'photonic'),
					'growY' => __('Grow Y', 'photonic'),
					'curtainX' => __('Curtain-X', 'photonic'),
					'curtainY' => __('Curtain-Y', 'photonic'),
					'cover' => __('Cover', 'photonic'),
					'uncover' => __('Uncover', 'photonic'),
					'wipe' => __('Wipe', 'photonic'),
				),
			),

			array(
				'id' => 'slideshow_height',
				'name' => __('Slideshow Height', 'photonic'),
				'type' => 'text',
				'std' => 500,
				'hint' => __('In pixels. This is applicable only if you are displaying the slideshow directly on the page.', 'photonic'),
				'req' => true,
			),

			array(
				'id' => 'columns',
				'name' => __('Number of columns', 'photonic'),
				'type' => 'text',
				'std' => 3,
			),

			array(
				'id' => 'thumb_width',
				'name' => __('Thumbnail width', 'photonic'),
				'type' => 'text',
				'std' => 75,
				'hint' => __('In pixels', 'photonic')
			),

			array(
				'id' => 'thumb_height',
				'name' => __('Thumbnail height', 'photonic'),
				'type' => 'text',
				'std' => 75,
				'hint' => __('In pixels', 'photonic')
			),

			array(
				'id' => 'thumbnail_size',
				'name' => __('Thumbnail size', 'photonic'),
				'type' => 'raw',
				'std' => Photonic::get_image_sizes_selection('thumbnail_size', false),
				'hint' => __('Sizes defined by your theme. Image picked here will be resized to the dimensions above.', 'photonic')
			),

			array(
				'id' => 'slide_size',
				'name' => __('Slides image size', 'photonic'),
				'type' => 'raw',
				'std' => Photonic::get_image_sizes_selection('slide_size', true),
				'hint' => __('Sizes defined by your theme. Applies to slideshows only. Avoid loading large sizes to reduce page loads.', 'photonic')
			),

			array(
				'id' => 'timeout',
				'name' => __('Time between slides in ms', 'photonic'),
				'type' => 'text',
				'std' => 4000,
				'hint' => __('Applies to slideshows only', 'photonic')
			),

			array(
				'id' => 'speed',
				'name' => __('Time for each transition in ms', 'photonic'),
				'type' => 'text',
				'std' => 1000,
				'hint' => __('Applies to slideshows only', 'photonic')
			),
		),
	),
	'flickr' => array(
		'name' => __('Flickr', 'photonic'),
		'prelude' => __('You have to define your Flickr API Key under Settings &rarr; Photonic &rarr; Flickr &rarr; Flickr Settings', 'photonic'),
		'fields' => array(
			array(
				'id' => 'user_id',
				'name' => "<a href='http://idgettr.com/' target='_blank'>".__('User ID', 'photonic')."</a>",
				'type' => 'text',
				'req' => true,
			),

			array(
				'id' => 'view',
				'name' => __('Display', 'photonic'),
				'type' => 'select',
				'options' => array(
					'photos' => __('Photos', 'photonic'),
					'photosets' => __('Photosets', 'photonic'),
					'galleries' => __('Galleries', 'photonic'),
					'collections' => __('Collections', 'photonic'),
					'photo' => __('Single Photo', 'photonic'),
				),
				'req' => true,
			),

			array(
				'id' => 'photoset_id',
				'name' => __('Photoset ID', 'photonic')."</a>",
				'type' => 'text',
				'hint' => __('Will show a single photoset if "Display" is set to "Photosets"', 'photonic')
			),

			array(
				'id' => 'gallery_id',
				'name' => __('Gallery ID', 'photonic')."</a>",
				'type' => 'text',
				'hint' => __('Will show a single gallery if "Display" is set to "Galleries"', 'photonic')
			),

			array(
				'id' => 'collection_id',
				'name' => __('Collection ID', 'photonic')."</a>",
				'type' => 'text',
				'hint' => __('Will show contents of a single collection if "Display" is set to "Collections"', 'photonic')
			),

			array(
				'id' => 'photo_id',
				'name' => __('Photo ID', 'photonic')."</a>",
				'type' => 'text',
				'hint' => __('Will show a single photo if "Display" is set to "Single Photo"', 'photonic')
			),

			array(
				'id' => 'columns',
				'name' => __('Number of columns', 'photonic'),
				'type' => 'text',
			),

			array(
				'id' => 'tags',
				'name' => __('Tags', 'photonic'),
				'type' => 'text',
				'hint' => __('Comma-separated list of tags', 'photonic')
			),

			array(
				'id' => 'tag_mode',
				'name' => __('Tag mode', 'photonic'),
				'type' => 'select',
				'options' => array(
					'any' => __('Any tag', 'photonic'),
					'all' => __('All tags', 'photonic'),
				),
			),

			array(
				'id' => 'text',
				'name' => __('With text', 'photonic'),
				'type' => 'text',
			),

			array(
				'id' => 'sort',
				'name' => __('Sort by', 'photonic'),
				'type' => 'select',
				'options' => array(
					'date-posted-desc' => __('Date posted, descending', 'photonic'),
					'date-posted-asc' => __('Date posted, ascending', 'photonic'),
					'date-taken-asc' => __('Date taken, ascending', 'photonic'),
					'date-taken-desc' => __('Date taken, descending', 'photonic'),
					'interestingness-desc' => __('Interestingness, descending', 'photonic'),
					'interestingness-asc' => __('Interestingness, ascending', 'photonic'),
					'relevance' => __('Relevance', 'photonic'),
				),
			),

			array(
				'id' => 'group_id',
				'name' => __('Group ID', 'photonic')."</a>",
				'type' => 'text',
			),

			array(
				'id' => 'per_page',
				'name' => __('Number of photos to show', 'photonic')."</a>",
				'type' => 'text',
			),

			array(
				'id' => 'privacy_filter',
				'name' => __('Privacy filter', 'photonic'),
				'type' => 'select',
				'options' => array(
					'' => __('None', 'photonic'),
					'1' => __('Public photos', 'photonic'),
					'2' => __('Private photos visible to friends', 'photonic'),
					'3' => __('Private photos visible to family', 'photonic'),
					'4' => __('Private photos visible to friends & family', 'photonic'),
					'5' => __('Completely private photos', 'photonic'),
				),
				'hint' => __('Applicable only if Flickr private photos are turned on', 'photonic'),
			),

		),
	),
	'picasa' => array(
		'name' => __('Picasa', 'photonic'),
		'fields' => array(
			array(
				'id' => 'user_id',
				'name' => __('User ID', 'photonic'),
				'type' => 'text',
				'req' => true,
			),

			array(
				'id' => 'kind',
				'name' => __('Display', 'photonic'),
				'type' => 'select',
				'options' => array(
					'album' => __('Albums', 'photonic'),
					'photo' => __('Photos', 'photonic'),
				),
			),

			array(
				'id' => 'album',
				'name' => __('Album', 'photonic')."</a>",
				'type' => 'text',
			),

			array(
				'id' => 'max_results',
				'name' => __('Number of photos to show', 'photonic')."</a>",
				'type' => 'text',
			),

			array(
				'id' => 'thumbsize',
				'name' => __('Thumbnail size', 'photonic'),
				'type' => 'text',
				'std' => 75,
				'hint' => __('In pixels', 'photonic')
			),

		),
	),
	'smugmug' => array(
		'name' => __('SmugMug', 'photonic'),
		'prelude' => __('You have to define your SmugMug API Key under Settings &rarr; Photonic &rarr; SmugMug &rarr; SmugMug Settings', 'photonic'),
		'fields' => array(
			array(
				'id' => 'view',
				'name' => __('Display', 'photonic'),
				'type' => 'select',
				'options' => array(
					'tree' => __('Tree', 'photonic'),
					'albums' => __('All albums', 'photonic'),
					'album' => __('Single Album', 'photonic'),
				),
				'req' => true,
			),

			array(
				'id' => 'nick_name',
				'name' => __('Nickname', 'photonic'),
				'type' => 'text',
				'hint' => __('If your SmugMug URL is http://joe-sixpack.smugmug.com, this is "joe-sixpack". Required if the "Display" is "Tree" or "All albums".', 'photonic')
			),

			array(
				'id' => 'album',
				'name' => __('Album', 'photonic')."</a>",
				'type' => 'text',
				'hint' => __('Required if you are showing "Single Album" above. If your gallery URL is http://nick-name.smugmug.com/gallery/<b>abcdefgh_123456</b>, this is <b>abcdefgh_123456</b>', 'photonic')
			),

			array(
				'id' => 'columns',
				'name' => __('Number of columns', 'photonic'),
				'type' => 'text',
			),
		),
	),
	'500px' => array(
		'name' => __('500px', 'photonic'),
		'prelude' => __('You have to define your Consumer API Key under Settings &rarr; Photonic &rarr; 500px &rarr; 500px Settings', 'photonic'),
		'fields' => array(
			array(
				'id' => 'feature',
				'name' => __('Feature', 'photonic'),
				'type' => 'select',
				'options' => array(
					'popular' => __('Popular photos', 'photonic'),
					'upcoming' => __('Upcoming photos', 'photonic'),
					'editors' => __("Editor's choices", 'photonic'),
					'fresh_today' => __('Fresh today', 'photonic'),
					'fresh_yesterday' => __('Fresh today', 'photonic'),
					'fresh_week' => __('Fresh today', 'photonic'),
					'user' => __("Specified user's photos", 'photonic'),
					'user_friends' => __("Photos of specified user's friends", 'photonic'),
					'user_favorites' => __("Specified user's favourite photos", 'photonic'),
				),
				'req' => true,
			),

			array(
				'id' => 'user_id',
				'name' => __('User ID', 'photonic'),
				'type' => 'text',
				'hint' => __('Either User ID or User Name is required if Feature is user-specific', 'photonic')
			),

			array(
				'id' => 'username',
				'name' => __('User Name', 'photonic'),
				'type' => 'text',
				'hint' => __('Either User ID or User Name is required if Feature is user-specific', 'photonic')
			),

			array(
				'id' => 'only',
				'name' => __('Category', 'photonic'),
				'type' => 'select',
				'options' => array(
					'' => __('All Categories', 'photonic'),
					'Abstract' => __('Abstract', 'photonic'),
					'Animals' => __('Animals', 'photonic'),
					'Black and White' => __("Black and White", 'photonic'),
					'Celebrities' => __('Celebrities', 'photonic'),
					'City and Architecture' => __('Fresh today', 'photonic'),
					'Commercial' => __('Commercial', 'photonic'),
					'Concert' => __("Concert", 'photonic'),
					'Family' => __("Family", 'photonic'),
					'Fashion' => __("Fashion", 'photonic'),
					'Film' => __("Film", 'photonic'),
					'Fine Art' => __("Fine Art", 'photonic'),
					'Food' => __("Food", 'photonic'),
					'Journalism' => __("Journalism", 'photonic'),
					'Landscapes' => __("Landscapes", 'photonic'),
					'Macro' => __("Macro", 'photonic'),
					'Nature' => __("Nature", 'photonic'),
					'Nude' => __("Nude", 'photonic'),
					'People' => __("People", 'photonic'),
					'Performing Arts' => __("Performing Arts", 'photonic'),
					'Sport' => __("Sport", 'photonic'),
					'Still Life' => __("Still Life", 'photonic'),
					'Street' => __("Street", 'photonic'),
					'Travel' => __("Travel", 'photonic'),
					'Underwater' => __("Underwater", 'photonic'),
				),
			),

			array(
				'id' => 'sort',
				'name' => __('Sort by', 'photonic'),
				'type' => 'select',
				'options' => array(
					'created_at' => __('Created at', 'photonic'),
					'rating' => __('Rating', 'photonic'),
					'times_viewed' => __('Times viewed', 'photonic'),
					'taken_at' => __('Taken at', 'photonic'),
				),
			),

			array(
				'id' => 'rpp',
				'name' => __('Number of photos to show', 'photonic')."</a>",
				'type' => 'text',
				'std' => 20,
			),

			array(
				'id' => 'thumb_size',
				'name' => __('Thumbnail size', 'photonic'),
				'type' => 'select',
				'options' => array(
					'1' => __('75 &times; 75 px', 'photonic'),
					'2' => __('140 &times; 140 px', 'photonic'),
					'3' => __('280 &times; 280 px', 'photonic'),
				),
			),

			array(
				'id' => 'main_size',
				'name' => __('Main image size', 'photonic'),
				'type' => 'select',
				'options' => array(
					'3' => __('280 &times; 280 px', 'photonic'),
					'4' => __('Full size', 'photonic'),
				),
			),

			array(
				'id' => 'columns',
				'name' => __('Number of columns', 'photonic'),
				'type' => 'text',
			),

		),
	),
);

$tab_list = '';
$tab_fields = '';
$field_list = array();
$prelude = '';
foreach ($fields as $tab => $field_group) {
	$tab_list .= "<li><a href='".esc_url(add_query_arg(array('photonic-tab' => $tab)))."' class='".($tab == $selected_tab ? 'current' : '')."'>".esc_attr($field_group['name'])."</a> | </li>";
	if ($tab == $selected_tab) {
		$field_list = $field_group['fields'];
		$prelude = isset($field_group['prelude']) ? $field_group['prelude'] : '';
	}
}

echo "<form id='photonic-shortcode-form' method='post' action=''>";
echo "<ul class='subsubsub'>";
if (strlen($tab_list) > 8) {
	$tab_list = substr($tab_list, 0, -8);
}
echo $tab_list;
echo "</ul>";

if (!empty($prelude)) {
	echo "<p class='prelude'>"; print_r($prelude); echo "</p>";
}

echo "<table class='photonic-form'>";
echo "<tr>";"</tr>";
foreach ($field_list as $field) {
	echo "<tr>";
	echo "<th scope='row'>{$field['name']} ".(isset($field['req']) && $field['req'] ? '(*)' : '')." </th>";
	switch ($field['type']) {
		case 'text':
			echo "<td><input type='text' name='{$field['id']}' value='".(isset($field['std']) ? $field['std'] : '')."'/></td>";
			continue;
		case 'select':
			echo "<td><select name='{$field['id']}'>";
			foreach ($field['options'] as $option_name => $option_value) {
				echo "<option value='$option_name'>$option_value</option>";
			}
			echo "</select></td>";
			continue;
		case 'raw':
			echo "<td>".$field['std']."</td>";
			continue;
	}
	echo "<td class='hint'>".(isset($field['hint']) ? $field['hint'] : '')."</td>";
	echo "</tr>";
}
echo "</table>";

echo "<div class='preview'>";
echo "<script type='text/javascript'></script>";
echo "<h4>".__('Shortcode preview', 'photonic')."</h4>";
echo "<pre class='html' id='photonic-preview' name='photonic-preview'></pre>";
echo "<input type='hidden' id='photonic-shortcode' name='photonic-shortcode' />";
echo "</div>";

echo "<div class='button-panel'>";
echo get_submit_button(__('Insert into post', 'photonic'), 'primary', 'photonic-submit', false);
echo get_submit_button(__('Cancel', 'photonic'), 'delete', 'photonic-cancel', false);
echo "</div>";
echo "</form>";
?>