	<?php if ($frontend_controller->listings): ?>
		<table class="w2gm-table w2gm-dashboard-listings w2gm-table-striped">
			<tr>
				<th class="w2gm-td-listing-id"><?php _e('ID', 'W2GM'); ?></th>
				<th class="w2gm-td-listing-title"><?php _e('Title', 'W2GM'); ?></th>
				<?php 
				// adapted for WPML
				global $sitepress;
				if (function_exists('wpml_object_id_filter') && $sitepress && get_option('w2gm_enable_frontend_translations') && ($languages = $sitepress->get_active_languages()) && count($languages) > 1): ?>
				<th class="w2gm-td-listing-translations">
					<?php foreach ($languages AS $lang_code=>$lang): ?>
					<?php if ($lang_code != ICL_LANGUAGE_CODE && apply_filters('wpml_object_id', $w2gm_instance->dashboard_page_id, 'page', false, $lang_code)): ?>
					<img src="<?php echo $sitepress->get_flag_url($lang_code); ?>" title="<?php esc_attr_e($lang['native_name']); ?>" />&nbsp;&nbsp;
					<?php endif; ?>
					<?php endforeach; ?>
				</th>
				<?php endif; ?>
				<th class="w2gm-td-listing-status"><?php _e('Status', 'W2GM'); ?></th>
				<th class="w2gm-td-listing-expiration-date"><?php _e('Expiration date', 'W2GM'); ?></th>
				<th class="w2gm-td-listing-options"></th>
			</tr>
		<?php while ($frontend_controller->query->have_posts()): ?>
			<?php $frontend_controller->query->the_post(); ?>
			<?php $listing = $frontend_controller->listings[get_the_ID()]; ?>
			<tr>
				<td class="w2gm-td-listing-id"><?php echo $listing->post->ID; ?></td>
				<td class="w2gm-td-listing-title">
					<?php
					if (w2gm_current_user_can_edit_listing($listing->post->ID))
						echo '<a href="' . w2gm_get_edit_listing_link($listing->post->ID) . '">' . $listing->title() . '</a>';
					else
						echo $listing->title();
					do_action('w2gm_dashboard_listing_title', $listing);
					?>
					<?php if ($listing->post->post_status == 'pending') echo ' - ' . $listing->getPendingStatus(); ?>
					<?php if ($listing->post->post_status == 'draft') echo ' - ' . __('Draft or expired', 'W2GM'); ?>
					<?php if ($listing->claim && $listing->claim->isClaimed()) echo '<div>' . $listing->claim->getClaimMessage() . '</div>'; ?>
				</td>
				<?php 
				// adapted for WPML
				global $sitepress;
				if (function_exists('wpml_object_id_filter') && $sitepress && get_option('w2gm_enable_frontend_translations') && ($languages = $sitepress->get_active_languages()) && count($languages) > 1): ?>
				<td class="w2gm-td-listing-translations">
				<?php if (w2gm_current_user_can_edit_listing($listing->post->ID)):
					global $sitepress;
					$trid = $sitepress->get_element_trid($listing->post->ID, 'post_' . W2GM_POST_TYPE);
					$translations = $sitepress->get_element_translations($trid); ?>
					<?php foreach ($languages AS $lang_code=>$lang): ?>
					<?php if ($lang_code != ICL_LANGUAGE_CODE && apply_filters('wpml_object_id', $w2gm_instance->dashboard_page_id, 'page', false, $lang_code)): ?>
					<?php $lang_details = $sitepress->get_language_details($lang_code); ?>
					<?php do_action('wpml_switch_language', $lang_code); ?>
					<?php if (isset($translations[$lang_code])): ?>
					<a style="text-decoration:none" title="<?php echo sprintf(__('Edit the %s translation', 'sitepress'), $lang_details['display_name']); ?>" href="<?php echo add_query_arg(array('w2gm_action' => 'edit_listing', 'listing_id' => apply_filters('wpml_object_id', $listing->post->ID, W2GM_POST_TYPE, true, $lang_code)), get_permalink(apply_filters('wpml_object_id', $w2gm_instance->dashboard_page_id, 'page', true, $lang_code))); ?>">
						<img src="<?php echo ICL_PLUGIN_URL; ?>/res/img/edit_translation.png" alt="<?php esc_attr_e(__('edit translation', 'W2GM')); ?>" />
					</a>&nbsp;&nbsp;
					<?php else: ?>
					<a style="text-decoration:none" title="<?php echo sprintf(__('Add translation to %s', 'sitepress'), $lang_details['display_name']); ?>" href="<?php echo w2gm_dashboardUrl(array('w2gm_action' => 'add_translation', 'listing_id' => $listing->post->ID, 'to_lang' => $lang_code)); ?>">
						<img src="<?php echo ICL_PLUGIN_URL; ?>/res/img/add_translation.png" alt="<?php esc_attr_e(__('add translation', 'W2GM')); ?>" />
					</a>&nbsp;&nbsp;
					<?php endif; ?>
					<?php endif; ?>
					<?php endforeach; ?>
					<?php do_action('wpml_switch_language', ICL_LANGUAGE_CODE); ?>
				<?php endif; ?>
				</td>
				<?php endif; ?>
				<td class="w2gm-td-listing-status">
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
				</td>
				<td class="w2gm-td-listing-expiration-date">
					<?php
					if ($listing->level->eternal_active_period)
						_e('Eternal active period', 'W2GM');
					else
						echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), intval($listing->expiration_date));
					
					if ($listing->expiration_date > time())
						echo '<br />' . human_time_diff(time(), $listing->expiration_date) . '&nbsp;' . __('left', 'W2GM');
					?>
				</td>
				<td class="w2gm-td-listing-options">
					<?php if (w2gm_current_user_can_edit_listing($listing->post->ID)): ?>
					<div class="w2gm-btn-group">
						<a href="<?php echo w2gm_get_edit_listing_link($listing->post->ID); ?>" class="w2gm-btn w2gm-btn-primary w2gm-btn-sm w2gm-dashboard-edit-btn" title="<?php esc_attr_e('edit listing', 'W2GM'); ?>"><span class="w2gm-glyphicon w2gm-glyphicon-edit"></span></a>
						<a href="<?php echo w2gm_get_edit_listing_link($listing->post->ID); ?>" class="w2gm-dashboard-btn-mobile"><?php _e('edit', 'W2GM'); ?></a>
						<a href="<?php echo w2gm_dashboardUrl(array('w2gm_action' => 'delete_listing', 'listing_id' => $listing->post->ID)); ?>" class="w2gm-btn w2gm-btn-primary w2gm-btn-sm w2gm-dashboard-delete-btn" title="<?php esc_attr_e('delete listing', 'W2GM'); ?>"><span class="w2gm-glyphicon w2gm-glyphicon-trash"></span></a>
						<a href="<?php echo w2gm_dashboardUrl(array('w2gm_action' => 'delete_listing', 'listing_id' => $listing->post->ID)); ?>" class="w2gm-dashboard-btn-mobile"><?php _e('delete', 'W2GM'); ?></a>
						<?php
						if ($listing->status == 'expired') {
							$renew_link = strip_tags(apply_filters('w2gm_renew_option', __('renew', 'W2GM'), $listing));
							echo '<a href="' . w2gm_dashboardUrl(array('w2gm_action' => 'renew_listing', 'listing_id' => $listing->post->ID)) . '" class="w2gm-btn w2gm-btn-primary w2gm-btn-sm w2gm-dashboard-renew-btn" title="' . esc_attr($renew_link) . '"><span class="w2gm-glyphicon w2gm-glyphicon-refresh"></span></a>';
							echo '<a href="' . w2gm_dashboardUrl(array('w2gm_action' => 'renew_listing', 'listing_id' => $listing->post->ID)) . '" class="w2gm-dashboard-btn-mobile">' . $renew_link . '</a>';
						}?>
						<?php
						if (get_option('w2gm_enable_stats')) {
							echo '<a href="' . w2gm_dashboardUrl(array('w2gm_action' => 'view_stats', 'listing_id' => $listing->post->ID)) . '" class="w2gm-btn w2gm-btn-primary w2gm-btn-sm w2gm-dashboard-stats-btn" title="' . esc_attr__('view clicks stats', 'W2GM') . '"><span class="w2gm-glyphicon w2gm-glyphicon-signal"></span></a>';
							echo '<a href="' . w2gm_dashboardUrl(array('w2gm_action' => 'view_stats', 'listing_id' => $listing->post->ID)) . '" class="w2gm-dashboard-btn-mobile">' . __('stats', 'W2GM') . '</a>';
						}?>
						<?php
						if ($listing->status == 'active' && $listing->post->post_status == 'publish' && ($permalink = get_permalink($listing->post->ID))) {
							echo '<a href="' . $permalink . '" class="w2gm-btn w2gm-btn-primary w2gm-btn-sm w2gm-dashboard-view-btn" title="' . esc_attr__('view listing', 'W2GM') . '"><span class="w2gm-glyphicon w2gm-glyphicon-link"></span></a>';
							echo '<a href="' . $permalink . '" class="w2gm-dashboard-btn-mobile">' . __('view', 'W2GM') . '</a>';
						}?>
						<?php do_action('w2gm_dashboard_listing_options', $listing); ?>
					</div>
					<?php endif; ?>
				</td>
			</tr>
		<?php endwhile; ?>
		</table>
		<?php w2gm_renderPaginator($frontend_controller->query); ?>
		<?php endif; ?>