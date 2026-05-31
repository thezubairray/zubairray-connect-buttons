<?php
/**
 * Front-end assets and markup.
 *
 * @package ZubairRay_Connect_Buttons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Outputs floating contact buttons on the public site.
 */
class ZRCB_Frontend {

	/**
	 * Hook front-end functionality.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_action( 'wp_footer', array( __CLASS__, 'render_buttons' ) );
	}

	/**
	 * Enqueue frontend assets.
	 */
	public static function enqueue_assets() {
		$channels = zrcb_get_active_channels();

		if ( empty( $channels ) || ! zrcb_should_show_buttons() ) {
			return;
		}

		wp_enqueue_style(
			'zubairray-connect-buttons',
			ZRCB_PLUGIN_URL . 'assets/css/zrcb.css',
			array(),
			ZRCB_VERSION
		);

		$custom_css = zrcb_build_channel_button_css( $channels );
		$custom_css .= zrcb_build_visibility_css();

		wp_add_inline_style( 'zubairray-connect-buttons', $custom_css );
	}

	/**
	 * Output contact buttons in the footer.
	 */
	public static function render_buttons() {
		$channels = zrcb_get_active_channels();

		if ( empty( $channels ) || ! zrcb_should_show_buttons() ) {
			return;
		}

		$position       = get_option( 'zrcb_position', 'right' );
		$position_class = 'left' === $position ? 'zrcb-left' : 'zrcb-right';
		$wrapper_class  = trim( $position_class . ' ' . implode( ' ', zrcb_get_visibility_classes() ) );
		?>
		<div class="zrcb-wrapper <?php echo esc_attr( $wrapper_class ); ?>">
			<?php foreach ( zrcb_get_channel_types() as $channel_id => $type ) : ?>
				<?php
				if ( ! isset( $channels[ $channel_id ] ) ) {
					continue;
				}

				$channel = $channels[ $channel_id ];
				$url     = zrcb_build_channel_url( $channel_id, $channel['value'] );

				if ( ! $url ) {
					continue;
				}

				$link_attrs = array(
					'href'  => $url,
					'class' => 'zrcb-btn zrcb-btn-' . $channel_id,
					'title' => $channel['label'],
				);

				if ( zrcb_channel_opens_externally( $channel_id ) ) {
					$link_attrs['target'] = '_blank';
					$link_attrs['rel']    = 'noopener noreferrer';
				}

				$attr_string = '';

				foreach ( $link_attrs as $attr => $attr_value ) {
					$attr_string .= sprintf( ' %s="%s"', esc_attr( $attr ), esc_attr( $attr_value ) );
				}
				?>
				<a<?php echo $attr_string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<span class="zrcb-icon" aria-hidden="true"><?php echo wp_kses( zrcb_get_channel_svg( $channel_id ), zrcb_allowed_svg_tags() ); ?></span>
					<span class="zrcb-label"><?php echo esc_html( $channel['label'] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
