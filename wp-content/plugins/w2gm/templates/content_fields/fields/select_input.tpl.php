<?php if (count($content_field->selection_items)): ?>
<div class="w2gm-form-group w2gm-field w2gm-field-input-block w2gm-field-input-block-<?php echo $content_field->id; ?>">
	<div class="w2gm-col-md-2">
		<label class="w2gm-control-label">
			<?php echo $content_field->name; ?><?php if ($content_field->canBeRequired() && $content_field->is_required): ?><span class="w2gm-red-asterisk">*</span><?php endif; ?>
		</label>
	</div>
	<div class="w2gm-col-md-10">
		<select name="w2gm-field-input-<?php echo $content_field->id; ?>" class="w2gm-field-input-select w2gm-form-control">
			<option value=""><?php printf(__('- Select %s -', 'W2GM'), $content_field->name); ?></option>
			<?php foreach ($content_field->selection_items AS $key=>$item): ?>
			<option value="<?php echo esc_attr($key); ?>" <?php selected($content_field->value, $key, true); ?>><?php echo $item; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php if ($content_field->description): ?>
	<div class="w2gm-col-md-12 w2gm-col-md-offset-2">
		<p class="description"><?php echo $content_field->description; ?></p>
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>