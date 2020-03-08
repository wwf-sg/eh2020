<div class="w2gm-form-group w2gm-field w2gm-field-input-block w2gm-field-input-block-<?php echo $content_field->id; ?>">
	<div class="w2gm-col-md-2">
		<label class="w2gm-control-label">
			<?php echo $content_field->name; ?>
		</label>
	</div>
	<div class="w2gm-col-md-10">
		<?php foreach ($week_days AS $key=>$day): ?>
		<div class="w2gm-week-day-wrap">
			<span class="w2gm-week-day"><?php echo $content_field->week_days_names[$key]; ?></span> <span class="w2gm-week-day-controls"><select name="<?php echo $day; ?>_from_hour_<?php echo $content_field->id; ?>" class="w2gm-week-day-input"><?php echo $content_field->getOptionsHour($day.'_from'); ?></select><?php if ($content_field->hours_clock == 12): ?> <select name="<?php echo $day; ?>_from_am_pm_<?php echo $content_field->id; ?>" class="w2gm-week-day-input"><?php echo $content_field->getOptionsAmPm($day.'_from'); ?></select><?php endif; ?></span> &nbsp;&nbsp;-&nbsp;&nbsp; <span class="w2gm-week-day-controls"><select name="<?php echo $day; ?>_to_hour_<?php echo $content_field->id; ?>" class="w2gm-week-day-input"><?php echo $content_field->getOptionsHour($day.'_to'); ?></select><?php if ($content_field->hours_clock == 12): ?> <select name="<?php echo $day; ?>_to_am_pm_<?php echo $content_field->id; ?>" class="w2gm-week-day-input"><?php echo $content_field->getOptionsAmPm($day.'_to'); ?></select><?php endif; ?></span> <label><input type="checkbox" name="<?php echo $day; ?>_closed_<?php echo $content_field->id; ?>" class="w2gm-closed-day-option" <?php checked($content_field->value[$day.'_closed'], 1); ?> class="closed_cb" value="1" /> <?php _e('Closed', 'W2GM'); ?></label>
		</div>
		<?php endforeach; ?>
		<div class="w2gm-week-day-clear-button">
			<button class="w2gm-btn w2gm-btn-primary w2gm-clear-hours"><?php _e('Reset hours & minutes', 'W2GM'); ?></button>
		</div>
	</div>
</div>