<?php $listing = $w2gm_instance->current_listing; ?>
<div class="w2gm-content">
	<?php w2gm_renderMessages(); ?>

	<h2><?php echo sprintf(__('Edit listing "%s"', 'W2GM'), $listing->title()); ?></h2>

	<div class="w2gm-edit-listing-wrapper w2gm-row">
		<?php if ($listing_info): ?>
		<?php w2gm_renderTemplate(array(W2GM_FSUBMIT_TEMPLATES_PATH, 'info_metabox.tpl.php'), array('listing' => $listing)); ?>
		<?php endif; ?>
		<div class="w2gm-edit-listing-form w2gm-col-md-<?php echo ($listing_info) ? 9: 12; ?>">
			<form action="" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="referer" value="<?php echo $frontend_controller->referer; ?>" />
				<?php wp_nonce_field('w2gm_submit', '_submit_nonce'); ?>
		
				<div class="w2gm-submit-section w2gm-submit-section-title">
					<h3 class="w2gm-submit-section-label"><?php _e('Listing title', 'W2GM'); ?><span class="w2gm-red-asterisk">*</span></h3>
					<div class="w2gm-submit-section-inside">
						<input type="text" name="post_title" style="width: 100%" class="w2gm-form-control" value="<?php if ($listing->post->post_title != __('Auto Draft')) echo esc_attr($listing->post->post_title); ?>" />
					</div>
				</div>
				
				<?php if ($listing->level->locations_number > 0): ?>
				<div class="w2gm-submit-section w2gm-submit-section-locations">
					<h3 class="w2gm-submit-section-label"><?php _e('Listing locations', 'W2GM'); ?><?php if ($w2gm_instance->content_fields->getContentFieldBySlug('address')->is_required): ?><span class="w2gm-red-asterisk">*</span><?php endif; ?></h3>
					<div class="w2gm-submit-section-inside">
						<?php if ($w2gm_instance->content_fields->getContentFieldBySlug('address')->description): ?><p class="w2gm-description"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('address')->description; ?></p><?php endif; ?>
						<?php $w2gm_instance->locations_manager->listingLocationsMetabox($listing->post); ?>
					</div>
				</div>
				<?php endif; ?>
		
				<?php if (post_type_supports(W2GM_POST_TYPE, 'editor')): ?>
				<div class="w2gm-submit-section w2gm-submit-section-description">
					<h3 class="w2gm-submit-section-label"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('content')->name; ?><?php if ($w2gm_instance->content_fields->getContentFieldBySlug('content')->is_required): ?><span class="w2gm-red-asterisk">*</span><?php endif; ?></h3>
					<div class="w2gm-submit-section-inside">
						<?php wp_editor($listing->post->post_content, 'post_content', array('media_buttons' => false, 'editor_class' => 'w2gm-editor-class')); ?>
						<?php if ($w2gm_instance->content_fields->getContentFieldBySlug('content')->description): ?><p class="w2gm-description"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('content')->description; ?></p><?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
		
				<?php if (post_type_supports(W2GM_POST_TYPE, 'excerpt')): ?>
				<div class="w2gm-submit-section w2gm-submit-section- excerpt">
					<h3 class="w2gm-submit-section-label"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('summary')->name; ?><?php if ($w2gm_instance->content_fields->getContentFieldBySlug('summary')->is_required): ?><span class="w2gm-red-asterisk">*</span><?php endif; ?></h3>
					<div class="w2gm-submit-section-inside">
						<textarea name="post_excerpt" class="w2gm-form-control" rows="4"><?php echo esc_textarea($listing->post->post_excerpt)?></textarea>
						<?php if ($w2gm_instance->content_fields->getContentFieldBySlug('summary')->description): ?><p class="w2gm-description"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('summary')->description; ?></p><?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
				
				<?php do_action('w2gm_edit_listing_metaboxes_pre', $listing); ?>
		
				<?php if (!$listing->level->eternal_active_period && (get_option('w2gm_change_expiration_date') || current_user_can('manage_options'))): ?>
				<div class="w2gm-submit-section w2gm-submit-section-expiration-date">
					<h3 class="w2gm-submit-section-label"><?php _e('Listing expiration date', 'W2GM'); ?></h3>
					<div class="w2gm-submit-section-inside">
						<?php $w2gm_instance->listings_manager->listingExpirationDateMetabox($listing->post); ?>
					</div>
				</div>
				<?php endif; ?>
				
				<?php if (get_option('w2gm_listing_contact_form') && get_option('w2gm_custom_contact_email')): ?>
				<div class="w2gm-submit-section w2gm-submit-section-contact-email">
					<h3 class="w2gm-submit-section-label"><?php _e('Contact email', 'W2GM'); ?></h3>
					<div class="w2gm-submit-section-inside">
						<?php $w2gm_instance->listings_manager->listingContactEmailMetabox($listing->post); ?>
					</div>
				</div>
				<?php endif; ?>
		
				<?php if (get_option('w2gm_claim_functionality') && !get_option('w2gm_hide_claim_metabox')): ?>
				<div class="w2gm-submit-section w2gm-submit-section-claim">
					<h3 class="w2gm-submit-section-label"><?php _e('Listing claim', 'W2GM'); ?></h3>
					<div class="w2gm-submit-section-inside">
						<?php $w2gm_instance->listings_manager->listingClaimMetabox($listing->post); ?>
					</div>
				</div>
				<?php endif; ?>
			
				<?php if ($listing->level->categories_number > 0 || $listing->level->unlimited_categories): ?>
				<div class="w2gm-submit-section w2gm-submit-section-categories">
					<h3 class="w2gm-submit-section-label"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('categories_list')->name; ?><?php if ($w2gm_instance->content_fields->getContentFieldBySlug('categories_list')->is_required): ?><span class="w2gm-red-asterisk">*</span><?php endif; ?></h3>
					<div class="w2gm-submit-section-inside">
						<a href="javascript:void(0);" class="w2gm-expand-terms"><?php _e('Expand All', 'W2GM'); ?></a> | <a href="javascript:void(0);" class="w2gm-collapse-terms"><?php _e('Collapse All', 'W2GM'); ?></a>
						<div class="w2gm-categories-tree-panel w2gm-editor-class" id="<?php echo W2GM_CATEGORIES_TAX; ?>-all">
							<?php w2gm_terms_checklist($listing->post->ID); ?>
							<?php if ($w2gm_instance->content_fields->getContentFieldBySlug('categories_list')->description): ?><p class="w2gm-description"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('categories_list')->description; ?></p><?php endif; ?>
						</div>
					</div>
				</div>
				<?php endif; ?>
		
				<?php if (get_option('w2gm_enable_tags')): ?>
				<div class="w2gm-submit-section w2gm-submit-section-tags">
					<h3 class="w2gm-submit-section-label"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('listing_tags')->name; ?> <i>(<?php _e('select existing or type new', 'W2GM'); ?>)</i></h3>
					<div class="w2gm-submit-section-inside">
						<?php w2gm_tags_selectbox($listing->post->ID); ?>
						<?php if ($w2gm_instance->content_fields->getContentFieldBySlug('listing_tags')->description): ?><p class="w2gm-description"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('listing_tags')->description; ?></p><?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
			
				<?php if ($w2gm_instance->content_fields->getNotCoreContentFields()): ?>
				<?php $w2gm_instance->content_fields->renderInputByGroups($listing->post); ?>
				<?php endif; ?>
			
				<?php if ($listing->level->images_number > 0 || $listing->level->videos_number > 0): ?>
				<div class="w2gm-submit-section w2gm-submit-section-media">
					<h3 class="w2gm-submit-section-label"><?php _e('Listing Media', 'W2GM'); ?></h3>
					<div class="w2gm-submit-section-inside">
						<?php $w2gm_instance->media_manager->mediaMetabox($listing->post, array('args' => array('target' => 'listings'))); ?>
					</div>
				</div>
				<?php endif; ?>
				
				<?php do_action('w2gm_edit_listing_metaboxes_post', $listing); ?>
		
				<?php require_once(ABSPATH . 'wp-admin/includes/template.php'); ?>
				<?php submit_button(__('Save changes', 'W2GM'), 'w2gm-btn w2gm-btn-primary', 'submit', false); ?>
				&nbsp;&nbsp;&nbsp;
				<?php submit_button(__('Cancel', 'W2GM'), 'w2gm-btn w2gm-btn-primary', 'cancel', false); ?>
			</form>
		</div>
	</div>
</div>