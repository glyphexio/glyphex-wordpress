<?php
/**
 * Plugin Name:       Glyphex Analytics
 * Plugin URI:        https://glyphex.io/docs/integrations/wordpress
 * Description:       Adds the Glyphex privacy-friendly analytics tag to your site. No cookies, no consent banner.
 * Version:           1.1.1
 * Requires at least: 6.3
 * Requires PHP:      7.4
 * Author:            Glyphex
 * Author URI:        https://glyphex.io
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       glyphex-analytics
 *
 * @package GlyphexAnalytics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GLYPHEX_ANALYTICS_VERSION', '1.1.1' );
define( 'GLYPHEX_ANALYTICS_OPTION', 'glyphex_analytics_options' );

/**
 * Default option values.
 *
 * @return array
 */
function glyphex_analytics_defaults() {
	return array(
		'site_id'        => '',
		'exclude_admins' => 1,
		'exclude_search' => 0,
	);
}

/**
 * Get saved options merged with defaults.
 *
 * @return array
 */
function glyphex_analytics_get_options() {
	$saved = get_option( GLYPHEX_ANALYTICS_OPTION, array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}
	return wp_parse_args( $saved, glyphex_analytics_defaults() );
}

/**
 * Check a string is a UUID.
 *
 * @param string $value Value to check.
 * @return bool
 */
function glyphex_analytics_is_uuid( $value ) {
	return (bool) preg_match( '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value );
}

/**
 * Sanitize and validate submitted options.
 *
 * @param mixed $input Raw input.
 * @return array
 */
function glyphex_analytics_sanitize( $input ) {
	$input   = is_array( $input ) ? $input : array();
	$current = glyphex_analytics_get_options();
	$site_id = isset( $input['site_id'] ) ? strtolower( trim( sanitize_text_field( wp_unslash( $input['site_id'] ) ) ) ) : '';

	if ( '' !== $site_id && ! glyphex_analytics_is_uuid( $site_id ) ) {
		add_settings_error(
			'glyphex_analytics',
			'glyphex_invalid_site_id',
			esc_html__( 'The Site ID must be a UUID, like 123e4567-e89b-12d3-a456-426614174000.', 'glyphex-analytics' )
		);
		$site_id = $current['site_id'];
	}

	return array(
		'site_id'        => $site_id,
		'exclude_admins' => empty( $input['exclude_admins'] ) ? 0 : 1,
		'exclude_search' => empty( $input['exclude_search'] ) ? 0 : 1,
	);
}

/**
 * Register the setting.
 */
function glyphex_analytics_register_settings() {
	register_setting(
		'glyphex_analytics_group',
		GLYPHEX_ANALYTICS_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'glyphex_analytics_sanitize',
			'default'           => glyphex_analytics_defaults(),
		)
	);
}
add_action( 'admin_init', 'glyphex_analytics_register_settings' );

/**
 * Add the Settings > Glyphex page.
 */
function glyphex_analytics_add_menu() {
	add_options_page(
		__( 'Glyphex Analytics', 'glyphex-analytics' ),
		__( 'Glyphex', 'glyphex-analytics' ),
		'manage_options',
		'glyphex-analytics',
		'glyphex_analytics_render_page'
	);
}
add_action( 'admin_menu', 'glyphex_analytics_add_menu' );

/**
 * Settings link on the plugins list.
 *
 * @param array $links Existing links.
 * @return array
 */
function glyphex_analytics_action_links( $links ) {
	$url = admin_url( 'options-general.php?page=glyphex-analytics' );
	array_unshift( $links, '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'glyphex-analytics' ) . '</a>' );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'glyphex_analytics_action_links' );

/**
 * Render the settings page. The Settings API handles nonce checks on save.
 */
function glyphex_analytics_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'glyphex-analytics' ) );
	}
	$opts = glyphex_analytics_get_options();
	$name = GLYPHEX_ANALYTICS_OPTION;
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'glyphex_analytics_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="glyphex_site_id"><?php esc_html_e( 'Site ID', 'glyphex-analytics' ); ?></label></th>
					<td>
						<input type="text" id="glyphex_site_id" class="regular-text code" name="<?php echo esc_attr( $name ); ?>[site_id]" value="<?php echo esc_attr( $opts['site_id'] ); ?>" placeholder="123e4567-e89b-12d3-a456-426614174000" autocomplete="off" />
						<p class="description"><?php esc_html_e( 'Find it in your Glyphex site settings.', 'glyphex-analytics' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Tracking', 'glyphex-analytics' ); ?></th>
					<td>
						<fieldset>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[exclude_admins]" value="1" <?php checked( 1, (int) $opts['exclude_admins'] ); ?> />
								<?php esc_html_e( 'Do not track logged-in administrators', 'glyphex-analytics' ); ?>
							</label><br />
							<label>
								<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[exclude_search]" value="1" <?php checked( 1, (int) $opts['exclude_search'] ); ?> />
								<?php esc_html_e( 'Strip the search string from referrer and 404 URLs', 'glyphex-analytics' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Pageview, outbound link and download URLs never include it.', 'glyphex-analytics' ); ?></p>
						</fieldset>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Enqueue the tracking script on the front end only.
 */
function glyphex_analytics_enqueue() {
	if ( is_admin() ) {
		return;
	}
	$opts = glyphex_analytics_get_options();
	if ( '' === $opts['site_id'] || ! glyphex_analytics_is_uuid( $opts['site_id'] ) ) {
		return;
	}
	if ( ! empty( $opts['exclude_admins'] ) && current_user_can( 'manage_options' ) ) {
		return;
	}

	wp_enqueue_script(
		'glyphex-analytics',
		'https://glyphex.io/t/' . rawurlencode( $opts['site_id'] ) . '.js',
		array(),
		null, // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- remote script, version is part of the vendor URL.
		array(
			'strategy'  => 'defer',
			'in_footer' => false,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'glyphex_analytics_enqueue' );

/**
 * Add data attributes to the script tag.
 *
 * @param string $tag    Script tag.
 * @param string $handle Script handle.
 * @return string
 */
function glyphex_analytics_script_attributes( $tag, $handle ) {
	if ( 'glyphex-analytics' !== $handle ) {
		return $tag;
	}
	$opts  = glyphex_analytics_get_options();
	$attrs = '';
	if ( ! empty( $opts['exclude_search'] ) ) {
		$attrs .= ' data-exclude-search="true"';
	}
	if ( '' === $attrs ) {
		return $tag;
	}
	return preg_replace( '/^<script /', '<script' . $attrs . ' ', $tag, 1 );
}
add_filter( 'script_loader_tag', 'glyphex_analytics_script_attributes', 10, 2 );
