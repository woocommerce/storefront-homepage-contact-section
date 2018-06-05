=== Storefront Homepage Contact Section ===
Contributors: tiagonoronha, woothemes, rynald0s, automattic
Tags: woocommerce, ecommerce, storefront, contact, form, map, email
Requires at least: 4.0
Tested up to: 4.9
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a "Contact" section to the Storefront homepage.

== Description ==

A simple plugin that adds custom "Contact" homepage section to Storefront. Customise the display by adding your contact details via the Customizer.

This plugin requires the Storefront theme to be installed. Jetpack is required for the contact form.

== Installation ==

1. Upload `storefront-homepage-contact-section` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the display in the Customizer (look for the 'Homepage Contact' section).
3. Done!

== Frequently Asked Questions ==

= I installed the plugin but cannot see the "Contact" section =

This plugin will only work with the [Storefront](http://wordpress.org/themes/storefront/) theme.

= I can't see the contact form =

This plugin requires the [Jetpack](http://wordpress.org/plugins/jetpack/) plugin for the contact form to work.

= Google map won’t generate =

The Google Static Maps API requires an API key (as of June 22, 2016), which you can generate from here: https://developers.google.com/maps/documentation/static-maps/. Once you have the API key, add it to the settings, and the map will be displayed.

== Screenshots ==

1. The Homepage Contact Section

== Changelog ==

= 1.0.5 =
Fix - Move "send to" attribute to correct shortcode.

= 1.0.4 =
Fix - Set Jetpack Contact Form "send to" email address to the address in General Settings.

= 1.0.3 =
New - Added setting for users to include own Google Static Maps API key (these API keys are required as of June 22, 2016).

= 1.0.2 =
New - Added filter to manipulate the section title.

= 1.0.1 =
Tweak - Improvements to the Google Maps Static API call. Kudos @tarranjones.

= 1.0.0 =
Initial release.
