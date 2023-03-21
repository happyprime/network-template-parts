<?php
/**
 * Render the Site Template block.
 *
 * @package network-template-parts
 */

$stp_block_defaults = [
	'slug' => '',
];

$attributes = wp_parse_args( $attributes, $stp_block_defaults );
$switched   = false;

if ( '' === $attributes['slug'] ) {
	return '<p>Please specify a template part slug.</p>';
}

// If we're operating in a switched state, switch to the site
// that made the original request.
if ( is_multisite() && ! empty( $GLOBALS['_wp_switched_stack'] ) ) {
	$switched = true;
	switch_to_blog( $GLOBALS['_wp_switched_stack'][ array_key_first( $GLOBALS['_wp_switched_stack'] ) ] );
}

block_template_part( 'site-templates-' . $attributes['slug'] );

if ( $switched ) {
	restore_current_blog();
}
