<?php
/**
 * Remove plugin data on uninstall.
 *
 * @package GlyphexAnalytics
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'glyphex_analytics_options' );
