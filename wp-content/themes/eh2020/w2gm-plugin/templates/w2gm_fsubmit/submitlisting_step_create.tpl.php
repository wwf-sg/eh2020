<?php $listing = $w2gm_instance->current_listing; ?>
<div class="w2gm-content">
	<?php w2gm_renderMessages(); ?>

	<h3 style="color:white;"><?php echo apply_filters('w2gm_create_option', __('', 'W2GM')); ?></h3>

	<div class="w2gm-create-listing-wrapper w2gm-row">
		<div class="w2gm-create-listing-form w2gm-col-md-12">
			<form action="<?php echo w2gm_submitUrl(); ?>" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="listing_id" value="<?php echo $listing->post->ID; ?>" />
				<input type="hidden" name="listing_id_hash" value="<?php echo md5($listing->post->ID . wp_salt()); ?>" />
				<?php wp_nonce_field('w2gm_submit', '_submit_nonce'); ?>
		
			
				<div class="w2gm-submit-section w2gm-submit-section-title">
					<h3 class="w2gm-submit-section-label"><?php _e('Listing title', 'W2GM'); ?><span class="w2gm-red-asterisk">*</span></h3>
					<div class="w2gm-submit-section-inside">
						<input type="text" name="post_title" style="width: 100%" class="w2gm-form-control" value="<?php if ($listing->post->post_title != __('Auto Draft', 'W2GM')) echo esc_attr($listing->post->post_title); ?>" />
					</div>
				</div>
					<?php if (!is_user_logged_in() && (get_option('w2gm_fsubmit_login_mode') == 2 || get_option('w2gm_fsubmit_login_mode') == 3)): ?>
				<div class="w2gm-submit-section w2gm-submit-section-contact-info">
					<h3 class="w2gm-submit-section-label"><?php _e('User Information', 'W2GM'); ?></h3>
					<div class="w2gm-submit-section-inside">
						<label class="w2gm-fsubmit-contact"><?php _e('Your Name', 'W2GM'); ?><?php if (get_option('w2gm_fsubmit_login_mode') == 2): ?><span class="w2gm-red-asterisk">*</span><?php endif; ?></label>
						<input id="wwf-c-name" type="text" name="w2gm_user_contact_name" value="<?php echo esc_attr($frontend_controller->w2gm_user_contact_name); ?>" class="w2gm-form-control" style="width: 100%;" />
		
						<label class="w2gm-fsubmit-contact"><?php _e('Email Address', 'W2GM'); ?><?php if (get_option('w2gm_fsubmit_login_mode') == 2): ?><span class="w2gm-red-asterisk">*</span><?php endif; ?></label>
						<input id="wwf-c-mail" type="text" name="w2gm_user_contact_email" value="<?php echo esc_attr($frontend_controller->w2gm_user_contact_email); ?>" class="w2gm-form-control" style="width: 100%;" />
					</div>
				</div>
				<?php endif; ?>
		
						<?php if ($listing->level->categories_number > 0 || $listing->level->unlimited_categories): ?>
				<div class="w2gm-submit-section w2gm-submit-section-categories">
					<h3 class="w2gm-submit-section-label"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('categories_list')->name; ?><?php if ($w2gm_instance->content_fields->getContentFieldBySlug('categories_list')->is_required): ?><span class="w2gm-red-asterisk">*</span><?php endif; ?></h3>
					<div class="w2gm-submit-section-inside">
						<div class="w2gm-categories-tree-panel w2gm-editor-class" id="<?php echo W2GM_CATEGORIES_TAX; ?>-all">
							<?php w2gm_terms_checklist($listing->post->ID); ?>
						</div>
						<?php if ($w2gm_instance->content_fields->getContentFieldBySlug('categories_list')->description): ?><p class="w2gm-description"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('categories_list')->description; ?></p><?php endif; ?>
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
				
			
		
				
				
			
				<?php if ($w2gm_instance->content_fields->getNotCoreContentFields()): ?>
				<?php $w2gm_instance->content_fields->renderInputByGroups($listing->post); ?>
				<?php endif; ?>
			
				<?php if ($listing->level->images_number > 0 || $listing->level->videos_number > 0): ?>
				<div class="w2gm-submit-section w2gm-submit-section-media">
					<h3 class="w2gm-submit-section-label"><?php _e('Upload Company Logo', 'W2GM'); ?></h3>
					<div style="padding-left:10px;">Note* Image Size - limit of 1MB and below</div>
					<div class="w2gm-submit-section-inside">
						<?php $w2gm_instance->media_manager->mediaMetabox($listing->post, array('args' => array('target' => 'listings'))); ?>
					</div>
					
				</div>
				<?php endif; ?>
				
				<?php do_action('w2gm_create_listing_metaboxes_post', $listing); ?>
		
				<?php if (get_option('w2gm_enable_recaptcha')): ?>
				<div class="w2gm-submit-section-adv">
					<?php echo w2gm_recaptcha(); ?>
				</div>
				<?php endif; ?>
		
				<?php
				if ($tos_page = w2gm_get_wpml_dependent_option('w2gm_tospage')) : ?>
				<div class="w2gm-submit-section-adv">
					<label><input type="checkbox" name="w2gm_tospage" value="1" /> <?php printf(__('I agree to the ', 'W2GM') . '<a href="%s" target="_blank">%s</a>', get_permalink($tos_page), __('Terms of Services', 'W2GM')); ?></label>
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
		
				
				<?php do_action('w2gm_create_listing_metaboxes_pre', $listing); ?>
		
				<?php if (!$listing->level->eternal_active_period && (get_option('w2gm_change_expiration_date') || current_user_can('manage_options'))): ?>
				<div class="w2gm-submit-section w2gm-submit-section-expiration-date">
					<h3 class="w2gm-submit-section-label"><?php _e('Listing expiration date', 'W2GM'); ?></h3>
					<div class="w2gm-submit-section-inside">
						<?php $w2gm_instance->listings_manager->listingExpirationDateMetabox($listing->post); ?>
					</div>
				</div>
				<?php endif; ?>
		
						<?php if ($listing->level->locations_number > 0): ?>
				<div class="w2gm-submit-section w2gm-submit-section-locations">
					<h3 class="w2gm-submit-section-label"><?php _e('Listing locations', 'W2GM'); ?><?php if ($w2gm_instance->content_fields->getContentFieldBySlug('address')->is_required): ?><span class="w2gm-red-asterisk">*</span><?php endif; ?></h3>
					<div class="w2gm-submit-section-inside">
						<?php if ($w2gm_instance->content_fields->getContentFieldBySlug('address')->description): ?><p class="w2gm-description"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('address')->description; ?></p><?php endif; ?>
						<?php $w2gm_instance->locations_manager->listingLocationsMetabox($listing->post); ?>
					</div>
				</div>
				<?php endif; ?>
		
		
				<?php require_once(ABSPATH . 'wp-admin/includes/template.php'); ?>
				<?php submit_button(__('Submit', 'W2GM'), 'w2gm-btn w2gm-btn-primary')?>
			</form>
		</div>
	</div>
</div>