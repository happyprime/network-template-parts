<?php
/**
 * Register and render the block.
 *
 * @package network-template-parts
 */

namespace NTP\Blocks\NetworkTemplatePart;

add_action( 'init', __NAMESPACE__ . '\register_block' );
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_block_editor_assets' );

/**
 * Register the block server-side.
 */
function register_block() {
	register_block_type_from_metadata(
		__DIR__,
		array(
			'render_callback' => __NAMESPACE__ . '\render',
		)
	);
}

/**
 * Register the block editor scripts.
 */
function enqueue_block_editor_assets() {
	$asset_data = require_once NTP_PLUGIN_DIR . '/build/network-template-part/index.asset.php';

	wp_enqueue_script(
		'ntp-network-template-part-block',
		NTP_PLUGIN_URL . 'build/network-template-part/index.js',
		$asset_data['dependencies'],
		$asset_data['version'],
		true
	);
}

/**
 * Render the block.
 *
 * @param array $attributes The block attributes.
 *
 * @return string HTML
 */
function render( array $attributes ): string {
	$defaults = [
		'partSlug' => '',
	];

	$attributes = wp_parse_args( $attributes, $defaults );

	if ( '' === $attributes['partSlug'] ) {
		return '<p>Please specify a template part slug.</p>';
	}

	if ( is_multisite() ) {
		switch_to_blog( get_main_site_id() );
	}

	ob_start();

	block_template_part( $attributes['partSlug'] );

	if ( is_multisite() ) {
		restore_current_blog();
	}

	return ob_get_clean();
}
