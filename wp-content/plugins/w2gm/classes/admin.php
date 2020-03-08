<?php

class w2gm_admin {

	public function __construct() {
		global $w2gm_instance;

		add_action('admin_menu', array($this, 'menu'));

		$w2gm_instance->settings_manager = new w2gm_settings_manager;

		$w2gm_instance->listings_manager = new w2gm_listings_manager;
		
		$w2gm_instance->maps_manager = new w2gm_maps_manager;

		$w2gm_instance->locations_manager = new w2gm_locations_manager;

		$w2gm_instance->locations_levels_manager = new w2gm_locations_levels_manager;

		$w2gm_instance->categories_manager = new w2gm_categories_manager;

		$w2gm_instance->content_fields_manager = new w2gm_content_fields_manager;

		$w2gm_instance->media_manager = new w2gm_media_manager;

		$w2gm_instance->csv_manager = new w2gm_csv_manager;
		
		$w2gm_instance->comments_manager = new w2gm_comments_manager;
		
		$w2gm_instance->demo_data_manager = new w2gm_demo_data_manager;
		
		// remove in free version
		add_filter('w2gm_build_settings', array($this, 'addAddonsSettings'));

		/* add_action('admin_menu', array($this, 'addChooseLevelPage'));
		add_action('load-post-new.php', array($this, 'handleLevel')); */

		// hide some meta-blocks when create/edit posts
		add_action('admin_init', array($this, 'hideMetaBoxes'));
		add_filter('default_hidden_meta_boxes', array($this, 'showAuthorMetaBox'), 10, 2);

		// adapted for Relevanssi
		//add_action('admin_init', array($this, 'relevanssi_add_disable_shortcodes'));
		
		add_action('admin_head-post-new.php', array($this, 'hidePreviewButton'));
		
		add_filter('post_row_actions', array($this, 'removeQuickEdit'), 10, 2);
		add_filter('quick_edit_show_taxonomy', array($this, 'removeQuickEditTax'), 10, 2);

		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts_styles'), 0);
		add_action('admin_print_scripts', array($w2gm_instance, 'dequeue_maps_googleapis'), 1000);

		add_action('admin_notices', 'w2gm_renderMessages');
		
		add_filter('admin_body_class', array($this, 'addBodyClasses'));

		add_action('wp_ajax_w2gm_generate_color_palette', array($this, 'generate_color_palette'));
		add_action('wp_ajax_nopriv_w2gm_generate_color_palette', array($this, 'generate_color_palette'));
		add_action('wp_ajax_w2gm_get_jqueryui_theme', array($this, 'get_jqueryui_theme'));
		add_action('wp_ajax_nopriv_w2gm_get_jqueryui_theme', array($this, 'get_jqueryui_theme'));
		add_action('vp_w2gm_option_before_ajax_save', array($this, 'remove_colorpicker_cookie'));
		add_action('wp_footer', array($this, 'render_palette_picker'));
	}

	public function menu() {
		if (defined('W2GM_DEMO') && W2GM_DEMO) {
			$capability = 'publish_posts';
		} else {
			$capability = 'manage_options';
		}

		add_menu_page(__("Maps settings", "W2GM"),
			__('Maps Admin', 'W2GM'),
			$capability,
			'w2gm_settings',
			null,
			W2GM_RESOURCES_URL . 'images/menuicon.png'
		);
		add_submenu_page(
			'w2gm_settings',
			__("Maps settings", "W2GM"),
			__("Maps settings", "W2GM"),
			$capability,
			'w2gm_settings',
			null
		);

		add_submenu_page(
			null,
			__("Maps Debug", "W2GM"),
			__("Maps Debug", "W2GM"),
			$capability,
			'w2gm_debug',
			array($this, 'debug')
		);
		add_submenu_page(
			null,
			__("Maps Reset", "W2GM"),
			__("Maps Reset", "W2GM"),
			'manage_options',
			'w2gm_reset',
			array($this, 'reset')
		);
	}

	public function debug() {
		global $w2gm_instance, $wpdb;
		
		$w2gm_locationGeoname = new w2gm_locationGeoname();
		$geolocation_response = $w2gm_locationGeoname->geocodeRequest('1600 Amphitheatre Parkway Mountain View, CA 94043', 'test');

		$settings = $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE 'w2gm_%'", ARRAY_A);

		w2gm_renderTemplate('debug.tpl.php', array(
			'rewrite_rules' => get_option('rewrite_rules'),
			'geolocation_response' => $geolocation_response,
			'settings' => $settings,
			'levels' => $w2gm_instance->levels,
			'content_fields' => $w2gm_instance->content_fields,
		));
	}

	public function reset() {
		global $w2gm_instance, $wpdb;
		
		if (isset($_GET['reset']) && ($_GET['reset'] == 'settings' || $_GET['reset'] == 'settings_tables')) {
			if ($wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'w2gm_%'") !== false) {
				delete_option('vpt_option');
				w2gm_save_dynamic_css();
				w2gm_addMessage('All maps settings were deleted!');
			}
		}
		if (isset($_GET['reset']) && $_GET['reset'] == 'settings_tables') {
			$wpdb->query("DROP TABLE IF EXISTS $wpdb->w2gm_content_fields_groups");
			$wpdb->query("DROP TABLE IF EXISTS $wpdb->w2gm_content_fields");
			$wpdb->query("DROP TABLE IF EXISTS $wpdb->w2gm_locations_levels");
			$wpdb->query("DROP TABLE IF EXISTS $wpdb->w2gm_locations_relationships");
			w2gm_addMessage('W2GM database tables were dropped!');
		}
		w2gm_renderTemplate('reset.tpl.php');
	}
	
	public function hideMetaBoxes() {
		global $post, $pagenow;

		if (($pagenow == 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] == W2GM_POST_TYPE) || ($pagenow == 'post.php' && $post && $post->post_type == W2GM_POST_TYPE)) {
			$user_id = get_current_user_id();
			update_user_meta($user_id, 'metaboxhidden_' . W2GM_POST_TYPE, array('trackbacksdiv', 'commentstatusdiv', 'postcustom'));
		}
	}
	
	public function showAuthorMetaBox($hidden, $screen) {
		if ($screen->post_type == W2GM_POST_TYPE) {
			if ($key = array_search('authordiv', $hidden)) {
				unset($hidden[$key]);
			}
		}
	
		return $hidden;
	}

	public function hidePreviewButton() {
		global $post_type;
    	if ($post_type == W2GM_POST_TYPE)
    		echo '<style type="text/css">#preview-action {display: none;}</style>';
	}

	public function removeQuickEdit($actions, $post) {
		if ($post->post_type == W2GM_POST_TYPE) {
			unset($actions['inline hide-if-no-js']);
			unset($actions['view']);
		}
		return $actions;
	}
	
	public function addBodyClasses($classes) {
		return "$classes w2gm-body";
	}

	public function removeQuickEditTax($show_in_quick_edit, $taxonomy_name) {
		if ($taxonomy_name == W2GM_CATEGORIES_TAX || $taxonomy_name == W2GM_LOCATIONS_TAX)
			$show_in_quick_edit = false;
		
		return $show_in_quick_edit;
	}
	
	public function addAddonsSettings($options) {
		$options['template']['menus']['general']['controls'] = array_merge(
				array('addons' => array(
					'type' => 'section',
					'title' => __('Addons', 'W2GM'),
					'description' => __('Refresh this page after switch on/off any addon.', 'W2GM'),
					'fields' => array(
					 	array(
							'type' => 'toggle',
							'name' => 'w2gm_fsubmit_addon',
							'label' => __('Frontend submission & dashboard addon', 'W2GM'),
					 		'description' => __('Allow users to submit new listings at the frontend side of your site, also provides users dashboard functionality.', 'W2GM'),
							'default' => get_option('w2gm_fsubmit_addon'),
						),
					 	array(
							'type' => 'toggle',
							'name' => 'w2gm_ratings_addon',
							'label' => __('Ratings addon', 'W2GM'),
					 		'description' => __('Ability to place ratings for listings, then manage these ratings by listings owners.', 'W2GM'),
							'default' => get_option('w2gm_ratings_addon'),
						),
					),
				)),
				$options['template']['menus']['general']['controls']
		);
		
		return $options;
	}
	
	public function admin_enqueue_scripts_styles($hook) {
		global $w2gm_instance;
		
		// include admin.css, rtl.css, bootstrap, custom.css and datepicker files in admin,
		// also in customizer and required for VC plugin, SiteOrigin plugin and widgets
		if (
			w2gm_isMapsPageInAdmin() ||
			is_customize_preview() ||
			$hook == "widgets.php" ||
			get_post_meta(get_the_ID(), '_wpb_vc_js_status', true)
		) {
			wp_enqueue_script('jquery-ui-datepicker');
			wp_register_style('w2gm-jquery-ui-style', W2GM_RESOURCES_URL . 'css/jquery-ui/themes/smoothness/jquery-ui.css');
			wp_enqueue_style('w2gm-jquery-ui-style');
			if ($i18n_file = w2gm_getDatePickerLangFile(get_locale())) {
				wp_register_script('datepicker-i18n', $i18n_file, array('jquery-ui-datepicker'));
				wp_enqueue_script('datepicker-i18n');
			}
			
			if (is_customize_preview())
				$this->enqueue_global_vars();
			else
				add_action('admin_head', array($this, 'enqueue_global_vars'));
			
			wp_register_style('w2gm_bootstrap', W2GM_RESOURCES_URL . 'css/bootstrap.css', array(), W2GM_VERSION);
			wp_register_style('w2gm_admin', W2GM_RESOURCES_URL . 'css/admin.css', array(), W2GM_VERSION);
			if (function_exists('is_rtl') && is_rtl()) {
				wp_register_style('w2gm_admin_rtl', W2GM_RESOURCES_URL . 'css/admin-rtl.css', array(), W2GM_VERSION);
			}
			
			if ($admin_custom = w2gm_isResource('css/admin-custom.css')) {
				wp_register_style('w2gm_admin-custom', $admin_custom, array(), W2GM_VERSION);
			}
		}
		
		if (w2gm_isMapsPageInAdmin()) {
			add_action('wp_print_scripts', array($w2gm_instance, 'dequeue_maps_googleapis'), 1000);

			wp_register_style('w2gm_font_awesome', W2GM_RESOURCES_URL . 'css/font-awesome.css', array(), W2GM_VERSION);
			wp_register_script('w2gm_js_functions', W2GM_RESOURCES_URL . 'js/js_functions.js', array('jquery'), false, true);

			wp_register_script('w2gm_categories_edit_scripts', W2GM_RESOURCES_URL . 'js/categories_icons.js', array('jquery'));
			wp_register_script('w2gm_categories_scripts', W2GM_RESOURCES_URL . 'js/manage_categories.js', array('jquery'));
			
			wp_register_script('w2gm_locations_edit_scripts', W2GM_RESOURCES_URL . 'js/locations_icons.js', array('jquery'));
			
			wp_register_style('w2gm_media_styles', W2GM_RESOURCES_URL . 'lightbox/css/lightbox.css', array(), W2GM_VERSION);
			wp_register_script('w2gm_media_scripts_lightbox', W2GM_RESOURCES_URL . 'lightbox/js/lightbox.min.js', array('jquery'));
			
			wp_localize_script(
				'w2gm_js_functions',
				'w2gm_maps_callback',
				array(
						'callback' => 'w2gm_load_maps_api_backend'
				)
			);
			
			wp_enqueue_script('jquery-ui-selectmenu');
			wp_enqueue_script('jquery-ui-autocomplete');
		}
		
		wp_enqueue_style('w2gm_bootstrap');
		wp_enqueue_style('w2gm_font_awesome');
		wp_enqueue_style('w2gm_admin');
		wp_enqueue_style('w2gm_admin_rtl');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('w2gm_js_functions');
		wp_enqueue_style('w2gm_admin-custom');
		
		if (w2gm_isMapsPageInAdmin()) {
			wp_register_script('w2gm_google_maps', W2GM_RESOURCES_URL . 'js/google_maps.js', array('jquery'), W2GM_VERSION, true);
			wp_enqueue_script('w2gm_google_maps');
		}
	}

	public function enqueue_global_vars() {
		// adapted for WPML
		global $sitepress;
		if (function_exists('wpml_object_id_filter') && $sitepress) {
			$ajaxurl = admin_url('admin-ajax.php?lang=' .  $sitepress->get_current_language());
		} else {
			$ajaxurl = admin_url('admin-ajax.php');
		}

		echo '
<script>
';
		echo 'var w2gm_js_objects = ' . json_encode(
				array(
						'ajaxurl' => $ajaxurl,
						'is_rtl' => (int)is_rtl(),
						'fields_in_categories' => array(),
				)
		) . ';
';

		echo 'var w2gm_maps_objects = ' . json_encode(
				array(
						'notinclude_maps_api' => ((defined('W2GM_NOTINCLUDE_MAPS_API') && W2GM_NOTINCLUDE_MAPS_API) ? 1 : 0),
						'google_api_key' => get_option('w2gm_google_api_key'),
						'map_markers_type' => get_option('w2gm_map_markers_type'),
						'default_marker_color' => get_option('w2gm_default_marker_color'),
						'default_marker_icon' => get_option('w2gm_default_marker_icon'),
						'global_map_icons_path' => W2GM_MAP_ICONS_URL,
						'marker_image_width' => (int)get_option('w2gm_map_marker_width'),
						'marker_image_height' => (int)get_option('w2gm_map_marker_height'),
						'marker_image_anchor_x' => (int)get_option('w2gm_map_marker_anchor_x'),
						'marker_image_anchor_y' => (int)get_option('w2gm_map_marker_anchor_y'),
						'default_geocoding_location' => get_option('w2gm_default_geocoding_location'),
						'map_style' => w2gm_getSelectedMapStyle(),
						'address_autocomplete' => (int)get_option('w2gm_address_autocomplete'),
						'address_autocomplete_code' => get_option('w2gm_address_autocomplete_code'),
						'enable_my_location_button' => (int)get_option('w2gm_address_geocode'),
				)
		) . ';
';
		echo '</script>
';
	}

	public function generate_color_palette() {
		ob_start();
		include W2GM_PATH . '/classes/customization/dynamic_css.php';
		$dynamic_css = ob_get_contents();
		ob_get_clean();

		echo $dynamic_css;
		die();
	}

	public function get_jqueryui_theme() {
		global $w2gm_color_schemes;

		if (isset($_COOKIE['w2gm_compare_palettes']) && get_option('w2gm_compare_palettes')) {
			$scheme = $_COOKIE['w2gm_compare_palettes'];
			if ($scheme && isset($w2gm_color_schemes[$scheme]['w2gm_jquery_ui_schemas']))
				echo '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/' . $w2gm_color_schemes[$scheme]['w2gm_jquery_ui_schemas'] . '/jquery-ui.css';
		}
		die();
	}
	
	public function remove_colorpicker_cookie($opt) {
		if (isset($_COOKIE['w2gm_compare_palettes'])) {
			unset($_COOKIE['w2gm_compare_palettes']);
			setcookie('w2gm_compare_palettes', null, -1, '/');
		}
	}

	public function render_palette_picker() {
		global $w2gm_instance;

		if (!empty($w2gm_instance->frontend_controllers)) {
			if ((get_option('w2gm_compare_palettes') && current_user_can('manage_options')) || (defined('W2GM_DEMO') && W2GM_DEMO)) {
				w2gm_renderTemplate('color_picker/color_picker_panel.tpl.php');
			}
		}
	}
}
?>