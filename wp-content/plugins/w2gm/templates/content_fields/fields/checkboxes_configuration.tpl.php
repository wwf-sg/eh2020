<?php w2gm_renderTemplate('admin_header.tpl.php'); ?>

<h2>
	<?php _e('Configure checkboxes field', 'W2GM'); ?>
</h2>

<script>
	(function($) {
		"use strict";
	
		$(function() {
			var max_index = <?php echo ((count(array_keys($content_field->selection_items)) ? max(array_keys($content_field->selection_items)) : 1)); ?>;
			$("#add_selection_item").click(function() {
				max_index = max_index+1;
				$("#w2gm-selection-items-wrapper").append('<div class="selection_item"><input name="selection_items['+max_index+']" type="text" class="regular-text" value="" /><img class="w2gm-delete-selection-item" src="<?php echo W2GM_RESOURCES_URL . 'images/delete.png'?>" title="<?php esc_attr_e('Remove selection item', 'W2GM')?>" /> <span class="w2gm-icon-tag w2gm-icon-tag-'+max_index+'" style="display: none;"></span><input type="hidden" name="icon_images['+max_index+']" id="w2gm-icon-image-'+max_index+'" value=""><a class="w2gm-select-fa-icon" href="javascript: void(0);" data-icon-tag="w2gm-icon-tag-'+max_index+'" data-icon-image-name="w2gm-icon-image-'+max_index+'"><?php echo esc_js(__('select icon', 'W2GM')); ?></a> <span class="w2gm-move-label"><?php esc_attr_e('move', 'W2GM'); ?></span><?php echo esc_js('(ID: ', 'W2GM'); ?>'+max_index+')</div>');
			});
			$(document).on("click", ".w2gm-delete-selection-item", function() {
				$(this).parent().remove();
			});

			$("#w2gm-selection-items-wrapper").sortable({
				delay: 50,
				placeholder: "ui-sortable-placeholder",
				helper: function(e, ui) {
					ui.children().each(function() {
						$(this).width($(this).width());
					});
					return ui;
				},
				start: function(e, ui){
					ui.placeholder.height(ui.item.height());
				}
	    	});
		});
	})(jQuery);
</script>

<?php _e('You may order items by drag & drop.', 'W2GM'); ?>
<form method="POST" action="">
	<?php wp_nonce_field(W2GM_PATH, 'w2gm_configure_content_fields_nonce');?>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label><?php _e('Number of columns on frontend listing page', 'W2GM'); ?></label>
				</th>
				<td>
					<select name="columns_number">
						<option value="1" <?php selected($content_field->columns_number, 1); ?>>1</option>
						<option value="2" <?php selected($content_field->columns_number, 2); ?>>2</option>
						<option value="3" <?php selected($content_field->columns_number, 3); ?>>3</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php _e('How to display items', 'W2GM'); ?></label>
				</th>
				<td>
					<label>
						<input
							name="how_display_items"
							type="radio"
							value="all"
							<?php checked($content_field->how_display_items, 'all'); ?> />
							<?php _e('All items with checked/unchecked marks', 'W2GM'); ?>
					</label>
					<br />
					<label>
						<input
							name="how_display_items"
							type="radio"
							value="checked"
							<?php checked($content_field->how_display_items, 'checked'); ?> />
							<?php _e('Only checked items', 'W2GM'); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php _e('Selection items:', 'W2GM'); ?></label>
				</th>
				<td>
					<div id="w2gm-selection-items-wrapper">
						<?php if (count($content_field->selection_items)): ?>
						<?php foreach ($content_field->selection_items AS $key=>$item): ?>
						<div class="selection_item">
							<input
								name="selection_items[<?php echo $key; ?>]"
								type="text"
								class="regular-text"
								value="<?php echo $item; ?>" />
							<img class="w2gm-delete-selection-item" src="<?php echo W2GM_RESOURCES_URL . 'images/delete.png'?>" title="<?php esc_attr_e('Remove selection item', 'W2GM')?>" />
							
							<span class="w2gm-icon-tag w2gm-icon-tag-<?php echo $key; ?> <?php if (!empty($content_field->icon_images[$key])): ?>w2gm-fa <?php echo $content_field->icon_images[$key]; ?><?php endif; ?>" <?php if (empty($content_field->icon_images[$key])): ?>style="display: none;"<?php endif; ?>></span>
							<input type="hidden" name="icon_images[<?php echo $key; ?>]" id="w2gm-icon-image-<?php echo $key; ?>" value="<?php if (!empty($content_field->icon_images[$key])) echo esc_attr($content_field->icon_images[$key]); ?>">
							<a class="w2gm-select-fa-icon" href="javascript: void(0);" data-icon-tag="w2gm-icon-tag-<?php echo $key; ?>" data-icon-image-name="w2gm-icon-image-<?php echo $key; ?>"><?php echo esc_js(__('select icon', 'W2GM')); ?></a>
							
							<span class="w2gm-move-label"><?php _e('move', 'W2GM'); ?></span>
							<?php printf(__('(ID: %d)', 'W2GM'), $key); ?>
						</div>
						<?php endforeach; ?>
						<?php else: ?>
						<div class="selection_item">
							<input
								name="selection_items[1]"
								type="text"
								class="regular-text"
								value="" />
							<img class="w2gm-delete-selection-item" src="<?php echo W2GM_RESOURCES_URL . 'images/delete.png'?>" title="<?php esc_attr_e('Remove selection item', 'W2GM')?>" />
							<span class="w2gm-move-label"><?php _e('move', 'W2GM'); ?></span>
							<?php printf(__('(ID: %d)', 'W2GM'), 1); ?>
						</div>
						<?php endif; ?>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<input type="button" id="add_selection_item" class="button button-primary" value="<?php esc_attr_e('Add selection item', 'W2GM'); ?>" />
	
	<?php submit_button(__('Save changes', 'W2GM')); ?>
</form>

<?php w2gm_renderTemplate('admin_footer.tpl.php'); ?>