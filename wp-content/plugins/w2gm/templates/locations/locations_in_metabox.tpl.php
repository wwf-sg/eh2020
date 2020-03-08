		<div class="w2gm-location-in-metabox">
			<?php $uID = rand(1, 10000); ?>
			<input type="hidden" name="w2gm_location[<?php echo $uID;?>]" value="1" />

			<?php
			if (w2gm_is_anyone_in_taxonomy(W2GM_LOCATIONS_TAX)) {
				w2gm_tax_dropdowns_init(
					W2GM_LOCATIONS_TAX,
					null,
					$location->selected_location,
					false,
					$locations_levels->getNamesArray(),
					$locations_levels->getSelectionsArray(),
					$uID,
					$listing->level->locations,
					false
				);
			}
			?>

			<div class="w2gm-row w2gm-form-group w2gm-location-input w2gm-address-line-1-wrapper" <?php if (!w2gm_get_dynamic_option('w2gm_enable_address_line_1')): ?>style="display: none;"<?php endif; ?>>
				<div class="w2gm-col-md-2">
					<label class="w2gm-control-label">
						<?php
						if (!w2gm_get_dynamic_option('w2gm_enable_address_line_2'))
							_e('Address', 'W2GM');
						else
							_e('Address line 1', 'W2GM');
						?>
					</label>
				</div>
				<div class="w2gm-col-md-10">
					<div class="w2gm-has-feedback">
						<input type="text" id="address_line_<?php echo $uID;?>" name="address_line_1[<?php echo $uID;?>]" class="w2gm-address-line-1 w2gm-form-control <?php if (get_option('w2gm_address_autocomplete')): ?>w2gm-field-autocomplete<?php endif; ?>" value="<?php echo esc_attr($location->address_line_1); ?>" placeholder="" />
						<?php if (get_option('w2gm_address_geocode')): ?>
						<span class="w2gm-get-location w2gm-form-control-feedback w2gm-glyphicon w2gm-glyphicon-screenshot"></span>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="w2gm-row w2gm-form-group w2gm-location-input w2gm-address-line-2-wrapper" <?php if (!w2gm_get_dynamic_option('w2gm_enable_address_line_2')): ?>style="display: none;"<?php endif; ?>>
				<div class="w2gm-col-md-2">
					<label class="w2gm-control-label">
						<?php
						if (!w2gm_get_dynamic_option('w2gm_enable_address_line_1'))
							_e('Address', 'W2GM');
						else
							_e('Address line 2', 'W2GM');
						?>
					</label>
				</div>
				<div class="w2gm-col-md-10">
					<input type="text" name="address_line_2[<?php echo $uID;?>]" class="w2gm-address-line-2 w2gm-form-control" value="<?php echo esc_attr($location->address_line_2); ?>" />
				</div>
			</div>

			<div class="w2gm-row w2gm-form-group w2gm-location-input w2gm-zip-or-postal-index-wrapper" <?php if (!w2gm_get_dynamic_option('w2gm_enable_postal_index')): ?>style="display: none;"<?php endif; ?>>
				<div class="w2gm-col-md-2">
					<label class="w2gm-control-label">
						<?php _e('Zip code', 'W2GM'); ?>
					</label>
				</div>
				<div class="w2gm-col-md-10">
					<input type="text" name="zip_or_postal_index[<?php echo $uID;?>]" class="w2gm-zip-or-postal-index w2gm-form-control" value="<?php echo esc_attr($location->zip_or_postal_index); ?>" />
				</div>
			</div>

			<div class="w2gm-row w2gm-form-group w2gm-location-input w2gm-additional-info-wrapper" <?php if (!w2gm_get_dynamic_option('w2gm_enable_additional_info')): ?>style="display: none;"<?php endif; ?>>
				<div class="w2gm-col-md-2">
					<label class="w2gm-control-label">
						<?php _e('Additional info for map marker infowindow', 'W2GM'); ?>
					</label>
				</div>
				<div class="w2gm-col-md-10">
					<textarea name="additional_info[<?php echo $uID;?>]" class="w2gm-additional-info w2gm-form-control" rows="2"><?php echo esc_textarea($location->additional_info); ?></textarea>
				</div>
			</div>

			<div class="w2gm-manual-coords-wrapper" <?php if (!w2gm_get_dynamic_option('w2gm_enable_manual_coords')): ?>style="display: none;"<?php endif; ?>>
				<div class="w2gm-row w2gm-location-input w2gm-form-group">
					<div class="w2gm-col-md-12 w2gm-checkbox">
						<label>
							<input type="checkbox" name="manual_coords[<?php echo $uID;?>]" value="1" class="w2gm-manual-coords" <?php if ($location->manual_coords) echo 'checked'; ?> /> <?php _e('Enter coordinates manually', 'W2GM'); ?>
						</label>
					</div>
				</div>

				<!-- w2gm-manual-coords-block - position required for jquery selector -->
				<div class="w2gm-manual-coords-block" <?php if (!$location->manual_coords) echo 'style="display: none;"'; ?>>
					<div class="w2gm-row w2gm-form-group w2gm-location-input">
						<div class="w2gm-col-md-2">
							<label class="w2gm-control-label">
								<?php _e('Latitude', 'W2GM'); ?>
							</label>
						</div>
						<div class="w2gm-col-md-10">
							<input type="text" name="map_coords_1[<?php echo $uID;?>]" class="w2gm-map-coords-1 w2gm-form-control" value="<?php echo esc_attr($location->map_coords_1); ?>">
						</div>
					</div>
	
					<div class="w2gm-row w2gm-form-group w2gm-location-input">
						<div class="w2gm-col-md-2">
							<label class="w2gm-control-label">
								<?php _e('Longitude', 'W2GM'); ?>
							</label>
						</div>
						<div class="w2gm-col-md-10">
							<input type="text" name="map_coords_2[<?php echo $uID;?>]" class="w2gm-map-coords-2 w2gm-form-control" value="<?php echo esc_attr($location->map_coords_2); ?>">
						</div>
					</div>
				</div>
			</div>

			<?php if ($listing->level->map_markers): ?>
			<div class="w2gm-row w2gm-location-input">
				<div class="w2gm-col-md-12">
					<a class="w2gm-select-map-icon" href="javascript: void(0);">
						<span class="w2gm-fa w2gm-fa-map-marker"></span>
						<?php _e('Select marker icon', 'W2GM'); ?><?php if (get_option('w2gm_map_markers_type') == 'icons') _e(' (icon and color may depend on selected categories)', 'W2GM'); ?>
					</a>
					<input type="hidden" name="map_icon_file[<?php echo $uID;?>]" class="w2gm-map-icon-file" value="<?php if ($location->map_icon_manually_selected) echo esc_attr($location->map_icon_file); ?>">
				</div>
			</div>
			<?php endif; ?>

			<div class="w2gm-row w2gm-location-input">
				<div class="w2gm-col-md-12">
					<a href="javascript: void(0);" <?php if (!$delete_location_link) echo 'style="display:none;"'; ?> class="w2gm-delete-address">
						<span class="w2gm-fa w2gm-fa-minus"></span>
						<?php _e('Delete address', 'W2GM'); ?>
					</a>
				</div>
			</div>
		</div>