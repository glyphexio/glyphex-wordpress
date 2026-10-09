=== Glyphex Analytics ===
Contributors: glyphex
Tags: analytics, privacy, statistics, cookieless, gdpr
Requires at least: 6.3
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add Glyphex privacy-friendly analytics to WordPress. No cookies, no consent banner.

== Description ==

Glyphex is cookieless website analytics. This plugin adds the Glyphex tracking tag to your site so you do not need to edit theme files.

Features:

* Paste your Site ID in Settings > Glyphex.
* Skip logged-in administrators (on by default).
* Optionally exclude the search string and hash fragment from page URLs.
* Loads with the defer strategy. Nothing runs in wp-admin.

= External service =

This plugin loads a script from https://glyphex.io and sends page view data (page URL, referrer, and basic device information, without cookies) to the Glyphex service for the site ID you enter. Nothing is sent until you enter a Site ID. See the privacy policy at https://glyphex.io/privacy and the terms at https://glyphex.io/terms.

== Installation ==

1. Upload the `glyphex-analytics` folder to `/wp-content/plugins/`, or install the zip from Plugins > Add New Plugin > Upload Plugin.
2. Activate the plugin.
3. Go to Settings > Glyphex and paste your Site ID.
4. Save changes.

== Frequently Asked Questions ==

= Where do I find my Site ID? =

In your Glyphex site settings. It is a UUID.

= Does it track me when I am logged in as admin? =

No, not by default. You can change this in Settings > Glyphex.

== Changelog ==

= 1.0.0 =
* First release.
