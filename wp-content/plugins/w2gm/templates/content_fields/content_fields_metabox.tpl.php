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

<div class="w2gm-content w2gm-content-fields-metabox">
	<div class="w2gm-form-horizontal">
		<p class="w2gm-description"><?php _e('Content fields may be dependent on selected categories', 'W2GM'); ?></p>
		<?php
		foreach ($content_fields AS $content_field) {
			if (
				!$content_field->is_core_field &&
				($content_field->filterForAdmins() || $post->post_author == get_current_user_id()) // this content field may be hidden from all users except admins and listing author
			) {
				$content_field->renderInput();
			}
		}
		?>
	</div>
</div>