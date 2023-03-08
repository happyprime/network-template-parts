<?php
/**
 * Plugin Name: Network Template Parts
 * Version: 0.0.1
 *
 * @package network-template-parts
 */

define( 'NTP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'NTP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once __DIR__ . '/src/network-template-part/index.php';
require_once __DIR__ . '/src/site-template-part/index.php';
