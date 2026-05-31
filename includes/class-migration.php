<?php
/**
 * Legacy option migration.
 *
 * @package ZubairRay_Connect_Buttons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Migrates settings from older option names.
 */
class ZRCB_Migration {

	/**
	 * Copy legacy options into current keys when needed.
	 */
	public static function run() {
		self::migrate_legacy_option_names();
		self::migrate_flat_options_to_channels();
	}

	/**
	 * Migrate fic_/zrfc_ prefixed options to zrcb_ keys.
	 */
	private static function migrate_legacy_option_names() {
		foreach ( zrcb_get_legacy_option_map() as $old_option => $new_option ) {
			$old_value = get_option( $old_option, false );

			if ( false !== $old_value && false === get_option( $new_option, false ) ) {
				update_option( $new_option, $old_value );
			}
		}
	}

	/**
	 * Migrate flat email/WhatsApp options into the channel system.
	 */
	private static function migrate_flat_options_to_channels() {
		if ( get_option( 'zrcb_channels_migrated', false ) ) {
			return;
		}

		$channels = zrcb_get_default_channels();
		$email    = get_option( 'zrcb_email', '' );
		$whatsapp = get_option( 'zrcb_whatsapp_number', '' );

		if ( $email ) {
			$channels['email']['value'] = sanitize_email( $email );
			$channels['email']['label'] = sanitize_text_field( get_option( 'zrcb_contact_label', $channels['email']['label'] ) );
			$email_color                  = sanitize_hex_color( get_option( 'zrcb_email_color', $channels['email']['color'] ) );
			$channels['email']['color']   = $email_color ? $email_color : $channels['email']['color'];
		}

		if ( $whatsapp ) {
			$channels['whatsapp']['value'] = preg_replace( '/[^0-9+]/', '', $whatsapp );
			$channels['whatsapp']['label'] = sanitize_text_field( get_option( 'zrcb_whatsapp_label', $channels['whatsapp']['label'] ) );
			$whatsapp_color                = sanitize_hex_color( get_option( 'zrcb_whatsapp_color', $channels['whatsapp']['color'] ) );
			$channels['whatsapp']['color'] = $whatsapp_color ? $whatsapp_color : $channels['whatsapp']['color'];
		}

		update_option( 'zrcb_channels', $channels );
		update_option( 'zrcb_channels_migrated', '1' );
	}
}
