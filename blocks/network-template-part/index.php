<?php
/**
 * Manage the Network Template Part block.
 *
 * @package network-template-parts
 */

namespace NTP\Blocks\NetworkTemplatePart;

add_action( 'init', __NAMESPACE__ . '\register_block' );

/**
 * Registers the block.
 */
function register_block(): void {
	register_block_type_from_metadata(
		NTP_PLUGIN_DIR . '/build/network-template-part',
		[
			'render_callback' => __NAMESPACE__ . '\get_block_html',
		]
	);
}

/**
 * Retrieves the block rendered as HTML.
 *
 * @param array<string, mixed> $attributes The block attributes.
 * @return string The block HTML.
 */
function get_block_html( array $attributes ): string {
	static $rendering = [];

	$slug    = $attributes['slug'] ?? '';
	$context = $attributes['context'] ?? 'site';

	// The first entry in the switched stack is the site that made the original request.
	$switched_stack   = $GLOBALS['_wp_switched_stack'] ?? [];
	$original_site_id = is_array( $switched_stack ) ? reset( $switched_stack ) : false;

	if ( ! is_string( $slug ) || '' === $slug ) {
		return '<p>Please specify a template part slug.</p>';
	}

	$switched = false;

	if ( 'network' === $context && is_multisite() && ! is_main_site() ) {
		$switched = true;
		switch_to_blog( get_main_site_id() );
	} elseif ( 'site' === $context && is_multisite() && is_int( $original_site_id ) ) {
		$switched = true;
		switch_to_blog( $original_site_id );
	}

	// A part that includes itself, directly or through another site, would recurse until PHP runs out of memory.
	$key     = get_current_blog_id() . ':' . $slug;
	$content = '';

	if ( ! isset( $rendering[ $key ] ) ) {
		$rendering[ $key ] = true;

		ob_start();
		block_template_part( $slug );
		$content = (string) ob_get_clean();

		unset( $rendering[ $key ] );
	}

	if ( $switched ) {
		restore_current_blog();
	}

	return $content;
}
