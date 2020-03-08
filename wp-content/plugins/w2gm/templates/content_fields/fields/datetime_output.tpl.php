<?php if ($formatted_date_start || $formatted_date_end): ?>
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
	<span class="w2gm-field-content">
		<?php
		if ($formatted_date_start) {
			echo $formatted_date_start;
		}
		if ($formatted_date_start && $formatted_date_end && $formatted_date_start != $formatted_date_end) {
			echo ' - ';
		}
		if ($formatted_date_end && $formatted_date_start != $formatted_date_end) {
			echo $formatted_date_end;
		}
		if($content_field->is_time) {
			echo ' ' . $content_field->value['hour'] . ':' . $content_field->value['minute'];
		}
		?>
	</span>
</div>
<?php endif; ?>