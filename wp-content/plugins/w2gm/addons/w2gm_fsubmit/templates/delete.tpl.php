<h3>
	<?php echo sprintf(__('Delete listing "%s"', 'W2GM'), $w2gm_instance->current_listing->title()); ?>
</h3>

<p><?php _e('Listing will be completely deleted with all metadata, comments and attachments.', 'W2GM'); ?></p>

<a href="<?php echo w2gm_dashboardUrl(array('w2gm_action' => 'delete_listing', 'listing_id' => $w2gm_instance->current_listing->post->ID, 'delete_action' => 'delete', 'referer' => urlencode($frontend_controller->referer))); ?>" class="w2gm-btn w2gm-btn-primary"><?php _e('Delete listing', 'W2GM'); ?></a>
&nbsp;&nbsp;&nbsp;
<a href="<?php echo $frontend_controller->referer; ?>" class="w2gm-btn w2gm-btn-primary"><?php _e('Cancel', 'W2GM'); ?></a>