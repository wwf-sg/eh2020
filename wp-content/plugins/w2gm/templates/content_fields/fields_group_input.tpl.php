<script>
	<?php
	foreach ($content_fields AS $content_field): 
		if (!$content_field->isCategories() || $content_field->categories === array()) { ?>
			w2gm_js_objects.fields_in_categories[<?php echo $content_field->id?>] = [];
		<?php } else { ?>
			w2gm_js_objects.fields_in_categories[<?php echo $content_field->id?>] = [<?php echo implode(',', $content_field->categories); ?>];
		<?php } ?>
	<?php endforeach; ?>
</script>

<div class="w2gm-submit-section w2gm-content-fields-metabox">
	<h3 class="w2gm-submit-section-label"><?php echo $group->name; ?></h3>
	<div class="w2gm-submit-section-inside w2gm-form-horizontal">
		<?php
		foreach ($content_fields AS $content_field) {
			$content_field->renderInput();
		}
		?>
	</div>
</div>