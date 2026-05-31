<?php
/**
 * Main plugin bootstrap.
 *
 * @package ZubairRay_Connect_Buttons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loads components and registers hooks.
 */
final class ZRCB_Plugin {

	/**
	 * Plugin instance.
	 *
	 * @var ZRCB_Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get plugin instance.
	 *
	 * @return ZRCB_Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->load_dependencies();
		$this->init_components();
	}

	/**
	 * Require class files.
	 */
	private function load_dependencies() {
		require_once ZRCB_PLUGIN_DIR . 'includes/options.php';
		require_once ZRCB_PLUGIN_DIR . 'includes/helpers.php';
		require_once ZRCB_PLUGIN_DIR . 'includes/channels.php';
		require_once ZRCB_PLUGIN_DIR . 'includes/class-migration.php';
		require_once ZRCB_PLUGIN_DIR . 'includes/class-settings.php';
		require_once ZRCB_PLUGIN_DIR . 'includes/class-admin.php';
		require_once ZRCB_PLUGIN_DIR . 'includes/class-frontend.php';
	}

	/**
	 * Initialize plugin components.
	 */
	private function init_components() {
		add_action( 'init', array( 'ZRCB_Migration', 'run' ), 5 );

		ZRCB_Settings::init();
		ZRCB_Admin::init();
		ZRCB_Frontend::init();
	}
}
