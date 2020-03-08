<h3>
	<?php echo apply_filters('w2gm_claim_option', sprintf(__('Claim listing "%s"', 'W2GM'), $w2gm_instance->current_listing->title()), $w2gm_instance->current_listing); ?>
</h3>

<!-- <?php if ($frontend_controller->action == 'show'): ?>
<?php if (get_option('w2gm_claim_approval')): ?>
<p><?php _e('The notification about claim for this listing will be sent to the current listing owner.', 'W2GM'); ?></p>
<p><?php esc_attr_e("After approval you will become owner of this listing, you'll receive email notification.", 'W2GM'); ?></p>
<?php endif; ?>
<?php if (get_option('w2gm_after_claim') == 'expired'): ?>
<p><?php echo __('After approval listing status become expired.', 'W2GM') . ((get_option('w2gm_payments_addon')) ? apply_filters('w2gm_renew_option', __(' The price for renewal', 'W2GM'), $w2gm_instance->current_listing) : ''); ?></p>
<?php endif; ?> -->

<?php do_action('w2gm_claim_html', $w2gm_instance->current_listing); ?>

<form method="post" action="<?php echo w2gm_dashboardUrl(array('w2gm_action' => 'claim_listing', 'listing_id' => $w2gm_instance->current_listing->post->ID, 'claim_action' => 'claim')); ?>">
	<input type="hidden" name="referer" value="<?php echo $frontend_controller->referer; ?>" />
	<div class="w2gm-form-group w2gm-row">
		<div class="w2gm-col-md-12">
			<textarea name="claim_message" class="w2gm-form-control" rows="5"></textarea>
			<p class="description"><?php _e('additional information to moderator', 'W2GM'); ?></p>
		</div>
	</div>
	<input type="submit" class="w2gm-btn w2gm-btn-primary" value="<?php esc_attr_e('Send Claim', 'W2GM'); ?>"></input>
	&nbsp;&nbsp;&nbsp;
	<a href="<?php echo $frontend_controller->referer; ?>" class="w2gm-btn w2gm-btn-primary"><?php _e('Cancel', 'W2GM'); ?></a>
</form>
<?php elseif ($frontend_controller->action == 'claim'): ?>
<a href="<?php echo $frontend_controller->referer; ?>" class="w2gm-btn w2gm-btn-primary"><?php _e('Go back ', 'W2GM'); ?></a>
<?php endif; ?>