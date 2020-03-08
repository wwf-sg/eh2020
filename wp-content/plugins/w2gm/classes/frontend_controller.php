<?php 

class w2gm_frontend_controller {
	public $args = array();
	public $query;
	public $template;
	public $listings = array();
	public $search_form;
	public $map;
	public $messages = array();
	public $hash = null;
	public $request_by = 'frontend_controller';
	public $template_args = array();

	public function __construct($args = array()) {
		apply_filters('w2gm_frontend_controller_construct', $this);
		
		$this->template_args = array('frontend_controller' => $this);
	}
	
	public function add_template_args($args) {
		$this->template_args += $args;
	}
	
	public function init($attrs = array()) {
		if (!$this->hash) {
			if (isset($attrs['uid']) && $attrs['uid']) {
				$this->hash = md5($attrs['uid']);
			} else {
				$this->hash = md5(get_class($this).serialize($attrs));
			}
		}
	}

	public function processQuery($load_only_for_the_map = true, $load_map = true, $map_args = array()) {
		// this is special construction,
		// this needs when we order by any postmeta field, this adds listings to the list with "empty" fields
		if (($this->getQueryVars('orderby') == 'meta_value_num' || $this->getQueryVars('orderby') == 'meta_value') && ($this->getQueryVars('meta_key') != '_order_date')) {
			$args = $this->getQueryVars();

			// there is strange thing - WP adds `taxonomy` and `term_id` args to the root of query vars array
			// this may cause problems
			unset($args['taxonomy']);
			unset($args['term_id']);
			if (empty($args['s'])) {
				unset($args['s']);
			}
			
			$original_posts_per_page = $args['posts_per_page'];

			$ordered_posts_ids = get_posts(array_merge($args, array('fields' => 'ids', 'nopaging' => true)));
			//var_dump($ordered_posts_ids);
			$ordered_max_num_pages = ceil(count($ordered_posts_ids)/$original_posts_per_page) - (int) $ordered_posts_ids;

			$args['paged'] = $args['paged'] - $ordered_max_num_pages;
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_order_date';
			$args['order'] = 'DESC';
			$args['posts_per_page'] = $original_posts_per_page - $this->query->post_count;
			$all_posts_ids = get_posts(array_merge($args, array('fields' => 'ids', 'nopaging' => true)));
			$all_posts_count = count($all_posts_ids);
			//var_dump($all_posts_count);

			if ($this->query->found_posts) {
				$args['post__not_in'] = array_map('intval', $ordered_posts_ids);
				if (!empty($args['post__in']) && is_array($args['post__in'])) {
					$args['post__in'] = array_diff($args['post__in'], $args['post__not_in']);
					if (!$args['post__in']) {
						$args['posts_per_page'] = 0;
					}
				}
			}

			$unordered_query = new WP_Query($args);
			//var_dump($args);

			//var_dump($unordered_query->request);
			//var_dump($this->query->request);

			if ($args['posts_per_page']) {
				$this->query->posts = array_merge($this->query->posts, $unordered_query->posts);
			}

			$this->query->post_count = count($this->query->posts);
			$this->query->found_posts = $all_posts_count;
			$this->query->max_num_pages = ceil($all_posts_count/$original_posts_per_page);
		}

		if ($load_map && empty($this->map)) {
			$this->map = new w2gm_maps($map_args, $this->request_by);
			$this->map->setUniqueId($this->hash);
		}
		
		while ($this->query->have_posts()) {
			$this->query->the_post();
		
			$listing = new w2gm_listing;
			if ($load_only_for_the_map) {
				if (empty($this->args['ajax_markers_loading'])) {
					$listing->loadListingForMap(get_post());
					$this->map->collectLocations($listing);
				} else {
					$listing->loadListingForAjaxMap(get_post());
					$this->map->collectLocationsForAjax($listing);
				}
			} else {
				$listing->loadListingFromPost(get_post());
			}
		
			$this->listings[get_the_ID()] = $listing;
		}
		
		global $w2gm_address_locations, $w2gm_tax_terms_locations;
		// empty this global arrays - there may be some maps on one page with different arguments
		$w2gm_address_locations = array();
		$w2gm_tax_terms_locations = array();
		
		// this is reset is really required after the loop ends
		wp_reset_postdata();
	}
	
	public function getQueryVars($var = null) {
		if (is_null($var)) {
			return $this->query->query_vars;
		} else {
			if (isset($this->query->query_vars[$var])) {
				return $this->query->query_vars[$var];
			}
		}
		return false;
	}

	public function getBreadCrumbs($separator = ' » ') {
		return implode($separator, $this->breadcrumbs);
	}

	public function getListingClasses() {
		$classes = array();
		
		if ($this->listings[get_the_ID()]->level->featured) {
			$classes[] = 'w2gm-featured';
		}
		if ($this->listings[get_the_ID()]->level->sticky) {
			$classes[] = 'w2gm-sticky';
		}
		if (!empty($this->args['summary_on_logo_hover'])) {
			$classes[] = 'w2gm-summary-on-logo-hover';
		}
		if (!empty($this->args['hide_content'])) {
			$classes[] = 'w2gm-hidden-content';
		}
		return $classes;
	}
	
	public function getListingsBlockClasses() {
		$classes[] = "w2gm-container-fluid";
		$classes[] = "w2gm-listings-block";
		$classes[] = "w2gm-mobile-listings-grid-" . get_option('w2gm_mobile_listings_grid_columns');
		$views_cookie = false;
		if ($this->args['show_views_switcher'] && isset($_COOKIE['w2gm_listings_view_'.$this->hash])) {
			$views_cookie = $_COOKIE['w2gm_listings_view_'.$this->hash];
		}
		if (($this->args['listings_view_type'] == 'grid' && !$views_cookie) || ($views_cookie == 'grid')) {
			$classes[] = "w2gm-listings-grid";
			$classes[] = "w2gm-listings-grid-" . $this->args['listings_view_grid_columns'];
		} else {
			$classes[] = "w2gm-listings-list-view";
		}
		
		$classes = apply_filters("w2gm_listings_block_classes", $classes, $this);
	
		return implode(" ", $classes);
	}
	
	public function display() {
		$output =  w2gm_renderTemplate($this->template, $this->template_args, true);
		wp_reset_postdata();
	
		return $output;
	}
}


add_filter('w2gm_order_args', 'w2gm_order_listings', 10, 3);
function w2gm_order_listings($order_args = array(), $defaults = array(), $include_GET_params = true) {
	global $w2gm_instance;
	
	// adapted for Relevanssi
	if (w2gm_is_relevanssi_search($defaults)) {
		return $order_args;
	}

	if ($include_GET_params && isset($_GET['order_by']) && $_GET['order_by']) {
		$order_by = $_GET['order_by'];
		$order = w2gm_getValue($_GET, 'order', 'ASC');
	} else {
		if (isset($defaults['order_by']) && $defaults['order_by']) {
			$order_by = $defaults['order_by'];
			$order = w2gm_getValue($defaults, 'order', 'ASC');
		} else {
			$order_by = 'post_date';
			$order = 'DESC';
		}
	}

	$order_args['orderby'] = $order_by;
	$order_args['order'] = $order;

	if ($order_by == 'rand' || $order_by == 'random') {
		// do not order by rand in search results
		//if ($_REQUEST['w2gm_action'] != 'search') {
			$order_args['orderby'] = 'rand';
		/* } else {
			$order_by = 'post_date';
		} */
	}

	if ($order_by == 'title') {
		//$order_args['orderby'] = 'title';
		$order_args['orderby'] = array('title' => $order_args['order'], 'meta_value_num' => 'ASC');
		$order_args['meta_key'] = '_order_date';
	} elseif ($order_by == 'post_date') {
		if ($order_by == 'post_date') {
			$w2gm_instance->order_by_date = true;
			// First of all order by _order_date parameter
			$order_args['orderby'] = 'meta_value_num';
			$order_args['meta_key'] = '_order_date';
		} else
			$order_args = array_merge($order_args, $w2gm_instance->content_fields->getOrderParams($defaults));
	} else {
		$order_args = array_merge($order_args, $w2gm_instance->content_fields->getOrderParams($defaults));
	}

	return $order_args;
}

class w2gm_query_search extends WP_Query {
	function __parse_search($q) {
		$x = $this->parse_search($q);
		return $x;
	}
}
add_filter('posts_clauses', 'w2gm_posts_clauses', 10, 2);
function w2gm_posts_clauses($clauses, $q) {
	if ($title = $q->get('_meta_or_title')) {
		$tax_query_vars = array();
		if (!empty($q->query_vars['tax_query'])) {
			$tax_query_vars = $q->query_vars['tax_query'];
		}
		if (isset($tax_query_vars[0]['taxonomy']) && in_array($tax_query_vars[0]['taxonomy'], array(W2GM_CATEGORIES_TAX, W2GM_TAGS_TAX))) {
			$tq_AND = new WP_Tax_Query($tax_query_vars);
			
			$tax_query_vars['relation'] = 'OR';
			$tq_OR = new WP_Tax_Query($tax_query_vars);

			$qu['s'] = $title;
			$w2gm_query_search = new w2gm_query_search;
	
			global $wpdb;
			$tc_AND = $tq_AND->get_sql($wpdb->posts, 'ID');
			$tc_OR = $tq_OR->get_sql($wpdb->posts, 'ID');

			if ($tc_AND['where'] && ($search_sql = $w2gm_query_search->__parse_search($qu))) {
				$clauses['where'] = str_ireplace( 
					$search_sql, 
					' ', 
					$clauses['where'] 
				);
				$clauses['where'] = str_ireplace( 
					$tc_AND['where'], 
					' ', 
					$clauses['where'] 
				);
				$clauses['where'] .= sprintf( 
					" AND ( ( 1=1 %s ) OR ( 1=1 %s ) ) ", 
					$tc_OR['where'],
					$search_sql
				);
			}
		}
    }
    return $clauses;
}

function w2gm_what_search($args, $defaults = array(), $include_GET_params = true) {
	if ($include_GET_params) {
		$args['s'] = w2gm_getValue($_GET, 'what_search', w2gm_getValue($defaults, 'what_search'));
	} else {
		$args['s'] = w2gm_getValue($defaults, 'what_search');
	}
	
	$args['s'] = stripslashes($args['s']);
	
	$args['s'] = apply_filters('w2gm_search_param_what_search', $args['s']);

	// 's' parameter must be removed when it is empty, otherwise it may case WP_query->is_search = true
	if (empty($args['s'])) {
		unset($args['s']);
	}

	return $args;
}
add_filter('w2gm_search_args', 'w2gm_what_search', 10, 3);

function w2gm_address($args, $defaults = array(), $include_GET_params = true) {
	global $wpdb, $w2gm_address_locations;

	if ($include_GET_params) {
		$address = w2gm_getValue($_GET, 'address', w2gm_getValue($defaults, 'address'));
		$search_location = w2gm_getValue($_GET, 'location_id', w2gm_getValue($defaults, 'location_id'));
	} else {
		$search_location = w2gm_getValue($defaults, 'location_id');
		$address = w2gm_getValue($defaults, 'address');
	}
	
	$search_location = apply_filters('w2gm_search_param_location_id', $search_location);
	$address = apply_filters('w2gm_search_param_address', $address);
	
	$where_sql_array = array();
	if ($search_location && is_numeric($search_location)) {
		$term_ids = get_terms(W2GM_LOCATIONS_TAX, array('child_of' => $search_location, 'fields' => 'ids', 'hide_empty' => false));
		$term_ids[] = $search_location;
		$where_sql_array[] = "(location_id IN (" . implode(', ', $term_ids) . "))";
	}
	
	if ($address) {
		$where_sql_array[] = $wpdb->prepare("(address_line_1 LIKE '%%%s%%' OR address_line_2 LIKE '%%%s%%' OR zip_or_postal_index LIKE '%%%s%%')", $address, $address, $address);
		
		// Search keyword in locations terms
		$t_args = array(
				'taxonomy'      => array(W2GM_LOCATIONS_TAX),
				'orderby'       => 'id',
				'order'         => 'ASC',
				'hide_empty'    => true,
				'fields'        => 'tt_ids',
				'name__like'    => $address
		);
		$address_locations = get_terms($t_args);

		foreach ($address_locations AS $address_location) {
			$term_ids = get_terms(W2GM_LOCATIONS_TAX, array('child_of' => $address_location, 'fields' => 'ids', 'hide_empty' => false));
			$term_ids[] = $address_location;
			$where_sql_array[] = "(location_id IN (" . implode(', ', $term_ids) . "))";
		}
	}

	if ($where_sql_array) {
		$results = $wpdb->get_results("SELECT id, post_id FROM {$wpdb->w2gm_locations_relationships} WHERE " . implode(' OR ', $where_sql_array), ARRAY_A);
		$post_ids = array();
		foreach ($results AS $row) {
			$post_ids[] = $row['post_id'];
			$w2gm_address_locations[] = $row['id'];
		}
		if ($post_ids) {
			$args['post__in'] = $post_ids;
		} else {
			// Do not show any listings
			$args['post__in'] = array(0);
		}	
	}
	return $args;
}
add_filter('w2gm_search_args', 'w2gm_address', 10, 3);

// Exclude a part of keyword string equal to category name
function w2gm_keywordInCategorySearch($keyword) {
	if (w2gm_getValue($_REQUEST, 'w2gm_action') == 'search') {
		$categories = w2gm_getValue($_REQUEST, 'categories');
		if (!is_array($categories)) {
			$categories = array_filter(explode(',', $categories), 'trim');
		} else {
			$categories = array_filter($categories);
		}
		if ($categories && count($categories) == 1 && $category = get_term(array_pop($categories), W2GM_CATEGORIES_TAX)) {
			$keyword = trim(str_ireplace(htmlspecialchars_decode($category->name), '', $keyword));
		}
	}
	return $keyword;
}
add_filter('w2gm_search_param_what_search', 'w2gm_keywordInCategorySearch');

// Exclude a part of address string equal to location name
function w2gm_addressInLocationSearch($address) {
	if (w2gm_getValue($_REQUEST, 'w2gm_action') == 'search' && ($location_id = array_filter(explode(',', w2gm_getValue($_REQUEST, 'location_id')), 'trim')) && count($location_id) == 1) {
		if ($location = get_term(array_pop($location_id), W2GM_LOCATIONS_TAX)) {
			$address = trim(str_ireplace(htmlspecialchars_decode($location->name), '', $address));
		}
	}
	return $address;
}
add_filter('w2gm_search_param_address', 'w2gm_addressInLocationSearch');

?>