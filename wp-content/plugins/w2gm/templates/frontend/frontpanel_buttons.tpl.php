<div class="w2gm-content w2gm-listing-frontpanel">
	<?php do_action('w2gm_listing_frontpanel', $frontpanel_buttons); ?>
	<?php if ($frontpanel_buttons->isEditButton()): ?>
	<a class="w2gm-edit-listing-link w2gm-btn w2gm-btn-primary" href="<?php echo w2gm_get_edit_listing_link($frontpanel_buttons->getListingId()); ?>" rel="nofollow" <?php $frontpanel_buttons->tooltipMeta(__('Edit listing', 'W2GM')); ?>><span class="w2gm-glyphicon w2gm-glyphicon-pencil"></span><?php if (!$frontpanel_buttons->hide_button_text): ?> <?php _e('Edit listing', 'W2GM'); ?><?php endif; ?></a>
	<?php endif; ?>
	<?php do_action('w2gm_listing_frontpanel_after', $frontpanel_buttons); ?>
</div>