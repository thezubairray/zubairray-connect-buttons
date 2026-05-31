<?php
/**
 * Settings registration and sanitization.
 *
 * @package ZubairRay_Connect_Buttons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers plugin settings with the Options API.
 */
class ZRCB_Settings {

	/**
	 * Hook settings registration.
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'register' ) );
	}

	/**
	 * Register all plugin settings.
	 */
	public static function register() {
		register_setting(
			'zrcb_settings',
			'zrcb_channels',
			array(
				'type'              => 'array',
				'sanitize_callback' => 'zrcb_sanitize_channels',
				'default'           => zrcb_get_default_channels(),
			)
		);

		register_setting(
			'zrcb_settings',
			'zrcb_visibility',
			array(
				'type'              => 'array',
				'sanitize_callback' => 'zrcb_sanitize_visibility',
				'default'           => zrcb_get_default_visibility(),
			)
		);

		register_setting(
			'zrcb_settings',
			'zrcb_position',
			array(
				'type'              => 'string',
				'sanitize_callback' => array( __CLASS__, 'sanitize_position' ),
				'default'           => 'right',
			)
		);
	}

	/**
	 * Sanitize button position.
	 *
	 * @param string $value Raw setting value.
	 * @return string
	 */
	public static function sanitize_position( $value ) {
		return in_array( $value, array( 'left', 'right' ), true ) ? $value : 'right';
	}
}
