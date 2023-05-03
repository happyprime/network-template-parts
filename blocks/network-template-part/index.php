<?php
/**
 * Manage the Network Template Part block.
 *
 * @package network-template-parts
 */

namespace NTP\Blocks\NetworkTemplatePart;

add_action( 'init', __NAMESPACE__ . '\register_block' );

/**
 * Register the block.
 */
function register_block() {
	register_block_type_from_metadata(
		NTP_PLUGIN_DIR . '/build/network-template-part',
		[
			'render_callback' => __NAMESPACE__ . '\get_block_html',
		]
	);
}

/**
 * Retrieve the block rendered as HTML.
 *
 * @param array $attributes The block attributes.
 * @return string The block HTML.
 */
function get_block_html( array $attributes ): string {
	$ntp_block_defaults = [
		'slug'    => '',
		'context' => 'site',
	];

	$attributes = wp_parse_args( $attributes, $ntp_block_defaults );

	if ( '' === $attributes['slug'] ) {
		return '<p>Please specify a template part slug.</p>';
	}

	$switched = false;

	if ( 'network' === $attributes['context'] && is_multisite() && ! is_main_site() ) {
		$switched = true;
		switch_to_blog( get_main_site_id() );
	} elseif ( 'site' === $attributes['context'] && is_multisite() && ! empty( $GLOBALS['_wp_switched_stack'] ) ) {
		$switched = true;

		// We're already operating in a switched state, switch to the site that made the original request.
		switch_to_blog( $GLOBALS['_wp_switched_stack'][ array_key_first( $GLOBALS['_wp_switched_stack'] ) ] );
	}

	ob_start();
	block_template_part( $attributes['slug'] );
	$content = ob_get_clean();

	if ( $switched ) {
		restore_current_blog();
	}

	return $content;
}
