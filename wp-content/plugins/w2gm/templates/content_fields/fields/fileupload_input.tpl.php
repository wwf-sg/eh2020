<div class="w2gm-form-group w2gm-field w2gm-field-input-block w2gm-field-input-block-<?php echo $content_field->id; ?>">
	<div class="w2gm-col-md-2">
		<label class="w2gm-control-label">
			<?php echo $content_field->name; ?><?php if ($content_field->canBeRequired() && $content_field->is_required): ?><span class="w2gm-red-asterisk">*</span><?php endif; ?>
		</label>
	</div>
	<div class="w2gm-col-md-10">
		<div class="w2gm-row">
			<?php if ($file): ?>
			<div class="w2gm-col-md-6">
				<label><?php _e('Uploaded file:', 'W2GM'); ?></label>
				<a href="<?php echo esc_url($file->guid); ?>" target="_blank"><?php echo basename($file->guid); ?></a>
				<input type="hidden" name="w2gm-uploaded-file-<?php echo $content_field->id; ?>" value="<?php echo $file->ID; ?>" />
				<br />
				<label><input type="checkbox" name="w2gm-reset-file-<?php echo $content_field->id; ?>" value="1" /> <?php _e('reset uploaded file', 'W2GM'); ?></label>
			</div>
			<?php endif; ?>
			<div class="w2gm-col-md-6">
				<label><?php _e('Select file to upload:', 'W2GM'); ?></label>
				<input type="file" name="w2gm-field-input-<?php echo $content_field->id; ?>" class="w2gm-field-input-fileupload" />
			</div>
			<?php if ($content_field->use_text): ?>
			<div class="w2gm-col-md-12">
				<label><?php _e('File title:', 'W2GM'); ?></label>
				<input type="text" name="w2gm-field-input-text-<?php echo $content_field->id; ?>" class="w2gm-field-input-text w2gm-form-control regular-text" value="<?php echo esc_attr($content_field->value['text']); ?>" />
			</div>
			<?php endif; ?>
		</div>
		<?php if ($content_field->description): ?><p class="description"><?php echo $content_field->description; ?></p><?php endif; ?>
	</div>
</div>