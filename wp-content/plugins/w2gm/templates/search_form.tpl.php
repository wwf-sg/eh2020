<?php $search_form->getSearchFormStyles(); ?>
<form class="w2gm-content w2gm-search-form w2gm-search-form-submit <?php if ($search_form->args['sticky_scroll']):?>w2gm-sticky-scroll<?php endif; ?>" id="w2gm-search-form-<?php echo $search_form_id; ?>" data-id="<?php echo $search_form_id; ?>" <?php if ($search_form->args['sticky_scroll_toppadding']):?>data-toppadding="<?php echo $search_form->args['sticky_scroll_toppadding']; ?>"<?php endif; ?> <?php if ($search_form->args['scroll_to']):?>data-scroll-to="<?php echo $search_form->args['scroll_to'];?>"<?php endif; ?>>
	<?php $search_form->outputHiddenFields(); ?>

	<div class="w2gm-search-overlay w2gm-container-fluid">
		<?php if ($search_form->isDefaultSearchFields()): ?>
		<div class="w2gm-search-section w2gm-row">
			<?php if ($search_form->isCategoriesOrKeywords()): ?>
			<?php do_action('pre_search_what_form_html', $search_form_id); ?>
			<div class="w2gm-col-md-<?php echo $search_form->getColMd(); ?> w2gm-search-input-field-wrap">
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
						<?php if ($search_form->isKeywordsExamples()): ?>
						<p class="w2gm-search-suggestions">
							<?php printf(__("Try to search: %s", "W2GM"), $search_form->getKeywordsExamples()); ?>
						</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php do_action('post_search_what_form_html', $search_form_id); ?>
			<?php endif; ?>
			
			<?php if ($search_form->isLocationsOrAddress()): ?>
			<?php do_action('pre_search_where_form_html', $search_form_id); ?>
			<div class="w2gm-col-md-<?php echo $search_form->getColMd(); ?> w2gm-search-input-field-wrap">
				<div class="w2gm-row w2gm-form-group">
					<div class="w2gm-col-md-12">
						<?php
						if ($search_form->isLocations()) {
							w2gm_tax_dropdowns_menu_init($search_form->getLocationsDropdownsMenuParams(__('Select location', 'W2GM'), __('Select location or enter address', 'W2GM')));
						} else { ?>
						<div class="w2gm-has-feedback">
							<input name="address" value="<?php echo esc_attr($search_form->getAddressValue()); ?>" placeholder="<?php esc_attr_e('Enter address', 'W2GM')?>" class="w2gm-address-autocomplete w2gm-form-control w2gm-main-search-field" autocomplete="off" />
							<span class="w2gm-dropdowns-menu-button w2gm-form-control-feedback w2gm-glyphicon w2gm-glyphicon-map-marker"></span>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php do_action('post_search_where_form_html', $search_form_id); ?>
			<?php endif; ?>
			
			<?php if ($search_form->args['on_row_search_button']): ?>
			<div class="w2gm-col-md-2 w2gm-search-submit-button-wrap">
				<?php $search_form->displaySearchButton(true); ?>
			</div>
			<?php endif; ?>
			
			<?php if ($search_form->isRadius()): ?>
			<div class="w2gm-col-md-12">
				<div class="w2gm-jquery-ui-slider">
					<div class="w2gm-search-radius-label">
						<?php _e('Search in radius', 'W2GM'); ?>
						<strong id="radius_label_<?php echo $search_form_id; ?>"><?php echo $search_form->getRadiusValue(); ?></strong>
						<?php if (get_option('w2gm_miles_kilometers_in_search') == 'miles') _e('miles', 'W2GM'); else _e('kilometers', 'W2GM'); ?>
					</div>
					<div class="w2gm-radius-slider" data-id="<?php echo $search_form_id; ?>" id="radius_slider_<?php echo $search_form_id; ?>"></div>
					<input type="hidden" name="radius" id="radius_<?php echo $search_form_id; ?>" value="<?php echo $search_form->getRadiusValue(); ?>" />
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php $w2gm_instance->search_fields->render_content_fields($search_form_id, $search_form->args['columns'], $search_form); ?>

		<?php do_action('post_search_form_html', $search_form_id); ?>

		<div class="w2gm-search-section w2gm-search-form-bottom w2gm-row w2gm-clearfix">
			<?php if (!$search_form->args['on_row_search_button']): ?>
			<?php $search_form->displaySearchButton(); ?>
			<?php endif; ?>

			<?php if ($search_form->is_advanced_search_panel): ?>
			<script>
				(function($) {
					"use strict";

					$(function() {
						w2gm_advancedSearch(<?php echo $search_form_id; ?>, "<?php _e('More filters', 'W2GM'); ?>", "<?php _e('Less filters', 'W2GM'); ?>");
					});
				})(jQuery);
			</script>
			<div class="w2gm-col-md-6 w2gm-form-group w2gm-pull-left">
				<a id="w2gm-advanced-search-label_<?php echo $search_form_id; ?>" class="w2gm-advanced-search-label" href="javascript: void(0);"><span class="w2gm-advanced-search-text"><?php _e('More filters', 'W2GM'); ?></span> <span class="w2gm-advanced-search-toggle w2gm-glyphicon w2gm-glyphicon-chevron-down"></span></a>
			</div>
			<?php endif; ?>

			<?php do_action('buttons_search_form_html', $search_form_id); ?>
		</div>
	</div>
</form>