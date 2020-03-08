<?php
/*
Plugin Name: Google Maps locator plugin
Plugin URI: http://www.salephpscripts.com/wordpress_maps/
Description: Build powerful, searchable and responsive Google Maps with markers and insert them on pages in some seconds.
Version: 2.2.6
Author: salephpscripts.com
Author URI: http://www.salephpscripts.com
*/

define('W2GM_VERSION', '2.2.6');

define('W2GM_PATH', plugin_dir_path(__FILE__));
define('W2GM_URL', plugins_url('/', __FILE__));

define('W2GM_TEMPLATES_PATH', W2GM_PATH . 'templates/');

define('W2GM_RESOURCES_PATH', W2GM_PATH . 'resources/');
define('W2GM_RESOURCES_URL', W2GM_URL . 'resources/');

define('W2GM_POST_TYPE', 'w2gm_listing');
define('W2GM_MAP_TYPE', 'w2gm_map');
define('W2GM_CATEGORIES_TAX', 'w2gm-category');
define('W2GM_LOCATIONS_TAX', 'w2gm-location');
define('W2GM_TAGS_TAX', 'w2gm-tag');

include_once W2GM_PATH . 'install.php';
include_once W2GM_PATH . 'classes/admin.php';
include_once W2GM_PATH . 'classes/form_validation.php';
include_once W2GM_PATH . 'classes/listings/listings_manager.php';
include_once W2GM_PATH . 'classes/listings/listing.php';
include_once W2GM_PATH . 'classes/categories_manager.php';
include_once W2GM_PATH . 'classes/media_manager.php';
include_once W2GM_PATH . 'classes/comments_manager.php';
include_once W2GM_PATH . 'classes/content_fields/content_fields_manager.php';
include_once W2GM_PATH . 'classes/content_fields/content_fields.php';
include_once W2GM_PATH . 'classes/locations/locations_manager.php';
include_once W2GM_PATH . 'classes/locations/locations_levels_manager.php';
include_once W2GM_PATH . 'classes/locations/locations_levels.php';
include_once W2GM_PATH . 'classes/locations/location.php';
include_once W2GM_PATH . 'classes/levels/levels.php';
include_once W2GM_PATH . 'classes/demo_data.php';
include_once W2GM_PATH . 'classes/frontend_controller.php';
include_once W2GM_PATH . 'classes/shortcodes/map_controller.php';
include_once W2GM_PATH . 'classes/shortcodes/search_controller.php';
include_once W2GM_PATH . 'classes/ajax_controller.php';
include_once W2GM_PATH . 'vafpress-framework/bootstrap.php';
include_once W2GM_PATH . 'classes/settings_manager.php';
include_once W2GM_PATH . 'classes/maps/maps.php';
include_once W2GM_PATH . 'classes/maps/maps_manager.php';
include_once W2GM_PATH . 'classes/widgets/widget.php'; // remove in free version
include_once W2GM_PATH . 'classes/widgets/search.php'; // remove in free version
include_once W2GM_PATH . 'classes/widgets/map.php'; // remove in free version
include_once W2GM_PATH . 'classes/csv_manager.php'; 
include_once W2GM_PATH . 'classes/location_geoname.php';
include_once W2GM_PATH . 'classes/search_form.php';
include_once W2GM_PATH . 'classes/search_map_form.php';
include_once W2GM_PATH . 'classes/search_fields/search_fields.php';
include_once W2GM_PATH . 'classes/updater.php'; // remove in free version
include_once W2GM_PATH . 'classes/frontpanel_buttons.php';
include_once W2GM_PATH . 'functions.php';
include_once W2GM_PATH . 'functions_ui.php';
include_once W2GM_PATH . 'classes/maps/google_maps_styles.php';
include_once W2GM_PATH . 'vc.php'; // remove in free version
include_once W2GM_PATH . 'classes/customization/color_schemes.php';

// Categories icons constant
if ($custom_dir = w2gm_isCustomResourceDir('images/categories_icons/')) {
	define('W2GM_CATEGORIES_ICONS_PATH', $custom_dir);
	define('W2GM_CATEGORIES_ICONS_URL', w2gm_getCustomResourceDirURL('images/categories_icons/'));
} else {
	define('W2GM_CATEGORIES_ICONS_PATH', W2GM_RESOURCES_PATH . 'images/categories_icons/');
	define('W2GM_CATEGORIES_ICONS_URL', W2GM_RESOURCES_URL . 'images/categories_icons/');
}

// Locations icons constant
if ($custom_dir = w2gm_isCustomResourceDir('images/locations_icons/')) {
	define('W2GM_LOCATION_ICONS_PATH', $custom_dir);
	define('W2GM_LOCATIONS_ICONS_URL', w2gm_getCustomResourceDirURL('images/locations_icons/'));
} else {
	define('W2GM_LOCATION_ICONS_PATH', W2GM_RESOURCES_PATH . 'images/locations_icons/');
	define('W2GM_LOCATIONS_ICONS_URL', W2GM_RESOURCES_URL . 'images/locations_icons/');
}

// Map Markers Icons Path
if ($custom_dir = w2gm_isCustomResourceDir('images/map_icons/')) {
	define('W2GM_MAP_ICONS_PATH', $custom_dir);
	define('W2GM_MAP_ICONS_URL', w2gm_getCustomResourceDirURL('images/map_icons/'));
} else {
	define('W2GM_MAP_ICONS_PATH', W2GM_RESOURCES_PATH . 'images/map_icons/');
	define('W2GM_MAP_ICONS_URL', W2GM_RESOURCES_URL . 'images/map_icons/');
}

global $w2gm_instance;
global $w2gm_messages;

/*
 * There are 2 types of shortcodes in the system:
 1. those process as simple wordpress shortcodes
 2. require initialization on 'wp' hook
 
 */
global $w2gm_shortcodes, $w2gm_shortcodes_init;
$w2gm_shortcodes = array(
		'webmap' => 'w2gm_map_controller', // remove in free version
		'webmap-search' => 'w2gm_search_controller', // remove in free version
);
$w2gm_shortcodes_init = array();

class w2gm_plugin {
	public $admin;
	public $listings_manager;
	public $comments_manager;
	public $maps_manager;
	public $locations_manager;
	public $locations_levels_manager;
	public $categories_manager;
	public $content_fields_manager;
	public $media_manager;
	public $settings_manager;
	public $demo_data_manager;
	public $levels_manager;
	public $csv_manager;
	public $updater; // remove in free version

	public $current_listing; // this is object of listing under edition right now
	public $levels;
	public $locations_levels;
	public $content_fields;
	public $search_fields;
	public $ajax_controller;
	public $index_page_id;
	public $index_page_slug;
	public $index_page_url;
	public $index_pages_all = array();
	public $listing_pages_all = array();
	public $original_index_page_id;
	public $listing_page_id;
	public $listing_page_slug;
	public $listing_page_url;
	public $frontend_controllers = array();
	public $_frontend_controllers = array(); // this duplicate property needed because we unset each controller when we render shortcodes, but WP doesn't really know which shortcode already was processed
	public $action;
	
	public $radius_values_array = array();
	
	public $order_by_date = false; // special flag, used to display or hide sticky pin

	public function __construct() {
		register_activation_hook(__FILE__, array($this, 'activation'));
		register_deactivation_hook(__FILE__, array($this, 'deactivation'));
	}
	
	public function activation() {
		global $wp_version;

		if (version_compare($wp_version, '3.6', '<')) {
			deactivate_plugins(basename(__FILE__)); // Deactivate ourself
			wp_die("Sorry, but you can't run this plugin on current WordPress version, it requires WordPress v3.6 or higher.");
		}
		flush_rewrite_rules();
		
		wp_schedule_event(current_time('timestamp'), 'hourly', 'scheduled_events');
	}

	public function deactivation() {
		flush_rewrite_rules();

		wp_clear_scheduled_hook('scheduled_events');
	}
	
	public function init() {
		global $w2gm_instance, $w2gm_shortcodes, $w2gm_google_maps_styles, $wpdb;

		if (isset($_REQUEST['w2gm_action'])) {
			$this->action = $_REQUEST['w2gm_action'];
		}

		add_action('plugins_loaded', array($this, 'load_textdomains'));

		if (!isset($wpdb->w2gm_content_fields))
			$wpdb->w2gm_content_fields = $wpdb->prefix . 'w2gm_content_fields';
		if (!isset($wpdb->w2gm_content_fields_groups))
			$wpdb->w2gm_content_fields_groups = $wpdb->prefix . 'w2gm_content_fields_groups';
		if (!isset($wpdb->w2gm_locations_levels))
			$wpdb->w2gm_locations_levels = $wpdb->prefix . 'w2gm_locations_levels';
		if (!isset($wpdb->w2gm_locations_relationships))
			$wpdb->w2gm_locations_relationships = $wpdb->prefix . 'w2gm_locations_relationships';

		add_action('scheduled_events', array($this, 'suspend_expired_listings'));
		
		foreach ($w2gm_shortcodes AS $shortcode=>$function) {
			add_shortcode($shortcode, array($this, 'renderShortcode'));
		}
		
		add_action('init', 'w2gm_sessionStart');
		add_action('after_setup_theme', array($this, 'register_post_type'));
		add_action('wp', array($this, 'checkMainShortcode'), 1);
		add_filter('body_class', array($this, 'addBodyClasses'));

		add_action('wp', array($this, 'loadFrontendControllers'), 1);

		if (!get_option('w2gm_installed_maps') || get_option('w2gm_installed_maps_version') != W2GM_VERSION) {
			// load classes ONLY after locator was fully installed, otherwise it can not get content fields, e.t.c. from the database
			if (get_option('w2gm_installed_maps')) {
				$this->loadClasses();
			}

			add_action('init', 'w2gm_install_maps', 0);
		} else {
			$this->loadClasses();
		}

		// adapted for Polylang
		add_action('init', array($this, 'pll_setup'));

		add_filter('comments_open', array($this, 'filter_comment_status'), 100, 2);
		
		add_filter('no_texturize_shortcodes', array($this, 'w2gm_no_texturize'));

		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles_custom'), 9999);
		add_action('wp_enqueue_scripts', array($this, 'enqueue_dynamic_css'));
		
		add_filter('wpseo_sitemap_post_type_archive_link', array($this, 'exclude_post_type_archive_link'), 10, 2);
		
		add_filter('w2gm_dequeue_maps_googleapis', array($this, 'divi_not_dequeue_maps_api'));
		
		add_filter('plugin_row_meta', array($this, 'plugin_row_meta'), 10, 2);
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugin_action_links'));
		
		$w2gm_google_maps_styles = apply_filters('w2gm_google_maps_styles', $w2gm_google_maps_styles);
	}

	public function load_textdomains() {
		load_plugin_textdomain('W2GM', '', dirname(plugin_basename( __FILE__ )) . '/languages');
	}
	
	public function loadClasses() {
		$this->locations_levels = new w2gm_locations_levels;
		$this->content_fields = new w2gm_content_fields;
		$this->search_fields = new w2gm_search_fields;
		$this->ajax_controller = new w2gm_ajax_controller;
		$this->admin = new w2gm_admin;
		// remove in free version
		$this->updater = new w2gm_updater(__FILE__, get_option('w2gm_purchase_code'), get_option('w2gm_access_token'));
	}

	public function w2gm_no_texturize($shortcodes) {
		global $w2gm_shortcodes;
		
		foreach ($w2gm_shortcodes AS $shortcode=>$function)
			$shortcodes[] = $shortcode;
		
		return $shortcodes;
	}

	public function renderShortcode() {
		// Some "genial" themes and plugins can load our shortcodes at the admin part, breaking some important functionality
		if (!is_admin()) {
			global $w2gm_shortcodes;
	
			// remove content filters in order not to break the layout of page
			$filters_to_remove = array(
					'wpautop',
					'wptexturize',
					'shortcode_unautop',
					'convert_chars',
					'prepend_attachment',
					'convert_smilies',
			);
			foreach ($filters_to_remove AS $filter) {
				while (($priority = has_filter('the_content', $filter)) !== false) {
					remove_filter('the_content', $filter, $priority);
				}
			}
	
			$attrs = func_get_args();
			$shortcode = $attrs[2];
	
			$filters_where_not_to_display = array(
					'wp_head',
					'init',
					'wp',
					'edit_attachment',
			);
			
			//var_dump(current_filter());
			if (isset($this->_frontend_controllers[$shortcode]) && !in_array(current_filter(), $filters_where_not_to_display)) {
				$shortcode_controllers = $this->_frontend_controllers[$shortcode];
				foreach ($shortcode_controllers AS $key=>&$controller) {
					unset($this->_frontend_controllers[$shortcode][$key]); // there are possible more than 1 same shortcodes on a page, so we have to unset which already was displayed
					if (method_exists($controller, 'display'))
						return $controller->display();
				}
			}
	
			if (isset($w2gm_shortcodes[$shortcode])) {
				$shortcode_class = $w2gm_shortcodes[$shortcode];
				if ($attrs[0] === '')
					$attrs[0] = array();
				$shortcode_instance = new $shortcode_class();
				$this->frontend_controllers[$shortcode][] = $shortcode_instance;
				$shortcode_instance->init($attrs[0], $shortcode);
	
				if (method_exists($shortcode_instance, 'display'))
					return $shortcode_instance->display();
			}
		}
	}

	public function loadFrontendControllers() {
		global $post, $wp_query;

		if ($wp_query->posts) {
			$pattern = get_shortcode_regex();
			foreach ($wp_query->posts AS $archive_post) {
				if (isset($archive_post->post_content))
					$this->loadNestedFrontendController($pattern, $archive_post->post_content);
			}
		} elseif ($post && isset($post->post_content)) {
			$pattern = get_shortcode_regex();
			$this->loadNestedFrontendController($pattern, $post->post_content);
		}
	}

	// this may be recursive function to catch nested shortcodes
	public function loadNestedFrontendController($pattern, $content) {
		global $w2gm_shortcodes_init, $w2gm_shortcodes;

		if (preg_match_all('/'.$pattern.'/s', $content, $matches) && array_key_exists(2, $matches)) {
			foreach ($matches[2] AS $key=>$shortcode) {
				if ($shortcode != 'shortcodes') {
					if (isset($w2gm_shortcodes_init[$shortcode]) && class_exists($w2gm_shortcodes_init[$shortcode])) {
						$shortcode_class = $w2gm_shortcodes_init[$shortcode];
						if (!($attrs = shortcode_parse_atts($matches[3][$key])))
							$attrs = array();
						$shortcode_instance = new $shortcode_class();
						$this->frontend_controllers[$shortcode][] = $shortcode_instance;
						$this->_frontend_controllers[$shortcode][] = $shortcode_instance;
						$shortcode_instance->init($attrs, $shortcode);
					} elseif (isset($w2gm_shortcodes[$shortcode]) && class_exists($w2gm_shortcodes[$shortcode])) {
						$shortcode_class = $w2gm_shortcodes[$shortcode];
						$this->frontend_controllers[$shortcode][] = $shortcode_class;
					}
					if ($shortcode_content = $matches[5][$key])
						$this->loadNestedFrontendController($pattern, $shortcode_content);
				}
			}
		}
	}

	public function checkMainShortcode() {
		if (!get_option('w2gm_google_api_key') && is_admin()) {
			w2gm_addMessage(sprintf(__("<b>Google Maps locator plugin</b>: Google requires mandatory Maps API key for maps created on NEW websites/domains. Please, <a href=\"http://www.salephpscripts.com/wordpress_maps/demo/documentation/#google_maps_keys\" target=\"_blank\">follow instructions</a> and enter API key on <a href=\"%s\">maps settings page</a>. Otherwise it may cause problems with Google Maps, Geocoding, addition/edition listings locations, autocomplete on addresses fields.", 'W2GM'), admin_url('admin.php?page=w2gm_settings#_advanced')));
		}
	}

	public function addBodyClasses($classes) {
		$classes[] = 'w2gm-body';
		
		return $classes;
	}

	public function register_post_type() {
		$args = array(
			'labels' => array(
				'name' => __('Maps listings', 'W2GM'),
				'singular_name' => __('Maps listing', 'W2GM'),
				'add_new' => __('Create new listing', 'W2GM'),
				'add_new_item' => __('Create new listing', 'W2GM'),
				'edit_item' => __('Edit listing', 'W2GM'),
				'new_item' => __('New listing', 'W2GM'),
				'view_item' => __('View listing', 'W2GM'),
				'search_items' => __('Search listings', 'W2GM'),
				'not_found' =>  __('No listings found', 'W2GM'),
				'not_found_in_trash' => __('No listings found in trash', 'W2GM')
			),
			'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
			'has_archive' => true,
			'show_ui' => true,
			'description' => __('Maps listings', 'W2GM'),
			'exclude_from_search' => false, // this must be false otherwise it breaks pagination for custom taxonomies
			'supports' => array('title', 'author', 'comments'),
			'menu_icon' => W2GM_RESOURCES_URL . 'images/menuicon.png',
		);
		if (get_option('w2gm_enable_description'))
			$args['supports'][] = 'editor';
		if (get_option('w2gm_enable_summary'))
			$args['supports'][] = 'excerpt';
		register_post_type(W2GM_POST_TYPE, $args);
		
		$args = array(
				'labels' => array(
						'name' => __('Google Maps', 'W2GM'),
						'singular_name' => __('Map', 'W2GM'),
						'add_new' => __('Create new map', 'W2GM'),
						'add_new_item' => __('Create new map', 'W2GM'),
						'edit_item' => __('Edit map', 'W2GM'),
						'new_item' => __('New map', 'W2GM'),
						'view_item' => __('View map', 'W2GM'),
						'search_items' => __('Search maps', 'W2GM'),
						'not_found' =>  __('No maps found', 'W2GM'),
						'not_found_in_trash' => __('No maps found in trash', 'W2GM')
				),
				'description' => __('Maps listings', 'W2GM'),
				'public' => false,
				'publicly_queryable' => true,
				'show_ui' => true,
				'exclude_from_search' => true,
				'show_in_nav_menus' => false,
				'has_archive' => false,
				'rewrite' => false,
				'supports' => array('title'),
				'menu_icon' => W2GM_RESOURCES_URL . 'images/menuicon.png',
		);
		register_post_type(W2GM_MAP_TYPE, $args);
		
		register_taxonomy(W2GM_CATEGORIES_TAX, W2GM_POST_TYPE, array(
				'hierarchical' => true,
				'has_archive' => true,
				'labels' => array(
					'name' =>  __('Listing categories', 'W2GM'),
					'menu_name' =>  __('Maps categories', 'W2GM'),
					'singular_name' => __('Category', 'W2GM'),
					'add_new_item' => __('Create category', 'W2GM'),
					'new_item_name' => __('New category', 'W2GM'),
					'edit_item' => __('Edit category', 'W2GM'),
					'view_item' => __('View category', 'W2GM'),
					'update_item' => __('Update category', 'W2GM'),
					'search_items' => __('Search categories', 'W2GM'),
				),
			)
		);
		register_taxonomy(W2GM_LOCATIONS_TAX, W2GM_POST_TYPE, array(
				'hierarchical' => true,
				'has_archive' => true,
				'labels' => array(
					'name' =>  __('Listing locations', 'W2GM'),
					'menu_name' =>  __('Maps locations', 'W2GM'),
					'singular_name' => __('Location', 'W2GM'),
					'add_new_item' => __('Create location', 'W2GM'),
					'new_item_name' => __('New location', 'W2GM'),
					'edit_item' => __('Edit location', 'W2GM'),
					'view_item' => __('View location', 'W2GM'),
					'update_item' => __('Update location', 'W2GM'),
					'search_items' => __('Search locations', 'W2GM'),
					
				),
			)
		);
		register_taxonomy(W2GM_TAGS_TAX, W2GM_POST_TYPE, array(
				'hierarchical' => false,
				'labels' => array(
					'name' =>  __('Listing tags', 'W2GM'),
					'menu_name' =>  __('Maps tags', 'W2GM'),
					'singular_name' => __('Tag', 'W2GM'),
					'add_new_item' => __('Create tag', 'W2GM'),
					'new_item_name' => __('New tag', 'W2GM'),
					'edit_item' => __('Edit tag', 'W2GM'),
					'view_item' => __('View tag', 'W2GM'),
					'update_item' => __('Update tag', 'W2GM'),
					'search_items' => __('Search tags', 'W2GM'),
				),
			)
		);
	}

	public function suspend_expired_listings() {
		global $wpdb;

		$posts_ids = $wpdb->get_col($wpdb->prepare("
				SELECT
					wp_pm1.post_id
				FROM
					{$wpdb->postmeta} AS wp_pm1
				LEFT JOIN
					{$wpdb->postmeta} AS wp_pm2 ON wp_pm1.post_id=wp_pm2.post_id
				LEFT JOIN
					{$wpdb->posts} AS wp_posts ON wp_pm1.post_id=wp_posts.ID
				WHERE
					wp_pm1.meta_key = '_expiration_date' AND
					wp_pm1.meta_value < %d AND
					wp_pm2.meta_key = '_listing_status' AND
					(wp_pm2.meta_value = 'active' OR wp_pm2.meta_value = 'stopped')
			", current_time('timestamp')));
		$listings_ids_to_suspend = $posts_ids;
		foreach ($posts_ids AS $post_id) {
			if (!get_post_meta($post_id, '_expiration_notification_sent', true) && $listing = w2gm_getListing($post_id)) {
				if (get_option('w2gm_expiration_notification')) {
					$listing_owner = get_userdata($listing->post->post_author);
			
					$subject = __('Expiration notification', 'W2GM');
			
					$body = str_replace('[listing]', $listing->title(),
							str_replace('[link]', ((get_option('w2gm_fsubmit_addon') && isset($this->dashboard_page_url) && $this->dashboard_page_url) ? w2gm_dashboardUrl(array('w2gm_action' => 'renew_listing', 'listing_id' => $post_id)) : admin_url('options.php?page=w2gm_renew&listing_id=' . $post_id)),
							get_option('w2gm_expiration_notification')));
					w2gm_mail($listing_owner->user_email, $subject, $body);
					
					add_post_meta($post_id, '_expiration_notification_sent', true);
				}
			}

			// adapted for WPML
			global $sitepress;
			if (function_exists('wpml_object_id_filter') && $sitepress) {
				$trid = $sitepress->get_element_trid($post_id, 'post_' . W2GM_POST_TYPE);
				$translations = $sitepress->get_element_translations($trid, 'post_' . W2GM_POST_TYPE, false, true);
				foreach ($translations AS $lang=>$translation) {
					$listings_ids_to_suspend[] = $translation->element_id;
				}
			} else {
				$listings_ids_to_suspend[] = $post_id;
			}
		}
		$listings_ids_to_suspend = array_unique($listings_ids_to_suspend);
		foreach ($listings_ids_to_suspend AS $listing_id) {
			update_post_meta($listing_id, '_listing_status', 'expired');
			wp_update_post(array('ID' => $listing_id, 'post_status' => 'draft')); // This needed in order terms counts were always actual
			
			$listing = w2gm_getListing($listing_id);
			
			$continue = true;
			$continue_invoke_hooks = true;
			apply_filters('w2gm_listing_renew', $continue, $listing, array(&$continue_invoke_hooks));
		}

		$posts_ids = $wpdb->get_col($wpdb->prepare("
				SELECT
					wp_pm1.post_id
				FROM
					{$wpdb->postmeta} AS wp_pm1
				LEFT JOIN
					{$wpdb->postmeta} AS wp_pm2 ON wp_pm1.post_id=wp_pm2.post_id
				LEFT JOIN
					{$wpdb->posts} AS wp_posts ON wp_pm1.post_id=wp_posts.ID
				WHERE
					wp_pm1.meta_key = '_expiration_date' AND
					wp_pm1.meta_value < %d AND
					wp_pm2.meta_key = '_listing_status' AND
					(wp_pm2.meta_value = 'active' OR wp_pm2.meta_value = 'stopped')
			", current_time('timestamp')+(get_option('w2gm_send_expiration_notification_days')*86400)));

		$listings_ids = $posts_ids;

		// adapted for WPML
		global $sitepress;
		if (function_exists('wpml_object_id_filter') && $sitepress) {
			foreach ($posts_ids AS $post_id) {
				$trid = $sitepress->get_element_trid($post_id, 'post_' . W2GM_POST_TYPE);
				$listings_ids[] = $trid;
			}
		} else {
			$listings_ids = $posts_ids;
		}

		$listings_ids = array_unique($listings_ids);
		foreach ($listings_ids AS $listing_id) {
			if (!get_post_meta($listing_id, '_preexpiration_notification_sent', true) && ($listing = w2gm_getListing($listing_id))) {
				if (get_option('w2gm_preexpiration_notification')) {
					$listing_owner = get_userdata($listing->post->post_author);

					$subject = __('Expiration notification', 'W2GM');
					
					$body = str_replace('[listing]', $listing->title(),
							str_replace('[days]', get_option('w2gm_send_expiration_notification_days'),
							str_replace('[link]', ((get_option('w2gm_fsubmit_addon') && isset($this->dashboard_page_url) && $this->dashboard_page_url) ? w2gm_dashboardUrl(array('w2gm_action' => 'renew_listing', 'listing_id' => $listing_id)) : admin_url('options.php?page=w2gm_renew&listing_id=' . $listing_id)),
							get_option('w2gm_preexpiration_notification'))));
					w2gm_mail($listing_owner->user_email, $subject, $body);
					
					add_post_meta($listing_id, '_preexpiration_notification_sent', true);
				}

				$continue_invoke_hooks = true;
				if ($listing = $this->listings_manager->loadListing($listing_id)) {
					apply_filters('w2gm_listing_renew', false, $listing, array(&$continue_invoke_hooks));
				}
			}
		}
	}
	
	function filter_comment_status($open, $post_id) {
		$post = get_post($post_id);
		if ($post->post_type == W2GM_POST_TYPE) {
			if (get_option('w2gm_listings_comments_mode') == 'enabled')
				return true;
			elseif (get_option('w2gm_listings_comments_mode') == 'disabled')
				return false;
		}

		return $open;
	}

	/**
	 * Get property by shortcode name
	 * 
	 * @param string $shortcode
	 * @param string $property if property missed - return controller object
	 * @return mixed
	 */
	public function getShortcodeProperty($shortcode, $property = false) {
		if (!isset($this->frontend_controllers[$shortcode]) || !isset($this->frontend_controllers[$shortcode][0]))
			return false;

		if ($property && !isset($this->frontend_controllers[$shortcode][0]->$property))
			return false;

		if ($property)
			return $this->frontend_controllers[$shortcode][0]->$property;
		else 
			return $this->frontend_controllers[$shortcode][0];
	}
	
	public function getShortcodeByHash($hash) {
		if (!isset($this->frontend_controllers) || !is_array($this->frontend_controllers) || empty($this->frontend_controllers))
			return false;

		foreach ($this->frontend_controllers AS $shortcodes)
			foreach ($shortcodes AS $controller)
				if (is_object($controller) && $controller->hash == $hash)
					return $controller;
	}
	
	public function getListingsShortcodeByuID($uid) {
		foreach ($this->frontend_controllers AS $shortcodes)
			foreach ($shortcodes AS $controller)
				if (is_object($controller) && get_class($controller) == 'w2gm_maps_controller' && $controller->args['uid'] == $uid)
					return $controller;
	}

	public function enqueue_scripts_styles($load_scripts_styles = false) {
		global $w2gm_enqueued;
		if ((($this->frontend_controllers || $load_scripts_styles) && !$w2gm_enqueued) || get_option('w2gm_force_include_js_css')) {
			add_action('wp_head', array($this, 'enqueue_global_vars'));
			
			wp_enqueue_script('jquery');

			wp_register_style('w2gm_bootstrap', W2GM_RESOURCES_URL . 'css/bootstrap.css', array(), W2GM_VERSION);
			wp_register_style('w2gm_frontend', W2GM_RESOURCES_URL . 'css/frontend.css', array(), W2GM_VERSION);

			if (function_exists('is_rtl') && is_rtl()) {
				wp_register_style('w2gm_frontend_rtl', W2GM_RESOURCES_URL . 'css/frontend-rtl.css', array(), W2GM_VERSION);
			}

			wp_register_style('w2gm_font_awesome', W2GM_RESOURCES_URL . 'css/font-awesome.css', array(), W2GM_VERSION);

			wp_register_script('w2gm_js_functions', W2GM_RESOURCES_URL . 'js/js_functions.js', array('jquery'), W2GM_VERSION, true);

			wp_register_script('w2gm_categories_scripts', W2GM_RESOURCES_URL . 'js/manage_categories.js', array('jquery'), false, true);

			wp_register_style('w2gm_media_styles', W2GM_RESOURCES_URL . 'lightbox/css/lightbox.css', array(), W2GM_VERSION);
			wp_register_script('w2gm_media_scripts_lightbox', W2GM_RESOURCES_URL . 'lightbox/js/lightbox.min.js', array('jquery'), false, true);
			wp_enqueue_style('w2gm_media_styles');
			wp_enqueue_script('w2gm_media_scripts_lightbox');

			// this jQuery UI version 1.10.4
			if (get_option('w2gm_jquery_ui_schemas')) $ui_theme = w2gm_get_dynamic_option('w2gm_jquery_ui_schemas'); else $ui_theme = 'smoothness';
			wp_register_style('w2gm-jquery-ui-style', W2GM_RESOURCES_URL . 'css/jquery-ui/themes/' . $ui_theme . '/jquery-ui.css');

			
			wp_register_style('w2gm_listings_slider', W2GM_RESOURCES_URL . 'css/bxslider/jquery.bxslider.css', array(), W2GM_VERSION);
			wp_enqueue_style('w2gm_listings_slider');

			wp_enqueue_style('w2gm_bootstrap');
			wp_enqueue_style('w2gm_font_awesome');
			wp_enqueue_style('w2gm_frontend');
			wp_enqueue_style('w2gm_frontend_rtl');
			
			// Include dynamic-css file only when we are not in palettes comparison mode
			if (!isset($_COOKIE['w2gm_compare_palettes']) || !get_option('w2gm_compare_palettes')) {
				// Include dynamically generated css file if this file exists
				$upload_dir = wp_upload_dir();
				$filename = trailingslashit(set_url_scheme($upload_dir['baseurl'])) . 'w2gm-plugin.css';
				$filename_dir = trailingslashit($upload_dir['basedir']) . 'w2gm-plugin.css';
				global $wp_filesystem;
				if (empty($wp_filesystem)) {
					require_once(ABSPATH .'/wp-admin/includes/file.php');
					WP_Filesystem();
				}
				if ($wp_filesystem && trim($wp_filesystem->get_contents($filename_dir))) { // if css file creation success
					wp_enqueue_style('w2gm-dynamic-css', $filename, array(), time());
				}
			}

			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('jquery-ui-selectmenu');
			wp_enqueue_script('jquery-ui-autocomplete');
			if (!get_option('w2gm_notinclude_jqueryui_css')) {
				wp_enqueue_style('w2gm-jquery-ui-style');
			}

			wp_enqueue_script('w2gm_js_functions');

			add_action('wp_print_scripts', array($this, 'dequeue_maps_googleapis'), 1000);
			wp_register_script('w2gm_google_maps', W2GM_RESOURCES_URL . 'js/google_maps.js', array('jquery'), W2GM_VERSION, true);
			wp_enqueue_script('w2gm_google_maps');
			
			wp_localize_script(
				'w2gm_js_functions',
				'w2gm_maps_callback',
				array(
						'callback' => 'w2gm_load_maps_api'
				)
			);
			
			if (get_option('w2gm_enable_recaptcha') && get_option('w2gm_recaptcha_public_key') && get_option('w2gm_recaptcha_private_key')) {
				wp_register_script('w2gm_recaptcha', '//google.com/recaptcha/api.js');
				wp_enqueue_script('w2gm_recaptcha');
			}

			$w2gm_enqueued = true;
		}
	}
	
	public function enqueue_scripts_styles_custom($load_scripts_styles = false) {
		if ((($this->frontend_controllers || $load_scripts_styles)) || get_option('w2gm_force_include_js_css')) {
			if ($frontend_custom = w2gm_isResource('css/frontend-custom.css')) {
				wp_register_style('w2gm_frontend-custom', $frontend_custom, array(), W2GM_VERSION);
				
				wp_enqueue_style('w2gm_frontend-custom');
			}
		}
	}
	
	public function dequeue_maps_googleapis() {
		$dequeue = false;
		if ((get_option('w2gm_google_api_key') && !(defined('W2GM_NOTINCLUDE_MAPS_API') && W2GM_NOTINCLUDE_MAPS_API)) && !(defined('W2GM_NOT_DEQUEUE_MAPS_API') && W2GM_NOT_DEQUEUE_MAPS_API)) {
			$dequeue = true;
		}
		
		$dequeue = apply_filters('w2gm_dequeue_maps_googleapis', $dequeue);
		
		if ($dequeue) {
			// dequeue only at the frontend or at admin locator pages
			if (!is_admin() || (is_admin() && w2gm_isMapsPageInAdmin())) {
				global $wp_scripts;
				foreach ($wp_scripts->registered AS $key=>$script) {
					if (strpos($script->src, 'maps.googleapis.com') !== false || strpos($script->src, 'maps.google.com/maps/api') !== false) {
						unset($wp_scripts->registered[$key]);
					}
				}
			}
		}
	}
	
	public function enqueue_global_vars() {
		// adapted for WPML
		global $sitepress;
		if (function_exists('wpml_object_id_filter') && $sitepress) {
			$ajaxurl = admin_url('admin-ajax.php?lang=' .  $sitepress->get_current_language());
		} else
			$ajaxurl = admin_url('admin-ajax.php');

		echo '
<script>
';
		echo 'var w2gm_controller_args_array = {};
';
		echo 'var w2gm_map_markers_attrs_array = [];
';
		echo 'var w2gm_map_markers_attrs = (function(map_id, markers_array, map_attrs) {
		this.map_id = map_id;
		this.markers_array = markers_array;
		this.map_attrs = map_attrs;
		for (var attrname in map_attrs) { this[attrname] = map_attrs[attrname]; }
		});
';
		echo 'var w2gm_js_objects = ' . json_encode(
				array(
						'ajaxurl' => $ajaxurl,
						'search_map_button_text' => __('Search on map', 'W2GM'),
						'in_favourites_icon' => 'w2gm-glyphicon-heart',
						'not_in_favourites_icon' => 'w2gm-glyphicon-heart-empty',
						'in_favourites_msg' => __('Add Bookmark', 'W2GM'),
						'not_in_favourites_msg' => __('Remove Bookmark', 'W2GM'),
						'is_rtl' => is_rtl(),
						'leave_comment' => __('Leave a comment', 'W2GM'),
						'leave_reply' => __('Leave a reply to', 'W2GM'),
						'cancel_reply' => __('Cancel reply', 'W2GM'),
						'more' => __('More', 'W2GM'),
						'less' => __('Less', 'W2GM'),
						'send_button_text' => __('Send message', 'W2GM'),
						'send_button_sending' => __('Sending...', 'W2GM'),
						'recaptcha_public_key' => ((get_option('w2gm_enable_recaptcha') && get_option('w2gm_recaptcha_public_key') && get_option('w2gm_recaptcha_private_key')) ? get_option('w2gm_recaptcha_public_key') : ''),
						'lang' => (($sitepress && get_option('w2gm_map_language_from_wpml')) ? ICL_LANGUAGE_CODE : ''),
						'fields_in_categories' => array(),
				)
		) . ';
';
			
		$map_content_fields = $this->content_fields->getMapContentFields();
		$map_content_fields_icons = array('w2gm-fa-info-circle');
		foreach ($map_content_fields AS $content_field)
			if (is_a($content_field, 'w2gm_content_field') && $content_field->icon_image)
				$map_content_fields_icons[] = $content_field->icon_image;
			else
				$map_content_fields_icons[] = '';
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
						'infowindow_width' => (int)get_option('w2gm_map_infowindow_width'),
						'infowindow_offset' => -(int)get_option('w2gm_map_infowindow_offset'),
						'infowindow_logo_width' => (int)get_option('w2gm_map_infowindow_logo_width'),
						'w2gm_map_info_window_button_readmore' => __('Read more »', 'W2GM'),
						'w2gm_map_info_window_button_directions' => __('« Directions', 'W2GM'),
						'draw_area_button' => __('Draw Area', 'W2GM'),
						'edit_area_button' => __('Edit Area', 'W2GM'),
						'apply_area_button' => __('Apply Area', 'W2GM'),
						'reload_map_button' => __('Refresh Map', 'W2GM'),
						'enable_my_location_button' => (int)get_option('w2gm_address_geocode'),
						'my_location_button' => __('My Location', 'W2GM'),
						'my_location_button_error' => __('GeoLocation service does not work on your device!', 'W2GM'),
						'w2gm_map_content_fields_icons' => $map_content_fields_icons,
						'map_style' => w2gm_getSelectedMapStyle(),
						'address_autocomplete' => (int)get_option('w2gm_address_autocomplete'),
						'address_autocomplete_code' => get_option('w2gm_address_autocomplete_code'),
						'directions_functionality' => get_option('w2gm_directions_functionality'),
				)
		) . ';
';
		echo '</script>
';
	}

	// Include dynamically generated css code if css file does not exist.
	public function enqueue_dynamic_css($load_scripts_styles = false) {
		$upload_dir = wp_upload_dir();
		$filename = trailingslashit(set_url_scheme($upload_dir['baseurl'])) . 'w2gm-plugin.css';
		$filename_dir = trailingslashit($upload_dir['basedir']) . 'w2gm-plugin.css';
		global $wp_filesystem;
		if (empty($wp_filesystem)) {
			require_once(ABSPATH .'/wp-admin/includes/file.php');
			WP_Filesystem();
		}
		if ((!$wp_filesystem || !trim($wp_filesystem->get_contents($filename_dir))) ||
			// When we are in palettes comparison mode - this will build css according to $_COOKIE['w2gm_compare_palettes']
			(isset($_COOKIE['w2gm_compare_palettes']) && get_option('w2gm_compare_palettes')))
		{
			ob_start();
			include W2GM_PATH . '/classes/customization/dynamic_css.php';
			$dynamic_css = ob_get_contents();
			ob_get_clean();
				
			wp_add_inline_style('w2gm_frontend', $dynamic_css);
		}
	}
	
	public function exclude_post_type_archive_link($archive_url, $post_type) {
		if ($post_type == W2GM_POST_TYPE) {
			return false;
		}
		
		return $archive_url;
	}
	
	public function plugin_row_meta($links, $file) {
		if (dirname(plugin_basename(__FILE__) == $file)) {
			$row_meta = array(
					'docs' => '<a href="https://www.salephpscripts.com/wordpress_maps/demo/documentation/">' . esc_html__("Documentation", "W2GM") . '</a>',
					'codecanoyn' => '<a href="https://codecanyon.net/item/web-20-google-maps-plugin-for-wordpress/14615094#item-description__changelog">' . esc_html__("Changelog", "W2GM") . '</a>',
			);
	
			return array_merge($links, $row_meta);
		}
	
		return $links;
	}
	
	public function plugin_action_links($links) {
		$action_links = array(
				'settings' => '<a href="' . admin_url('admin.php?page=w2gm_settings') . '">' . esc_html__("Settings", "W2GM") . '</a>',
		);
	
		return array_merge($action_links, $links);
	}

	// adapted for Polylang
	public function pll_setup() {
		if (defined("POLYLANG_VERSION")) {
			add_filter('post_type_link', array($this, 'pll_stop_add_lang_to_url_post'), 0, 2);
			add_filter('post_type_link', array($this, 'pll_start_add_lang_to_url_post'), 100, 2);
			add_filter('term_link', array($this, 'pll_stop_add_lang_to_url_term'), 0, 3);
			add_filter('term_link', array($this, 'pll_start_add_lang_to_url_term'), 100, 3);
			add_filter('rewrite_rules_array', array($this, 'pll_rewrite_rules'));
		}
	}
	public function pll_stop_add_lang_to_url_post($permalink, $post) {
		$this->pll_force_lang = false;
		if ($post->post_type == W2GM_POST_TYPE) {
			global $polylang;
			if (isset($polylang->links->links_model->model->options['force_lang']) && $polylang->links->links_model->model->options['force_lang']) {
				$this->pll_force_lang = true;
				$polylang->links->links_model->model->options['force_lang'] = 0;
			}
		}
		return $permalink;
	}
	public function pll_start_add_lang_to_url_post($permalink, $post) {
		if ($this->pll_force_lang && $post->post_type == W2GM_POST_TYPE) {
			global $polylang;
			$polylang->links->links_model->model->options['force_lang'] = 1;
		}
		return $permalink;
	}
	public function pll_stop_add_lang_to_url_term($permalink, $term, $tax) {
		$this->pll_force_lang = false;
		if ($tax == W2GM_CATEGORIES_TAX || $tax == W2GM_LOCATIONS_TAX || $tax == W2GM_TAGS_TAX) {
			global $polylang;
			if (isset($polylang->links->links_model->model->options['force_lang']) && $polylang->links->links_model->model->options['force_lang']) {
				$this->pll_force_lang = true;
				$polylang->links->links_model->model->options['force_lang'] = 0;
			}
		}
		return $permalink;
	}
	public function pll_start_add_lang_to_url_term($permalink, $term, $tax) {
		if ($this->pll_force_lang && ($tax == W2GM_CATEGORIES_TAX || $tax == W2GM_LOCATIONS_TAX || $tax == W2GM_TAGS_TAX)) {
			global $polylang;
			$polylang->links->links_model->model->options['force_lang'] = 1;
		}
		return $permalink;
	}
	public function pll_rewrite_rules($rules) {
		global $polylang, $wp_current_filter;
		$wp_current_filter[] = 'w2gm_listing';
		return $polylang->links->links_model->rewrite_rules($this->buildRules()) + $rules;
	}
	
	// when dequeue google maps api from divi VISUAL builder - it breaks the page and does not complete loading
	public function divi_not_dequeue_maps_api($dequeue) {
		if (get_option('et_enqueue_google_maps_script')) {
			return false;
		}
	}
}

$w2gm_instance = new w2gm_plugin();
$w2gm_instance->init();

if (get_option('w2gm_fsubmit_addon'))
	include_once W2GM_PATH . 'addons/w2gm_fsubmit/w2gm_fsubmit.php';
if (get_option('w2gm_ratings_addon'))
	include_once W2GM_PATH . 'addons/w2gm_ratings/w2gm_ratings.php';

?>