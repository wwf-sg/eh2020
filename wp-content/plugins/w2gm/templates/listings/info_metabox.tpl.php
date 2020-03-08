<div id="misc-publishing-actions">
	<?php if ($listing->listing_created): ?>
	<div class="misc-pub-section">
		<label for="post_level"><?php _e('Listing status', 'W2GM'); ?>:</label>
		<span id="post-level-display">
			<?php if ($listing->status == 'active'): ?>
			<span class="w2gm-badge w2gm-listing-status-active"><?php _e('active', 'W2GM'); ?></span>
			<?php elseif ($listing->status == 'expired' && get_option('w2gm_enable_renew')): ?>
			<span class="w2gm-badge w2gm-listing-status-expired"><?php _e('expired', 'W2GM'); ?></span><br />
			<a href="<?php echo admin_url('options.php?page=w2gm_renew&listing_id=' . $listing->post->ID); ?>"><span class="w2gm-fa w2gm-fa-refresh w2gm-fa-lg"></span> <?php echo apply_filters('w2gm_renew_option', __('renew listing', 'W2GM'), $listing); ?></a>
			<?php elseif ($listing->status == 'unpaid'): ?>
			<span class="w2gm-badge w2gm-listing-status-unpaid"><?php _e('unpaid ', 'W2GM'); ?></span>
			<?php elseif ($listing->status == 'stopped'): ?>
			<span class="w2gm-badge w2gm-listing-status-stopped"><?php _e('stopped', 'W2GM'); ?></span>
			<?php endif;?>
			<?php do_action('w2gm_listing_status_option', $listing); ?>
		</span>
		<?php if (get_post_meta($listing->post->ID, '_preexpiration_notification_sent', true)): ?><br /><?php _e('Pre-expiration notification was sent', 'W2GM'); ?><?php endif; ?>
	</div>
	
	<?php
	$post_type_object = get_post_type_object(W2GM_POST_TYPE);
	$can_publish = current_user_can($post_type_object->cap->publish_posts);
	?>
	<?php if ($can_publish && $listing->status != 'active'): ?>
	<div class="misc-pub-section">
		<input name="w2gm_save_as_active" value="Save as Active" class="button" type="submit">
	</div>
	<?php endif; ?>

	<?php if (get_option('w2gm_enable_stats')): ?>
	<div class="misc-pub-section">
		<label for="post_level"><?php echo sprintf(__('Total clicks: %d', 'W2GM'), (get_post_meta($w2gm_instance->current_listing->post->ID, '_total_clicks', true) ? get_post_meta($w2gm_instance->current_listing->post->ID, '_total_clicks', true) : 0)); ?></label>
	</div>
	<?php endif; ?>

	<div class="misc-pub-section curtime">
		<span id="timestamp">
			<?php _e('Sorting date', 'W2GM'); ?>:
			<b><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), intval($listing->order_date)); ?></b>
		</span>
	</div>

	<?php if ($listing->level->eternal_active_period || $listing->expiration_date): ?>
	<div class="misc-pub-section curtime">
		<span id="timestamp">
			<?php _e('Expire on', 'W2GM'); ?>:
			<?php if ($listing->level->eternal_active_period): ?>
			<b><?php _e('Eternal active period', 'W2GM'); ?></b>
			<?php else: ?>
			<b><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), intval($listing->expiration_date)); ?></b>
			<?php endif; ?>
		</span>
	</div>
	<?php endif; ?>
	
	<?php do_action('w2gm_listing_info_metabox_html', $listing); ?>

	<?php endif; ?>
</div>