<div class="w2gm-map-directions-panel-wrapper" id="w2gm-map-directions-panel-wrapper-<?php echo $map_id; ?>">
	<div class="w2gm-map-directions-panel">
		<div class="w2gm-form-group">
			<div class="w2gm-has-feedback">
				<input name="address_a" value="" placeholder="<?php esc_attr_e('Enter origin address', 'W2GM')?>" class="w2gm-address-autocomplete w2gm-form-control w2gm-directions-address w2gm-directions-address-a" autocomplete="off" />
			</div>
		</div>
		<div class="w2gm-form-group">
			<div class="w2gm-has-feedback">
				<input name="listing_title" value="" placeholder="<?php esc_attr_e('Select distination on the map', 'W2GM')?>" class="w2gm-form-control w2gm-directions-address w2gm-directions-listing-title" autocomplete="off" readonly="readonly" />
				<input type="hidden" name="destination_coords" value="" class="w2gm-form-control w2gm-directions-address w2gm-directions-address-b" />
				<span class="w2gm-dropdowns-menu-button w2gm-form-control-feedback w2gm-glyphicon w2gm-glyphicon-map-marker"></span>
			</div>
		</div>
		<div class="w2gm-form-group w2gm-map-directions-sidebar-buttons w2gm-clearfix">
			<a href="javascript:void(0);" class="w2gm-btn w2gm-btn-primary w2gm-map-directions-sidebar-close-button" data-id="<?php echo $map_id; ?>"><?php _e("Close", "W2GM"); ?></a>
			<a href="javascript:void(0);" class="w2gm-btn w2gm-btn-primary w2gm-map-directions-sidebar-get-button" data-id="<?php echo $map_id; ?>"><?php _e("Get directions", "W2GM"); ?></a>
		</div>
		<div id="w2gm-route-container-<?php echo $map_id; ?>" class="w2gm-route-container">
			<p class="w2gm-route-container-description"><?php _e("Click 'Directions' button in selected location, enter origin address or use 'My location' button, then click 'Get directions' button.", "W2GM"); ?></p>
		</div>
	</div>
</div>