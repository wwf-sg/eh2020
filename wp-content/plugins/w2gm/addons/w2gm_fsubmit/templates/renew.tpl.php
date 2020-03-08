<h3>
	<?php echo apply_filters('w2gm_renew_option', sprintf(__('Renew listing "%s"', 'W2GM'), $w2gm_instance->current_listing->title()), $w2gm_instance->current_listing); ?>
</h3>

<p><?php _e('Listing will be renewed and raised up.', 'W2GM'); ?></p>

<?php do_action('w2gm_renew_html', $w2gm_instance->current_listing); ?>

<?php if ($frontend_controller->action == 'show'): ?>
<a href="<?php echo w2gm_dashboardUrl(array('w2gm_action' => 'renew_listing', 'listing_id' => $w2gm_instance->current_listing->post->ID, 'renew_action' => 'renew', 'referer' => urlencode($frontend_controller->referer))); ?>" class="w2gm-btn w2gm-btn-primary"><?php _e('Renew listing', 'W2GM'); ?></a>
&nbsp;&nbsp;&nbsp;
<a href="<?php echo $frontend_controller->referer; ?>" class="w2gm-btn w2gm-btn-primary"><?php _e('Cancel', 'W2GM'); ?></a>
<?php elseif ($frontend_controller->action == 'renew'): ?>
<a href="<?php echo $frontend_controller->referer; ?>" class="w2gm-btn w2gm-btn-primary"><?php _e('Go back ', 'W2GM'); ?></a>
<?php endif; ?>