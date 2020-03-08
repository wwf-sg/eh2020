<form class="w2gm-content w2gm-map-search-form w2gm-search-form-submit <?php $search_form->printClasses(); ?>" id="w2gm-map-search-form-<?php echo $uid; ?>" data-id="<?php echo $search_form_id; ?>">
	<?php $search_form->outputHiddenFields(); ?>
	<?php if ($search_form->isCategoriesOrKeywords()): ?>
	<div class="w2gm-map-search-wrapper" id="w2gm-map-search-wrapper-<?php echo $uid; ?>">
		<div class="w2gm-map-search-input-container">
			<div class="w2gm-row w2gm-form-group">
				<div class="w2gm-col-md-12">
					<?php
					if ($search_form->isCategories()) {
						w2gm_tax_dropdowns_menu_init($search_form->getCategoriesDropdownsMenuParams(__('Select category', 'W2GM'), __('Select category or enter keywords', 'W2GM'))); 
					} else { ?>
					<div class="w2gm-has-feedback">
						<input name="what_search" value="<?php echo esc_attr($search_form->getKeywordValue()); ?>" placeholder="<?php esc_attr_e('Enter keywords', 'W2GM')?>" class="<?php if ($search_form->isKeywordsAJAX()): ?>w2gm-keywords-autocomplete<?php endif; ?> w2gm-form-control w2gm-main-search-field" autocomplete="off" />
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="w2gm-map-search-toggle-container">
			<span class="w2gm-map-search-toggle" data-id="<?php echo $uid; ?>"></span>
		</div>
	</div>
	<?php endif; ?>
	<div class="w2gm-map-search-panel-wrapper" id="w2gm-map-search-panel-wrapper-<?php echo $uid; ?>">
		<?php if ($search_form->isLocationsOrAddress()): ?>
		<div class="w2gm-map-search-panel">
			<div class="w2gm-row w2gm-form-group">
				<div class="w2gm-col-md-12">
					<?php
					if ($search_form->isLocations()) {
						w2gm_tax_dropdowns_menu_init($search_form->getLocationsDropdownsMenuParams(__('Select location', 'W2GM'), __('Select location or enter address', 'W2GM')));
					} else { ?>
					<div class="w2gm-has-feedback">
						<input name="address" value="<?php echo esc_attr($search_form->getAddressValue()); ?>" placeholder="<?php esc_attr_e('Enter address', 'W2GM')?>" class="w2gm-address-autocomplete w2gm-form-control w2gm-main-search-field" autocomplete="off" />
					</div>
					<?php } ?>
				</div>
			</div>
			<?php if ($search_form->isRadius()): ?>
				<div class="w2gm-jquery-ui-slider">
					<div class="w2gm-search-radius-label">
						<?php _e('Search in radius', 'W2GM'); ?>
						<strong id="radius_label_<?php echo $search_form_id; ?>"><?php echo $search_form->getRadiusValue(); ?></strong>
						<?php if (get_option('w2gm_miles_kilometers_in_search') == 'miles') _e('miles', 'W2GM'); else _e('kilometers', 'W2GM'); ?>
					</div>
					<div class="w2gm-radius-slider" data-id="<?php echo $search_form_id; ?>" id="radius_slider_<?php echo $search_form_id; ?>"></div>
					<input type="hidden" name="radius" id="radius_<?php echo $search_form_id; ?>" value="<?php echo $search_form->getRadiusValue(); ?>" />
				</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<div class="w2gm-map-listings-panel" id="w2gm-map-listings-panel-<?php echo $uid; ?>">
			<?php echo $search_form->listings_content; ?>
		</div>
	</div>
	
	<?php if (!$search_form->isCategoriesOrKeywords()): ?>
	<div class="w2gm-map-sidebar-toggle-container w2gm-map-sidebar-toggle-container-<?php echo $uid; ?>">
		<span class="w2gm-map-sidebar-toggle" data-id="<?php echo $uid; ?>"></span>
	</div>
	<?php endif; ?>
	<div class="w2gm-map-sidebar-toggle-container-mobile w2gm-map-sidebar-toggle-container-mobile-<?php echo $uid; ?>" data-id="<?php echo $uid; ?>" title="<?php esc_attr_e("Search panel", "W2GM"); ?>">
		<span class="w2gm-map-sidebar-toggle"></span>
	</div>
	<input type="submit" name="submit" class="w2gm-submit-button-hidden" tabindex="-1" />
</form>