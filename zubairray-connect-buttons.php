<?php
/**
 * Plugin Name: ZubairRay Connect Buttons
 * Description: A lightweight privacy-focused contact connection system for WordPress websites.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Zubair Ray
 * Author URI: https://zubairray.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: zubairray-connect-buttons
 *
 * @package ZubairRay_Connect_Buttons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZRCB_VERSION', '1.0.0' );
define( 'ZRCB_PLUGIN_FILE', __FILE__ );
define( 'ZRCB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ZRCB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once ZRCB_PLUGIN_DIR . 'includes/class-plugin.php';

ZRCB_Plugin::instance();
