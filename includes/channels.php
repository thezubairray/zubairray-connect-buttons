<?php
/**
 * Contact channel registry and helpers.
 *
 * @package ZubairRay_Connect_Buttons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registered contact channel definitions.
 *
 * @return array<string, array<string, string>>
 */
function zrcb_get_channel_types() {
	return array(
		'email'     => array(
			'title'         => __( 'Email', 'zubairray-connect-buttons' ),
			'value_label'   => __( 'Email Address', 'zubairray-connect-buttons' ),
			'default_label' => __( 'Contact Now', 'zubairray-connect-buttons' ),
			'default_color' => '#1a4fd6',
			'placeholder'   => 'hello@example.com',
			'description'   => __( 'Opens the visitor\'s default email app.', 'zubairray-connect-buttons' ),
			'input_type'    => 'email',
		),
		'whatsapp'  => array(
			'title'         => __( 'WhatsApp', 'zubairray-connect-buttons' ),
			'value_label'   => __( 'WhatsApp Number', 'zubairray-connect-buttons' ),
			'default_label' => __( 'Let\'s Talk', 'zubairray-connect-buttons' ),
			'default_color' => '#25d366',
			'placeholder'   => '+1234567890',
			'description'   => __( 'Include the country code, for example +1234567890.', 'zubairray-connect-buttons' ),
			'input_type'    => 'text',
		),
		'phone'     => array(
			'title'         => __( 'Phone', 'zubairray-connect-buttons' ),
			'value_label'   => __( 'Phone Number', 'zubairray-connect-buttons' ),
			'default_label' => __( 'Call Us', 'zubairray-connect-buttons' ),
			'default_color' => '#2563eb',
			'placeholder'   => '+1234567890',
			'description'   => __( 'Opens the phone dialer on mobile devices.', 'zubairray-connect-buttons' ),
			'input_type'    => 'tel',
		),
		'telegram'  => array(
			'title'         => __( 'Telegram', 'zubairray-connect-buttons' ),
			'value_label'   => __( 'Telegram Username or Link', 'zubairray-connect-buttons' ),
			'default_label' => __( 'Telegram', 'zubairray-connect-buttons' ),
			'default_color' => '#0088cc',
			'placeholder'   => '@username',
			'description'   => __( 'Enter a username or full t.me link.', 'zubairray-connect-buttons' ),
			'input_type'    => 'text',
		),
		'messenger' => array(
			'title'         => __( 'Messenger', 'zubairray-connect-buttons' ),
			'value_label'   => __( 'Messenger Username or Link', 'zubairray-connect-buttons' ),
			'default_label' => __( 'Messenger', 'zubairray-connect-buttons' ),
			'default_color' => '#0084ff',
			'placeholder'   => 'your.page',
			'description'   => __( 'Enter a Facebook page username or m.me link.', 'zubairray-connect-buttons' ),
			'input_type'    => 'text',
		),
		'custom'    => array(
			'title'         => __( 'Custom Link', 'zubairray-connect-buttons' ),
			'value_label'   => __( 'Custom URL', 'zubairray-connect-buttons' ),
			'default_label' => __( 'Connect', 'zubairray-connect-buttons' ),
			'default_color' => '#6366f1',
			'placeholder'   => 'https://example.com/contact',
			'description'   => __( 'Any URL — booking page, form, support portal, etc.', 'zubairray-connect-buttons' ),
			'input_type'    => 'url',
		),
	);
}

/**
 * Default channel configuration.
 *
 * @return array<string, array<string, string>>
 */
function zrcb_get_default_channels() {
	$channels = array();

	foreach ( zrcb_get_channel_types() as $channel_id => $type ) {
		$channels[ $channel_id ] = array(
			'value' => '',
			'label' => $type['default_label'],
			'color' => $type['default_color'],
		);
	}

	return $channels;
}

/**
 * Get saved channel settings merged with defaults.
 *
 * @return array<string, array<string, string>>
 */
function zrcb_get_channels() {
	$saved = get_option( 'zrcb_channels', array() );

	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	$channels = array();

	foreach ( zrcb_get_channel_types() as $channel_id => $type ) {
		$channel = isset( $saved[ $channel_id ] ) && is_array( $saved[ $channel_id ] )
			? $saved[ $channel_id ]
			: array();

		$channels[ $channel_id ] = array(
			'value' => isset( $channel['value'] ) ? (string) $channel['value'] : '',
			'label' => isset( $channel['label'] ) ? (string) $channel['label'] : $type['default_label'],
			'color' => isset( $channel['color'] ) ? (string) $channel['color'] : $type['default_color'],
		);
	}

	return $channels;
}

/**
 * Get channels that have a valid destination URL.
 *
 * @return array<string, array<string, string>>
 */
function zrcb_get_active_channels() {
	$active = array();

	foreach ( zrcb_get_channels() as $channel_id => $channel ) {
		if ( zrcb_build_channel_url( $channel_id, $channel['value'] ) ) {
			$active[ $channel_id ] = $channel;
		}
	}

	return $active;
}

/**
 * Default visibility rules.
 *
 * @return array<string, string>
 */
function zrcb_get_default_visibility() {
	return array(
		'desktop'   => '1',
		'mobile'    => '1',
		'logged_in' => '1',
		'guests'    => '1',
	);
}

/**
 * Get saved visibility rules merged with defaults.
 *
 * @return array<string, string>
 */
function zrcb_get_visibility() {
	$saved = get_option( 'zrcb_visibility', array() );

	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	$visibility = wp_parse_args( $saved, zrcb_get_default_visibility() );

	if ( empty( $saved ) && '1' === get_option( 'zrcb_hide_on_mobile', '0' ) ) {
		$visibility['mobile'] = '0';
	}

	foreach ( array_keys( zrcb_get_default_visibility() ) as $rule ) {
		$visibility[ $rule ] = zrcb_is_visibility_enabled( $visibility, $rule ) ? '1' : '0';
	}

	return $visibility;
}

/**
 * Whether a visibility rule is enabled.
 *
 * @param array<string, mixed> $visibility Visibility settings.
 * @param string               $rule       Rule key.
 * @return bool
 */
function zrcb_is_visibility_enabled( $visibility, $rule ) {
	if ( ! isset( $visibility[ $rule ] ) ) {
		return true;
	}

	$value = $visibility[ $rule ];

	if ( is_array( $value ) ) {
		$value = end( $value );
	}

	return in_array( (string) $value, array( '1', 'true', 'yes', 'on' ), true );
}

/**
 * Whether buttons should render for the current visitor context.
 *
 * @return bool
 */
function zrcb_should_show_buttons() {
	$visibility = zrcb_get_visibility();

	if ( is_user_logged_in() ) {
		if ( ! zrcb_is_visibility_enabled( $visibility, 'logged_in' ) ) {
			return false;
		}
	} elseif ( ! zrcb_is_visibility_enabled( $visibility, 'guests' ) ) {
		return false;
	}

	if ( wp_is_mobile() ) {
		return zrcb_is_visibility_enabled( $visibility, 'mobile' );
	}

	return zrcb_is_visibility_enabled( $visibility, 'desktop' );
}

/**
 * CSS classes for device-based visibility.
 *
 * @return string[]
 */
function zrcb_get_visibility_classes() {
	$visibility = zrcb_get_visibility();
	$classes    = array();

	if ( ! zrcb_is_visibility_enabled( $visibility, 'desktop' ) ) {
		$classes[] = 'zrcb-hide-desktop';
	}

	if ( ! zrcb_is_visibility_enabled( $visibility, 'mobile' ) ) {
		$classes[] = 'zrcb-hide-mobile';
	}

	return $classes;
}

/**
 * Build inline CSS for device visibility rules.
 *
 * @return string
 */
function zrcb_build_visibility_css() {
	$visibility = zrcb_get_visibility();
	$css        = '';

	if ( ! zrcb_is_visibility_enabled( $visibility, 'desktop' ) ) {
		$css .= '@media (min-width:769px){.zrcb-wrapper{display:none!important}}';
	}

	if ( ! zrcb_is_visibility_enabled( $visibility, 'mobile' ) ) {
		$css .= '@media (max-width:768px){.zrcb-wrapper{display:none!important}}';
	}

	return $css;
}

/**
 * Sanitize a single channel value.
 *
 * @param string $channel_id Channel identifier.
 * @param string $value      Raw value.
 * @return string
 */
function zrcb_sanitize_channel_value( $channel_id, $value ) {
	$value = sanitize_text_field( $value );

	switch ( $channel_id ) {
		case 'email':
			return sanitize_email( $value );
		case 'whatsapp':
		case 'phone':
			return preg_replace( '/[^0-9+]/', '', $value );
		case 'telegram':
		case 'messenger':
			return sanitize_text_field( $value );
		case 'custom':
			return esc_url_raw( $value );
		default:
			return $value;
	}
}

/**
 * Sanitize all channel settings.
 *
 * @param mixed $value Raw settings value.
 * @return array<string, array<string, string>>
 */
function zrcb_sanitize_channels( $value ) {
	if ( ! is_array( $value ) ) {
		return zrcb_get_default_channels();
	}

	$sanitized = array();

	foreach ( zrcb_get_channel_types() as $channel_id => $type ) {
		$raw = isset( $value[ $channel_id ] ) && is_array( $value[ $channel_id ] )
			? $value[ $channel_id ]
			: array();

		$color = isset( $raw['color'] ) ? sanitize_hex_color( $raw['color'] ) : false;

		$sanitized[ $channel_id ] = array(
			'value' => zrcb_sanitize_channel_value( $channel_id, isset( $raw['value'] ) ? $raw['value'] : '' ),
			'label' => sanitize_text_field( isset( $raw['label'] ) ? $raw['label'] : $type['default_label'] ),
			'color' => $color ? $color : $type['default_color'],
		);
	}

	return $sanitized;
}

/**
 * Sanitize visibility rules.
 *
 * @param mixed $value Raw settings value.
 * @return array<string, string>
 */
function zrcb_sanitize_visibility( $value ) {
	if ( ! is_array( $value ) ) {
		return zrcb_get_default_visibility();
	}

	$sanitized = zrcb_get_default_visibility();

	foreach ( array_keys( $sanitized ) as $rule ) {
		$raw = isset( $value[ $rule ] ) ? $value[ $rule ] : '0';

		if ( is_array( $raw ) ) {
			$raw = end( $raw );
		}

		$sanitized[ $rule ] = zrcb_is_visibility_enabled( array( $rule => $raw ), $rule ) ? '1' : '0';
	}

	return $sanitized;
}

/**
 * Build a destination URL for a channel value.
 *
 * @param string $channel_id Channel identifier.
 * @param string $value      Channel value.
 * @return string|false
 */
function zrcb_build_channel_url( $channel_id, $value ) {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return false;
	}

	switch ( $channel_id ) {
		case 'email':
			return is_email( $value ) ? 'mailto:' . $value : false;

		case 'whatsapp':
			$digits = preg_replace( '/[^0-9]/', '', $value );
			return $digits ? 'https://wa.me/' . $digits : false;

		case 'phone':
			$phone = preg_replace( '/[^0-9+]/', '', $value );
			return $phone ? 'tel:' . $phone : false;

		case 'telegram':
			if ( preg_match( '#^https?://#i', $value ) ) {
				$url = esc_url_raw( $value );
				return $url ? $url : false;
			}

			$username = ltrim( $value, '@' );
			return $username ? 'https://t.me/' . rawurlencode( $username ) : false;

		case 'messenger':
			if ( preg_match( '#^https?://#i', $value ) ) {
				$url = esc_url_raw( $value );
				return $url ? $url : false;
			}

			$page = trim( $value, '/' );
			return $page ? 'https://m.me/' . rawurlencode( $page ) : false;

		case 'custom':
			$url = esc_url_raw( $value );
			return $url ? $url : false;

		default:
			return false;
	}
}

/**
 * Whether a channel link should open in a new tab.
 *
 * @param string $channel_id Channel identifier.
 * @return bool
 */
function zrcb_channel_opens_externally( $channel_id ) {
	return in_array( $channel_id, array( 'whatsapp', 'telegram', 'messenger', 'custom' ), true );
}

/**
 * Get inline SVG markup for a channel icon.
 *
 * @param string $channel_id Channel identifier.
 * @return string
 */
function zrcb_get_channel_svg( $channel_id ) {
	switch ( $channel_id ) {
		case 'email':
			return zrcb_svg_email();
		case 'whatsapp':
			return zrcb_svg_whatsapp();
		case 'phone':
			return zrcb_svg_phone();
		case 'telegram':
			return zrcb_svg_telegram();
		case 'messenger':
			return zrcb_svg_messenger();
		case 'custom':
			return zrcb_svg_link();
		default:
			return zrcb_svg_link();
	}
}

/**
 * Build gradient CSS for all active channel buttons.
 *
 * @param array<string, array<string, string>> $channels Active channels.
 * @return string
 */
function zrcb_build_channel_button_css( $channels ) {
	$css = '';

	foreach ( $channels as $channel_id => $channel ) {
		$color = sanitize_hex_color( $channel['color'] );

		if ( ! $color ) {
			continue;
		}

		$dark   = zrcb_darken_color( $color, 15 );
		$hover  = zrcb_lighten_color( $color, 5 );
		$hover2 = zrcb_lighten_color( $dark, 10 );

		$css .= sprintf(
			'.zrcb-btn-%1$s{background:linear-gradient(135deg,%2$s,%3$s)!important}.zrcb-btn-%1$s:hover{background:linear-gradient(135deg,%4$s,%5$s)!important}',
			esc_attr( $channel_id ),
			$color,
			$dark,
			$hover,
			$hover2
		);
	}

	return $css;
}
