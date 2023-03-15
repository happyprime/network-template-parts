<?php
/**
 * Plugin Name: Network Template Parts
 * Version: 0.0.1
 *
 * @package network-template-parts
 */

namespace NTP;

add_action( 'init', __NAMESPACE__ . '\register_blocks' );

/**
 * Register the blocks.
 */
function register_blocks() {
	register_block_type_from_metadata( __DIR__ . '/build/network-template' );
	register_block_type_from_metadata( __DIR__ . '/build/network-template-part' );
	register_block_type_from_metadata( __DIR__ . '/build/site-template' );
	register_block_type_from_metadata( __DIR__ . '/build/site-template-part' );
}
