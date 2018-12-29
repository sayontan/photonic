<?php
/**
 * Contains the fields used by the gallery builder. Cannot be overridden by a theme file
 * Screen 1: Provider selection: Pick from Flickr, Picasa etc.
 * Screen 2: Display Type selection; Input: Provider. Pick from: Single Photo
 * Screen 3: Gallery object selection; input: Display Type
 * Screen 4: Layout selection; input: Gallery & Display Type
 * Screen 5: Layout configuration; Set <code>count</code>, <code>more</code> etc.
 * Screen 6: Final shortcode display
 *
 * @package Photonic
 * @subpackage Flow
 * @since 2.00
 */
class Photonic_Flow_Fields {
	var $flow_options, $layout_options, $column_options, $allowed_image_sizes;
	function __construct() {
		$this->layout_options = array(
			'square' => __('Square Grid', 'photonic'),
			'circle' => __('Circular Icon Grid', 'photonic'),
			'random' => __('Justified Grid', 'photonic'),
			'masonry' => __('Masonry', 'photonic'),
			'mosaic' => __('Mosaic', 'photonic'),
			'slideshow' => __('Slideshow', 'photonic'),
		);

		$this->column_options = array(
			'desc' => __('Number of columns in output', 'photonic'),
			'type' => 'select',
			'options' => array(
				'' => '',
				'auto' => __('Automatic (Photonic calculates the columns)', 'photonic'),
				'1' => 1,
				'2' => 2,
				'3' => 3,
				'4' => 4,
				'5' => 5,
				'6' => 6,
				'7' => 7,
				'8' => 8,
				'9' => 9,
				'10' => 10,
				'11' => 11,
				'12' => 12,
				'13' => 13,
				'14' => 14,
				'15' => 15,
				'16' => 16,
				'17' => 17,
				'18' => 18,
				'19' => 19,
				'20' => 20,
				'21' => 21,
				'22' => 22,
				'23' => 23,
				'24' => 24,
				'25' => 25,
			)
		);

		$this->set_allowed_image_sizes();
		$this->set_options();
	}

	private function set_options() {
		global $photonic_thumbnail_style, $photonic_enable_popup,
			   $photonic_flickr_media, $photonic_picasa_media, $photonic_smug_media, $photonic_google_media, $photonic_zenfolio_media, $photonic_instagram_media,
		       $photonic_smug_title_caption, $photonic_zenfolio_title_caption, $photonic_flickr_title_caption, $photonic_picasa_use_desc;
		$this->flow_options = array(
			'screen-1' => array('wp', 'flickr', 'picasa', 'google', 'smugmug', 'zenfolio', 'instagram'),
			'screen-2' => array(
				'wp' => array(
					'header' => __('Choose Type of Gallery', 'photonic'),
					'display' => array(
						'display_type' => array(
							'desc' => __('What do you want to show?', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => '',
								'current-post' => __('Gallery attached to the current post', 'photonic'),
								'multi-photo' => __('Photos from Media Library', 'photonic'),
							),
							'req' => 1,
						),
					),
				),
				'flickr' => array(
					'header' => __('Choose Type of Gallery', 'photonic'),
					'display' => array(
						'kind' => array(
							'type' => 'field_list',
							'list_type' => 'sequence',
							'list' => array(
								'display_type' => array(
									'desc' => __('What do you want to show?', 'photonic'),
									'type' => 'select',
									'options' => array(
										'' => '',
										'single-photo' => __('A Single Photo', 'photonic'),
										'multi-photo' => __('Multiple Photos', 'photonic'),
										'album-photo' => __('Photos from an Album / Photoset', 'photonic'),
										'gallery-photo' => __('Photos from a Gallery', 'photonic'),
										'multi-album' => __('Multiple Albums', 'photonic'),
										'multi-gallery' => __('Multiple Galleries', 'photonic'),
										'collection' => __('Albums from a single collection', 'photonic'),
										'collections' => __('Multiple collections', 'photonic'),
									),
									'req' => 1,
								),
								'for' => array(
									'desc' => __('For whom?', 'photonic'),
									'type' => 'radio',
									'options' => array(
										'current' => __('Current user (Defined under <em>Photonic &rarr; Settings &rarr; Flickr &rarr; Flickr Settings &rarr; Default user</em>)', 'photonic'),
										'other' => __('Another user', 'photonic'),
										'group' => __('Group', 'photonic'),
										'any' => __('All users', 'photonic'),
									),
									'option-conditions' => array(
										'group' => array('display_type' => array('multi-photo')),
										'any' => array('display_type' => array('multi-photo')),
									),
									'req' => 1,
								),
								'login' => array(
									'desc' => sprintf(__('User name, e.g. %s', 'photonic'), 'https://www.flickr.com/photos/<span style="text-decoration: underline">username</span>/'),
									'type' => 'text',
									'std' => '',
									'conditions' => array('for' => array('other')),
									'req' => 1,
								),
								'group' => array(
									'desc' => sprintf(__('Group name, e.g. %s', 'photonic'), 'https://www.flickr.com/groups/<span style="text-decoration: underline">groupname</span>/'),
									'type' => 'text',
									'std' => '',
									'conditions' => array('for' => array('group')),
									'req' => 1,
								),
							),
						),
					),
				),
				'smugmug' => array(
					'header' => __('Choose Type of Gallery', 'photonic'),
					'display' => array(
						'kind' => array(
							'type' => 'field_list',
							'list_type' => 'sequence',
							'list' => array(
								'display_type' => array(
									'desc' => __('What do you want to show?', 'photonic'),
									'type' => 'select',
									'options' => array(
										'' => '',
										'album-photo' => __('Photos from an Album', 'photonic'),
										'folder-photo' => __('Photos from a Folder', 'photonic'),
										'user-photo' => __('Photos from a User', 'photonic'),
										'multi-album' => __('Multiple Albums', 'photonic'),
										'folder' => __('Albums in a Folder', 'photonic'),
										'tree' => __('User Tree', 'photonic'),
									),
									'req' => 1,
								),
								'for' => array(
									'desc' => __('For whom?', 'photonic'),
									'type' => 'radio',
									'options' => array(
										'current' => __('Current user (Defined under <em>Photonic &rarr; Settings &rarr; SmugMug &rarr; SmugMug Settings &rarr; Default user</em>)', 'photonic'),
										'other' => __('Another user', 'photonic'),
									),
									'req' => 1,
								),
								'user' => array(
									'desc' => sprintf(__('User name, e.g. %s', 'photonic'), 'https://<span style="text-decoration: underline">username</span>.smugmug.com/'),
									'type' => 'text',
									'std' => '',
									'conditions' => array('for' => array('other')),
									'req' => 1,
								),
							),
						),
					),
				),
				'picasa' => array(
					'header' => __('Choose Type of Gallery', 'photonic'),
					'display' => array(
						'kind' => array(
							'type' => 'field_list',
							'list_type' => 'sequence',
							'list' => array(
								'display_type' => array(
									'desc' => __('What do you want to show?', 'photonic'),
									'type' => 'select',
									'options' => array(
										'' => '',
										'album-photo' => __('Photos from an Album', 'photonic'),
										'multi-album' => __('Multiple Albums', 'photonic'),
									),
									'req' => 1,
								),
								'for' => array(
									'desc' => __('For whom?', 'photonic'),
									'type' => 'radio',
									'options' => array(
										'current' => __('Current user (Defined under <em>Photonic &rarr; Settings &rarr; Picasa &rarr; Picasa Settings &rarr; Default user</em>)', 'photonic'),
										'other' => __('Another user', 'photonic'),
									),
									'req' => 1,
								),
								'user' => array(
									'desc' => sprintf(__('User name, e.g. %s', 'photonic'), '<span style="text-decoration: underline">username</span>@gmail.com'),
									'type' => 'text',
									'std' => '',
									'conditions' => array('for' => array('other')),
									'req' => 1,
								),
							),
						),
					),
				),
				'google' => array(
					'header' => __('Choose Type of Gallery', 'photonic'),
					'display' => array(
						'display_type' => array(
							'desc' => __('What do you want to show?', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => '',
								'multi-photo' => __('Multiple Photos', 'photonic'),
								'album-photo' => __('Photos from an Album', 'photonic'),
								'multi-album' => __('Multiple Albums', 'photonic'),
							),
							'req' => 1,
						),
					),
				),
				'zenfolio' => array(
					'header' => __('Choose Type of Gallery', 'photonic'),
					'display' => array(
						'kind' => array(
							'type' => 'field_list',
							'list_type' => 'sequence',
							'list' => array(
								'display_type' => array(
									'desc' => __('What do you want to show?', 'photonic'),
									'type' => 'select',
									'options' => array(
										'' => '',
										'single-photo' => __('Single Photo', 'photonic'),
										'multi-photo' => __('Multiple Photos', 'photonic'),
										'gallery-photo' => __('Photos from a Gallery or Collection', 'photonic'),
//										'collection-photo' => __('Photos from a Collection', 'photonic'),
										'multi-gallery' => __('Multiple Galleries', 'photonic'),
										'multi-collection' => __('Multiple Collections', 'photonic'),
										'multi-gallery-collection' => __('Multiple Galleries and Collections', 'photonic'),
										'group' => __('Single Group', 'photonic'),
										'group-hierarchy' => __('Group Hierarchy', 'photonic'),
									),
									'req' => 1,
								),
								'for' => array(
									'desc' => __('For whom?', 'photonic'),
									'type' => 'radio',
									'options' => array(
										'current' => __('Current user (Defined under <em>Photonic &rarr; Settings &rarr; Zenfolio &rarr; Zenfolio Photo Settings &rarr; Default user</em>)', 'photonic'),
										'other' => __('Another user', 'photonic'),
										'any' => __('All users', 'photonic'),
									),
									'option-conditions' => array(
										'current' => array('display_type' => array('single-photo', 'gallery-photo', 'collection-photo', 'multi-gallery', 'multi-collection', 'multi-gallery-collection', 'group', 'group-hierarchy')),
										'other' => array('display_type' => array('single-photo', 'gallery-photo', 'collection-photo', 'multi-gallery', 'multi-collection', 'multi-gallery-collection', 'group', 'group-hierarchy')),
										'any' => array('display_type' => array('multi-photo', /*'multi-gallery', 'multi-collection', */)),
									),
									'req' => 1,
								),
								'login_name' => array(
									'desc' => sprintf(__('User name, e.g. %s', 'photonic'), 'https://<span style="text-decoration: underline">username</span>.zenfolio.com/'),
									'type' => 'text',
									'std' => '',
									'conditions' => array('for' => array('other')),
									'req' => 1,
								),
							),
						),
					),
				),
				'instagram' => array(
					'header' => __('Choose Type of Gallery', 'photonic'),
					'display' => array(
						'display_type' => array(
							'desc' => __('What do you want to show?', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => '',
								'single-photo' => __('A Single Photo', 'photonic'),
								'multi-photo' => __('Multiple Photos', 'photonic'),
							),
							'req' => 1,
						),
					),
				),
			),
			'screen-3' => array(
				'wp' => array(),
				'flickr' => array(
					'header' => __('Build your gallery', 'photonic'),
					'single-photo' => array(
						'header' => __('Pick a photo', 'photonic'),
						'desc' => __('From the list below pick the single photo you wish to display.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'multi-photo' => array(
						'header' => __('All your photos', 'photonic'),
						'desc' => __('You can show all your photos, or apply tags to show some of them.', 'photonic'),
						'display' => array(
							'tags' => array(
								'desc' => __('Tags', 'photonic'),
								'type' => 'text',
								'hint' => __('Comma-separated list of tags', 'photonic')
							),

							'tag_mode' => array(
								'desc' => __('Tag mode', 'photonic'),
								'type' => 'select',
								'options' => array(
									'any' => __('Any tag', 'photonic'),
									'all' => __('All tags', 'photonic'),
								),
							),

							'text' => array(
								'desc' => __('With text', 'photonic'),
								'type' => 'text',
							),

							'privacy_filter' => array(
								'desc' => __('Privacy filter', 'photonic'),
								'type' => 'select',
								'options' => array(
									'' => __('None', 'photonic'),
									'1' => __('Public photos', 'photonic'),
									'2' => __('Private photos visible to friends', 'photonic'),
									'3' => __('Private photos visible to family', 'photonic'),
									'4' => __('Private photos visible to friends & family', 'photonic'),
									'5' => __('Completely private photos', 'photonic'),
								),
								'hint' => sprintf(__('Applicable only if Flickr private photos are turned on (%1$s) and Back-end authentication is off (%2$s)', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Flickr &rarr; Flickr Settings &rarr; Allow User Login</em>', '<em>Photonic &rarr; Settings &rarr; Flickr &rarr; Flickr Settings &rarr; Access Token</em>'),
							),

							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'none',
								'for' => 'selected_data',
							),
						),
					),
					'album-photo' => array(
						'header' => __('Pick your album', 'photonic'),
						'desc' => __('From the list below pick the album whose photos you wish to display. Photos from that album will show up as thumbnails.', 'photonic'),
						'display' => array(
							'privacy_filter' => array(
								'desc' => __('Privacy filter', 'photonic'),
								'type' => 'select',
								'options' => array(
									'' => __('None', 'photonic'),
									'1' => __('Public photos', 'photonic'),
									'2' => __('Private photos visible to friends', 'photonic'),
									'3' => __('Private photos visible to family', 'photonic'),
									'4' => __('Private photos visible to friends & family', 'photonic'),
									'5' => __('Completely private photos', 'photonic'),
								),
								'hint' => sprintf(__('Applicable only if Flickr private photos are turned on (%1$s) and Back-end authentication is off (%2$s)', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Flickr &rarr; Flickr Settings &rarr; Allow User Login</em>', '<em>Photonic &rarr; Settings &rarr; Flickr &rarr; Flickr Settings &rarr; Access Token</em>'),
							),

							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'gallery-photo' => array(
						'header' => __('Pick your gallery', 'photonic'),
						'desc' => __('From the list below pick the gallery whose photos you wish to display. Photos from that gallery will show up as thumbnails.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'multi-album' => array(
						'header' => __('Pick your albums / photosets', 'photonic'),
						'desc' => __('From the list below pick the albums / photosets you wish to display. Each album will show up as a single thumbnail.', 'photonic'),
						'display' => array(
							'selection' => array(
								'desc' => __('What do you want to show?', 'photonic'),
								'type' => 'select',
								'options' => array(
									'all' => __('Automatic all (will automatically add new albums)', 'photonic'),
									'selected' => __('Selected albums / photosets', 'photonic'),
									'not-selected' => __('All except selected albums / photosets', 'photonic'),
								),
								'hint' => __('If you pick "Automatic all" your selections below will be ignored.', 'photonic'),
								'req' => 1,
							),
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'multi',
								'for' => 'selected_data',
							),
						),
					),
					'multi-gallery' => array(
						'header' => __('Pick your galleries', 'photonic'),
						'desc' => __('From the list below pick the galleries you wish to display. Each album will show up as a single thumbnail.', 'photonic'),
						'display' => array(
							'selection' => array(
								'desc' => __('What do you want to show?', 'photonic'),
								'type' => 'select',
								'options' => array(
									'all' => __('Automatic all (will automatically add new galleries)', 'photonic'),
									'selected' => __('Selected galleries', 'photonic'),
									'not-selected' => __('All except selected galleries', 'photonic'),
								),
								'req' => 1,
								'hint' => __('If you pick "Automatic all" your selections below will be ignored.', 'photonic'),
							),
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'multi',
								'for' => 'selected_data',
							),
						),
					),
					'collection' => array(
						'header' => __('Pick your collection', 'photonic'),
						'desc' => __('From the list below pick the collection you wish to display. The albums within the collections will show up as single thumbnails.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'collections' => array(
						'header' => __('Pick your collections', 'photonic'),
						'desc' => __('From the list below pick the collections you wish to display. The albums within the collections will show up as single thumbnails.', 'photonic'),
						'display' => array(
							'selection' => array(
								'desc' => __('What do you want to show?', 'photonic'),
								'type' => 'select',
								'options' => array(
									'all' => __('Automatic all (will automatically add new collections)', 'photonic'),
									'selected' => __('Selected collections', 'photonic'),
									'not-selected' => __('All except selected collections', 'photonic'),
								),
								'req' => 1,
								'hint' => __('If you pick "Automatic all" your selections below will be ignored.', 'photonic'),
							),
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'multi',
								'for' => 'selected_data',
							),
						),
					),
				),
				'picasa' => array(
					'header' => __('Build your gallery', 'photonic'),
					'album-photo' => array(
						'header' => __('Pick your album', 'photonic'),
						'desc' => __('From the list below pick the album whose photos you wish to display. Photos from that album will show up as thumbnails.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'multi-album' => array(
						'header' => __('Pick your albums', 'photonic'),
						'desc' => __('From the list below pick the albums you wish to display. Each album will show up as a single thumbnail.', 'photonic'),
						'display' => array(
							'selection' => array(
								'desc' => __('What do you want to show?', 'photonic'),
								'type' => 'select',
								'options' => array(
									'all' => __('Automatic all (will automatically add new albums)', 'photonic'),
									'selected' => __('Selected albums', 'photonic'),
									'not-selected' => __('All except selected albums', 'photonic'),
								),
								'hint' => __('If you pick "Automatic all" your selections below will be ignored.', 'photonic'),
								'req' => 1,
							),
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'multi',
								'for' => 'selected_data',
							),
						),
					),
				),
				'google' => array(
					'header' => __('Build your gallery', 'photonic'),
					'multi-photo' => array(
						'header' => __('All your photos', 'photonic'),
						'desc' => __('You can show all your photos, or apply filters to show some of them.', 'photonic'),
						'display' => array(
							'date_filters' => array(
								'desc' => __('Date Filters', 'photonic'),
								'type' => 'date-filter',
								'count' => 5
							),

							'date_range_filters' => array(
								'desc' => __('Date Range Filters', 'photonic'),
								'type' => 'date-range-filter',
								'count' => 5
							),

							'content_filters' => array(
								'desc' => __('Content Filters', 'photonic'),
								'type' => 'text',
								'hint' => sprintf(__('Comma-separated. Pick from: %1$s. See <a href="%2$s" target="_blank">here</a> for documentation. Filters will be applied on the front-end, not on the display below', 'photonic'), 'NONE, LANDSCAPES, RECEIPTS, CITYSCAPES, LANDMARKS, SELFIES, PEOPLE, PETS, WEDDINGS, BIRTHDAYS, DOCUMENTS, TRAVEL, ANIMALS, FOOD, SPORT, NIGHT, PERFORMANCES, WHITEBOARDS, SCREENSHOTS, UTILITY', 'https://aquoid.com/plugins/photonic/google-photos/photos/#filtering-photos'),
							),

							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'none',
								'for' => 'selected_data',
							),
						),
					),
					'album-photo' => array(
						'header' => __('Pick your album', 'photonic'),
						'desc' => __('From the list below pick the album whose photos you wish to display. Photos from that album will show up as thumbnails.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'multi-album' => array(
						'header' => __('Pick your albums', 'photonic'),
						'desc' => __('From the list below pick the albums you wish to display. Each album will show up as a single thumbnail.', 'photonic'),
						'display' => array(
							'selection' => array(
								'desc' => __('What do you want to show?', 'photonic'),
								'type' => 'select',
								'options' => array(
									'all' => __('Automatic all (will automatically add new albums)', 'photonic'),
									'selected' => __('Selected albums', 'photonic'),
									'not-selected' => __('All except selected albums', 'photonic'),
								),
								'hint' => __('If you pick "Automatic all" your selections below will be ignored.', 'photonic'),
								'req' => 1,
							),
							'access' => array(
								'desc' => __('What type of album?', 'photonic'),
								'type' => 'select',
								'options' => array(
									'' => '',
									'all' => __('Show both shared and not shared albums', 'photonic'),
									'shared' => __('Only show shared albums', 'photonic'),
									'not-shared' => __('Only show albums not shared', 'photonic'),
								),
								'std' => '',
							),
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'multi',
								'for' => 'selected_data',
							),
						),
					),
				),
				'smugmug' => array(
					'header' => __('Build your gallery', 'photonic'),
					'album-photo' => array(
						'header' => __('Pick your album', 'photonic'),
						'desc' => __('From the list below pick the album whose photos you wish to display. Photos from that album will show up as thumbnails.', 'photonic'),
						'display' => array(
							'text' => array(
								'desc' => __('Only show photos with this text', 'photonic'),
								'type' => 'text',
								'std' => '',
								'hint' => __('Comma-separated list of values. Filters will be applied on the front-end, not on the display below', 'photonic'),
							),
							'keywords' => array(
								'desc' => __('Only show photos with these keywords', 'photonic'),
								'type' => 'text',
								'std' => '',
								'hint' => __('Comma-separated list of values. Filters will be applied on the front-end, not on the display below', 'photonic'),
							),
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'folder-photo' => array(
						'header' => __('Pick your folder', 'photonic'),
						'desc' => __('From the list below pick the folder whose photos you wish to display. Photos from that folder will show up as thumbnails.', 'photonic'),
						'display' => array(
							'text' => array(
								'desc' => __('Only show photos with this text', 'photonic'),
								'type' => 'text',
								'std' => '',
								'hint' => __('Comma-separated list of values. Filters will be applied on the front-end, not on the display below', 'photonic'),
							),
							'keywords' => array(
								'desc' => __('Only show photos with these keywords', 'photonic'),
								'type' => 'text',
								'std' => '',
								'hint' => __('Comma-separated list of values. Filters will be applied on the front-end, not on the display below.', 'photonic'),
							),
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'user-photo' => array(
						'header' => __('Photos for a User', 'photonic'),
						'desc' => __('The following lists the top-level folders and albums for the selected user. All photos from these folders will show up as thumbnails.', 'photonic'),
						'display' => array(
							'text' => array(
								'desc' => __('Only show photos with this text', 'photonic'),
								'type' => 'text',
								'std' => '',
								'hint' => __('Comma-separated list of values', 'photonic'),
							),
							'keywords' => array(
								'desc' => __('Only show photos with these keywords', 'photonic'),
								'type' => 'text',
								'std' => '',
								'hint' => __('Comma-separated list of values', 'photonic'),
							),
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'none',
								'for' => 'selected_data',
							),
						),
					),
					'multi-album' => array(
						'header' => __('Pick your albums', 'photonic'),
						'desc' => __('From the list below pick the albums you wish to display. Each album will show up as a single thumbnail.', 'photonic'),
						'display' => array(
							'selection' => array(
								'desc' => __('What do you want to show?', 'photonic'),
								'type' => 'select',
								'options' => array(
									'all' => __('Automatic all (will automatically add new albums)', 'photonic'),
									'selected' => __('Selected albums', 'photonic'),
									'not-selected' => __('All except selected albums', 'photonic'),
								),
								'hint' => __('If you pick "Automatic all" your selections below will be ignored.', 'photonic'),
								'req' => 1,
							),
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'multi',
								'for' => 'selected_data',
							),
						),
					),
					'folder' => array(
						'header' => __('Pick your folder', 'photonic'),
						'desc' => __('From the list below pick the folder you wish to display. The albums within the folder will show up as single thumbnails.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'tree' => array(
						'header' => __('User Tree', 'photonic'),
						'desc' => __('The following user tree will be displayed on your site. Only top level folders and albums are shown here. The albums within the folders will show up as single thumbnails and can be clicked to show the images within.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'none',
								'for' => 'selected_data',
							),
						),
					),
				),
				'zenfolio' => array(
					'header' => __('Build your gallery', 'photonic'),
					'single-photo' => array(
						'header' => __('Pick a photo', 'photonic'),
						'desc' => __('From the list below pick the single photo you wish to display.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'multi-photo' => array(
						'header' => __('Photos from all users', 'photonic'),
						'desc' => __('You can show photos from all users, and apply text or category filters to show some of them.', 'photonic'),
						'display' => array(
							'text' => array(
								'desc' => __('With text', 'photonic'),
								'type' => 'text',
							),

							'category_code' => array(
								'desc' => __('Category', 'photonic'),
								'type' => 'select',
								'options' => $this->get_zenfolio_categories(),
							),

							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'none',
								'for' => 'selected_data',
							),
						),
					),
					'gallery-photo' => array(
						'header' => __('Pick your gallery', 'photonic'),
						'desc' => __('From the list below pick the gallery whose photos you wish to display. Photos from that gallery will show up as thumbnails.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'collection-photo' => array(
						'header' => __('Pick your collection', 'photonic'),
						'desc' => __('From the list below pick the collection whose photos you wish to display. Photos from that collection will show up as thumbnails.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'multi-gallery' => array(
						'header' => __('Pick your galleries', 'photonic'),
						'desc' => __('From the list below pick the galleries you wish to display. Each album will show up as a single thumbnail. Note that text and category filters are not applied here but will be applied on the front-end.', 'photonic'),
						'display' => array(
							'selection' => array(
								'desc' => __('What do you want to show?', 'photonic'),
								'type' => 'select',
								'options' => array(
									'all' => __('Automatic all (will automatically add new galleries)', 'photonic'),
									'selected' => __('Selected galleries', 'photonic'),
									'not-selected' => __('All except selected galleries', 'photonic'),
								),
								'req' => 1,
								'hint' => __('If you pick "Automatic all" your selections below will be ignored.', 'photonic'),
							),

							'text' => array(
								'desc' => __('With text', 'photonic'),
								'type' => 'text',
							),

							'category_code' => array(
								'desc' => __('Category', 'photonic'),
								'type' => 'select',
								'options' => $this->get_zenfolio_categories(),
							),

							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'multi',
								'for' => 'selected_data',
							),
						),
					),
					'multi-collection' => array(
						'header' => __('Pick your collections', 'photonic'),
						'desc' => __('From the list below pick the collections you wish to display. Each collection will show up as a single thumbnail. Note that text and category filters are not applied here but will be applied on the front-end.', 'photonic'),
						'display' => array(
							'selection' => array(
								'desc' => __('What do you want to show?', 'photonic'),
								'type' => 'select',
								'options' => array(
									'all' => __('Automatic all (will automatically add new collections)', 'photonic'),
									'selected' => __('Selected collections', 'photonic'),
									'not-selected' => __('All except selected collections', 'photonic'),
								),
								'hint' => __('If you pick "Automatic all" your selections below will be ignored.', 'photonic'),
								'req' => 1,
							),

							'text' => array(
								'desc' => __('With text', 'photonic'),
								'type' => 'text',
							),

							'category_code' => array(
								'desc' => __('Category', 'photonic'),
								'type' => 'select',
								'options' => $this->get_zenfolio_categories(),
							),

							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'multi',
								'for' => 'selected_data',
							),
						),
					),
					'multi-gallery-collection' => array(
						'header' => __('Pick your galleries and collections', 'photonic'),
						'desc' => __('From the list below pick the galleries and collections you wish to display. Each gallery and collection will show up as a single thumbnail. Note that text and category filters are not applied here but will be applied on the front-end.', 'photonic'),
						'display' => array(
							'selection' => array(
								'desc' => __('What do you want to show?', 'photonic'),
								'type' => 'select',
								'options' => array(
									'all' => __('Automatic all (will automatically add new galleries and collections)', 'photonic'),
									'selected' => __('Selected galleries and collections', 'photonic'),
									'not-selected' => __('All except selected galleries and collections', 'photonic'),
								),
								'hint' => __('If you pick "Automatic all" your selections below will be ignored.', 'photonic'),
								'req' => 1,
							),

							'text' => array(
								'desc' => __('With text', 'photonic'),
								'type' => 'text',
							),

							'category_code' => array(
								'desc' => __('Category', 'photonic'),
								'type' => 'select',
								'options' => $this->get_zenfolio_categories(),
							),

							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'multi',
								'for' => 'selected_data',
							),
						),
					),
					'group' => array(
						'header' => __('Pick your group', 'photonic'),
						'desc' => __('From the list below pick the group you wish to display. The galleries / collections within the group will show up as single thumbnails.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'group-hierarchy' => array(
						'header' => __('Your group hierarchy', 'photonic'),
						'desc' => __('The following group hierarchy will be displayed on your site. Only top level groups and galleries / collections are shown here. The galleries / collections within the groups will show up as single thumbnails and can be clicked to show the images within.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'none',
								'for' => 'selected_data',
							),
						),
					),
				),
				'instagram' => array(
					'header' => __('Build your gallery', 'photonic'),
					'single-photo' => array(
						'header' => __('Pick a photo', 'photonic'),
						'desc' => __('From the list below pick the single photo you wish to display.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'single',
								'for' => 'selected_data',
							),
						),
					),
					'multi-photo' => array(
						'header' => __('All your photos', 'photonic'),
						'desc' => __('Though Photonic has the capability to handle tags, Instagram has not granted it permission to display photos by tag. You can only show all your photos without filtering. In the following only the latest 20 photos are displayed. You can change this in subsequent screens.', 'photonic'),
						'display' => array(
							'container' => array(
								'type' => 'thumbnail-selector',
								'mode' => 'none',
								'for' => 'selected_data',
							),
						),
					),
				),
			),
			'screen-4' => array(
				'wp' => array(),
				'flickr' => array(),
				'picasa' => array(),
				'google' => array(),
				'smugmug' => array(),
				'zenfolio' => array(),
				'instagram' => array(),
			),
			'screen-5' => array(
				'wp' => array(
					'count' => array(
						'desc' => __('Number of photos to show', 'photonic'),
						'type' => 'text',
						'hint' => __('Numeric values only. Shows all photos by default.', 'photonic'),
					),
					'main_size' => array(
						'desc' => __('Main image size', 'photonic'),
						'type' => 'select',
						'options' => $this->allowed_image_sizes['wp']['main_size'],
						'std' => 'full',
					),
				),
				'flickr' => array(
					'L1' => array(
						'sort' => array(
							'desc' => __('Sort by', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => '',
								'date-posted-desc' => __('Date posted, descending', 'photonic'),
								'date-posted-asc' => __('Date posted, ascending', 'photonic'),
								'date-taken-asc' => __('Date taken, ascending', 'photonic'),
								'date-taken-desc' => __('Date taken, descending', 'photonic'),
								'interestingness-desc' => __('Interestingness, descending', 'photonic'),
								'interestingness-asc' => __('Interestingness, ascending', 'photonic'),
								'relevance' => __('Relevance', 'photonic'),
							),
						),
						'media' => array(
							'desc' => __('Media to Show', 'photonic'),
							'type' => 'select',
							'options' => Photonic::media_options(true, $photonic_flickr_media),
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Flickr &rarr; Flickr Settings &rarr; Media to show</em>'),
						),
						'caption' => array(
							'desc' => __('Photo titles and captions', 'photonic'),
							'type' => 'select',
							'options' => Photonic::title_caption_options(true, $photonic_flickr_title_caption),
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Flickr &rarr; Flickr Settings &rarr; Photo titles and captions</em>'),
						),
						'headers' => array(
							'desc' => __('Show Header', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => __('Default from settings', 'photonic'),
								'none' => __('No header', 'photonic'),
								'title' => __('Title only', 'photonic'),
								'thumbnail' => __('Thumbnail only', 'photonic'),
								'counter' => __('Counts only', 'photonic'),
								'title,counter' => __('Title and counts', 'photonic'),
								'thumbnail,counter' => __('Thumbnail and counts', 'photonic'),
								'thumbnail,title' => __('Thumbnail and title', 'photonic'),
								'thumbnail,title,counter' => __('Thumbnail, title and counts', 'photonic'),
							),
							'conditions' => array('display_type' => array('album-photo', 'gallery-photo')),
						),
					),
					'L2' => array(),
					'L3' => array(
						'collections_display' => array(
							'desc' => __('Expand Collections', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => '',
								'lazy' => __('Lazy loading', 'photonic'),
								'expanded' => __('Expanded upfront', 'photonic'),
							),
							'hint' => __('The Collections API is slow, so, if you are displaying collections, pick <a href="https://aquoid.com/plugins/photonic/flickr/flickr-collections/" target="_blank">lazy loading</a> if your collections have many albums / photosets.', 'photonic'),
						),
						'headers' => array(
							'desc' => __('Show Header', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => __('Default from settings', 'photonic'),
								'none' => __('No header', 'photonic'),
								'title' => __('Title only', 'photonic'),
								'thumbnail' => __('Thumbnail only', 'photonic'),
								'counter' => __('Counts only', 'photonic'),
								'title,counter' => __('Title and counts', 'photonic'),
								'thumbnail,counter' => __('Thumbnail and counts', 'photonic'),
								'thumbnail,title' => __('Thumbnail and title', 'photonic'),
								'thumbnail,title,counter' => __('Thumbnail, title and counts', 'photonic'),
							),
						),
					),
					'main_size' => array(
						'desc' => __('Main image size', 'photonic'),
						'type' => 'select',
						'options' => $this->allowed_image_sizes['flickr']['main_size'],
						'std' => '',
						'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Flickr &rarr; Flickr Settings &rarr; Main image size</em>'),
					),
					'video_size' => array(
						'desc' => __('Main video size', 'photonic'),
						'type' => 'select',
						'options' => $this->allowed_image_sizes['flickr']['video_size'],
						'std' => '',
						'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Flickr &rarr; Flickr Settings &rarr; Video size</em>'),
					),
				),
				'picasa' => array(
					'L1' => array(
						'media' => array(
							'desc' => __('Media to Show', 'photonic'),
							'type' => 'select',
							'options' => Photonic::media_options(true, $photonic_picasa_media),
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Picasa &rarr; Picasa Settings &rarr; Media to show</em>'),
						),
						'caption' => array(
							'desc' => __('Photo titles and captions', 'photonic'),
							'type' => 'select',
							'options' => Photonic::title_caption_options(true, $photonic_picasa_use_desc),
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Picasa &rarr; Picasa Settings &rarr; Photo titles and captions</em>'),
						),
					),
					'L2' => array(
						'access' => array(
							'desc' => __('Displayed Access Levels', 'photonic'),
							'type' => 'select',
							'options' => array(
								'private,protected,public' => __('Show all public, protected and private albums', 'photonic'),
								'public' => __('Show public albums only', 'photonic'),
								'protected' => __('Show protected albums only', 'photonic'),
								'protected,public' => __('Show public and protected albums', 'photonic'),
								'private,protected' => __('Show protected and private albums', 'photonic'),
								'private,public' => __('Show public and private albums', 'photonic'),
							),
							'std' => 'public',
							'hint' => __('You can show public, private (Picasa only) or protected (Google Photos only) albums. You can use the <code>album</code> or <code>filter</code> attributes to filter the content further. See <a href="https://aquoid.com/plugins/photonic/picasa/picasa-albums/" target="_blank">here</a> for more details.', 'photonic')
						),
						'protection' => array(
							'desc' => __('Protection for Private Albums', 'photonic'),
							'type' => 'select',
							'options' => array(
								'none' => __('None - visitors see albums without providing the authkey', 'photonic'),
								'authkey' => __('Authkey - visitors are prompted for the authkey', 'photonic'),
							),
							'std' => 'none',
							'hint' => __('Will prompt your users for an <code>authkey</code> before they see the photos in your old Picasa private album. Not applicable to new Google Photos albums. See <a href="https://aquoid.com/plugins/photonic/picasa/picasa-albums/" target="_blank">here</a> for more details.', 'photonic')
						),
					),
					'main_size' => array(
						'desc' => __('Main image size', 'photonic'),
						'type' => 'select',
						'options' => $this->allowed_image_sizes['picasa']['main_size'],
						'std' => 1600,
					),
				),
				'google' => array(
					'L1' => array(
						'media' => array(
							'desc' => __('Media to Show', 'photonic'),
							'type' => 'select',
							'options' => Photonic::media_options(true, $photonic_google_media),
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Google Photos &rarr; Google Photos settings &rarr; Media to show</em>'),
						),
					),
					'main_size' => array(
						'desc' => __('Main image size', 'photonic'),
						'type' => 'text',
						'std' => 1600,
						'hint' => __('Numeric values between 1 and 16383, both inclusive.', 'photonic'),
					),
				),
				'smugmug' => array(
					'L1' => array(
						'media' => array(
							'desc' => __('Media to Show', 'photonic'),
							'type' => 'select',
							'options' => Photonic::media_options(true, $photonic_smug_media),
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; SmugMug &rarr; SmugMug Settings &rarr; Media to show</em>'),
						),
						'caption' => array(
							'desc' => __('Photo titles and captions', 'photonic'),
							'type' => 'select',
							'options' => Photonic::title_caption_options(true, $photonic_smug_title_caption),
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; SmugMug &rarr; SmugMug Settings &rarr; Photo titles and captions</em>'),
						),
						'password' => array(
							'desc' => __('Password for password-protected album', 'photonic'),
							'type' => 'text',
							'req' => 1,
							'hint' => __('You are trying to display photos from a password-protected album. The password is mandatory for such an album.', 'photonic'),
							'conditions' => array('selection_passworded' => array('1')),
						),
						'sort_method' => array(
							'desc' => __('Sort photos by', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => '',
								'DateTaken' => __('Date Taken', 'photonic'),
								'DateUploaded' => __('Date Uploaded', 'photonic'),
								'Popular' => __('Popular', 'photonic'),
							),
						),
						'sort_order' => array(
							'desc' => __('Sort order', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => '',
								'Ascending' => __('Ascending', 'photonic'),
								'Descending' => __('Descending', 'photonic'),
							),
						),
						'headers' => array(
							'desc' => __('Show Header', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => __('Default from settings', 'photonic'),
								'none' => __('No header', 'photonic'),
								'title' => __('Title only', 'photonic'),
								'thumbnail' => __('Thumbnail only', 'photonic'),
								'counter' => __('Counts only', 'photonic'),
								'title,counter' => __('Title and counts', 'photonic'),
								'thumbnail,counter' => __('Thumbnail and counts', 'photonic'),
								'thumbnail,title' => __('Thumbnail and title', 'photonic'),
								'thumbnail,title,counter' => __('Thumbnail, title and counts', 'photonic'),
							),
						),
					),
					'L2' => array(
						'site_password' => array(
							'desc' => __('Site Password for password-protected sites', 'photonic'),
							'type' => 'text',
							'hint' => __('If you SmugMug site is password-protected you will need to provide the password to be able to show your photos.', 'photonic'),
						),
						'album_sort_order' => array(
							'desc' => __('Album sort order', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => __('Default from settings', 'photonic'),
								'Last Updated (Descending)' => __('Last Updated (Descending)', 'photonic'),
								'Last Updated (Ascending)' => __('Last Updated (Ascending)', 'photonic'),
								'Date Added (Descending)' => __('Date Added (Descending)', 'photonic'),
								'Date Added (Ascending)' => __('Date Added (Ascending)', 'photonic'),
							),
						),
					),
					'L3' => array(
						'site_password' => array(
							'desc' => __('Site Password for password-protected sites', 'photonic'),
							'type' => 'text',
							'hint' => __('If you SmugMug site is password-protected you will need to provide the password to be able to show your photos.', 'photonic'),
						),
					),
					'main_size' => array(
						'desc' => __('Main image size', 'photonic'),
						'type' => 'select',
						'options' => $this->allowed_image_sizes['smugmug']['main_size'],
						'std' => '',
						'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; SmugMug &rarr; SmugMug Settings &rarr; Main image size</em>'),
					),
					'video_size' => array(
						'desc' => __('Main video size', 'photonic'),
						'type' => 'select',
						'options' => $this->allowed_image_sizes['smugmug']['video_size'],
						'std' => '',
						'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; SmugMug &rarr; SmugMug Settings &rarr; Video size</em>'),
					),
				),
				'zenfolio' => array(
					'L1' => array(
						'media' => array(
							'desc' => __('Media to Show', 'photonic'),
							'type' => 'select',
							'options' => Photonic::media_options(true, $photonic_zenfolio_media),
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Zenfolio &rarr; Zenfolio Photo Settings &rarr; Media to show</em>'),
						),
						'caption' => array(
							'desc' => __('Photo titles and captions', 'photonic'),
							'type' => 'select',
							'options' => Photonic::title_caption_options(true, $photonic_zenfolio_title_caption),
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Zenfolio &rarr; Zenfolio Photo Settings &rarr; Photo titles and captions</em>'),
						),
						'sort_order' => array(
							'desc' => __('Search results sort order', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => '',
								'Date' => __('Date', 'photonic'),
								'Popularity' => __('Popularity', 'photonic'),
								'Rank' => __('Rank (for searching by text only)', 'photonic'),
							),
						),
						'password' => array(
							'desc' => __('Password for password-protected album', 'photonic'),
							'type' => 'text',
							'req' => 1,
							'hint' => __('You are trying to display photos from a password-protected album. The password is mandatory for such an album.', 'photonic'),
							'conditions' => array('selection_passworded' => array('1')),
						),
					),
					'L2' => array(
						'sort_order' => array(
							'desc' => __('Search results sort order', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => '',
								'Date' => __('Date', 'photonic'),
								'Popularity' => __('Popularity', 'photonic'),
								'Rank' => __('Rank (for searching by text only)', 'photonic'),
							),
						),
					),
					'L3' => array(
						'structure' => array(
							'desc' => __('Group / Hierarchy structure', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => '',
								'flat' => __('All photosets shown in single level', 'photonic'),
								'nested' => __('Photosets shown nested within groups', 'photonic'),
							),
							'hint' => __('See examples <a href="https://aquoid.com/plugins/photonic/zenfolio/group-hierarchy/" target="_blank">here</a>.', 'photonic'),
						),
						'headers' => array(
							'desc' => __('Show Group Header', 'photonic'),
							'type' => 'select',
							'options' => array(
								'' => __('Default from settings', 'photonic'),
								'none' => __('No header', 'photonic'),
								'title' => __('Title only', 'photonic'),
								'counter' => __('Counts only', 'photonic'),
								'title,counter' => __('Title and counts', 'photonic'),
							),
						),
					),
					'main_size' => array(
						'desc' => __('Main image size', 'photonic'),
						'type' => 'select',
						'options' => $this->allowed_image_sizes['zenfolio']['main_size'],
						'std' => '',
						'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Zenfolio &rarr; Zenfolio Photo Settings &rarr; Main image size</em>'),
					),
					'video_size' => array(
						'desc' => __('Main video size', 'photonic'),
						'type' => 'select',
						'options' => $this->allowed_image_sizes['zenfolio']['video_size'],
						'std' => '',
						'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Zenfolio &rarr; Zenfolio Photo Settings &rarr; Video size</em>'),
					),
				),
				'instagram' => array(
					'media' => array(
						'desc' => __('Media to Show', 'photonic'),
						'type' => 'select',
						'options' => Photonic::media_options(true, $photonic_instagram_media),
						'std' => '',
						'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Instagram &rarr; Instagram Settings &rarr; Media to show</em>'),
					),
					'main_size' => array(
						'desc' => __('Main image size', 'photonic'),
						'type' => 'select',
						'options' => $this->allowed_image_sizes['instagram']['main_size'],
						'std' => '',
						'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Instagram &rarr; Instagram Settings &rarr; Expanded size</em>'),
					),
					'video_size' => array(
						'desc' => __('Main video size', 'photonic'),
						'type' => 'select',
						'options' => $this->allowed_image_sizes['instagram']['video_size'],
						'std' => '',
						'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Instagram &rarr; Instagram Settings &rarr; Expanded video size</em>'),
					),
				),
				'slideshow' => array(
					'slideshow-style' => array(
						'desc' => __('Slideshow display style', 'photonic'),
						'type' => 'image-select',
						'options' => array(
							'strip-below' => __('Thumbnail strip or buttons below slideshow', 'photonic'),
							'strip-above' => __('Thumbnail strip above slideshow', 'photonic'),
							'strip-right' => __('Thumbnail strip to the right of slideshow', 'photonic'),
							'no-strip' => __('No thumbnails or buttons for the slideshow', 'photonic'),
						),
						'std' => $photonic_thumbnail_style,
					),
					'strip-style' => array(
						'desc' => __('Thumbnails or buttons for the strip?', 'photonic'),
						'type' => 'image-select',
						'options' => array(
							'thumbs' => __('Thumbnails', 'photonic'),
							'button' => __('Buttons', 'photonic'),
						),
						'hint' => __('If you choose "Buttons" those are only shown below the slideshow.', 'photonic'),
						'std' => 'thumbs',
					),
					'controls' => array(
						'desc' => __('Slideshow Controls', 'photonic'),
						'type' => 'select',
						'options' => array(
							'hide' => __('Hide', 'photonic'),
							'show' => __('Show', 'photonic'),
						),
						'hint' => __('Shows Previous and Next buttons on the slideshow.', 'photonic'),
					),
					'fx' => array(
						'desc' => __('Slideshow Effects', 'photonic'),
						'type' => 'select',
						'options' => array(
							'fade' => __('Fade', 'photonic'),
							'slide' => __('Slide', 'photonic'),
						),
						'hint' => __('Determines if a photo in a slideshow should fade in or slide in.', 'photonic')
					),
					'timeout' => array(
						'desc' => __('Time between slides in ms', 'photonic'),
						'type' => 'text',
						'std' => '',
						'hint' => __('Please enter numbers only', 'photonic')
					),
					'speed' => array(
						'desc' => __('Time for each transition in ms', 'photonic'),
						'type' => 'text',
						'std' => '',
						'hint' => __('How fast do you want the fade or slide effect to happen?', 'photonic')
					),
					'pause' => array(
						'desc' => __('Pause upon hover?', 'photonic'),
						'type' => 'select',
						'options' => array(
							'0' => __('No', 'photonic'),
							'1' => __('Yes', 'photonic'),
						),
						'hint' => __('Should the slideshow pause when you hover over it?', 'photonic')
					),
					'columns' => array(
						'desc' => __('Number of columns in slideshow', 'photonic'),
						'type' => 'select',
						'options' => array(
							'' => '',
							'1' => 1,
							'2' => 2,
							'3' => 3,
							'4' => 4,
							'5' => 5,
							'6' => 6,
							'7' => 7,
							'8' => 8,
							'9' => 9,
							'10' => 10,
						),
						'hint' => __('Pick > 1 for a carousel', 'photonic'),
					),
				),
				'square' => array(
					'columns' => $this->column_options,
					'flickr' => array(
						'thumb_size' => array(
							'desc' => __('Thumbnail size', 'photonic'),
							'type' => 'select',
							'options' => $this->allowed_image_sizes['flickr']['thumb_size'],
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Flickr &rarr; Flickr Settings &rarr; Thumbnail size</em>'),
						),
					),
					'picasa' => array(
						'thumb_size' => array(
							'desc' => __('Thumbnail size', 'photonic'),
							'type' => 'select',
							'options' => $this->allowed_image_sizes['picasa']['thumb_size'],
							'std' => '150c',
						),
					),
					'google' => array(
						'thumb_size' => array(
							'desc' => __('Thumbnail size', 'photonic'),
							'type' => 'text',
							'hint' => __('Numeric values between 1 and 256, both inclusive.', 'photonic'),
							'std' => 150,
						),
						'crop_thumb' => array(
							'desc' => __('Crop Thumbnail', 'photonic'),
							'type' => 'select',
							'options' => array(
								'crop' => __('Crop the thumbnail', 'photonic'),
								'no-crop' => __('Do not crop the thumbnail', 'photonic'),
							),
							'std' => 'crop',
							'hint' => __('Cropping the thumbnail presents you with a square thumbnail.', 'photonic')
						),
					),
					'smugmug' => array(
						'thumb_size' => array(
							'desc' => __('Thumbnail size', 'photonic'),
							'type' => 'select',
							'options' => $this->allowed_image_sizes['smugmug']['thumb_size'],
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; SmugMug &rarr; SmugMug Settings &rarr; Thumbnail size</em>'),
						),
					),
					'zenfolio' => array(
						'thumb_size' => array(
							'desc' => __('Thumbnail size', 'photonic'),
							'type' => 'select',
							'options' => $this->allowed_image_sizes['zenfolio']['thumb_size'],
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Zenfolio &rarr; Zenfolio Photo Settings &rarr; Thumbnail size</em>'),
						),
					),
					'instagram' => array(
						'thumb_size' => array(
							'desc' => __('Thumbnail size', 'photonic'),
							'type' => 'text',
							'hint' => __('Numeric values only. Leave blank for default.', 'photonic'),
							'std' => 150,
						),
					),
					'wp' => array(
						'thumb_size' => array(
							'desc' => __('Thumbnail size', 'photonic'),
							'type' => 'select',
							'options' => $this->allowed_image_sizes['wp']['thumb_size'],
							'std' => 'thumbnail',
						),
					),
					'title_position' => $this->get_title_position_options(),
				),
				'random' => array(
					'flickr' => array(
						'tile_size' => array(
							'desc' => __('Tile size', 'photonic'),
							'type' => 'select',
							'options' => $this->allowed_image_sizes['flickr']['tile_size'],
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Flickr &rarr; Flickr Settings &rarr; Tile image size</em>'),
						),
					),
					'picasa' => array(
						'tile_size' => array(
							'desc' => __('Tile size', 'photonic'),
							'type' => 'select',
							'options' => $this->allowed_image_sizes['picasa']['tile_size'],
							'std' => 1600,
						),
					),
					'google' => array(
						'tile_size' => array(
							'desc' => __('Tile size', 'photonic'),
							'type' => 'text',
							'hint' => __('Numeric values between 1 and 16383, both inclusive. Leave blank to use the "Main image size".', 'photonic'),
						),
					),
					'smugmug' => array(
						'tile_size' => array(
							'desc' => __('Tile size', 'photonic'),
							'type' => 'select',
							'options' => $this->allowed_image_sizes['smugmug']['tile_size'],
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; SmugMug &rarr; SmugMug Settings &rarr; Tile image size</em>'),
						),
					),
					'zenfolio' => array(
						'tile_size' => array(
							'desc' => __('Tile size', 'photonic'),
							'type' => 'select',
							'options' => $this->allowed_image_sizes['zenfolio']['tile_size'],
							'std' => '',
							'hint' => sprintf(__('Default settings can be configured under: %s', 'photonic'), '<em>Photonic &rarr; Settings &rarr; Zenfolio &rarr; Zenfolio Photo Settings &rarr; Tile image size</em>'),
						),
					),
					'wp' => array(
						'tile_size' => array(
							'desc' => __('Tile size', 'photonic'),
							'type' => 'select',
							'options' => $this->allowed_image_sizes['wp']['tile_size'],
							'std' => 'full',
						),
					),
					'title_position' => $this->get_title_position_options(),
				),
				'L1' => array(
					'count' => array(
						'desc' => __('Number of photos to show', 'photonic'),
						'type' => 'text',
						'hint' => __('Numeric values only. Leave blank for default.', 'photonic'),
					),
					'more' => array(
						'desc' => __('"More" button text', 'photonic'),
						'type' => 'text',
						'hint' => __('Will show a "More" button with the specified text if the number of photos is higher than the above entry. Leave blank to show no button', 'photonic'),
					),
				),
				'L2' => array(
					'count' => array(
						'desc' => __('Number of albums to show', 'photonic'),
						'type' => 'text',
						'hint' => __('Numeric values only. Leave blank for default.', 'photonic'),
					),
					'more' => array(
						'desc' => __('"More" button text', 'photonic'),
						'type' => 'text',
						'hint' => __('Will show a "More" button with the specified text if the number of albums is higher than the above entry. Leave blank to show no button', 'photonic'),
					),
					'popup_type' => array(
						'type' => 'field_list',
						'list_type' => 'sequence',
						'list' => array(
							'popup' => array(
								'desc' => __('Show an overlaid popup panel', 'photonic'),
								'type' => 'select',
								'options' => array(
									'' => '',
									'hide' => __('No', 'photonic'),
									'show' => __('Yes', 'photonic'),
								),
								'std' => empty($photonic_enable_popup) ? 'hide' : 'show',
								'hint' => sprintf(__('Setting this to "No" would directly start up a lightbox with photos. Setting this to "Yes" would show an overlaid panel that has the photos. See %1$sdocumentation%2$s.', 'photonic'), '<a href="https://aquoid.com/plugins/photonic/layouts/#nested" target="_blank">', '</a>'),
							),
							'photo_count' => array(
								'desc' => __('Number of photos to show in overlaid popup', 'photonic'),
								'type' => 'text',
								'hint' => __('Numeric values only. Leave blank for default.', 'photonic'),
								'conditions' => array('popup' => array('show')),
							),
							'photo_more' => array(
								'desc' => __('"More" button text in overlaid popup', 'photonic'),
								'type' => 'text',
								'hint' => __('Will show a "More" button with the specified text if the number of photos in the overlaid popup is higher than the above entry. Leave blank to show no button', 'photonic'),
								'conditions' => array('popup' => array('show')),
							)
						)
					),
				),
				'L3' => array(),
			),
		);

		$this->flow_options['screen-5']['circle'] = $this->flow_options['screen-5']['square'];
		$this->flow_options['screen-5']['masonry'] = $this->flow_options['screen-5']['random'];
		$this->flow_options['screen-5']['masonry']['columns'] = $this->column_options;
		$this->flow_options['screen-5']['mosaic'] = $this->flow_options['screen-5']['random'];
		unset($this->flow_options['screen-5']['random']['title_position']['options']['below']);
		unset($this->flow_options['screen-5']['mosaic']['title_position']['options']['below']);
		$this->flow_options['screen-5']['L3']['popup_type'] = $this->flow_options['screen-5']['L2']['popup_type'];
	}

	private function get_zenfolio_categories() {
		$response = wp_remote_request('https://api.zenfolio.com/api/1.8/zfapi.asmx/GetCategories', array('sslverify' => PHOTONIC_SSL_VERIFY));
		$category_list = array('' => '');

		if (!is_wp_error($response)) {
			if (isset($response['response']) && isset($response['response']['code'])) {
				if ($response['response']['code'] == 200) {
					if (isset($response['body'])) {
						$response = simplexml_load_string($response['body']);
						if (!empty($response->Category)) {
							$categories = $response->Category;
							foreach ($categories as $category) {
								$category_list[esc_attr($category->Code)] = $category->DisplayName;
							}
						}
					}
				}
			}
		}
		asort($category_list);
		return $category_list;
	}

	private function set_allowed_image_sizes() {
		$this->allowed_image_sizes = array(
			'flickr' => array(
				'thumb_size' => array(
					'' => __('Default from settings', 'photonic'),
					's' => __('Small square, 75x75px', 'photonic'),
					'q' => __('Large square, 150x150px', 'photonic'),
					't' => __('Thumbnail, 100px on longest side', 'photonic'),
					'm' => __('Small, 240px on longest side', 'photonic'),
					'n' => __('Small, 320px on longest side', 'photonic'),
				),
				'tile_size' => array(
					'' => __('Default from settings', 'photonic'),
					'same' => __('Same as Main image size', 'photonic'),
					'n' => __('Small, 320px on longest side', 'photonic'),
					'none' => __('Medium, 500px on the longest side', 'photonic'),
					'z' => __('Medium, 640px on longest side', 'photonic'),
					'c' => __('Medium, 800px on longest side', 'photonic'),
					'b' => __('Large, 1024px on longest side', 'photonic'),
					'h' => __('Large, 1600px on longest side', 'photonic'),
					'k' => __('Large, 2048px on longest side', 'photonic'),
					'o' => __('Original', 'photonic'),
				),
				'main_size' => array(
					'' => __('Default from settings', 'photonic'),
					'none' => __('Medium, 500px on the longest side', 'photonic'),
					'z' => __('Medium, 640px on longest side', 'photonic'),
					'c' => __('Medium, 800px on longest side', 'photonic'),
					'b' => __('Large, 1024px on longest side', 'photonic'),
					'h' => __('Large, 1600px on longest side', 'photonic'),
					'k' => __('Large, 2048px on longest side', 'photonic'),
					'o' => __('Original', 'photonic'),
				),
				'video_size' => array(
					'' => __('Default from settings', 'photonic'),
					'Site MP4' => __('Site MP4', 'photonic'),
					'Mobile MP4' => __('Mobile MP4', 'photonic'),
					'HD MP4' => __('HD MP4', 'photonic'),
					'Video Original' => __('Video Original', 'photonic'),
				),
			),
			'smugmug' => array(
				'thumb_size' => array(
					'' => __('Default from settings', 'photonic'),
					'Tiny' => __('Tiny', 'photonic'),
					'Thumb' => __('Thumb', 'photonic'),
					'Small' => __('Small', 'photonic'),
				),
				'tile_size' => array(
					'' => __('Default from settings', 'photonic'),
					'same' => __('Same as Main image size', 'photonic'),
					'4K' => __('4K (not always available)', 'photonic'),
					'5K' => __('5K (not always available)', 'photonic'),
					'Medium' => __('Medium', 'photonic'),
					'Original' => __('Original (not always available)', 'photonic'),
					'Large' => __('Large', 'photonic'),
					'Largest' => __('Largest available', 'photonic'),
					'XLarge' => __('XLarge (not always available)', 'photonic'),
					'X2Large' => __('X2Large (not always available)', 'photonic'),
					'X3Large' => __('X3Large (not always available)', 'photonic'),
				),
				'main_size' => array(
					'' => __('Default from settings', 'photonic'),
					'4K' => __('4K (not always available)', 'photonic'),
					'5K' => __('5K (not always available)', 'photonic'),
					'Medium' => __('Medium', 'photonic'),
					'Original' => __('Original (not always available)', 'photonic'),
					'Large' => __('Large', 'photonic'),
					'Largest' => __('Largest available', 'photonic'),
					'XLarge' => __('XLarge (not always available)', 'photonic'),
					'X2Large' => __('X2Large (not always available)', 'photonic'),
					'X3Large' => __('X3Large (not always available)', 'photonic'),
				),
				'video_size' => array(
					'' => __('Default from settings', 'photonic'),
					'110' => __('110px along longest side', 'photonic'),
					'200' => __('200px along longest side', 'photonic'),
					'320' => __('320px along longest side', 'photonic'),
					'640' => __('640px along longest side', 'photonic'),
					'1280' => __('1280px along longest side', 'photonic'),
					'1920' => __('1920px along longest side', 'photonic'),
					'Largest' => __('Largest available', 'photonic'),
				),
			),
			'picasa' => array(
				'thumb_size' => array(
					'32' => '32',
					'32c' => __('32 - cropped', 'photonic'),
					'48' => 48,
					'48c' => __('48 - cropped', 'photonic'),
					'64' => 64,
					'64c' => __('64 - cropped', 'photonic'),
					'72' => 72,
					'72c' => __('72 - cropped', 'photonic'),
					'104' => 104,
					'104c' => __('104 - cropped', 'photonic'),
					'144' => 144,
					'144c' => __('144 - cropped', 'photonic'),
					'150' => 150,
					'150c' => __('150 - cropped', 'photonic'),
					'160' => 160,
					'160c' => __('160 - cropped', 'photonic'),
				),
				'tile_size' => array(
					'same' => __('Same as Main image size', 'photonic'),
					'94' => 94,
					'110' => 110,
					'128' => 128,
					'200' => 200,
					'220' => 220,
					'288' => 288,
					'320' => 320,
					'400' => 400,
					'512' => 512,
					'576' => 576,
					'640' => 640,
					'720' => 720,
					'800' => 800,
					'912' => 912,
					'1024' => 1024,
					'1152' => 1152,
					'1280' => 1280,
					'1440' => 1440,
					'1600' => 1600,
				),
				'main_size' => array(
					'94' => 94,
					'110' => 110,
					'128' => 128,
					'200' => 200,
					'220' => 220,
					'288' => 288,
					'320' => 320,
					'400' => 400,
					'512' => 512,
					'576' => 576,
					'640' => 640,
					'720' => 720,
					'800' => 800,
					'912' => 912,
					'1024' => 1024,
					'1152' => 1152,
					'1280' => 1280,
					'1440' => 1440,
					'1600' => 1600,
				),
			),
			'google' => array(
				'thumb_size' => array(
					'32' => '32',
					'48' => 48,
					'64' => 64,
					'72' => 72,
					'104' => 104,
					'144' => 144,
					'150' => 150,
					'160' => 160,
				),
				'tile_size' => array(
					'same' => __('Same as Main image size', 'photonic'),
					'94' => 94,
					'110' => 110,
					'128' => 128,
					'200' => 200,
					'220' => 220,
					'288' => 288,
					'320' => 320,
					'400' => 400,
					'512' => 512,
					'576' => 576,
					'640' => 640,
					'720' => 720,
					'800' => 800,
					'912' => 912,
					'1024' => 1024,
					'1152' => 1152,
					'1280' => 1280,
					'1440' => 1440,
					'1600' => 1600,
				),
				'main_size' => array(
					'94' => 94,
					'110' => 110,
					'128' => 128,
					'200' => 200,
					'220' => 220,
					'288' => 288,
					'320' => 320,
					'400' => 400,
					'512' => 512,
					'576' => 576,
					'640' => 640,
					'720' => 720,
					'800' => 800,
					'912' => 912,
					'1024' => 1024,
					'1152' => 1152,
					'1280' => 1280,
					'1440' => 1440,
					'1600' => 1600,
				),
			),
			'zenfolio' => array(
				'thumb_size' => array(
					'' => __('Default from settings', 'photonic'),
					"1" => __("Square thumbnail, 60 &times; 60px, cropped square", 'photonic'),
					"0" => __("Small thumbnail, upto 80 &times; 80px", 'photonic'),
					"10" => __("Medium thumbnail, upto 120 &times; 120px", 'photonic'),
					"11" => __("Large thumbnail, upto 120 &times; 120px", 'photonic'),
					"2" => __("Small image, upto 400 &times; 400px", 'photonic'),
				),
				'tile_size' => array(
					'' => __('Default from settings', 'photonic'),
					'same' => __('Same as Main image size', 'photonic'),
					'2' => __('Small image, upto 400 &times; 400px', 'photonic'),
					'3' => __('Medium image, upto 580 &times; 450px', 'photonic'),
					'4' => __('Large image, upto 800 &times; 630px', 'photonic'),
					'5' => __('X-Large image, upto 1100 &times; 850px', 'photonic'),
					'6' => __('XX-Large image, upto 1550 &times; 960px', 'photonic'),
				),
				'main_size' => array(
					'' => __('Default from settings', 'photonic'),
					'2' => __('Small image, upto 400 &times; 400px', 'photonic'),
					'3' => __('Medium image, upto 580 &times; 450px', 'photonic'),
					'4' => __('Large image, upto 800 &times; 630px', 'photonic'),
					'5' => __('X-Large image, upto 1100 &times; 850px', 'photonic'),
					'6' => __('XX-Large image, upto 1550 &times; 960px', 'photonic'),
				),
				'video_size' => array(
					'' => __('Default from settings', 'photonic'),
					'220' => __('360p resolution (MP4)', 'photonic'),
					'215' => __('480p resolution (MP4)', 'photonic'),
					'210' => __('720p resolution (MP4)', 'photonic'),
					'200' => __('1080p resolution (MP4)', 'photonic'),
				),
			),
			'instagram' => array(
				'main_size' => array(
					'' => __('Default from settings', 'photonic'),
					'low_resolution' => __('Low Resolution - 306x306px, or 320x320px', 'photonic'),
					'standard_resolution' => __('Standard Resolution - 612x612px or 640x640px', 'photonic'),
					'largest' => __('Largest available resolution (640x640px for old images, upto 1080x1080px for new images)', 'photonic'),
				),
				'video_size' => array(
					'' => __('Default from settings', 'photonic'),
					'low_resolution' => __('Low Resolution', 'photonic'),
					'standard_resolution' => __('Standard Resolution', 'photonic'),
					'low_bandwidth' => __('Low Bandwidth', 'photonic'),
				),
			),
			'wp' => array(
				'thumb_size' => Photonic::get_wp_image_sizes(false, true),
				'tile_size' => Photonic::get_wp_image_sizes(true, true),
				'main_size' => Photonic::get_wp_image_sizes(true, true),
			),
		);

		global $photonic_flickr_thumb_size, $photonic_flickr_tile_size, $photonic_flickr_main_size, $photonic_flickr_video_size,
		       $photonic_smug_thumb_size, $photonic_smug_tile_size, $photonic_smug_main_size, $photonic_smug_video_size,
		       $photonic_zenfolio_thumb_size, $photonic_zenfolio_tile_size, $photonic_zenfolio_main_size, $photonic_zenfolio_video_size,
		       $photonic_instagram_main_size, $photonic_instagram_video_size;

		$this->allowed_image_sizes['flickr']['thumb_size'][''] .= ' - '.$this->allowed_image_sizes['flickr']['thumb_size'][$photonic_flickr_thumb_size];
		$this->allowed_image_sizes['flickr']['tile_size'][''] .= ' - '.$this->allowed_image_sizes['flickr']['tile_size'][$photonic_flickr_tile_size];
		$this->allowed_image_sizes['flickr']['main_size'][''] .= ' - '.$this->allowed_image_sizes['flickr']['main_size'][$photonic_flickr_main_size];
		$this->allowed_image_sizes['flickr']['video_size'][''] .= ' - '.$this->allowed_image_sizes['flickr']['video_size'][$photonic_flickr_video_size];

		$this->allowed_image_sizes['smugmug']['thumb_size'][''] .= ' - '.$this->allowed_image_sizes['smugmug']['thumb_size'][$photonic_smug_thumb_size];
		$this->allowed_image_sizes['smugmug']['tile_size'][''] .= ' - '.$this->allowed_image_sizes['smugmug']['tile_size'][$photonic_smug_tile_size];
		$this->allowed_image_sizes['smugmug']['main_size'][''] .= ' - '.$this->allowed_image_sizes['smugmug']['main_size'][$photonic_smug_main_size];
		$this->allowed_image_sizes['smugmug']['video_size'][''] .= ' - '.$this->allowed_image_sizes['smugmug']['video_size'][$photonic_smug_video_size];

		$this->allowed_image_sizes['zenfolio']['thumb_size'][''] .= ' - '.$this->allowed_image_sizes['zenfolio']['thumb_size'][$photonic_zenfolio_thumb_size];
		$this->allowed_image_sizes['zenfolio']['tile_size'][''] .= ' - '.$this->allowed_image_sizes['zenfolio']['tile_size'][$photonic_zenfolio_tile_size];
		$this->allowed_image_sizes['zenfolio']['main_size'][''] .= ' - '.$this->allowed_image_sizes['zenfolio']['main_size'][$photonic_zenfolio_main_size];
		$this->allowed_image_sizes['zenfolio']['video_size'][''] .= ' - '.$this->allowed_image_sizes['zenfolio']['video_size'][$photonic_zenfolio_video_size];

		$this->allowed_image_sizes['instagram']['main_size'][''] .= ' - '.$this->allowed_image_sizes['instagram']['main_size'][$photonic_instagram_main_size];
		$this->allowed_image_sizes['instagram']['video_size'][''] .= ' - '.$this->allowed_image_sizes['instagram']['video_size'][$photonic_instagram_video_size];
	}

	/**
	 * @return mixed
	 */
	public function get_flow_options() {
		return $this->flow_options;
	}

	/**
	 * @return array
	 */
	public function get_layout_options() {
		return $this->layout_options;
	}

	private function get_title_position_options() {
		$ret = array(
			'' => __('Default from settings', 'photonic'),
			'regular' => __('Normal title display using the HTML "title" attribute', 'photonic'),
			'below' => __('Below the thumbnail', 'photonic'),
			'tooltip' => __('Using the JQuery Tooltip plugin', 'photonic'),
			'hover-slideup-show' => __('Slide up from bottom upon hover', 'photonic'),
			'slideup-stick' => __('Cover the lower portion always', 'photonic'),
			'none' => __('No title', 'photonic'),
		);

		return array(
			'desc' => __('How do you want the title?', 'photonic'),
			'type' => 'select',
			'options' => $ret,
			'std' => '',
		);
	}
}
