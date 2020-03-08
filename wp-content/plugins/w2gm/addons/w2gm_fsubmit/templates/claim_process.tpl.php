<h3>
	<?php printf(__('Approve or decline claim of listing "%s"', 'W2GM'), $w2gm_instance->current_listing->title()); ?>
</h3>

<?php if ($frontend_controller->action == 'show'): ?>
<p><?php printf(__('User "%s" had claimed this listing.', 'W2GM'), $w2gm_instance->current_listing->claim->claimer->display_name); ?></p>
<?php if ($w2gm_instance->current_listing->claim->claimer_message): ?>
<p><?php _e('Message from claimer:', 'W2GM'); ?><br /><i><?php echo $w2gm_instance->current_listing->claim->claimer_message; ?></i></p>
<?php endif; ?>
<p><?php _e('Claimer will receive email notification.', 'W2GM'); ?></p>

<a href="<?php echo w2gm_dashboardUrl(array('w2gm_action' => 'process_claim', 'listing_id' => $w2gm_instance->current_listing->post->ID, 'claim_action' => 'approve', 'referer' => urlencode($frontend_controller->referer))); ?>" class="w2gm-btn w2gm-btn-primary"><?php _e('Approve', 'W2GM'); ?></a>
&nbsp;&nbsp;&nbsp;
<a href="<?php echo w2gm_dashboardUrl(array('w2gm_action' => 'process_claim', 'listing_id' => $w2gm_instance->current_listing->post->ID, 'claim_action' => 'decline', 'referer' => urlencode($frontend_controller->referer))); ?>" class="w2gm-btn w2gm-btn-primary"><?php _e('Decline', 'W2GM'); ?></a>
&nbsp;&nbsp;&nbsp;
<a href="<?php echo $frontend_controller->referer; ?>" class="w2gm-btn w2gm-btn-primary"><?php _e('Cancel', 'W2GM'); ?></a>
<?php elseif ($frontend_controller->action == 'processed'): ?>
<a href="<?php echo $frontend_controller->referer; ?>" class="w2gm-btn w2gm-btn-primary"><?php _e('Go back ', 'W2GM'); ?></a>
<?php endif; ?>