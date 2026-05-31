<?php
/**
 * Uninstall cleanup for ZubairRay Connect Buttons.
 *
 * @package ZubairRay_Connect_Buttons
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/options.php';

foreach ( array_merge( zrcb_get_option_keys(), zrcb_get_legacy_option_keys() ) as $zrcb_option_key ) {
	delete_option( $zrcb_option_key );
}
