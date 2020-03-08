<?php if ($content_field->value): ?>
<div class="w2gm-field w2gm-field-output-block w2gm-field-output-block-<?php echo $content_field->type; ?> w2gm-field-output-block-<?php echo $content_field->id; ?>">
	<?php if ($content_field->icon_image || !$content_field->is_hide_name): ?>
	<span class="w2gm-field-caption <?php w2gm_is_any_field_name_in_group($group); ?>">
		<?php if ($content_field->icon_image): ?>
		<span class="w2gm-field-icon w2gm-fa w2gm-fa-lg <?php echo $content_field->icon_image; ?>"></span>
		<?php endif; ?>
		<?php if (!$content_field->is_hide_name): ?>
		<span class="w2gm-field-name"><?php echo $content_field->name?>:</span>
		<?php endif; ?>
	</span>
	<?php endif; ?>
	<ul class="w2gm-field-content w2gm-checkboxes-columns-<?php echo $content_field->columns_number; ?>">
	<?php if ($content_field->how_display_items == 'all'): ?>
	<?php foreach ($content_field->selection_items AS $key=>$item): ?>
		<li class="w2gm-field-checkbox-item-<?php echo (in_array($key, $content_field->value) ? "checked" : "unchecked"); ?>">
			<?php if ($content_field->icon_images[$key]): ?>
			<span class="w2gm-field-icon w2gm-fa w2gm-fa-lg w2gm-fa-fw <?php echo $content_field->icon_images[$key]; ?>"></span>
			<?php endif; ?>
			<?php echo $item; ?>
		</li>
	<?php endforeach; ?>
	<?php elseif ($content_field->how_display_items == 'checked'): ?>
	<?php foreach ($content_field->value AS $key): ?>
		<?php if (isset($content_field->selection_items[$key])): ?>
		<li class="w2gm-field-checkbox-item-<?php echo (in_array($key, $content_field->value) ? "checked" : "unchecked"); ?>">
			<?php if ($content_field->icon_images[$key]): ?>
			<span class="w2gm-field-icon w2gm-fa w2gm-fa-lg <?php echo $content_field->icon_images[$key]; ?>"></span>
			<?php endif; ?>
			<?php echo $content_field->selection_items[$key]; ?>
		</li>
		<?php endif; ?>
	<?php endforeach; ?>
	<?php endif; ?>
	</ul>
</div>
<?php endif; ?>