<?php

define('W2GM_FSUBMIT_PATH', plugin_dir_path(__FILE__));

function w2gm_fsubmit_loadPaths() {
	define('W2GM_FSUBMIT_TEMPLATES_PATH', W2GM_FSUBMIT_PATH . 'templates/');
	define('W2GM_FSUBMIT_RESOURCES_PATH', W2GM_FSUBMIT_PATH . 'resources/');
	define('W2GM_FSUBMIT_RESOURCES_URL', plugins_url('/', __FILE__) . 'resources/');
}
add_action('init', 'w2gm_fsubmit_loadPaths', 0);

define('W2GM_FSUBMIT_SHORTCODE', 'webmap-submit');
define('W2GM_DASHBOARD_SHORTCODE', 'webmap-dashboard');

include_once W2GM_FSUBMIT_PATH . 'classes/dashboard_controller.php';
include_once W2GM_FSUBMIT_PATH . 'classes/submit_controller.php';
include_once W2GM_FSUBMIT_PATH . 'classes/submit_button_controller.php';
include_once W2GM_FSUBMIT_PATH . 'classes/functions.php';
include_once W2GM_FSUBMIT_PATH . 'classes/login_registrations.php';

global $w2gm_wpml_dependent_options;
$w2gm_wpml_dependent_options[] = 'w2gm_tospage';
$w2gm_wpml_dependent_options[] = 'w2gm_submit_login_page';
$w2gm_wpml_dependent_options[] = 'w2gm_dashboard_login_page';

class w2gm_fsubmit_plugin {

	public function init() {
		global $w2gm_instance, $w2gm_shortcodes, $w2gm_shortcodes_init;
		
		if (!get_option('w2gm_installed_fsubmit'))
			//w2gm_install_fsubmit();
			add_action('init', 'w2gm_install_fsubmit', 0);
		add_action('w2gm_version_upgrade', 'w2gm_upgrade_fsubmit');

		add_filter('w2gm_build_settings', array($this, 'plugin_settings'));

		// add new shortcodes for frontend submission and dashboard
		$w2gm_shortcodes['webmap-submit'] = 'w2gm_submit_controller';
		$w2gm_shortcodes['webmap-dashboard'] = 'w2gm_dashboard_controller';
		$w2gm_shortcodes['webmap-submit-button'] = 'w2gm_submit_button_controller';
		
		$w2gm_shortcodes_init['webmap-submit'] = 'w2gm_submit_controller';
		$w2gm_shortcodes_init['webmap-dashboard'] = 'w2gm_dashboard_controller';

		add_shortcode('webmap-submit', array($w2gm_instance, 'renderShortcode'));
		add_shortcode('webmap-dashboard', array($w2gm_instance, 'renderShortcode'));
		add_shortcode('webmap-submit-button', array($w2gm_instance, 'renderShortcode'));
		
		add_action('init', array($this, 'getSubmitPage'), 0);
		add_action('init', array($this, 'getDasboardPage'), 0);

		add_filter('w2gm_get_edit_listing_link', array($this, 'edit_listings_links'), 10, 2);

		add_action('w2gm_listing_frontpanel', array($this, 'add_submit_button'), 10);
		add_action('w2gm_listing_frontpanel', array($this, 'add_claim_button'), 11);
		
		add_action('w2gm_listing_frontpanel', array($this, 'add_logout_button'), 12);

		add_action('init', array($this, 'remove_admin_bar'));
		add_action('admin_init', array($this, 'restrict_dashboard'));

		add_action('transition_post_status', array($this, 'on_listing_approval'), 10, 3);
		add_action('w2gm_post_status_on_activation', array($this, 'post_status_on_activation'), 10, 2);
		
		add_filter('no_texturize_shortcodes', array($this, 'w2gm_no_texturize'));

		add_action('w2gm_render_template', array($this, 'check_custom_template'), 10, 2);
	}
	
	public function w2gm_no_texturize($shortcodes) {
		$shortcodes[] = 'webmap-submit';
		$shortcodes[] = 'webmap-dashboard';

		return $shortcodes;
	}
	
	/**
	 * check is there template in one of these paths:
	 * - themes/theme/w2gm-plugin/templates/w2gm_fsubmit/
	 * - plugins/w2gm/templates/w2gm_fsubmit/
	 * 
	 */
	public function check_custom_template($template, $args) {
		if (is_array($template)) {
			$template_path = $template[0];
			$template_file = $template[1];
			
			if ($template_path == W2GM_FSUBMIT_TEMPLATES_PATH && ($fsubmit_template = w2gm_isTemplate('w2gm_fsubmit/' . $template_file))) {
				return $fsubmit_template;
			}
		}
		return $template;
	}

	public function plugin_settings($options) {
		global $sitepress; // adapted for WPML

		$pages = get_pages();
		$all_pages[] = array('value' => 0, 'label' => __('- Select page -', 'W2GM'));
		foreach ($pages AS $page)
			$all_pages[] = array('value' => $page->ID, 'label' => $page->post_title);
		
		$options['template']['menus']['general']['controls']['fsubmit'] = array(
			'type' => 'section',
			'title' => __('Frontend submission and dashboard', 'W2GM'),
			'fields' => array(
				array(
					'type' => 'radiobutton',
					'name' => 'w2gm_fsubmit_login_mode',
					'label' => __('Frontend submission login mode', 'W2GM'),
					'items' => array(
						array(
							'value' => 1,
							'label' => __('login required', 'W2GM'),
						),
						array(
							'value' => 2,
							'label' => __('necessary to fill in user info form', 'W2GM'),
						),
						array(
							'value' => 3,
							'label' => __('not necessary to fill in user info form', 'W2GM'),
						),
						array(
							'value' => 4,
							'label' => __('do not show show user info form', 'W2GM'),
						),
					),
					'default' => array(
						get_option('w2gm_fsubmit_login_mode'),
					),
				),
				array(
					'type' => 'toggle',
					'name' => 'w2gm_fsubmit_moderation',
					'label' => __('Enable pre-moderation of listings', 'W2GM'),
					'default' => get_option('w2gm_fsubmit_moderation'),
					'description' => __('Moderation will be required for all listings even after payment', 'W2GM'),
				),
				array(
					'type' => 'toggle',
					'name' => 'w2gm_fsubmit_edit_moderation',
					'label' => __('Enable moderation after a listing was modified', 'W2GM'),
					'default' => get_option('w2gm_fsubmit_edit_moderation'),
				),
				array(
					'type' => 'toggle',
					'name' => 'w2gm_fsubmit_button',
					'label' => __('Enable submit listing button on listing page', 'W2GM'),
					'default' => get_option('w2gm_fsubmit_button'),
				),
				array(
					'type' => 'toggle',
					'name' => 'w2gm_hide_admin_bar',
					'label' => __('Hide top admin bar at the frontend for regular users', 'W2GM'),
					'default' => get_option('w2gm_hide_admin_bar'),
				),
				array(
					'type' => 'toggle',
					'name' => 'w2gm_allow_edit_profile',
					'label' => __('Allow users to manage own profile at the frontend dashboard', 'W2GM'),
					'default' => get_option('w2gm_allow_edit_profile'),
				),
				array(
					'type' => 'toggle',
					'name' => 'w2gm_enable_tags',
					'label' => __('Enable listings tags input at the frontend', 'W2GM'),
					'default' => get_option('w2gm_enable_tags'),
				),
				array(
					'type' => 'select',
					'name' => w2gm_get_wpml_dependent_option_name('w2gm_tospage'), // adapted for WPML
					'label' => __('Require Terms of Services on submission page?', 'W2GM'),
					'description' => __('If yes, create a WordPress page containing your TOS agreement and assign it using the dropdown above.', 'W2GM') . w2gm_get_wpml_dependent_option_description(),
					'items' => $all_pages,
					'default' => (w2gm_get_wpml_dependent_option('w2gm_tospage') ? array(w2gm_get_wpml_dependent_option('w2gm_tospage')) : array(0)), // adapted for WPML
				),
				array(
					'type' => 'select',
					'name' => w2gm_get_wpml_dependent_option_name('w2gm_submit_login_page'), // adapted for WPML
					'label' => __('Use custom login page for listings submission process', 'W2GM'),
					'description' => __('You may use any 3rd party plugin to make custom login page and assign it with submission process using the dropdown above.', 'W2GM') . w2gm_get_wpml_dependent_option_description(),
					'items' => $all_pages,
					'default' => (w2gm_get_wpml_dependent_option('w2gm_submit_login_page') ? array(w2gm_get_wpml_dependent_option('w2gm_submit_login_page')) : array(0)), // adapted for WPML
				),
				array(
					'type' => 'select',
					'name' => w2gm_get_wpml_dependent_option_name('w2gm_dashboard_login_page'), // adapted for WPML
					'label' => __('Use custom login page for login into dashboard', 'W2GM'),
					'description' => __('You may use any 3rd party plugin to make custom login page and assign it with login into dashboard using the dropdown above.', 'W2GM') . w2gm_get_wpml_dependent_option_description(),
					'items' => $all_pages,
					'default' => (w2gm_get_wpml_dependent_option('w2gm_dashboard_login_page') ? array(w2gm_get_wpml_dependent_option('w2gm_dashboard_login_page')) : array(0)), // adapted for WPML
				),
			),
		);
		$options['template']['menus']['general']['controls']['claim'] = array(
			'type' => 'section',
			'title' => __('Claim functionality', 'W2GM'),
			'fields' => array(
				array(
					'type' => 'toggle',
					'name' => 'w2gm_claim_functionality',
					'label' => __('Enable claim functionality', 'W2GM'),
					'default' => get_option('w2gm_claim_functionality'),
					'description' => __('Each listing will get new option "allow claim". Claim button appears on single listings pages only when user is not logged in as current listing owner and a page [webmap-dashboard] shortcode exists.', 'W2GM'),
				),
				array(
					'type' => 'toggle',
					'name' => 'w2gm_claim_approval',
					'label' => __('Approval of claim required', 'W2GM'),
					'description' => __('In other case claim will be processed immediately without any notifications', 'W2GM'),
					'default' => get_option('w2gm_claim_approval'),
				),
				array(
					'type' => 'radiobutton',
					'name' => 'w2gm_after_claim',
					'label' => __('What will be with listing status after successful approval?', 'W2GM'),
					'description' => __('When set to expired - renewal may be payment option', 'W2GM'),
					'items' => array(
						array(
							'value' => 'active',
							'label' =>__('just approval', 'W2GM'),
						),
						array(
							'value' => 'expired',
							'label' =>__('expired status', 'W2GM'),
						),
					),
					'default' => array(
							get_option('w2gm_after_claim')
					),
				),
				array(
					'type' => 'toggle',
					'name' => 'w2gm_hide_claim_contact_form',
					'label' => __('Hide contact form on claimable listings', 'W2GM'),
					'default' => get_option('w2gm_hide_claim_contact_form'),
				),
				array(
					'type' => 'toggle',
					'name' => 'w2gm_hide_claim_metabox',
					'label' => __('Hide claim metabox at the frontend dashboard', 'W2GM'),
					'default' => get_option('w2gm_hide_claim_metabox'),
				),
			),
		);
		
		// adapted for WPML
		global $sitepress;
		if (function_exists('wpml_object_id_filter') && $sitepress) {
			$options['template']['menus']['advanced']['controls']['wpml']['fields'][] = array(
				'type' => 'toggle',
				'name' => 'w2gm_enable_frontend_translations',
				'label' => __('Enable frontend translations management', 'W2GM'),
				'default' => get_option('w2gm_enable_frontend_translations'),
			);
		}
		
		$options['template']['menus']['notifications']['controls']['notifications']['fields'][] = array(
			'type' => 'textarea',
			'name' => 'w2gm_newuser_notification',
			'label' => __('Registration of new user notification', 'W2GM'),
			'default' => get_option('w2gm_newuser_notification'),
			'description' => __('Tags allowed: ', 'W2GM') . '[author], [listing], [login], [password]',
		);

		$options['template']['menus']['notifications']['controls']['notifications']['fields'][] = array(
			'type' => 'textarea',
			'name' => 'w2gm_newlisting_admin_notification',
			'label' => __('Notification to admin about new listing creation', 'W2GM'),
			'default' => get_option('w2gm_newlisting_admin_notification'),
			'description' => __('Tags allowed: ', 'W2GM') . '[user], [listing], [link]',
		);

		$options['template']['menus']['notifications']['controls']['notifications']['fields'][] = array(
			'type' => 'textarea',
			'name' => 'w2gm_editlisting_admin_notification',
			'label' => __('Notification to admin about listing modification and pending status', 'W2GM'),
			'default' => get_option('w2gm_editlisting_admin_notification'),
			'description' => __('Tags allowed: ', 'W2GM') . '[user], [listing], [link]',
		);

		$options['template']['menus']['notifications']['controls']['notifications']['fields'][] = array(
			'type' => 'textarea',
			'name' => 'w2gm_approval_notification',
			'label' => __('Notification to author about successful listing approval', 'W2GM'),
			'default' => get_option('w2gm_approval_notification'),
			'description' => __('Tags allowed: ', 'W2GM') . '[author], [listing], [link]',
		);

		$options['template']['menus']['notifications']['controls']['notifications']['fields'][] = array(
			'type' => 'textarea',
			'name' => 'w2gm_claim_notification',
			'label' => __('Notification of claim to current listing owner', 'W2GM'),
			'default' => get_option('w2gm_claim_notification'),
			'description' => __('Tags allowed: ', 'W2GM') . '[author], [listing], [claimer], [link], [message]',
		);
		$options['template']['menus']['notifications']['controls']['notifications']['fields'][] = array(
			'type' => 'textarea',
			'name' => 'w2gm_claim_approval_notification',
			'label' => __('Notification of successful approval of claim', 'W2GM'),
			'default' => get_option('w2gm_claim_approval_notification'),
			'description' => __('Tags allowed: ', 'W2GM') . '[claimer], [listing], [link]',
		);
		$options['template']['menus']['notifications']['controls']['notifications']['fields'][] = array(
			'type' => 'textarea',
			'name' => 'w2gm_claim_decline_notification',
			'label' => __('Notification of claim decline', 'W2GM'),
			'default' => get_option('w2gm_claim_decline_notification'),
			'description' => __('Tags allowed: ', 'W2GM') . '[claimer], [listing]',
		);
		
		return $options;
	}

	public function getSubmitPage() {
		global $w2gm_instance, $wpdb;

		if ($pages = $wpdb->get_results("SELECT ID AS id, post_name AS slug FROM {$wpdb->posts} WHERE (post_content LIKE '%[" . W2GM_FSUBMIT_SHORTCODE . "]%' OR post_content LIKE '%[" . W2GM_FSUBMIT_SHORTCODE . " %') AND post_status = 'publish' AND post_type = 'page'", ARRAY_A)) {

			// adapted for WPML
			global $sitepress;
			if (function_exists('wpml_object_id_filter') && $sitepress) {
				foreach ($pages AS $key=>&$cpage) {
					if ($tpage = apply_filters('wpml_object_id', $cpage['id'], 'page')) {
						$cpage['id'] = $tpage;
						$cpage['slug'] = get_post($cpage['id'])->post_name;
					} else {
						unset($pages[$key]);
					}
				}
			}
			
			$pages = array_unique($pages, SORT_REGULAR);
			
			$submit_page = null;
			
			$shortcodes = array(W2GM_FSUBMIT_SHORTCODE);
			foreach ($pages AS $page_id) {
				$page_id = $page_id['id'];
				$pattern = get_shortcode_regex($shortcodes);
				if (preg_match_all('/'.$pattern.'/s', get_post($page_id)->post_content, $matches) && array_key_exists(2, $matches)) {
					foreach ($matches[2] AS $key=>$shortcode) {
						if (in_array($shortcode, $shortcodes)) {
							$submit_page = $page_id;
							break;
						}
					}
				}
			}

			if ($submit_page) {
				$w2gm_instance->submit_page['id'] = $submit_page;
				$w2gm_instance->submit_page['url'] = get_permalink($submit_page);
				$w2gm_instance->submit_page['slug'] = get_post($submit_page)->post_name;
			}
		}

		if (get_option('w2gm_fsubmit_button') && empty($w2gm_instance->submit_page) && is_admin())
			w2gm_addMessage(sprintf(__("You enabled <b>Google Maps locator Frontend submission addon</b>: sorry, but there isn't any page with [webmap-submit] shortcode. Create new page with [webmap-submit] shortcode or disable Frontend submission addon in settings.", 'W2GM')));
	}

	public function getDasboardPage() {
		global $w2gm_instance, $wpdb, $wp_rewrite;
		
		$w2gm_instance->dashboard_page_url = '';
		$w2gm_instance->dashboard_page_slug = '';
		$w2gm_instance->dashboard_page_id = 0;

		if ($dashboard_page = $wpdb->get_row("SELECT ID AS id, post_name AS slug FROM {$wpdb->posts} WHERE post_content LIKE '%[" . W2GM_DASHBOARD_SHORTCODE . "]%' AND post_status = 'publish' AND post_type = 'page' LIMIT 1", ARRAY_A)) {
			$w2gm_instance->dashboard_page_id = $dashboard_page['id'];
			$w2gm_instance->dashboard_page_slug = $dashboard_page['slug'];

			// adapted for WPML
			global $sitepress;
			if (function_exists('wpml_object_id_filter') && $sitepress) {
				if ($tpage = apply_filters('wpml_object_id', $w2gm_instance->dashboard_page_id, 'page')) {
					$w2gm_instance->dashboard_page_id = $tpage;
					$w2gm_instance->dashboard_page_slug = get_post($w2gm_instance->dashboard_page_id)->post_name;
				}
			}
			
			if ($wp_rewrite->using_permalinks())
				$w2gm_instance->dashboard_page_url = get_permalink($w2gm_instance->dashboard_page_id);
			else
				$w2gm_instance->dashboard_page_url = add_query_arg('page_id', $w2gm_instance->dashboard_page_id, home_url('/'));
		}
	}
	
	public function add_submit_button($frontpanel_buttons) {
		global $w2gm_instance;

		if ($frontpanel_buttons->isButton('submit') && get_option('w2gm_fsubmit_button') && !empty($w2gm_instance->submit_page)) {
			$href = apply_filters('w2gm_submit_button_href', w2gm_submitUrl(), $frontpanel_buttons);
			
			echo '<a class="w2gm-submit-listing-link w2gm-btn w2gm-btn-primary" href="' . $href . '" rel="nofollow" ' . $frontpanel_buttons->tooltipMeta(__('Submit new listing', 'W2GM'), true) . '><span class="w2gm-glyphicon w2gm-glyphicon-plus"></span> ' . ((!$frontpanel_buttons->hide_button_text) ? __('Submit new listing', 'W2GM') : "") . '</a> ';
		}
	}

	public function add_claim_button($frontpanel_buttons) {
		global $w2gm_instance;
		
		if ($frontpanel_buttons->isButton('claim')) {
			if ($listing = w2gm_getListing($frontpanel_buttons->getListingId())) {
				if ($listing && $listing->is_claimable && $w2gm_instance->dashboard_page_url && get_option('w2gm_claim_functionality') && $listing->post->post_author != get_current_user_id()) {
					$href = w2gm_dashboardUrl(array('listing_id' => $listing->post->ID, 'w2gm_action' => 'claim_listing'));
					
					$href = apply_filters('w2gm_claim_button_href', $href, $frontpanel_buttons);
					
					echo '<a class="w2gm-claim-listing-link w2gm-btn w2gm-btn-primary" href="' . $href . '" rel="nofollow" ' . $frontpanel_buttons->tooltipMeta(__('Is this your ad?', 'W2GM'), true) . '><span class="w2gm-glyphicon w2gm-glyphicon-flag"></span> ' . ((!$frontpanel_buttons->hide_button_text) ? __('Is this your ad?', 'W2GM') : "") . '</a> ';
				}
			}
		}
	}

	public function add_logout_button($frontpanel_buttons) {
		if ($frontpanel_buttons->isButton('logout')) {
			echo '<a class="w2gm-logout-link w2gm-btn w2gm-btn-primary" href="' . wp_logout_url(w2gm_dashboardUrl()) . '" rel="nofollow" ' . $frontpanel_buttons->tooltipMeta(__('Log out', 'W2GM'), true) . '><span class="w2gm-glyphicon w2gm-glyphicon-log-out"></span> ' . ((!$frontpanel_buttons->hide_button_text) ? __('Log out', 'W2GM') : "") . '</a>';
		}
	}
	
	public function remove_admin_bar() {
		if (get_option('w2gm_hide_admin_bar') && !current_user_can('manage_options') && !current_user_can('editor') && !is_admin()) {
			show_admin_bar(false);
			add_filter('show_admin_bar', '__return_false', 99999);
		}
	}

	public function restrict_dashboard() {
		global $w2gm_instance, $pagenow;

		if ($pagenow != 'admin-ajax.php' && $pagenow != 'async-upload.php')
			if (get_option('w2gm_hide_admin_bar') && !current_user_can('manage_options') && !current_user_can('editor') && is_admin()) {
				//w2gm_addMessage(__('You can not see dashboard!', 'W2GM'), 'error');
				wp_redirect(w2gm_dashboardUrl());
				die();
			}
	}

	public function edit_listings_links($url, $post_id) {
		global $w2gm_instance;

		if (!is_admin() && $w2gm_instance->dashboard_page_url && ($post = get_post($post_id)) && $post->post_type == W2GM_POST_TYPE)
			return w2gm_dashboardUrl(array('w2gm_action' => 'edit_listing', 'listing_id' => $post_id));
	
		return $url;
	}

	public function on_listing_approval($new_status, $old_status, $post) {
		if (get_option('w2gm_approval_notification')) {
			if (
				$post->post_type == W2GM_POST_TYPE &&
				'publish' == $new_status &&
				'pending' == $old_status &&
				($listing = w2gm_getListing($post)) &&
				($author = get_userdata($listing->post->post_author))
			) {
				update_post_meta($post->ID, '_listing_approved', true);

				$subject = __('Approval of listing', 'W2GM');
					
				$body = str_replace('[author]', $author->display_name,
						str_replace('[listing]', $listing->post->post_title,
						str_replace('[link]', w2gm_dashboardUrl(),
				get_option('w2gm_approval_notification'))));

				w2gm_mail($author->user_email, $subject, $body);
			}
		}
	}
	
	public function post_status_on_activation($status, $listing) {
		$is_moderation = get_post_meta($listing->post->ID, '_requires_moderation', true);
		$is_approved = get_post_meta($listing->post->ID, '_listing_approved', true);
		if (!$is_moderation || ($is_moderation && $is_approved)) {
			return 'publish';
		} elseif ($is_moderation && !$is_approved) {
			return 'pending';
		}
		return $status;
	}

	public function enqueue_login_scripts_styles() {
		global $action;
		$action = 'login';
		do_action('login_enqueue_scripts');
		do_action('login_head');
	}
}

function w2gm_install_fsubmit() {
	add_option('w2gm_fsubmit_login_mode', 1);
	add_option('w2gm_fsubmit_button', 1);
	add_option('w2gm_hide_admin_bar', 0);
	add_option('w2gm_newuser_notification', 'Hello [author],
your listing "[listing]" was successfully submitted.

You may manage your listing using following credentials:
login: [login]
password: [password]');
	add_option('w2gm_newlisting_admin_notification', 'Hello,
user [user] created new listing "[listing]".
	
You may manage this listing at
[link]');
	add_option('w2gm_allow_edit_profile', 1);
	add_option('w2gm_enable_frontend_translations', 1);
	add_option('w2gm_enable_tags', 1);
	add_option('w2gm_tospage', "0");
	add_option('w2gm_submit_login_page', "0");
	add_option('w2gm_dashboard_login_page', "0");
	
	w2gm_upgrade_fsubmit('1.2.0');
	w2gm_upgrade_fsubmit('2.0.0');
	
	if (
		get_option('w2gm_newuser_notification') &&
		get_option('w2gm_claim_notification') &&
		get_option('w2gm_claim_approval_notification') &&
		get_option('w2gm_newlisting_admin_notification') &&
		get_option('w2gm_approval_notification') &&
		get_option('w2gm_claim_decline_notification') &&
		get_option('w2gm_editlisting_admin_notification')
	) {
		add_option('w2gm_installed_fsubmit', 1);
	}
}

function w2gm_upgrade_fsubmit($new_version) {
	if ($new_version == '1.2.0') {
		add_option('w2gm_approval_notification', 'Hello [author],
	
your listing "[listing]" was successfully approved.
	
Now you may manage your listing at the dashboard
[link]');
	}
	
	if ($new_version == '2.0.0') {
		add_option('w2gm_fsubmit_moderation', 0);
		add_option('w2gm_fsubmit_edit_moderation', 0);
		add_option('w2gm_claim_functionality', 0);
		add_option('w2gm_claim_approval', 1);
		add_option('w2gm_after_claim', 'active');
		add_option('w2gm_hide_claim_contact_form', 0);
		add_option('w2gm_hide_claim_metabox', 0);
		add_option('w2gm_claim_notification', 'Hello [author],
		
your listing "[listing]" was claimed by [claimer].
		
You may approve or reject this claim at
[link]
		
[message]');
		add_option('w2gm_claim_approval_notification', 'Hello [claimer],
		
congratulations, your claim for listing "[listing]" was successfully approved.
		
Now you may manage your listing at the dashboard
[link]');
		add_option('w2gm_claim_decline_notification', 'Hello [claimer],
		
your claim for listing "[listing]" was declined.');
		add_option('w2gm_editlisting_admin_notification', 'Hello,
		
user [user] modified listing "[listing]". Now it is pending review.
		
You may manage this listing at
[link]');
	}
}

global $w2gm_fsubmit_instance;

$w2gm_fsubmit_instance = new w2gm_fsubmit_plugin();
$w2gm_fsubmit_instance->init();

?>
