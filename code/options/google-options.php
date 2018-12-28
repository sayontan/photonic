<?php

global $photonic_google_options;

$photonic_google_options = array(
	array('name' => 'Google Photos settings',
		'desc' => 'Control settings for Google Photos',
		'category' => 'google-settings',
		'type' => 'section',),

	array('name' => 'Google Client ID',
		'desc' => "Enter your Google Client ID. You can get / create one from Google's <a href='https://console.developers.google.com/apis/'>API Manager</a>.
			The <a href='https://aquoid.com/plugins/photonic/google-photos/#api-key'>documentation page</a> can help you with further instructions.
			If you have previously obtained a Client ID for Picasa you can use that here, provided you follow the additional instructions in the documentation.
			<ol>
				<li>Use the option for 'OAuth Client ID', and subsequently pick 'Web applications'.</li>
				<li>Make sure that you add these as your Redirect URIs:
					<ol>
						<li>".site_url()."</li>
						<li>".admin_url('admin.php?page=photonic-auth&source=google')."</li>
					</ol>
				<strong>Without the above your authentication will not work.</strong>
				</li>
			</ol>",
		'id' => 'google_client_id',
		'grouping' => 'google-settings',
		'type' => 'text',
		'std' => ''),

	array('name' => 'Google Client Secret',
		'desc' => "Enter your Google Client Secret.",
		'id' => 'google_client_secret',
		'grouping' => 'google-settings',
		'type' => 'text',
		'std' => ''),

	array('name' => 'Refresh Token (for Back-end / Server-side Authentication)',
		'desc' => "To access any content in Google Photos you need to get a token. This wasn't a requirement for Picasa. To get your token go to
			<em>Photonic &rarr; Authentication &rarr; Google Photos &rarr; Google Photos Refresh Token Getter</em>, and authenticate.",
		'id' => 'google_refresh_token',
		'grouping' => 'google-settings',
		'type' => 'text',
		'std' => ''),

	array('name' => 'Media to show',
		'desc' => 'You can choose to include photos as well as videos in your output. This can be overridden by the <code>media</code> parameter in the shortcode:',
		'id' => 'google_media',
		'grouping' => 'google-settings',
		'type' => 'select',
		'options' => Photonic::media_options(),
		'std' => 'photos'),

	array('name' => "Disable lightbox linking",
		'desc' => "Check this to disable linking the photo title in the lightbox to the original photo.",
		'id' => "google_disable_title_link",
		'grouping' => 'google-settings',
		'type' => 'checkbox',
		'std' => ''),

	array('name' => "Hide Photo Count in Album Title Display",
		'desc' => "This will hide the number of photos in your Album's title.",
		'id' => "google_hide_album_photo_count_display",
		'grouping' => 'google-settings',
		'type' => 'checkbox',
		'std' => ''),

	array('name' => "Photos (Main Page)",
		'desc' => "Control settings for photos from Google Photos when displayed in your page",
		'category' => 'google-photos',
		'type' => 'section',),

	array('name' => "What is this section?",
		'desc' => "Options in this section are in effect when you use the shortcode format <code>[gallery type='google' view='photos']</code>. In other words, the photos are printed directly on the page.",
		'grouping' => 'google-photos',
		'type' => "blurb",),

	array('name' => "Photo Title Display",
		'desc' => "How do you want the title of the photos?",
		'id' => "google_photo_title_display",
		'grouping' => 'google-photos',
		'type' => 'radio',
		'options' => photonic_title_styles(),
		'std' => "tooltip"),

	array('name' => "Constrain Photos Per Row",
		'desc' => "How do you want the control the number of photo thumbnails per row by default? This can be overridden by adding the '<code>columns</code>' parameter to the '<code>gallery</code>' shortcode.",
		'id' => "google_photos_per_row_constraint",
		'grouping' => 'google-photos',
		'type' => 'select',
		'options' => array("padding" => "Fix the padding around the thumbnails",
			"count" => "Fix the number of thumbnails per row",
		),
		'std' => "padding"),

	array('name' => "Constrain by padding",
		'desc' => " If you have constrained by padding above, enter the number of pixels here to pad the thumbs by",
		'id' => "google_photos_constrain_by_padding",
		'grouping' => 'google-photos',
		'type' => 'text',
		'hint' => "Enter the number of pixels here (don't enter 'px'). Non-integers will be ignored.",
		'std' => "15"),

	array('name' => "Constrain by number of thumbnails",
		'desc' => " If you have constrained by number of thumbnails per row above, enter the number of thumbnails",
		'id' => "google_photos_constrain_by_count",
		'grouping' => 'google-photos',
		'type' => 'select',
		'options' => photonic_selection_range(1, 25),
		'std' => 5),

	array('name' => "Photo Thumbnail Border",
		'desc' => "Setup the border of photo thumbnail when the photo is displayed in the overlaid popup panel.",
		'id' => "google_photo_thumb_border",
		'grouping' => 'google-photos',
		'type' => 'border',
		'options' => array(),
		'std' => photonic_default_border(),
	),

	array('name' => "Photo Thumbnail - Padding between border and image",
		'desc' => "Setup the padding between the photo thumbnail and its border.",
		'id' => "google_photo_thumb_padding",
		'grouping' => 'google-photos',
		'type' => 'padding',
		'options' => array(),
		'std' => photonic_default_padding(),
	),

	array('name' => "Photos (Overlaid Popup Panel)",
		'desc' => "Control settings for photos from Google Photos when displayed in a popup",
		'category' => 'google-photos-pop',
		'type' => 'section',),

	array('name' => "What is this section?",
		'desc' => "Options in this section are in effect when you use the shortcode format <code>[gallery type='google' view='albums']</code>, then click on an album to show an overlaid panel. In other words, the photos are printed directly in the overlaid panel.",
		'grouping' => 'google-photos-pop',
		'type' => "blurb",),

	array('name' => "Photo Title Display",
		'desc' => "How do you want the title of the photos?",
		'id' => "google_photo_pop_title_display",
		'grouping' => 'google-photos-pop',
		'type' => 'radio',
		'options' => photonic_title_styles(),
		'std' => "tooltip"),

	array('name' => "Constrain Photos Per Row",
		'desc' => "How do you want the control the number of photo thumbnails per row by default? This can be overridden by adding the '<code>columns</code>' parameter to the '<code>gallery</code>' shortcode.",
		'id' => "google_photos_pop_per_row_constraint",
		'grouping' => 'google-photos-pop',
		'type' => 'select',
		'options' => array("padding" => "Fix the padding around the thumbnails",
			"count" => "Fix the number of thumbnails per row",
		),
		'std' => "padding"),

	array('name' => "Constrain by padding",
		'desc' => " If you have constrained by padding above, enter the number of pixels here to pad the thumbs by",
		'id' => "google_photos_pop_constrain_by_padding",
		'grouping' => 'google-photos-pop',
		'type' => 'text',
		'hint' => "Enter the number of pixels here (don't enter 'px'). Non-integers will be ignored.",
		'std' => "15"),

	array('name' => "Constrain by number of thumbnails",
		'desc' => " If you have constrained by number of thumbnails per row above, enter the number of thumbnails",
		'id' => "google_photos_pop_constrain_by_count",
		'grouping' => 'google-photos-pop',
		'type' => 'select',
		'options' => photonic_selection_range(1, 25),
		'std' => 5),

	array('name' => "Photo Thumbnail Border",
		'desc' => "Setup the border of photo thumbnail when the photo is displayed as a part of a photoset or in a photo-stream. This is valid for the short-code usage <code>[gallery type='flickr' photoset_id='xyz']</code>, or <code>[gallery type='flickr' user_id='abc' view='photos']</code>.",
		'id' => "google_photo_pop_thumb_border",
		'grouping' => 'google-photos-pop',
		'type' => 'border',
		'options' => array(),
		'std' => photonic_default_border(),
	),

	array('name' => "Photo Thumbnail - Padding between border and image",
		'desc' => "Setup the padding between the photo thumbnail and its border.",
		'id' => "google_photo_pop_thumb_padding",
		'grouping' => 'google-photos-pop',
		'type' => 'padding',
		'options' => array(),
		'std' => photonic_default_padding(),
	),
);
