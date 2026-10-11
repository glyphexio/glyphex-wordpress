# Glyphex Analytics for WordPress

Adds the [Glyphex](https://glyphex.io) analytics tag to WordPress. Cookieless, no consent banner.

## Install

1. Zip only the plugin folder (a zip of the whole repo nests it too deep and WordPress rejects it):
   `cd glyphex-analytics/.. && zip -r glyphex-analytics.zip glyphex-analytics`
2. In WordPress: Plugins > Add New Plugin > Upload Plugin, and choose `glyphex-analytics.zip`.
3. Activate, then go to Settings > Glyphex and paste your Site ID.

## Options

- Site ID (UUID)
- Do not track logged-in administrators (default on)
- Strip the search string from referrer and 404 URLs (pageview, outbound link and download URLs never include it)

Requires WordPress 6.3+ and PHP 7.4+. Licensed GPL-2.0-or-later.
