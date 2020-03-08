		<div class="w2gm-content w2gm-listing-single">
			<?php w2gm_renderMessages(); ?>

			<?php if ($frontend_controller->listings): ?>
			<?php while ($frontend_controller->query->have_posts()): ?>
				<?php $frontend_controller->query->the_post(); ?>
				<?php $listing = $frontend_controller->listings[get_the_ID()]; ?>

				<div id="<?php echo $listing->post->post_name; ?>" itemscope itemtype="http://schema.org/LocalBusiness">
					<?php $hide_button_text = apply_filters('w2gm_hide_button_text_on_listing', true)?>
					<?php $frontpanel_buttons = new w2gm_frontpanel_buttons(array('listing_id' => $listing->post->ID, 'hide_button_text' => $hide_button_text)); ?>
					<?php $frontpanel_buttons->display(); ?>
				
					<?php if ($listing->title()): ?>
					<header class="w2gm-listing-header">
						<?php do_action('w2gm_listing_title_html', $listing, true); ?>
						<?php if (!get_option('w2gm_hide_views_counter')): ?>
						<div class="w2gm-meta-data">
							<div class="w2gm-views-counter">
								<span class="w2gm-glyphicon w2gm-glyphicon-eye-open"></span> <?php _e('views', 'W2GM')?>: <?php echo get_post_meta($listing->post->ID, '_total_clicks', true); ?>
							</div>
						</div>
						<?php endif; ?>
						<?php if (!get_option('w2gm_hide_listings_creation_date')): ?>
						<div class="w2gm-meta-data">
							<div class="w2gm-listing-date" datetime="<?php echo date("Y-m-d", mysql2date('U', $listing->post->post_date)); ?>T<?php echo date("H:i", mysql2date('U', $listing->post->post_date)); ?>"><?php echo get_the_date(); ?> <?php echo get_the_time(); ?></div>
						</div>
						<?php endif; ?>
						<?php if (!get_option('w2gm_hide_author_link')): ?>
						<div class="w2gm-meta-data">
							<div class="w2gm-author-link">
								<?php _e('By', 'W2GM'); ?> <?php echo get_the_author_link(); ?>
							</div>
						</div>
						<?php endif; ?>
					</header>
					<?php endif; ?>

					<article id="post-<?php the_ID(); ?>" class="w2gm-listing">
						<?php if ($listing->logo_image): ?>
						<div class="w2gm-listing-logo-wrap w2gm-single-listing-logo-wrap" id="images">
							<?php do_action('w2gm_listing_pre_logo_wrap_html', $listing); ?>
							<meta itemprop="image" content="<?php echo $listing->get_logo_url(); ?>" />

							<?php
							$images = array();
							foreach ($listing->images AS $attachment_id=>$image) {
								$image_src = wp_get_attachment_image_src($attachment_id, 'full');
								$image_title = $image['post_title'];
								if (get_option('w2gm_enable_lighbox_gallery')) {
									$images[] = '<a href="' . $image_src[0] . '" data-w2gm-lightbox="listing_images" title="' . esc_attr($image_title) . '"><img src="' . $image_src[0] . '" alt="' . esc_attr($image_title) . '" title="' . esc_attr($image_title) . '" /></a>';
								} else {
									$images[] = '<img src="' . $image_src[0] . '" alt="' . esc_attr($image_title) . '" title="' . esc_attr($image_title) . '" />';
								}
							}
							$max_slides = (count($listing->images) < 5) ? count($listing->images) : 5;
							if (get_option('w2gm_100_single_logo_width'))
								w2gm_renderTemplate('frontend/slider.tpl.php', array(
										'slide_width' => 150,
										'max_width' => false,
										'max_slides' => $max_slides,
										'height' => 450,
										'images' => $images,
										'enable_links' => get_option('w2gm_enable_lighbox_gallery'),
										'auto_slides' => get_option('w2gm_auto_slides_gallery'),
										'auto_slides_delay' => get_option('w2gm_auto_slides_gallery_delay'),
										'random_id' => w2gm_generateRandomVal()
								));
							else
								w2gm_renderTemplate('frontend/slider.tpl.php', array(
										'slide_width' => 130,
										'max_width' => get_option('w2gm_single_logo_width'),
										'max_slides' => $max_slides,
										'height' => get_option('w2gm_single_logo_width')*0.7,
										'images' => $images,
										'enable_links' => get_option('w2gm_enable_lighbox_gallery'),
										'auto_slides' => get_option('w2gm_auto_slides_gallery'),
										'auto_slides_delay' => get_option('w2gm_auto_slides_gallery_delay'),
										'random_id' => w2gm_generateRandomVal()
								));

							// Special trick for lightbox
							if ($images && get_option('w2gm_enable_lighbox_gallery')): ?>
							<div id="w2gm-lighbox-images" style="display: none;"><?php foreach ($images AS $image) echo $image; ?></div>
							<?php endif; ?>
						</div>
						<?php endif; ?>

						<div class="w2gm-single-listing-text-content-wrap">
							<?php do_action('w2gm_listing_pre_content_html', $listing); ?>
					
							<?php $listing->renderContentFields(true); ?>

							<?php do_action('w2gm_listing_post_content_html', $listing); ?>
						</div>

						<script>
							var w2gm_listing_tabs_order = <?php echo json_encode(get_option('w2gm_listings_tabs_order')); ?>;
						
							(function($) {
								"use strict";
	
								$(function() {
									<?php if (get_option('w2gm_listings_tabs_order')): ?>
									if (1==2) var x = 1;
									<?php foreach (get_option('w2gm_listings_tabs_order') AS $tab): ?>
									else if ($('#<?php echo $tab; ?>').length)
										w2gm_show_tab($('.w2gm-listing-tabs a[data-tab="#<?php echo $tab; ?>"]'));
									<?php endforeach; ?>
									<?php else: ?>
									w2gm_show_tab($('.w2gm-listing-tabs a:first'));
									<?php endif; ?>
								});
							})(jQuery);
						</script>

						<?php if (
							($fields_groups = $listing->getFieldsGroupsOnTabs())
							|| ($listing->level->map && $listing->isMap() && $listing->locations)
							|| (w2gm_comments_open())
							|| ($listing->level->videos_number && $listing->videos)
							|| (get_option('w2gm_listing_contact_form') && (!$listing->is_claimable || !get_option('w2gm_hide_claim_contact_form')))
							|| (get_option('w2gm_report_form'))
							): ?>
						<ul class="w2gm-listing-tabs w2gm-nav w2gm-nav-tabs w2gm-clearfix" role="tablist">
							<?php if ($listing->level->map && $listing->isMap() && $listing->locations): ?>
							<li><a href="javascript: void(0);" data-tab="#addresses-tab" data-toggle="w2gm-tab" role="tab"><?php _e('Map', 'W2GM'); ?></a></li>
							<?php endif; ?>
							<?php if (w2gm_comments_open()): ?>
							<li><a href="javascript: void(0);" data-tab="#comments-tab" data-toggle="w2gm-tab" role="tab"><?php echo _n('Comment', 'Comments', $listing->post->comment_count, 'W2GM'); ?> (<?php echo $listing->post->comment_count; ?>)</a></li>
							<?php endif; ?>
							<?php if ($listing->level->videos_number && $listing->videos): ?>
							<li><a href="javascript: void(0);" data-tab="#videos-tab" data-toggle="w2gm-tab" role="tab"><?php echo _n('Video', 'Videos', count($listing->videos), 'W2GM'); ?> (<?php echo count($listing->videos); ?>)</a></li>
							<?php endif; ?>
							<?php if (get_option('w2gm_listing_contact_form') && (!$listing->is_claimable || !get_option('w2gm_hide_claim_contact_form')) && ($listing_owner = get_userdata($listing->post->post_author)) && $listing_owner->user_email): ?>
							<li><a href="javascript: void(0);" data-tab="#contact-tab" data-toggle="w2gm-tab" role="tab"><?php _e('Contact', 'W2GM'); ?></a></li>
							<?php endif; ?>
							<?php if (get_option('w2gm_report_form')): ?>
							<li><a href="javascript: void(0);" data-tab="#report-tab" data-toggle="w2gm-tab" role="tab"><?php _e('Report', 'W2GM'); ?></a></li>
							<?php endif; ?>
							<?php
							foreach ($fields_groups AS $fields_group): ?>
							<li><a href="javascript: void(0);" data-tab="#field-group-tab-<?php echo $fields_group->id; ?>" data-toggle="w2gm-tab" role="tab"><?php echo $fields_group->name; ?></a></li>
							<?php endforeach; ?>
							<?php do_action('w2gm_listing_single_tabs', $listing); ?>
						</ul>

						<div class="w2gm-tab-content">
							<?php if ($listing->level->map && $listing->isMap() && $listing->locations): ?>
							<div id="addresses-tab" class="w2gm-tab-pane w2gm-fade" role="tabpanel">
								<?php $listing->renderMap($frontend_controller->hash); ?>
							</div>
							<?php endif; ?>

							<?php if (w2gm_comments_open()): ?>
							<div id="comments-tab" class="w2gm-tab-pane w2gm-fade" role="tabpanel">
								<?php
								global $withcomments;
								$withcomments = true;
								comments_template('', true);
								?>
							</div>
							<?php endif; ?>

							<?php if ($listing->level->videos_number && $listing->videos): ?>
							<div id="videos-tab" class="w2gm-tab-pane w2gm-fade" role="tabpanel">
							<?php foreach ($listing->videos AS $video): ?>
								<?php if (strlen($video['id']) == 11): ?>
								<iframe width="100%" height="400" class="w2gm-video-iframe fitvidsignore" src="//www.youtube.com/embed/<?php echo $video['id']; ?>" frameborder="0" allowfullscreen></iframe>
								<?php elseif (strlen($video['id']) == 9): ?>
								<iframe width="100%" height="400" class="w2gm-video-iframe fitvidsignore" src="https://player.vimeo.com/video/<?php echo $video['id']; ?>?color=d1d1d1&title=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
								<?php endif; ?>
							<?php endforeach; ?>
							</div>
							<?php endif; ?>

							<?php if (get_option('w2gm_listing_contact_form') && (!$listing->is_claimable || !get_option('w2gm_hide_claim_contact_form')) && ($listing_owner = get_userdata($listing->post->post_author)) && $listing_owner->user_email): ?>
							<div id="contact-tab" class="w2gm-tab-pane w2gm-fade" role="tabpanel">
							<?php if (!get_option('w2gm_hide_anonymous_contact_form') || is_user_logged_in()): ?>
								<?php if (defined('WPCF7_VERSION') && w2gm_get_wpml_dependent_option('w2gm_listing_contact_form_7')): ?>
									<?php echo do_shortcode(w2gm_get_wpml_dependent_option('w2gm_listing_contact_form_7')); ?>
								<?php else: ?>
									<?php w2gm_renderTemplate('frontend/contact_form.tpl.php', array('listing' => $listing)); ?>
								<?php endif; ?>
							<?php else: ?>
								<?php printf(__('You must be <a href="%s">logged in</a> to submit contact form', 'W2GM'), wp_login_url(get_permalink($listing->post->ID))); ?>
							<?php endif; ?>
							</div>
							<?php endif; ?>
							
							<?php if (get_option('w2gm_report_form')): ?>
							<div id="report-tab" class="w2gm-tab-pane w2gm-fade" role="tabpanel">
								<?php w2gm_renderTemplate('frontend/report_form.tpl.php', array('listing' => $listing)); ?>
							</div>
							<?php endif; ?>
							
							<?php foreach ($fields_groups AS $fields_group): ?>
							<div id="field-group-tab-<?php echo $fields_group->id; ?>" class="w2gm-tab-pane w2gm-fade" role="tabpanel">
								<?php echo $fields_group->renderOutput($listing, true); ?>
							</div>
							<?php endforeach; ?>
							
							<?php do_action('w2gm_listing_single_tabs_content', $listing); ?>
						</div>
						<?php endif; ?>
					</article>
				</div>
			<?php endwhile; endif; ?>
		</div>