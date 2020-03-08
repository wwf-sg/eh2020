	<div class="w2gm-row w2gm-form-group">
		<?php if (get_option('w2gm_directions_functionality') == 'builtin'): ?>
		<div class="w2gm-form-group w2gm-col-md-12">
			<label class="w2gm-control-label"><?php _e('Get directions from:', 'W2GM'); ?></label>
			<div class="w2gm-has-feedback">
				<input type="text" id="w2gm-origin-address-<?php echo $map_id; ?>" class="w2gm-form-control <?php if (get_option('w2gm_address_autocomplete')): ?>w2gm-field-autocomplete<?php endif; ?>" placeholder="<?php esc_attr_e('Enter address or zip code', 'W2GM'); ?>" />
				<?php if (get_option('w2gm_address_geocode')): ?>
				<span class="w2gm-get-location w2gm-form-control-feedback w2gm-glyphicon w2gm-glyphicon-screenshot"></span>
				<?php endif; ?>
			</div>
		</div>
		<div class="w2gm-form-group w2gm-col-md-12">
			<?php $i = 1; ?>
			<?php foreach ($locations_array AS $location): ?>
			<div class="w2gm-radio">
				<label>
					<input type="radio" name="daddr" class="w2gm-select-directions-<?php echo $map_id; ?>" <?php checked($i, 1); ?> value="<?php esc_attr_e($location->map_coords_1.' '.$location->map_coords_2); ?>" />
					<?php 
					if ($address = $location->getWholeAddress(false))
						echo $address;
					else 
						echo $location->map_coords_1.' '.$location->map_coords_2;
					?>
				</label>
			</div>
			<?php $i++; ?>
			<?php endforeach; ?>
		</div>
		<div class="w2gm-form-group w2gm-col-md-12">
			<input type="button" class="w2gm-get-directions-button front-btn w2gm-btn w2gm-btn-primary" data-id="<?php echo $map_id; ?>" value="<?php esc_attr_e('Get directions', 'W2GM'); ?>">
		</div>
		<div class="w2gm-form-group w2gm-col-md-12">
			<div id="w2gm-route-container-<?php echo $map_id; ?>" class="w2gm-route-container w2gm-maps-direction-route"></div>
		</div>
		<?php elseif (get_option('w2gm_directions_functionality') == 'google'): ?>
		<label class="w2gm-col-md-12 w2gm-control-label"><?php _e('directions to:', 'W2GM'); ?></label>
		<form action="//maps.google.com" target="_blank">
			<input type="hidden" name="saddr" value="Current Location" />
			<div class="w2gm-col-md-12">
				<?php $i = 1; ?>
				<?php foreach ($locations_array AS $location): ?>
				<div class="w2gm-radio">
					<label>
						<input type="radio" name="daddr" class="w2gm-select-directions-<?php echo $map_id; ?>" <?php checked($i, 1); ?> value="<?php esc_attr_e($location->map_coords_1.','.$location->map_coords_2); ?>" />
						<?php 
						if ($address = $location->getWholeAddress(false))
							echo $address;
						else 
							echo $location->map_coords_1.' '.$location->map_coords_2;
						?>
					</label>
				</div>
				<?php $i++; ?>
				<?php endforeach; ?>
			</div>
			<div class="w2gm-col-md-12">
				<input class="w2gm-btn w2gm-btn-primary" type="submit" value="<?php esc_attr_e('Get directions', 'W2GM'); ?>" />
			</div>
		</form>
		<?php endif; ?>
	</div>