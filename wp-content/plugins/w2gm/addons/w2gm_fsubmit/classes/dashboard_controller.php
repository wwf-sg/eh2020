<?php 

class w2gm_dashboard_controller extends w2gm_frontend_controller {

	public function init($args = array()) {
		global $w2gm_instance, $w2gm_fsubmit_instance, $sitepress;
		
		parent::init($args);
		
		$shortcode_atts = array_merge(array(
				'listing_info' => 1,
		), $args);
		
		$this->args = $shortcode_atts;
		
		$this->add_template_args($this->args);

		$login_registrations = new w2gm_login_registrations;
		if ($login_registrations->is_action()) {
			$this->template = $login_registrations->process($this);
		} elseif (!is_user_logged_in()) {
			if (w2gm_get_wpml_dependent_option('w2gm_dashboard_login_page') && w2gm_get_wpml_dependent_option('w2gm_dashboard_login_page') != get_the_ID()) {
				$url = get_permalink(w2gm_get_wpml_dependent_option('w2gm_dashboard_login_page'));
				$url = add_query_arg('redirect_to', urlencode(get_permalink()), $url);
				wp_redirect($url);
			} else {
				$this->template = $login_registrations->login_template();
			}
		} else {
			if (isset($_POST['referer']))
				$this->referer = $_POST['referer'];
			else
				$this->referer = wp_get_referer();
			if (isset($_POST['cancel']) && isset($_POST['referer'])) {
				wp_redirect($_POST['referer']);
				die();
			}

			if (!$w2gm_instance->action) {
				if (get_query_var('page'))
					$paged = get_query_var('page');
				elseif (get_query_var('paged'))
					$paged = get_query_var('paged');
				else
					$paged = 1;
			} else
				$paged = -1;
			
			$args = array(
					'post_type' => W2GM_POST_TYPE,
					//'author' => get_current_user_id(),
					'paged' => $paged,
					'posts_per_page' => 20,
					'post_status' => 'any'
			);
			add_filter('posts_where', array($this, 'add_claimed_listings_where'));
			$this->query = new WP_Query($args);
			remove_filter('posts_where', array($this, 'add_claimed_listings_where'));
			wp_reset_postdata();
			
			$this->listings_count = $this->query->found_posts;
			
			$this->active_tab = 'listings';
			
			$listing_id = w2gm_getValue($_GET, 'listing_id');

			if (!$w2gm_instance->action) {
				$this->processQuery(false, false);

				$this->template = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'dashboard.tpl.php');
				$this->subtemplate = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'listings.tpl.php');
			} elseif ($w2gm_instance->action == 'edit_listing' && $listing_id) {
				if (w2gm_current_user_can_edit_listing($listing_id)) {
					$listing = w2gm_getListing($listing_id);
					$w2gm_instance->current_listing = $listing;
					$w2gm_instance->listings_manager->current_listing = $listing;
					
					$w2gm_instance->media_manager->load_media(array(
							'images' => $listing->images,
							'videos' => $listing->videos,
							'logo_image' => $listing->logo_image,
					));
					$w2gm_instance->media_manager->load_params(array(
							'object_id' => $listing->post->ID,
							'images_number' => $listing->level->images_number,
							'videos_number' => $listing->level->videos_number,
							'logo_enabled' => $listing->level->logo_enabled,
					));
					
					if (isset($_POST['submit'])) {
						$errors = array();

						if (!isset($_POST['post_title']) || !trim($_POST['post_title']) || $_POST['post_title'] == __('Auto Draft')) {
							$errors[] = __('Listing title field required', 'W2GM');
							$post_title = __('Auto Draft');
						} else
							$post_title = trim($_POST['post_title']);

						$post_categories_ids = array();
						if ($listing->level->categories_number > 0 || $listing->level->unlimited_categories) {
							if ($post_categories_ids = $w2gm_instance->categories_manager->validateCategories($listing->level, $_POST, $errors)) {
								foreach ($post_categories_ids AS $key=>$id)
									$post_categories_ids[$key] = intval($id);
							}
							wp_set_object_terms($listing->post->ID, $post_categories_ids, W2GM_CATEGORIES_TAX);
						}

						if (get_option('w2gm_enable_tags')) {
							if ($post_tags_ids = $w2gm_instance->categories_manager->validateTags($_POST, $errors)) {
								foreach ($post_tags_ids AS $key=>$id)
									$post_tags_ids[$key] = intval($id);
							}
							wp_set_object_terms($listing->post->ID, $post_tags_ids, W2GM_TAGS_TAX);
						}
						
						$w2gm_instance->content_fields->saveValues($listing->post->ID, $post_categories_ids, $errors, $_POST);
						
						if ($listing->level->locations_number) {
							if ($validation_results = $w2gm_instance->locations_manager->validateLocations($listing->level, $errors)) {
								$w2gm_instance->locations_manager->saveLocations($listing->level, $listing->post->ID, $validation_results);
							}
						}
						
						if ($listing->level->images_number || $listing->level->videos_number) {
							if ($validation_results = $w2gm_instance->media_manager->validateAttachments($errors))
								$w2gm_instance->media_manager->saveAttachments($validation_results);
						}
						
						if (get_option('w2gm_listing_contact_form') && get_option('w2gm_custom_contact_email')) {
							$w2gm_form_validation = new w2gm_form_validation();
							$w2gm_form_validation->set_rules('contact_email', __('Contact email', 'W2GM'), 'valid_email');
						
							if (!$w2gm_form_validation->run()) {
								$errors[] = $w2gm_form_validation->error_array();
							} else {
								update_post_meta($listing->post->ID, '_contact_email', $w2gm_form_validation->result_array('contact_email'));
							}
						}

						if ($errors) {
							$postarr = array(
									'ID' => $listing_id,
									'post_title' => apply_filters('w2gm_title_save_pre', $post_title, $listing),
									'post_name' => apply_filters('w2gm_name_save_pre', '', $listing),
									'post_content' => (isset($_POST['post_content']) ? $_POST['post_content'] : ''),
									'post_excerpt' => (isset($_POST['post_excerpt']) ? $_POST['post_excerpt'] : '')
							);
							$result = wp_update_post($postarr, true);
							if (is_wp_error($result))
								$errors[] = $result->get_error_message();

							foreach ($errors AS $error)
								w2gm_addMessage($error, 'error');
							$listing = w2gm_getListing($listing_id);
						} else {
							if (!$listing->level->eternal_active_period && $listing->status != 'expired') {
								if (get_option('w2gm_change_expiration_date') || current_user_can('manage_options')) {
									$w2gm_instance->listings_manager->changeExpirationDate();
								} else {
									$expiration_date = w2gm_calcExpirationDate(current_time('timestamp'), $listing->level);
									add_post_meta($listing->post->ID, '_expiration_date', $expiration_date);
								}
							}

							if (get_option('w2gm_claim_functionality') && !get_option('w2gm_hide_claim_metabox')) {
								if (isset($_POST['is_claimable'])) {
									update_post_meta($listing->post->ID, '_is_claimable', true);
								} else {
									update_post_meta($listing->post->ID, '_is_claimable', false);
								}
							}

							if ($listing->post->post_status == 'publish') {
								if (get_option('w2gm_fsubmit_edit_moderation')) {
									$post_status = 'pending';
									update_post_meta($listing_id, '_listing_approved', false);
									update_post_meta($listing_id, '_requires_moderation', true);
								} else {
									$post_status = 'publish';
								}
							}
							if (get_option('w2gm_fsubmit_edit_moderation')) {
								$message = esc_attr__("Listing was saved successfully! Now it's awaiting moderators approval.", 'W2GM');
							} else {
								$message = __('Listing was saved successfully!', 'W2GM');
							}

							$postarr = array(
									'ID' => $listing_id,
									'post_title' => apply_filters('w2gm_title_save_pre', $post_title, $listing),
									'post_name' => apply_filters('w2gm_name_save_pre', '', $listing),
									'post_content' => (isset($_POST['post_content']) ? $_POST['post_content'] : ''),
									'post_excerpt' => (isset($_POST['post_excerpt']) ? $_POST['post_excerpt'] : '')
							);
							if (isset($post_status)) {
								$postarr['post_status'] = $post_status;
							}

							$result = wp_update_post($postarr, true);
							if (is_wp_error($result)) {
								w2gm_addMessage($result->get_error_message(), 'error');
							} else {
								w2gm_addMessage($message);
								
								if (get_option('w2gm_editlisting_admin_notification')) {
									// Wehn listing was published and became pending after modification
									if ($listing->post->post_status == 'publish' && $post_status == 'pending') {
										$author = wp_get_current_user();
	
										$subject = __('Notification about listing modification (do not reply)', 'W2GM');
										$body = str_replace('[user]', $author->display_name,
												str_replace('[listing]', $listing->title(),
												str_replace('[link]', admin_url('post.php?post='.$listing->post->ID.'&action=edit'),
										get_option('w2gm_editlisting_admin_notification'))));
			
										w2gm_mail(w2gm_getAdminNotificationEmail(), $subject, $body);
									}
								}
								
								if (!$this->referer || (isset($post_status) && $post_status != 'publish')) {
									$this->referer = w2gm_dashboardUrl();
								}
								// 2 ways to redirect: listing single page and dashboard
								if (strpos($this->referer, w2gm_dashboardUrl()) !== false) {
									wp_redirect($this->referer);
								} else {
									// the slug could be changed and we will get 404
									wp_redirect(get_permalink($listing->post->ID));
								}
								die();
							}
						}
						
						// renew data inside $listing object
						$listing = w2gm_getListing($listing_id);
						$w2gm_instance->current_listing = $listing;
						$w2gm_instance->listings_manager->current_listing = $listing;
					}

					$this->template = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'dashboard.tpl.php');
					$this->subtemplate = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'edit_listing.tpl.php');
					if ($listing->level->categories_number > 0 || $listing->level->unlimited_categories) {
						add_action('wp_enqueue_scripts', array($w2gm_instance->categories_manager, 'admin_enqueue_scripts_styles'));
					}
					
					if ($listing->level->locations_number > 0) {
						add_action('wp_enqueue_scripts', array($w2gm_instance->locations_manager, 'admin_enqueue_scripts_styles'));
					}
	
					if ($listing->level->images_number > 0 || $listing->level->videos_number > 0)
						add_action('wp_enqueue_scripts', array($w2gm_instance->media_manager, 'admin_enqueue_scripts_styles'));
				}
			} elseif ($w2gm_instance->action == 'renew_listing' && $listing_id && get_option('w2gm_enable_renew')) {
				if (w2gm_current_user_can_edit_listing($listing_id) && ($listing = w2gm_getListing($listing_id))) {
					$this->action = 'show';
					if (isset($_GET['renew_action']) && $_GET['renew_action'] == 'renew') {
						if ($listing->processActivate(true))
							w2gm_addMessage(__('Listing was renewed successfully!', 'W2GM'));
						/* else
							w2gm_addMessage(__('An error has occurred and listing was not renewed', 'W2GM'), 'error'); */
						$this->action = $_GET['renew_action'];
						$this->referer = $_GET['referer'];
					}
					$this->template = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'dashboard.tpl.php');
					$this->subtemplate = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'renew.tpl.php');
				} else
					wp_die('You are not able to manage this listing', 'W2GM');
			} elseif ($w2gm_instance->action == 'delete_listing' && $listing_id) {
				if (w2gm_current_user_can_edit_listing($listing_id) && ($listing = w2gm_getListing($listing_id))) {
					if (isset($_GET['delete_action']) && $_GET['delete_action'] == 'delete') {
						if (wp_delete_post($listing_id, true) !== FALSE) {
							$w2gm_instance->listings_manager->delete_listing_data($listing_id);

							w2gm_addMessage(__('Listing was deleted successfully!', 'W2GM'));
							wp_redirect(w2gm_dashboardUrl());
							die();
						} else 
							w2gm_addMessage(__('An error has occurred and listing was not deleted', 'W2GM'), 'error');
					}
					$this->template = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'dashboard.tpl.php');
					$this->subtemplate = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'delete.tpl.php');
				} else
					wp_die('You are not able to manage this listing', 'W2GM');
			} elseif ($w2gm_instance->action == 'view_stats' && $listing_id) {
				if (get_option('w2gm_enable_stats') && w2gm_current_user_can_edit_listing($listing_id) && ($listing = w2gm_getListing($listing_id))) {
					add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles'));
					$this->template = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'dashboard.tpl.php');
					$this->subtemplate = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'view_stats.tpl.php');
				} else
					wp_die('You are not able to manage this listing', 'W2GM');
			} elseif ($w2gm_instance->action == 'profile') {
				if (get_option('w2gm_allow_edit_profile')) {
					$user_id = get_current_user_id();
					$current_user = wp_get_current_user();
	
					include_once ABSPATH . 'wp-admin/includes/user.php';
	
					if (isset($_POST['user_id']) && (!defined('W2GM_DEMO') || !W2GM_DEMO)) {
						if ($_POST['user_id'] == $user_id) {
							global $wpdb;
		
							if (!is_multisite()) {
								$errors = edit_user($user_id);
								update_user_meta($user_id, 'w2gm_billing_name', $_POST['w2gm_billing_name']);
								update_user_meta($user_id, 'w2gm_billing_address', $_POST['w2gm_billing_address']);
							} else {
								$user = get_userdata($user_id);
							
								// Update the email address in signups, if present.
								if ($user->user_login && isset($_POST['email']) && is_email($_POST['email']) && $wpdb->get_var($wpdb->prepare("SELECT user_login FROM {$wpdb->signups} WHERE user_login = %s", $user->user_login)))
									$wpdb->query($wpdb->prepare("UPDATE {$wpdb->signups} SET user_email = %s WHERE user_login = %s", $_POST['email'], $user->user_login));
							
								// We must delete the user from the current blog if WP added them after editing.
								$delete_role = false;
								$blog_prefix = $wpdb->get_blog_prefix();
								if ($user_id != $current_user->ID) {
									$cap = $wpdb->get_var("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = '{$user_id}' AND meta_key = '{$blog_prefix}capabilities' AND meta_value = 'a:0:{}'");
									if (!is_network_admin() && null == $cap && $_POST['role'] == '') {
										$_POST['role'] = 'contributor';
										$delete_role = true;
									}
								}
								if (!isset($errors) || (isset($errors) && is_object($errors) && false == $errors->get_error_codes()))
									$errors = edit_user($user_id);
								if ( $delete_role ) // stops users being added to current blog when they are edited
									delete_user_meta($user_id, $blog_prefix . 'capabilities');
							}
							
							if (!is_wp_error($errors)) {
								w2gm_addMessage(__('Your profile was successfully updated!', 'W2GM'));
								wp_redirect(w2gm_dashboardUrl(array('w2gm_action' => 'profile')));
								die();
							}
						} else
							wp_die('You are not able to manage profile', 'W2GM');
					}
	
					$this->user = get_user_to_edit($user_id);
	
					wp_enqueue_script('password-strength-meter');
					wp_enqueue_script('user-profile');
					
					$this->template = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'dashboard.tpl.php');
					$this->subtemplate = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'profile.tpl.php');
					$this->active_tab = 'profile';
				} else
					wp_die('You are not able to manage profile', 'W2GM');
			} elseif ($w2gm_instance->action == 'claim_listing' && $listing_id) {
				if (($listing = w2gm_getListing($listing_id)) && $listing->is_claimable) {
					$claimer_id = get_current_user_id();
					if ($listing->post->post_author != $claimer_id) {
						$this->action = 'show';
						if (isset($_GET['claim_action']) && $_GET['claim_action'] == 'claim') {
							if (isset($_POST['claim_message']) && $_POST['claim_message'])
								$claimer_message = $_POST['claim_message'];
							else
								$claimer_message = '';
							if ($listing->claim->updateRecord($claimer_id, $claimer_message, 'pending')) {
								update_post_meta($listing->post->ID, '_is_claimable', false);
								if (get_option('w2gm_claim_approval')) {
									if (get_option('w2gm_claim_notification')) {
										$author = get_userdata($listing->post->post_author);
										$claimer = get_userdata($claimer_id);

										$subject = __('Claim notification', 'W2GM');
		
										$body = str_replace('[author]', $author->display_name,
												str_replace('[listing]', $listing->post->post_title,
												str_replace('[claimer]', $claimer->display_name,
												str_replace('[link]', w2gm_dashboardUrl(array('listing_id' => $listing->post->ID, 'w2gm_action' => 'process_claim')),
												str_replace('[message]', $claimer_message,
										get_option('w2gm_claim_notification'))))));
		
										w2gm_mail($author->user_email, $subject, $body);
									}
									w2gm_addMessage(__('Listing was claimed successfully!', 'W2GM'));
								} else {
									// Automatically process claim without approval
									$listing->claim->approve();
									w2gm_addMessage(__('Listing claim was approved successfully!', 'W2GM'));
								}
							}

							$this->action = $_GET['claim_action'];
						}
						
						if ($this->action == 'show') {
							if (get_option('w2gm_claim_approval')) {
								w2gm_addMessage(__('The notification about claim for this listing will be sent to the current listing owner.', 'W2GM'));
								w2gm_addMessage(__("After approval you will become owner of this listing, you'll receive email notification.", 'W2GM'));
							}
							if (get_option('w2gm_after_claim') == 'expired') {
								w2gm_addMessage(__('After approval listing status become expired.', 'W2GM') . ((get_option('w2gm_payments_addon')) ? apply_filters('w2gm_renew_option', __(' The price for renewal', 'W2GM'), $w2gm_instance->current_listing) : ''));
							}
						}
						
						$this->template = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'dashboard.tpl.php');
						$this->subtemplate = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'claim.tpl.php');
					} else
						wp_die('This is your own listing', 'W2GM');
				}
			} elseif ($w2gm_instance->action == 'process_claim' && $listing_id) {
				if (w2gm_current_user_can_edit_listing($listing_id) && ($listing = w2gm_getListing($listing_id)) && $listing->claim->isClaimed()) {
					$this->action = 'show';
					if (isset($_GET['claim_action']) && ($_GET['claim_action'] == 'approve' || $_GET['claim_action'] == 'decline')) {
						if ($_GET['claim_action'] == 'approve') {
							$listing->claim->approve();
							if (get_option('w2gm_claim_approval_notification')) {
								$claimer = get_userdata($listing->claim->claimer_id);

								$subject = __('Approval of claim notification', 'W2GM');
							
								$body = str_replace('[claimer]', $claimer->display_name,
										str_replace('[listing]', $listing->post->post_title,
										str_replace('[link]', w2gm_dashboardUrl(),
								get_option('w2gm_claim_approval_notification'))));
							
								w2gm_mail($claimer->user_email, $subject, $body);
							}
							w2gm_addMessage(__('Listing claim was approved successfully!', 'W2GM'));
						} elseif ($_GET['claim_action'] == 'decline') {
							$listing->claim->deleteRecord();
							if (get_option('w2gm_claim_decline_notification')) {
								$claimer = get_userdata($listing->claim->claimer_id);

								$subject = __('Claim decline notification', 'W2GM');
									
								$body = str_replace('[claimer]', $claimer->display_name,
										str_replace('[listing]', $listing->post->post_title,
										get_option('w2gm_claim_decline_notification')));
									
								w2gm_mail($claimer->user_email, $subject, $body);
							}
							update_post_meta($listing->post->ID, '_is_claimable', true);
							w2gm_addMessage(__('Listing claim was declined!', 'W2GM'));
						}
						$this->action = 'processed';
						$this->referer = $_GET['referer'];
					}

					$this->template = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'dashboard.tpl.php');
					$this->subtemplate = array(W2GM_FSUBMIT_TEMPLATES_PATH, 'claim_process.tpl.php');
				} else
					wp_die('You are not able to manage this listing', 'W2GM');
			// adapted for WPML
			}  elseif (function_exists('wpml_object_id_filter') && $sitepress && get_option('w2gm_enable_frontend_translations') && $w2gm_instance->action == 'add_translation' && isset($_GET['listing_id']) && isset($_GET['to_lang'])) {
				$master_post_id = $_GET['listing_id'];
				$lang_code = $_GET['to_lang'];

				global $iclTranslationManagement;

				require_once( ICL_PLUGIN_PATH . '/inc/translation-management/translation-management.class.php' );
				if (!isset($iclTranslationManagement))
					$iclTranslationManagement = new TranslationManagement;
				
				$post_type = get_post_type($master_post_id);
				if ($sitepress->is_translated_post_type($post_type)) {
					// WPML has special option sync_post_status, that controls post_status duplication
					if ($new_listing_id = $iclTranslationManagement->make_duplicate($master_post_id, $lang_code)) {
						$iclTranslationManagement->reset_duplicate_flag($new_listing_id);
						w2gm_addMessage(__('Translation was successfully created!', 'W2GM'));
						do_action('wpml_switch_language', $lang_code);
						wp_redirect(add_query_arg(array('w2gm_action' => 'edit_listing', 'listing_id' => $new_listing_id), get_permalink(apply_filters('wpml_object_id', $w2gm_instance->dashboard_page_id, 'page', true, $lang_code))));
					} else {
						w2gm_addMessage(__('Translation was not created!', 'W2GM'), 'error');
						wp_redirect(w2gm_dashboardUrl());
					}
					die();
				}
			}
			
			$w2gm_instance->listings_manager->loadCurrentListing($listing_id);
		}

		apply_filters('w2gm_dashboard_controller_construct', $this);
	}

	public function display() {
		$output =  w2gm_renderTemplate($this->template, $this->template_args, true);
		wp_reset_postdata();

		return $output;
	}

	public function add_claimed_listings_where($where = '') {
		global $wpdb;
		
		$claimed_posts = '';
		$claimed_posts_ids = array();

		$results = $wpdb->get_results("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_claimer_id' AND meta_value='" . get_current_user_id() . "'", ARRAY_A);
		foreach ($results AS $row)
			$claimed_posts_ids[] = $row['post_id'];
		if ($claimed_posts_ids)
			$claimed_posts = " OR {$wpdb->posts}.ID IN (".implode(',', $claimed_posts_ids).") ";
		$where .= " AND ({$wpdb->posts}.post_author IN (".get_current_user_id().")" . $claimed_posts . ")";
		
		return $where;
	}
	
	public function enqueue_scripts_styles() {
		wp_register_script('w2gm_stats', W2GM_RESOURCES_URL . 'js/chart.min.js');
		wp_enqueue_script('w2gm_stats');
	}
}

?>