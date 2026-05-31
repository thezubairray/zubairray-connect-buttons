<?php
/**
 * Admin settings page template.
 *
 * @package ZubairRay_Connect_Buttons
 *
 * @var array<string, array<string, string>> $channels
 * @var array<string, string>                $visibility
 * @var string                               $position
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$channel_types = zrcb_get_channel_types();

if ( isset( $_GET['settings-updated'] ) ) {
	add_settings_error(
		'zrcb_messages',
		'zrcb_message',
		__( 'Settings saved.', 'zubairray-connect-buttons' ),
		'updated'
	);
}
?>
<div class="wrap zrcb-settings-wrap">
	<?php settings_errors( 'zrcb_messages' ); ?>
	<h1><?php esc_html_e( 'ZubairRay Connect Buttons Settings', 'zubairray-connect-buttons' ); ?></h1>
	<p class="zrcb-intro"><?php esc_html_e( 'A lightweight privacy-focused contact connection system for WordPress websites.', 'zubairray-connect-buttons' ); ?></p>

	<div class="zrcb-privacy-notice">
		<h2><?php esc_html_e( 'Privacy Friendly by Design', 'zubairray-connect-buttons' ); ?></h2>
		<ul>
			<li><?php esc_html_e( 'No external API calls', 'zubairray-connect-buttons' ); ?></li>
			<li><?php esc_html_e( 'No third-party scripts or tracking pixels', 'zubairray-connect-buttons' ); ?></li>
			<li><?php esc_html_e( 'No user data collection or visitor tracking', 'zubairray-connect-buttons' ); ?></li>
			<li><?php esc_html_e( 'Local CSS and bundled inline SVG icons only', 'zubairray-connect-buttons' ); ?></li>
		</ul>
	</div>

	<form method="post" action="options.php">
		<?php settings_fields( 'zrcb_settings' ); ?>

		<h2 class="zrcb-section-title"><?php esc_html_e( 'Contact Channels', 'zubairray-connect-buttons' ); ?></h2>
		<p class="description zrcb-section-desc"><?php esc_html_e( 'Configure one or more contact channels. Leave a value empty to hide that button.', 'zubairray-connect-buttons' ); ?></p>

		<div class="zrcb-channel-grid">
			<?php foreach ( $channel_types as $channel_id => $type ) : ?>
				<?php $channel = $channels[ $channel_id ]; ?>
				<fieldset class="zrcb-channel-card">
					<legend><?php echo esc_html( $type['title'] ); ?></legend>

					<p>
						<label for="zrcb_channel_<?php echo esc_attr( $channel_id ); ?>_value">
							<?php echo esc_html( $type['value_label'] ); ?>
						</label><br>
						<input
							type="<?php echo esc_attr( $type['input_type'] ); ?>"
							id="zrcb_channel_<?php echo esc_attr( $channel_id ); ?>_value"
							name="zrcb_channels[<?php echo esc_attr( $channel_id ); ?>][value]"
							value="<?php echo esc_attr( $channel['value'] ); ?>"
							class="regular-text"
							placeholder="<?php echo esc_attr( $type['placeholder'] ); ?>"
						>
						<span class="description"><?php echo esc_html( $type['description'] ); ?></span>
					</p>

					<p>
						<label for="zrcb_channel_<?php echo esc_attr( $channel_id ); ?>_label">
							<?php esc_html_e( 'Button Label', 'zubairray-connect-buttons' ); ?>
						</label><br>
						<input
							type="text"
							id="zrcb_channel_<?php echo esc_attr( $channel_id ); ?>_label"
							name="zrcb_channels[<?php echo esc_attr( $channel_id ); ?>][label]"
							value="<?php echo esc_attr( $channel['label'] ); ?>"
							class="regular-text"
							placeholder="<?php echo esc_attr( $type['default_label'] ); ?>"
						>
					</p>

					<p class="zrcb-color-row">
						<label for="zrcb_channel_<?php echo esc_attr( $channel_id ); ?>_color">
							<?php esc_html_e( 'Button Color', 'zubairray-connect-buttons' ); ?>
						</label><br>
						<input
							type="color"
							id="zrcb_channel_<?php echo esc_attr( $channel_id ); ?>_color"
							name="zrcb_channels[<?php echo esc_attr( $channel_id ); ?>][color]"
							value="<?php echo esc_attr( $channel['color'] ); ?>"
							class="zrcb-color-picker"
							data-hex-target="zrcb_channel_<?php echo esc_attr( $channel_id ); ?>_color_hex"
						>
						<input
							type="text"
							id="zrcb_channel_<?php echo esc_attr( $channel_id ); ?>_color_hex"
							value="<?php echo esc_attr( $channel['color'] ); ?>"
							class="zrcb-color-hex"
							maxlength="7"
							aria-label="<?php echo esc_attr( sprintf( /* translators: %s: channel name */ __( '%s button color hex value', 'zubairray-connect-buttons' ), $type['title'] ) ); ?>"
						>
					</p>
				</fieldset>
			<?php endforeach; ?>
		</div>

		<h2 class="zrcb-section-title"><?php esc_html_e( 'Display Settings', 'zubairray-connect-buttons' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="zrcb_position"><?php esc_html_e( 'Position', 'zubairray-connect-buttons' ); ?></label></th>
				<td>
					<select id="zrcb_position" name="zrcb_position">
						<option value="right" <?php selected( $position, 'right' ); ?>><?php esc_html_e( 'Right', 'zubairray-connect-buttons' ); ?></option>
						<option value="left" <?php selected( $position, 'left' ); ?>><?php esc_html_e( 'Left', 'zubairray-connect-buttons' ); ?></option>
					</select>
				</td>
			</tr>
		</table>

		<h2 class="zrcb-section-title"><?php esc_html_e( 'Smart Visibility Rules', 'zubairray-connect-buttons' ); ?></h2>
		<p class="description zrcb-section-desc"><?php esc_html_e( 'Choose where the contact buttons appear. Uncheck a rule to hide buttons for that audience or device.', 'zubairray-connect-buttons' ); ?></p>

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Device', 'zubairray-connect-buttons' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="zrcb_visibility[desktop]" value="1" <?php checked( $visibility['desktop'], '1' ); ?>>
						<?php esc_html_e( 'Show on desktop (769px and above)', 'zubairray-connect-buttons' ); ?>
					</label>
					<br>
					<label>
						<input type="checkbox" name="zrcb_visibility[mobile]" value="1" <?php checked( $visibility['mobile'], '1' ); ?>>
						<?php esc_html_e( 'Show on mobile (768px and below)', 'zubairray-connect-buttons' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Audience', 'zubairray-connect-buttons' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="zrcb_visibility[logged_in]" value="1" <?php checked( $visibility['logged_in'], '1' ); ?>>
						<?php esc_html_e( 'Show to logged-in users', 'zubairray-connect-buttons' ); ?>
					</label>
					<br>
					<label>
						<input type="checkbox" name="zrcb_visibility[guests]" value="1" <?php checked( $visibility['guests'], '1' ); ?>>
						<?php esc_html_e( 'Show to guests (logged-out visitors)', 'zubairray-connect-buttons' ); ?>
					</label>
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>
	</form>
</div>
