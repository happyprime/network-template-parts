<?php
/**
 * Plugin Name:  Network Template Parts
 * Description:  Manage site and network level block template parts.
 * Version:      0.1.0
 * Plugin URI:   https://github.com/happyprime/network-template-parts/
 * Author:       Happy Prime
 * Author URI:   https://happyprime.co
 * Text Domain:  network-template-parts
 * Requires PHP: 7.4
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
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
