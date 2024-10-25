<?php
/**
 * Plugin Name:  Network Template Parts
 * Description:  Render template parts in a site or network context.
 * Version:      1.0.4
 * Plugin URI:   https://github.com/happyprime/network-template-parts/
 * Author:       Happy Prime
 * Author URI:   https://happyprime.co
 * Text Domain:  network-template-parts
 * Domain Path:  /languages
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

define( 'NTP_PLUGIN_DIR', __DIR__ );

require_once __DIR__ . '/blocks/network-template-part/index.php';
