		<div class="w2gm-edit-listing-info w2gm-col-md-3">
			<div class="w2gm-submit-section w2gm-submit-section-locations">
				<h3 class="w2gm-submit-section-label"><?php _e('Listing info', 'W2GM'); ?></h3>
				<div class="w2gm-submit-section-inside">
					<div class="w2gm-edit-listing-info-label">
						<label><?php _e('Listing status', 'W2GM'); ?>:</label>
						<?php
						if ($listing->status == 'active')
							echo '<span class="w2gm-badge w2gm-listing-status-active">' . __('active', 'W2GM') . '</span>';
						elseif ($listing->status == 'expired')
							echo '<span class="w2gm-badge w2gm-listing-status-expired">' . __('expired', 'W2GM') . '</span>';
						elseif ($listing->status == 'unpaid')
							echo '<span class="w2gm-badge w2gm-listing-status-unpaid">' . __('unpaid', 'W2GM') . '</span>';
						elseif ($listing->status == 'stopped')
							echo '<span class="w2gm-badge w2gm-listing-status-stopped">' . __('stopped', 'W2GM') . '</span>';
						do_action('w2gm_listing_status_option', $listing);
						?>
					</div>
					<?php if ($listing->post->post_status == 'pending' || $listing->post->post_status == 'draft'): ?>
					<div class="w2gm-edit-listing-info-label">
						<label><?php _e('Post status', 'W2GM'); ?>:</label>
						<?php if ($listing->post->post_status == 'pending') echo $listing->getPendingStatus(); ?>
						<?php if ($listing->post->post_status == 'draft') echo  __('Draft or expired', 'W2GM'); ?>
					</div>
					<?php endif; ?>
					<?php if (get_option('w2gm_enable_stats')): ?>
					<div class="w2gm-edit-listing-info-label">
						<label><?php echo sprintf(__('Click stats: %d', 'W2GM'), (get_post_meta($listing->post->ID, '_total_clicks', true) ? get_post_meta($listing->post->ID, '_total_clicks', true) : 0)); ?></label>
					</div>
					<?php endif; ?>
					<div class="w2gm-edit-listing-info-label">
						<label><?php _e('Sorting date', 'W2GM'); ?>:</label>
						<b><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), intval($listing->order_date)); ?></b>
					</div>
					<?php if ($listing->status == 'active' || $listing->status == 'expired'): ?>
					<div class="w2gm-edit-listing-info-label">
						<label><?php _e('Expire on', 'W2GM'); ?>:</label>
						<?php if ($listing->status == 'expired'): 
							$renew_link = strip_tags(apply_filters('w2gm_renew_option', __('renew', 'W2GM'), $listing));
							echo '<a href="' . w2gm_dashboardUrl(array('w2gm_action' => 'renew_listing', 'listing_id' => $listing->post->ID)) . '" title="' . esc_attr($renew_link) . '"><span class="w2gm-glyphicon w2gm-glyphicon-refresh"></span> ' . $renew_link . '</a>';
						elseif ($listing->status == 'active'): 
							if ($listing->level->eternal_active_period): ?>
							<b><?php _e('Eternal active period', 'W2GM'); ?></b>
							<?php else: ?>
							<b><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), intval($listing->expiration_date)); ?></b>
							<?php endif; ?>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<?php if ($listing->claim && $listing->claim->isClaimed()): ?>
					<div class="w2gm-edit-listing-info-label">
						<?php echo '<div>' . $listing->claim->getClaimMessage() . '</div>'; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>