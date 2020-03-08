<?php if ($columns == 1) $col_md = 12; else $col_md = 6; ?>
<div class="w2gm-row w2gm-field-search-block-<?php echo $search_field->content_field->id; ?> w2gm-field-search-block-<?php echo $search_field->content_field->type; ?> w2gm-field-search-block-<?php echo $search_form_id; ?> w2gm-field-search-block-<?php echo $search_field->content_field->id; ?>_<?php echo $search_form_id; ?>">
	<div class="w2gm-col-md-12">
		<label><?php echo $search_field->content_field->name; ?></label>
	</div>

	<div class="w2gm-col-md-<?php echo $col_md; ?> w2gm-form-group">
		<input name="field_<?php echo $search_field->content_field->slug; ?>_min" class="w2gm-form-control" value="<?php echo esc_attr($search_field->min_max_value['min']); ?>" placeholder="<?php printf(esc_attr__('Min %s', 'W2GM'), $search_field->content_field->name); ?>">
	</div>

	<div class="w2gm-col-md-<?php echo $col_md; ?> w2gm-form-group">
		<input name="field_<?php echo $search_field->content_field->slug; ?>_max" class="w2gm-form-control" value="<?php echo esc_attr($search_field->min_max_value['max']); ?>" placeholder="<?php printf(esc_attr__('Max %s', 'W2GM'), $search_field->content_field->name); ?>">
	</div>
</div>