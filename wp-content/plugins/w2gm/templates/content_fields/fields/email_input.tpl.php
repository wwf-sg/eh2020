<div class="w2gm-form-group w2gm-field w2gm-field-input-block w2gm-field-input-block-<?php echo $content_field->id; ?>">
	<div class="w2gm-col-md-2">
		<label class="w2gm-control-label">
			<?php echo $content_field->name; ?><?php if ($content_field->canBeRequired() && $content_field->is_required): ?><span class="w2gm-red-asterisk">*</span><?php endif; ?>
		</label>
	</div>
	<div class="w2gm-col-md-10">
		<input type="text" name="w2gm-field-input-<?php echo $content_field->id; ?>" class="w2gm-field-input-email regular-text w2gm-form-control" value="<?php echo esc_attr($content_field->value); ?>" />
		<?php if ($content_field->description): ?><p class="description"><?php echo $content_field->description; ?></p><?php endif; ?>
	</div>
</div>