<?php w2gm_renderTemplate('admin_header.tpl.php'); ?>

<h2><?php _e('CSV Import'); ?></h2>

<p class="description"><?php _e('On this first step select CSV file for import, also you may import images in zip archive', 'W2GM'); ?></p>

<script>
	(function($) {
		"use strict";

		$(function() {
			$("#import_button").on("click", function(e) {
				if (confirm('Please, make backup of whole wordpress database before import.'))
					$("#import_form").trigger('click');
				else
					e.preventDefault();
			});
		});
	})(jQuery);
</script>
<form method="POST" action="" id="import_form" enctype="multipart/form-data">
	<input type="hidden" name="action" value="import_settings">
	<?php wp_nonce_field(W2GM_PATH, 'w2gm_csv_import_nonce');?>
	
	<h3><?php _e('Import settings', 'W2GM'); ?></h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label><?php _e('Import type', 'W2GM'); ?><span class="w2gm-red-asterisk">*</span></label>
				</th>
				<td>
					<label>
						<input
							name="import_type"
							type="radio"
							value="create"
							checked />
						<?php _e('create new listings', 'W2GM'); ?>
					</label>

					<br />

					<label>
						<input
							name="import_type"
							type="radio"
							value="update" />
						<?php _e('update existing listings (post ID column required)', 'W2GM'); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php _e('CSV File', 'W2GM'); ?><span class="w2gm-red-asterisk">*</span></label>
				</th>
				<td>
					<input
						name="csv_file"
						type="file" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php _e('Images ZIP archive', 'W2GM'); ?>
				</th>
				<td>
					<input
						name="images_file"
						type="file" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php _e('Columns separator', 'W2GM'); ?><span class="w2gm-red-asterisk">*</span></label>
				</th>
				<td>
					<input
						name="columns_separator"
						type="text"
						size="2"
						value="<?php echo isset($columns_separator) ? esc_attr($columns_separator) : ','; ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php _e('Categories, Locations, Tags, Images, MultiValues separator', 'W2GM'); ?><span class="w2gm-red-asterisk">*</span></label>
				</th>
				<td>
					<input
						name="values_separator"
						type="text"
						size="2"
						value="<?php echo isset($values_separator) ? esc_attr($values_separator) : ';'; ?>" />
				</td>
			</tr>
		</tbody>
	</table>
	
	<?php w2gm_renderTemplate('csv_manager/import_instructions.tpl.php'); ?>
	
	<?php submit_button(__('Upload', 'W2GM'), 'primary', 'submit', true, array('id' => 'import_button')); ?>
</form>

<h2><?php _e('CSV Export'); ?></h2>

<p class="description"><?php _e('Enter offset of listing to start with. Enter 0 to start from the beginning. It will export entered number of listings. Reduce the number of listings if you get timeout message.', 'W2GM'); ?></p>

<form method="POST" action="" enctype="multipart/form-data">
	<input type="hidden" name="action" value="export_settings">
	<?php wp_nonce_field(W2GM_PATH, 'w2gm_csv_import_nonce');?>
	
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<?php _e('Listings number', 'W2GM'); ?>
				</th>
				<td>
					<input
						name="number"
						type="text"
						value="1000" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('Listings offset', 'W2GM'); ?>
				</th>
				<td>
					<input
						name="offset"
						type="text"
						value="0" />
				</td>
			</tr>
		</tbody>
	</table>
	
	<?php submit_button(__('Export', 'W2GM'), 'primary', 'csv_export'); ?>
	<?php submit_button(__('Download Images', 'W2GM'), 'primary', 'export_images'); ?>
</form>

<?php w2gm_renderTemplate('admin_footer.tpl.php'); ?>