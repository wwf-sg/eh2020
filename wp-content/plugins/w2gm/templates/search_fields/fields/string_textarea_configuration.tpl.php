<?php w2gm_renderTemplate('admin_header.tpl.php'); ?>

<h2>
	<?php printf(__('Configure %s search field', 'W2GM'), $w2gm_instance->content_fields->fields_types_names[$search_field->content_field->type]); ?>
</h2>

<form method="POST" action="">
	<?php wp_nonce_field(W2GM_PATH, 'w2gm_configure_content_fields_nonce');?>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label><?php _e('Search input mode', 'W2GM'); ?><span class="w2gm-red-asterisk">*</span></label>
				</th>
				<td>
					<label>
						<input
							name="search_input_mode"
							type="radio"
							value="keywords"
							<?php checked($search_field->search_input_mode, 'keywords'); ?> />
						<?php _e('Search by keywords field', 'W2GM'); ?>
					</label>
					<br />
					<label>
						<input
							name="search_input_mode"
							type="radio"
							value="input"
							<?php checked($search_field->search_input_mode, 'input'); ?> />
							<?php _e('Render own search field', 'W2GM'); ?>
					</label>
				</td>
			</tr>
		</tbody>
	</table>
	
	<?php submit_button(__('Save changes', 'W2GM')); ?>
</form>

<?php w2gm_renderTemplate('admin_footer.tpl.php'); ?>