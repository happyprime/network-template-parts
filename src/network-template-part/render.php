<?php
/**
 * Render the Network Template Part block.
 *
 * @package network-template-parts
 */

$ntp_block_defaults = [
	'partSlug' => '',
];

$attributes = wp_parse_args( $attributes, $ntp_block_defaults );

if ( '' === $attributes['partSlug'] ) {
	return '<p>Please specify a template part slug.</p>';
}

if ( is_multisite() ) {
	switch_to_blog( get_main_site_id() );
}

block_template_part( $attributes['partSlug'] );

if ( is_multisite() ) {
	restore_current_blog();
}
