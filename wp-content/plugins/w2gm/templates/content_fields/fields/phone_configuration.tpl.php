<?php w2gm_renderTemplate('admin_header.tpl.php'); ?>

<h2>
	<?php printf(__('Configure %s field', 'W2GM'), $w2gm_instance->content_fields->fields_types_names[$content_field->type]); ?>
</h2>

<form method="POST" action="">
	<?php wp_nonce_field(W2GM_PATH, 'w2gm_configure_content_fields_nonce');?>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label><?php _e('Max length',  'W2GM'); ?><span class="w2gm-red-asterisk">*</span></label>
				</th>
				<td>
					<input
						name="max_length"
						type="text"
						size="2"
						value="<?php echo esc_attr($content_field->max_length); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php _e('PHP RegEx template',  'W2GM'); ?></label>
				</th>
				<td>
					<input
						name="regex"
						type="text"
						class="regular-text"
						value="<?php echo esc_attr($content_field->regex); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php _e('Phone mode',  'W2GM'); ?></label>
					<p class="description"><?php _e("for mobile devices adds special tag to call directly from phone or open needed app", 'W2GM'); ?></p>
				</th>
				<td>
					<input
						id="phone_mode_phone"
						name="phone_mode"
						type="radio"
						value="phone"
						<?php checked('phone', $content_field->phone_mode); ?> /> <label for="phone_mode_phone"><?php _e('Phone call', 'W2GM'); ?></label>
					</br>
					<input
						id="phone_mode_viber"
						name="phone_mode"
						type="radio"
						value="viber"
						<?php checked('viber', $content_field->phone_mode); ?> /> <label for="phone_mode_viber"><?php _e('Viber chat', 'W2GM'); ?></label>
					</br>
					<input
						id="phone_mode_whatsapp"
						name="phone_mode"
						type="radio"
						value="whatsapp"
						<?php checked('whatsapp', $content_field->phone_mode); ?> /> <label for="phone_mode_whatsapp"><?php _e('WhatsApp chat', 'W2GM'); ?></label>
					</br>
					<input
						id="phone_mode_telegram"
						name="phone_mode"
						type="radio"
						value="telegram"
						<?php checked('telegram', $content_field->phone_mode); ?> /> <label for="phone_mode_telegram"><?php _e('Telegram chat', 'W2GM'); ?></label>
				</td>
			</tr>
		</tbody>
	</table>
	
	<?php submit_button(__('Save changes', 'W2GM')); ?>
</form>

<?php w2gm_renderTemplate('admin_footer.tpl.php'); ?>