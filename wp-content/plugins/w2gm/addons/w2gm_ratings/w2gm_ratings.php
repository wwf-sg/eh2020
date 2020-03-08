<?php

define('W2GM_RATINGS_PATH', plugin_dir_path(__FILE__));

function w2gm_ratings_loadPaths() {
	define('W2GM_RATINGS_TEMPLATES_PATH',  W2GM_RATINGS_PATH . 'templates/');
	define('W2GM_RATINGS_RESOURCES_URL', plugins_url('/', __FILE__) . 'resources/');
}
add_action('init', 'w2gm_ratings_loadPaths', 0);

define('W2GM_RATING_PREFIX', '_rating_');
define('W2GM_AVG_RATING_KEY', '_avg_rating');

include_once W2GM_RATINGS_PATH . 'classes/ratings.php';

class w2gm_ratings_plugin {

	public function __construct() {
		register_activation_hook(__FILE__, array($this, 'activation'));
	}
	
	public function activation() {
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		if (!defined('W2GM_VERSION')) {
			deactivate_plugins(basename(__FILE__)); // Deactivate ourself
			wp_die("Google Maps locator plugin required.");
		}
	}

	public function init() {
		global $w2gm_instance;
		
		if (!get_option('w2gm_installed_ratings'))
			//w2gm_install_ratings();
			add_action('init', 'w2gm_install_ratings', 0);
		add_action('w2gm_version_upgrade', 'w2gm_upgrade_ratings');

		add_filter('w2gm_build_settings', array($this, 'plugin_settings'));
		
		add_action('wp_ajax_w2gm_save_rating', array($this, 'save_rating'));
		add_action('wp_ajax_nopriv_w2gm_save_rating', array($this, 'save_rating'));
		
		add_action('wp_ajax_w2gm_flush_ratings', array($this, 'flush_ratings'));
		add_action('wp_ajax_nopriv_w2gm_flush_ratings', array($this, 'flush_ratings'));
		
		add_filter('w2gm_listing_loading', array($this, 'load_listing'));
		add_filter('w2gm_listing_map_loading', array($this, 'load_listing'));

		add_filter('comment_text', array($this, 'rating_in_comment'), 10000);
		
		//add_action('w2gm_listing_pre_logo_wrap_html', array($this, 'render_rating'));
		add_action('w2gm_listing_title_html', array($this, 'render_rating'), 10, 2);
		add_filter('w2gm_listing_title_search_html', array($this, 'get_rating_stars'), 10, 2);
		add_action('w2gm_dashboard_listing_title', array($this, 'render_rating_dashboard'));

		add_filter('w2gm_map_info_window_fields', array($this, 'add_rating_field_to_map_window'));
		add_filter('w2gm_map_info_window_fields_values', array($this, 'render_rating_in_map_window'), 10, 3);
		
		add_filter('w2gm_default_orderby_options', array($this, 'order_by_rating_option'));
		add_filter('w2gm_order_args', array($this, 'order_by_rating_args'), 101, 3);
		
		add_action('add_meta_boxes', array($this, 'addRatingsMetabox'), 301);

		add_action('w2gm_edit_listing_metaboxes_post', array($this, 'frontendRatingsMetabox'));

		add_filter('manage_'.W2GM_POST_TYPE.'_posts_columns', array($this, 'add_listings_table_columns'));
		add_filter('manage_'.W2GM_POST_TYPE.'_posts_custom_column', array($this, 'manage_listings_table_rows'), 10, 2);

		add_action('w2gm_render_template', array($this, 'check_custom_template'), 10, 2);
	}
	
	/**
	 * check is there template in one of these paths:
	 * - themes/theme/w2gm-plugin/templates/w2gm_payments/
	 * - plugins/w2gm/templates/w2gm_payments/
	 *
	 */
	public function check_custom_template($template, $args) {
		if (is_array($template)) {
			$template_path = $template[0];
			$template_file = $template[1];
	
			if ($template_path == W2GM_RATINGS_TEMPLATES_PATH && ($fsubmit_template = w2gm_isTemplate('w2gm_payments/' . $template_file))) {
				return $fsubmit_template;
			}
		}
		return $template;
	}

	public function plugin_settings($options) {
		$options['template']['menus']['listings']['controls']['ratings'] = array(
			'type' => 'section',
			'title' => __('Ratings settings', 'W2GM'),
			'fields' => array(
				array(
					'type' => 'toggle',
					'name' => 'w2gm_only_registered_users',
					'label' => __('Only registered users may place ratings', 'W2GM'),
					'default' => get_option('w2gm_only_registered_users'),
				),
				array(
					'type' => 'toggle',
					'name' => 'w2gm_rating_on_map',
					'label' => __('Show rating in map info window', 'W2GM'),
					'default' => get_option('w2gm_rating_on_map'),
				),
				array(
					'type' => 'toggle',
					'name' => 'w2gm_manage_ratings',
					'label' => __('Allow users to flush ratings of own listings', 'W2GM'),
					'default' => get_option('w2gm_manage_ratings'),
				),
			),
		);
		return $options;
	}
	
	public function ratings_options_in_level_html($level) {
		w2gm_renderTemplate(array(W2GM_RATINGS_TEMPLATES_PATH, 'ratings_options_in_level.tpl.php'), array('level' => $level));
	}
	
	public function load_listing($listing) {
		$listing->avg_rating = new w2gm_avg_rating($listing->post->ID);
		
		return $listing;
	}
	
	public function addRatingsMetabox($post_type) {
		if ($post_type == W2GM_POST_TYPE) {
			add_meta_box('w2gm_ratings',
					__('Listing ratings', 'W2GM'),
					array($this, 'listingRatingsMetabox'),
					W2GM_POST_TYPE,
					'normal',
					'high');
		}
	}
	
	public function listingRatingsMetabox($post) {
		$listing = new w2gm_listing();
		$listing->loadListingFromPost($post);

		$total_counts = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0);
		foreach ($listing->avg_rating->ratings AS $rating)
			$total_counts[$rating->value]++;
		
		krsort($total_counts);

		w2gm_renderTemplate(array(W2GM_RATINGS_TEMPLATES_PATH, 'ratings_metabox.tpl.php'), array('listing' => $listing, 'total_counts' => $total_counts));
	}
	
	public function frontendRatingsMetabox($listing) {
		if (get_option('w2gm_manage_ratings') || current_user_can('edit_others_posts')) {
			echo '<div class="w2gm-submit-section w2gm-submit-section-ratings">';
				echo '<h3 class="w2gm-submit-section-label">' . __('Listing ratings', 'W2GM') . '</h3>';
				echo '<div class="w2gm-submit-section-inside">';
					$this->listingRatingsMetabox($listing->post);
				echo '</div>';
			echo '</div>';
		}
	}
	
	public function flush_ratings() {
		$post_id = w2gm_getValue($_POST, 'post_id');
		
		if (($post = get_post($post_id)) && ((get_option('w2gm_manage_ratings') && w2gm_current_user_can_edit_listing($post_id)) || current_user_can('edit_others_posts'))) {
			w2gm_flush_ratings($post_id);
		}
		die();
	}
	
	public function add_listings_table_columns($columns) {
		$w2gm_columns['w2gm_rating'] = __('Rating', 'W2GM');

		$comments_index = array_search("comments", array_keys($columns));

		return array_slice($columns, 0, $comments_index, true) + $w2gm_columns + array_slice($columns, $comments_index, count($columns)-$comments_index, true);
	}
	
	public function manage_listings_table_rows($column, $post_id) {
		if ($column == "w2gm_rating") {
			$listing = new w2gm_listing();
			$listing->loadListingFromPost($post_id);
			$this->render_rating($listing, false, false);
		}
	}
	
	public function save_rating() {
		$post_id = w2gm_getValue($_POST, 'post_id');
		$rating = w2gm_getValue($_POST, 'rating');
		$_wpnonce = wp_verify_nonce(w2gm_getValue($_POST, '_wpnonce'), 'save_rating');

		if (($post = get_post($post_id)) && $rating && ($rating >= 1 && $rating <= 5) && $_wpnonce) {
			$user_id = get_current_user_id();
			$ip = w2gm_ip_address();
			if (get_option('w2gm_only_registered_users') && !$user_id)
				return false;

			if (!$this->is_listing_rated($post->ID)) {
				if ($user_id)
					add_post_meta($post->ID, W2GM_RATING_PREFIX . $user_id, $rating);
				elseif ($ip)
					add_post_meta($post->ID, W2GM_RATING_PREFIX . $ip, $rating);

				setcookie(W2GM_RATING_PREFIX . $post->ID, $rating, time() + 31536000);

				$avg_rating = new w2gm_avg_rating($post->ID);
				$avg_rating->update_avg_rating();
			} else {
				// possible to change user rating
				if ($user_id)
					update_post_meta($post->ID, W2GM_RATING_PREFIX . $user_id, $rating);
				elseif ($ip)
					update_post_meta($post->ID, W2GM_RATING_PREFIX . $ip, $rating);
				
				setcookie(W2GM_RATING_PREFIX . $post->ID, $rating, time() + 31536000);
				
				$avg_rating = new w2gm_avg_rating($post->ID);
				$avg_rating->update_avg_rating();
			}
			
			$listing = w2gm_getListing($post);
			$out = w2gm_renderTemplate(array(W2GM_RATINGS_TEMPLATES_PATH, 'avg_rating.tpl.php'), array('listing' => $listing, 'meta_tags' => false, 'active' => true, 'show_avg' => true), true);
			echo json_encode(array('html' => $out));
		}
		die();
	}
	
	public function is_listing_rated($id) {
		if (!isset($_COOKIE[W2GM_RATING_PREFIX . $id])) {
			if ($user_id = get_current_user_id())
				if (get_post_meta($id, W2GM_RATING_PREFIX . $user_id, true))
					return true;
		
			if ($ip = w2gm_ip_address())
				if (get_post_meta($id, W2GM_RATING_PREFIX . $ip, true))
					return true;
		} else {
			return true;
		}
	}

	public function render_rating($listing, $meta_tags = false, $active = true, $show_avg = true) {
		global $w2gm_instance;

		if (get_option('w2gm_only_registered_users') && !get_current_user_id())
			$active = false;
		if ((get_current_user_id() == $listing->post->post_author) && !current_user_can('manage_options'))
			$active = false;
		if ($w2gm_instance->action == 'printlisting' || $w2gm_instance->action == 'pdflisting')
			$active = false;

		w2gm_renderTemplate(array(W2GM_RATINGS_TEMPLATES_PATH, 'avg_rating.tpl.php'), array('listing' => $listing, 'meta_tags' => $meta_tags, 'active' => $active, 'show_avg' => $show_avg));
		
		return $listing;
	}
	
	public function get_rating_stars($title, $listing) {
		return $title . ' ' . w2gm_renderTemplate(
				array(W2GM_RATINGS_TEMPLATES_PATH, 'avg_rating.tpl.php'),
				array(
						'listing' => $listing,
						'meta_tags' => false,
						'active' => false,
						'show_avg' => false
		), true);
	}

	public function render_rating_dashboard($listing) {

		w2gm_renderTemplate(array(W2GM_RATINGS_TEMPLATES_PATH, 'avg_rating.tpl.php'), array('listing' => $listing, 'meta_tags' => false, 'active' => false, 'show_avg' => true));
		
		return $listing;
	}
	
	public function add_rating_field_to_map_window($fields) {
		if (get_option('w2gm_rating_on_map'))
			$fields = array('rating' => '') + $fields;

		return $fields;
	}

	public function render_rating_in_map_window($content_field, $field_slug, $listing) {
		if (get_option('w2gm_rating_on_map') && $field_slug == 'rating' && isset($listing->avg_rating))
			return w2gm_renderTemplate(array(W2GM_RATINGS_TEMPLATES_PATH, 'avg_rating.tpl.php'), array('listing' => $listing, 'meta_tags' => false, 'active' => false, 'show_avg' => true), true);
	}
	
	public function order_by_rating_args($args, $defaults = array(), $include_GET_params = true) {
		if ($include_GET_params && isset($_REQUEST['order_by']) && $_REQUEST['order_by']) {
			$order_by = $_REQUEST['order_by'];
			$order = w2gm_getValue($_REQUEST, 'order', 'DESC');
		} else {
			if (isset($defaults['order_by']) && $defaults['order_by']) {
				$order_by = $defaults['order_by'];
				$order = w2gm_getValue($defaults, 'order', 'DESC');
			}
		}
		
		if (isset($order_by) && $order_by == 'rating_order') {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = W2GM_AVG_RATING_KEY;
			$args['order'] = $order;
		}

		return $args;
	}
	
	public function order_by_rating_option($ordering) {
		$ordering['rating_order'] = __('Rating', 'W2GM');
		
		return $ordering;
	}
	
	public function rating_in_comment($output) {
		$comment = 0;
		if (($comment = get_comment($comment)) && ($post = get_post()) && $post->post_type == W2GM_POST_TYPE) {
			if ($rating = w2gm_build_single_rating($comment->comment_post_ID, $comment->user_id))
				$output = w2gm_renderTemplate(array(W2GM_RATINGS_TEMPLATES_PATH, 'single_rating.tpl.php'), array('rating' => $rating), true) . $output;
		}
	
		return $output;
	}
}

function w2gm_install_ratings() {
	global $wpdb;

	add_option('w2gm_only_registered_users', 0);
	add_option('w2gm_rating_on_map', 1);
	add_option('w2gm_manage_ratings', 1);
		
	add_option('w2gm_installed_ratings', 1);
}

function w2gm_upgrade_ratings($new_version) {

}

global $w2gm_ratings_instance;

$w2gm_ratings_instance = new w2gm_ratings_plugin();
$w2gm_ratings_instance->init();

?>
