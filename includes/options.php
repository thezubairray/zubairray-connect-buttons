<?php
/**
 * Central option key registry.
 *
 * @package ZubairRay_Connect_Buttons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Current plugin option keys.
 *
 * @return string[]
 */
function zrcb_get_option_keys() {
	return array(
		'zrcb_channels',
		'zrcb_visibility',
		'zrcb_position',
		'zrcb_channels_migrated',
		'zrcb_whatsapp_number',
		'zrcb_email',
		'zrcb_contact_label',
		'zrcb_whatsapp_label',
		'zrcb_email_color',
		'zrcb_whatsapp_color',
		'zrcb_hide_on_mobile',
	);
}

/**
 * Legacy option keys from older plugin versions.
 *
 * @return string[]
 */
function zrcb_get_legacy_option_keys() {
	return array(
		'fic_whatsapp_number',
		'fic_email',
		'fic_contact_label',
		'fic_whatsapp_label',
		'fic_position',
		'fic_email_color',
		'fic_whatsapp_color',
		'fic_hide_on_mobile',
		'zrfc_whatsapp_number',
		'zrfc_email',
		'zrfc_contact_label',
		'zrfc_whatsapp_label',
		'zrfc_position',
		'zrfc_email_color',
		'zrfc_whatsapp_color',
		'zrfc_hide_on_mobile',
	);
}

/**
 * Map legacy option keys to current keys.
 *
 * @return array<string, string>
 */
function zrcb_get_legacy_option_map() {
	return array(
		'fic_whatsapp_number'  => 'zrcb_whatsapp_number',
		'fic_email'            => 'zrcb_email',
		'fic_contact_label'    => 'zrcb_contact_label',
		'fic_whatsapp_label'   => 'zrcb_whatsapp_label',
		'fic_position'         => 'zrcb_position',
		'fic_email_color'      => 'zrcb_email_color',
		'fic_whatsapp_color'   => 'zrcb_whatsapp_color',
		'fic_hide_on_mobile'   => 'zrcb_hide_on_mobile',
		'zrfc_whatsapp_number' => 'zrcb_whatsapp_number',
		'zrfc_email'           => 'zrcb_email',
		'zrfc_contact_label'   => 'zrcb_contact_label',
		'zrfc_whatsapp_label'  => 'zrcb_whatsapp_label',
		'zrfc_position'        => 'zrcb_position',
		'zrfc_email_color'     => 'zrcb_email_color',
		'zrfc_whatsapp_color'  => 'zrcb_whatsapp_color',
		'zrfc_hide_on_mobile'  => 'zrcb_hide_on_mobile',
	);
}
