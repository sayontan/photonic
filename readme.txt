=== Photonic Gallery for Flickr, Picasa, SmugMug and 500px ===
Contributors: sayontan
Donate link: http://aquoid.com/news/plugins/photonic/
Tags: gallery, flickr, picasa, 500px, smugmug, fancybox, colorbox, prettyphoto, slideshow, jquery, shortcode
Requires at least: WP 3.1
Tested up to: WP 3.3.1
Stable tag: trunk

WordPress galleries on steroids! A stylish plugin for beautiful galleries of your public and private Flickr, Picasa, SmugMug and 500px photos.

== Description ==

Photonic lets you use the WordPress <code>gallery</code> shortcode and ramps it up with a lot of added functionality. It adds support for
several new parameters to enhance your galleries. It supports Flickr photos, Photosets, Galleries and Collections, along with Picasa photos and albums,
SmugMug albums and images, and 500px photos. You can also enable authentication for your site visitors that will let them see private and protected
photos from each provider.

= Flickr Support =

The following Flickr concepts are supported in Photonic:

*	<a href='http://aquoid.com/news/plugins/photonic/flickr/flickr-photos/'>Photos</a>
*	<a href='http://aquoid.com/news/plugins/photonic/flickr/flickr-photosets/'>PhotoSets</a>
*	<a href='http://aquoid.com/news/plugins/photonic/flickr/flickr-galleries/'>Galleries</a>
*	<a href='http://aquoid.com/news/plugins/photonic/flickr/flickr-collections/'>Collections</a>
*	<a href='http://aquoid.com/news/plugins/photonic/flickr/flickr-photo/'>Single Photo</a>
*	<a href='http://aquoid.com/news/plugins/photonic/flickr/flickr-authentication/'>Authentication</a>

For demos of Flickr support visit the <a href='http://aquoid.com/news/plugins/photonic/flickr/'>Flickr page</a>.

= Picasa Support =

The following Picasa concepts are supported in Photonic:

*	<a href='http://aquoid.com/news/plugins/photonic/picasa/picasa-photos/'>Photos</a>
*	<a href='http://aquoid.com/news/plugins/photonic/picasa/picasa-albums/'>Albums</a>

For demos of Picasa support visit the <a href='http://aquoid.com/news/plugins/photonic/picasa/'>Picasa page</a>.

= SmugMug Support =

The following SmugMug concepts are supported in Photonic:

*	<a href='http://aquoid.com/news/plugins/photonic/smugmug/smugmug-tree/'>User Tree</a>
*	<a href='http://aquoid.com/news/plugins/photonic/smugmug/smugmug-photos/'>Photos</a>
*	<a href='http://aquoid.com/news/plugins/photonic/smugmug/smugmug-albums/'>Albums</a>

For demos of SmugMug support visit the <a href='http://aquoid.com/news/plugins/photonic/smugmug/'>SmugMug page</a>.

= 500px Support =

Photos on <a href='http://aquoid.com/news/plugins/photonic/500px/'>500px.com are supported in Photonic</a>.

= Enhanced Native Galleries =
Your existing gallery insertions are left intact. However you can add a <code>style</code> parameter to it for special effects. The permitted values for <code>style</code> are:

1. <code>strip-below</code>: Displays a thumbnail strip with a running slideshow above.
2. <code>strip-above</code>: Displays a thumbnail strip with a running slideshow below.
3. <code>no-strip</code>: Displays running slideshow with no thumbnails.
4. <code>launch</code>: Doesn't display a running slideshow, but shows all thumbnails. On clicking a thumbnail a popup slideshow is shown.

You can also specify a parameter called <code>fx</code> to add special effects. This parameter can take any of these values: <code>fade</code>
, <code>scrollUp</code>, <code>scrollDown</code>, <code>scrollLeft</code>, <code>scrollRight</code>, <code>scrollHorz</code>, <code>scrollVert</code>
, <code>slideX</code>, <code>slideY</code>, <code>turnUp</code>, <code>turnDown</code>, <code>turnLeft</code>, <code>turnRight</code>, <code>zoom</code>
, <code>fadeZoom</code>, <code>blindX</code>, <code>blindY</code>, <code>blindZ</code>, <code>growX</code>, <code>growY</code>, <code>curtainX</code>
, <code>curtainY</code>, <code>uncover</code>, <code>wipe</code>. See the <a href='http://jquery.malsup.com/cycle/'>JQuery Cycle site</a>
for demos of the effects.

Parameters such as <code>speed</code> can control the slide transition speed, and <code>thumbnail_size</code> can control the size of
the thumbnails.

== Installation ==

You can install the plugin through the WordPress installer under <strong>Plugins &rarr; Add New</strong> by searching for it,
or by uploading the file downloaded from here. Alternatively you can download the file from here, unzip it and move the unzipped
contents to the <code>wp-content/plugins</code> folder of your WordPress installation. You will then be able to activate the plugin.

== Screenshots ==

For the plugin in action see the <a href='http://aquoid.com/news/plugins/photonic/'>plugin page</a>.

1.	To insert the shortcode for Photonic through the "Add Media" options click on "Add an Image".
2.	You will see a new tab for "Photonic".
3.	Clicking on the "Photonic" tab will show you three new tabs, one for native WP Galleries, one for Flickr and one for Picasa. Fill out what you need and click "Insert into post"

== Frequently Asked Questions ==

= If I disable the plugin what happens to the galleries? =

Obviously, your galleries will not show. However, since you are using the native <code>gallery</code> shortcode, you will
not see any empty shortcode tags on your site.

= What about other photo-sharing platforms? =

Suggestions are welcome for other photo-sharing platforms. In addition there will be support provided for the generic
Media RSS format, which can facilitate integration with platforms such as ZenPhoto.

= What about other JS libraries? =

Currently Fancybox, Colorbox and PrettyPhoto are supported. Both lighter and heavier alternatives are being considered.
If you have specific suggestions please feel free to contact the plugin author.

Note that there have been slight modifications to both the above scripts to make them interact with JQuery tooltips.

= Are there any known issues? =

Yes, there are a couple dealing with tooltips. The plugin optionally uses the <a href='http://bassistance.de/jquery-plugins/jquery-plugin-tooltip/'>JQuery Tooltip</a>
script to show image tooltips. The drawback is that for IE7 the native HTML tooltip and the custom tooltip are shown simultaneously.
This is a bug with the thumbnail script.

In addition, if you access a protected album in SmugMug in Photonic, you don't get the desired results.

= Are translations supported? =

Yes, but only for the plugin front-end. The admin panel is not translated at this point. Also note that any strings included
in the third-party JS scripts are not translated.

== Changelog ==

= 1.22 =

*   Made minor corrections to authentication behaviour. If not connected to the internet, Photonic was returning a fatal error.
*   Changed the wording for PrettyPhoto licensing. PrettyPhoto is licensed under GPL, and that is now explicitly stated in the options page.
*   Added the capability to include shortcodes inside the parameters of the gallery shortcode (nested shortcodes)

= 1.21 =

*	Added support for SmugMug password-protected albums where the user has access to the albums. Other albums show up, but clicking on them takes the users nowhere.
*	Fixed a bug that was killing the last thumbnail in a popup for SmugMug.

= 1.20 =

*	Added authentication support for Flickr, 500px.com and SmugMug.
*   Disabled the showing of password-protected SmugMug galleries.

= 1.12 =

*	Fixed a problem with handling double quotes in the title of Flickr photos.

= 1.11 =

*	Combined SimpleModal script with Photonic to save an HTTP request.
*   Deleted some redundant JS files.
*   Added support for a different default type.
*   Added support for single photos in Flickr.

= 1.10 =

*	Fixed a bug with the SmugMug processor, which was preventing the display of an album popup.

= 1.09 =

*	Fixed a bug with the Flickr processor, which was preventing the execution of the shortcode with a group_id parameter
*	Fixed a minor problem with the popup panels, where hovering over the panel titles was resulting in a Tooltip error.
*	The Picasa inserter was not showing the "album" field. This has been rectified.

= 1.08 =

*	Made a change to the Picasa processor to handle the recent change by Google. Google's change was forcing images to 512px.
*	Updated the plugin to support WP 3.3.
*   Fixed an issue with FancyBox that was showing server log errors for IE.

= 1.07 =

*	Added support for SmugMug
*	Modified the Picasa plugin to not display the file name if no title is found.

= 1.06 =

*	Fixed an issue with the 500px.com extension, which had to be changed to invoke HTTPS instead of HTTP.
*	Added support for the "sort" parameter for 500px.
*	Fixed an issue with the PrettyPhoto library where you couldn't see the "View in Flickr" link.

= 1.05 =

*	Added support for the PrettyPhoto JS library.
*	Added check for _wp_additional_image_sizes. This was causing errors for some people.
*	Fixed a bug with the thumbnail_width and thumbnail_height attribute of the plugin. The attributes should have been called thumb_width and thumb_height.
*	Added option to launch images in a slideshow mode.
*	Fixed a bug that was not accepting border settings for the thumbnails.

= 1.04 =

*	Added support for photos on 500px.
*	Modified the Colorbox invocation to fit in the browser window.

= 1.03 =

*	Added support for Gallery objects in Flickr.
*	Fixed an issue with the Picasa galleries where under certain circumstances users were getting a "Division by zero" error.
*	Fixed an issue where Chrome and Safari were showing an overlap of the slides over the rest of the content in native galleries.
*	Updated translation file.

= 1.02 =

*	Added a UI panel for insertion of Photonic galleries through the Add Media screen.
*	Restructured code for better extensibility.
*	Updated translation file.

= 1.01 =

*	Included a PO file for translation support.
*	Fixed a bug to prevent appearance of Photonic stylesheets on other admin pages.

= 1.00 =

*	New version created.

== Upgrade Notice ==

No upgrade notices at this point.