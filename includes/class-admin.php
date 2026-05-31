<?php
/**
 * Admin area integration.
 *
 * @package ZubairRay_Connect_Buttons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers admin menu, assets, and settings UI.
 */
class ZRCB_Admin {

	/**
	 * Settings page slug.
	 */
	const PAGE_SLUG = 'zubairray-connect-buttons';

	/**
	 * Hook admin functionality.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( ZRCB_PLUGIN_FILE ), array( __CLASS__, 'plugin_action_links' ) );
	}

	/**
	 * Add settings link on the plugins page.
	 *
	 * @param array $links Plugin action links.
	 * @return array
	 */
	public static function plugin_action_links( $links ) {
		$settings_link = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( admin_url( 'admin.php?page=' . self::PAGE_SLUG ) ),
			esc_html__( 'Settings', 'zubairray-connect-buttons' )
		);

		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Add top-level admin menu.
	 */
	public static function register_menu() {
		add_menu_page(
			__( 'ZubairRay Connect Buttons', 'zubairray-connect-buttons' ),
			__( 'ZubairRay Connect Buttons', 'zubairray-connect-buttons' ),
			'manage_options',
			self::PAGE_SLUG,
			array( __CLASS__, 'render_settings_page' ),
			'dashicons-format-chat',
			58
		);
	}

	/**
	 * Enqueue admin scripts only on this plugin's settings screen.
	 *
	 * @param string $hook_suffix Admin page hook suffix.
	 */
	public static function enqueue_assets( $hook_suffix ) {
		if ( 'toplevel_page_' . self::PAGE_SLUG !== $hook_suffix ) {
			return;
		}

		$admin_css = ZRCB_PLUGIN_DIR . 'assets/css/zrcb-admin.css';
		$admin_js  = ZRCB_PLUGIN_DIR . 'assets/js/zrcb-admin.js';

		wp_enqueue_style(
			'zubairray-connect-buttons-admin',
			ZRCB_PLUGIN_URL . 'assets/css/zrcb-admin.css',
			array(),
			file_exists( $admin_css ) ? (string) filemtime( $admin_css ) : ZRCB_VERSION
		);

		wp_enqueue_script(
			'zubairray-connect-buttons-admin',
			ZRCB_PLUGIN_URL . 'assets/js/zrcb-admin.js',
			array(),
			file_exists( $admin_js ) ? (string) filemtime( $admin_js ) : ZRCB_VERSION,
			true
		);
	}

	/**
	 * Render settings page.
	 */
	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$channels   = zrcb_get_channels();
		$visibility = zrcb_get_visibility();
		$position   = get_option( 'zrcb_position', 'right' );

		require ZRCB_PLUGIN_DIR . 'admin/views/settings-page.php';
	}
}
