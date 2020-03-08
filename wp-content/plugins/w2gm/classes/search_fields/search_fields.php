<?php

include_once W2GM_PATH . 'classes/search_fields/content_field_search.php';
include_once W2GM_PATH . 'classes/search_fields/fields/content_field_string_search.php';
include_once W2GM_PATH . 'classes/search_fields/fields/content_field_select_search.php';
include_once W2GM_PATH . 'classes/search_fields/fields/content_field_checkbox_search.php';
include_once W2GM_PATH . 'classes/search_fields/fields/content_field_radio_search.php';
include_once W2GM_PATH . 'classes/search_fields/fields/content_field_number_search.php';
include_once W2GM_PATH . 'classes/search_fields/fields/content_field_price_search.php';
include_once W2GM_PATH . 'classes/search_fields/fields/content_field_datetime_search.php';
include_once W2GM_PATH . 'classes/search_fields/fields/content_field_textarea_search.php';
include_once W2GM_PATH . 'classes/search_fields/fields/content_field_phone_search.php';

class w2gm_search_fields {
	public $search_fields_array = array(); // fields on search form
	public $filter_fields_array = array(); // all content fields available to filter listings

	public function __construct() {
		$this->load_search_fields();
		
		add_filter('w2gm_search_args', array($this,  'retrieve_search_args'), 100, 4);
		add_action('w2gm_visible_search_params', array($this, 'visible_search_params'));
		
		// handle search configuration page
		add_action('admin_menu', array($this, 'menu'), 11);
		
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles'), 100);
	}
	
	public function menu() {
		global $w2gm_instance;
	
		add_action($w2gm_instance->content_fields_manager->menu_page_hook, array($this, 'search_configuration_page'));
	}
	
	public function search_configuration_page() {
		if (isset($_GET['action']) && $_GET['action'] == 'configure_search' && isset($_GET['field_id'])) {
			$field_id = $_GET['field_id'];
			if (isset($this->search_fields_array[$field_id])) {
				$search_field = $this->search_fields_array[$field_id];
				if (method_exists($search_field, 'searchConfigure'))
					$search_field->searchConfigure();
			}
		}
	}
	
	public function getSearchFieldById($id) {
		if (isset($this->filter_fields_array[$id])) {
			return $this->filter_fields_array[$id];
		}
	}
	
	public function load_search_fields() {
		global $w2gm_instance;
		
		//$this->search_fields_array = array();
		//$this->filter_fields_array = array();
	
		// load only for our page
		if (!$this->search_fields_array) {
			$content_fields = $w2gm_instance->content_fields->content_fields_array;
	
			foreach ($content_fields AS $content_field) {
				$field_search_class = get_class($content_field) . '_search';
				if (class_exists($field_search_class)) {
					$search_field = new $field_search_class;
					$search_field->assignContentField($content_field);
					$search_field->convertSearchOptions();
					// note: in admin panel you can place search value for any searchable content field, not only those enabled
					if ($content_field->canBeSearched() && (is_admin() || $content_field->on_search_form)) {
							$this->search_fields_array[$content_field->id] = $search_field;
							$this->filter_fields_array[$content_field->id] = $search_field;
					} else {
						$this->filter_fields_array[$content_field->id] = $search_field;
					}
				}
			}
		}
	}
	
	public function render_content_fields($search_form_id, $columns = 2, $search_form) {
		w2gm_renderTemplate('search_fields/fields_search_form.tpl.php',
			array(
				'search_fields' => $search_form->search_fields_array,
				'search_fields_advanced' => $search_form->search_fields_array_advanced,
				'search_fields_all' => $search_form->search_fields_array_all,
				'columns' => $columns,
				'is_advanced_search_panel' => $search_form->is_advanced_search_panel,
				'advanced_open' => $search_form->advanced_open,
				'search_form_id' => $search_form_id,
				'defaults' => $search_form->args
			)
		);
	}
	
	public function retrieve_search_args($args, $defaults = array(), $include_GET_params = true, $shortcode_hash = null) {
		global $w2gm_instance;

		$include_tax_children = w2gm_getValue($defaults, 'include_categories_children');

		if ($include_GET_params) {
			$categories = (w2gm_getValue($_REQUEST, 'categories') ? w2gm_getValue($_REQUEST, 'categories') : w2gm_getValue($defaults, 'categories'));
		} else {
			$categories = w2gm_getValue($defaults, 'categories');
		}
		/* if (!$categories) {
			$categories = w2gm_getValue($defaults, 'exact_categories');
			$categories = implode(',', $categories);
		} */
		
		// Search keyword in categories terms
		if (!empty($args["s"])) {
			$t_args = array(
					'taxonomy'      => array(W2GM_CATEGORIES_TAX), // taxonomy name
					'orderby'       => 'id',
					'order'         => 'ASC',
					'hide_empty'    => true,
					'fields'        => 'tt_ids',
					'name__like'    => $args["s"]
			);
			$categories .= ',' . implode(',', get_terms($t_args));
			$args["_meta_or_title"] = $args["s"]; // needed for posts_clauses filter in frotnend_controller.php
		}

		if ($categories) {
			if (!is_array($categories)) {
				$categories = array_filter(explode(',', $categories), 'trim');
			}
			if ($categories) {
				$field = 'term_id';
				foreach ($categories AS $category) {
					if (!is_numeric($category)) {
						$field = 'slug';
					}
				}

				$args['tax_query'][] = array(
						'taxonomy' => W2GM_CATEGORIES_TAX,
						'terms' => $categories,
						'field' => $field,
						'include_children' => $include_tax_children
				);
			}
		}
		
		if ($include_GET_params) {
			$search_locations = (w2gm_getValue($_REQUEST, 'locations') ? w2gm_getValue($_REQUEST, 'locations') : w2gm_getValue($defaults, 'locations'));
		} else {
			$search_locations = w2gm_getValue($defaults, 'locations');
		}
		if (!$search_locations) {
			if ($include_GET_params) {
				$search_locations = (w2gm_getValue($_REQUEST, 'exact_locations') ? w2gm_getValue($_REQUEST, 'exact_locations') : w2gm_getValue($defaults, 'exact_locations'));
			} else {
				$search_locations = w2gm_getValue($defaults, 'exact_locations');
			}
		}

		if ($search_locations) {
			if (!is_array($search_locations)) {
				$search_locations = array_filter(explode(',', $search_locations), 'trim');
			}
			if ($search_locations) {
				$field = 'term_id';
				foreach ($search_locations AS $location) {
					if (!is_numeric($location)) {
						$field = 'slug';
					}
				}

				$args['tax_query'][] = array(
						'taxonomy' => W2GM_LOCATIONS_TAX,
						'terms' => $search_locations,
						'field' => $field,
						'include_children' => $include_tax_children
				);
			}
		}
		
		if ($include_GET_params) {
			$tags = (w2gm_getValue($_REQUEST, 'tags') ? w2gm_getValue($_REQUEST, 'tags') : w2gm_getValue($defaults, 'tags'));
		} else {
			$tags = w2gm_getValue($defaults, 'tags');
		}
		
		if (!empty($args["s"])) {
			$t_args = array(
					'taxonomy'      => array(W2GM_TAGS_TAX), // taxonomy name
					'orderby'       => 'id',
					'order'         => 'ASC',
					'hide_empty'    => true,
					'fields'        => 'tt_ids',
					'name__like'    => $args["s"]
			);
			$tags .= ',' . implode(',', get_terms($t_args));
			$args["_meta_or_title"] = $args["s"]; // needed for posts_clauses filter in frotnend_controller.php
		}

		if ($tags) {
			if ($tags = array_filter(explode(',', $tags), 'trim')) {
				$field = 'term_id';
				foreach ($tags AS $tag) {
					if (!is_numeric($tag)) {
						$field = 'slug';
					}
				}

				$args['tax_query'][] = array(
						'taxonomy' => W2GM_TAGS_TAX,
						'terms' => $tags,
						'field' => $field,
				);
			}
		}

		if ($include_GET_params) {
			$search_location = w2gm_getValue($_REQUEST, 'location_id', w2gm_getValue($defaults, 'location_id'));
			$address = trim(w2gm_getValue($_REQUEST, 'address', w2gm_getValue($defaults, 'address', null)));
			$radius = w2gm_getValue($_REQUEST, 'radius', w2gm_getValue($defaults, 'radius'));
		} else {
			$search_location = w2gm_getValue($defaults, 'location_id');
			$address = trim(w2gm_getValue($defaults, 'address', null));
			$radius = w2gm_getValue($defaults, 'radius');
		}

		$search_location = apply_filters('w2gm_search_param_location_id', $search_location);
		$address = apply_filters('w2gm_search_param_address', $address);
		$radius = apply_filters('w2gm_search_param_radius', $radius);
		
		if (is_null($address) && (w2gm_getValue($defaults, 'start_address'))) {
			$address = w2gm_getValue($defaults, 'start_address');
		}

		$start_latitude = w2gm_getValue($defaults, 'start_latitude');
		$start_longitude = w2gm_getValue($defaults, 'start_longitude');
		
		$start_latitude = apply_filters('w2gm_search_param_start_latitude', $start_latitude);
		$start_longitude = apply_filters('w2gm_search_param_start_longitude', $start_longitude);

		// Do not search by radius when only location ID was selected and we do not have any real address clicking the search button,
		// but working with shortcode parameters must be enough to search by radius.
		if (w2gm_getValue($_REQUEST, 'w2gm_action') == 'search') {
			if (!$address) {
				$radius = 0;
			}
		}
		if ($radius && is_numeric($radius) && ($address || ($start_latitude && $start_longitude))) {
			$w2gm_instance->radius_values_array[$shortcode_hash]['radius'] = $radius;

			if (($search_location && is_numeric($search_location)) || $address || ($start_latitude && $start_longitude)) {
				$coords = null;
				if ($start_latitude && $start_longitude) {
					$coords[1] = $start_latitude;
					$coords[0] = $start_longitude;
				} elseif ($address || $search_location) {
					$chain = array();
					$parent_id = $search_location;
					while ($parent_id != 0) {
						if ($term = get_term($parent_id, W2GM_LOCATIONS_TAX)) {
							$chain[] = $term->name;
							$parent_id = $term->parent;
						} else
							$parent_id = 0;
					}
					$location_string = implode(', ', $chain);
					
					if ($address)
						$location_string = $address . ' ' . $location_string;
					if (get_option('w2gm_default_geocoding_location'))
						$location_string = $location_string . ' ' . get_option('w2gm_default_geocoding_location');
					
					$w2gm_locationGeoname = new w2gm_locationGeoname();
					$coords = $w2gm_locationGeoname->geocodeRequest($location_string, 'coordinates');
				}

				if ($coords) {
					$w2gm_instance->radius_values_array[$shortcode_hash]['x_coord'] = $coords[1]; // latitude
					$w2gm_instance->radius_values_array[$shortcode_hash]['y_coord'] = $coords[0]; // longitude

					// radius_params must be localized by 2 ways: inside retrieve_search_args() function and in wp_enqueue_scripts hook
					// this one for webmap shortcode and map widget
					//if (($frontend_controller = $w2gm_instance->getShortcodeByHash($shortcode_hash)) /* && $frontend_controller->map */) {
						wp_localize_script(
							'w2gm_js_functions',
							'radius_params_'.$shortcode_hash,
							array(
								'radius_value' => $radius,
								'map_coords_1' => $coords[1],
								'map_coords_2' => $coords[0],
								'dimension' => get_option('w2gm_miles_kilometers_in_search')
							)
						);
					//}

					if (get_option('w2gm_miles_kilometers_in_search') == 'miles')
						$R = 3956; // earth's mean radius in miles
					else
						$R = 6367; // earth's mean radius in km

					$dLat = '((map_coords_1-'.$coords[1].')*PI()/180)';
					$dLong = '((map_coords_2-'.$coords[0].')*PI()/180)';
					$a = '(sin('.$dLat.'/2) * sin('.$dLat.'/2) + cos('.$coords[1].'*pi()/180) * cos(map_coords_1*pi()/180) * sin('.$dLong.'/2) * sin('.$dLong.'/2))';
					$c = '2*atan2(sqrt('.$a.'), sqrt(1-'.$a.'))';
					$sql = $R.'*'.$c; 

					global $wpdb, $w2gm_address_locations;
					$results = $wpdb->get_results($wpdb->prepare(
						"SELECT DISTINCT
							id, post_id, " . $sql . " AS distance FROM {$wpdb->w2gm_locations_relationships}
						HAVING
							distance <= %d
						ORDER BY
							distance
						", $radius), ARRAY_A);

					$post_ids = array();
					$w2gm_address_locations = array();
					foreach ($results AS $row) {
						$post_ids[] = $row['post_id'];
						$w2gm_address_locations[] = $row['id'];
					}
					$post_ids = array_unique($post_ids);

					if ($post_ids) {
						$args['post__in'] = $post_ids;
					} else
						// Do not show any listings
						$args['post__in'] = array(0);

					$args = $this->order_by_distance_args($args, $defaults, $include_GET_params, $shortcode_hash);
				}
			}
		}

		foreach ($this->filter_fields_array AS $field_id=>$filter_field) {
			$filter_field->validateSearch($args, $defaults, $include_GET_params);
		}

		return $args;
	}
	
	public function order_by_distance_args($args, $defaults, $include_GET_params, $shortcode_hash) {
		global $w2gm_instance;

		if (isset($w2gm_instance->radius_values_array[$shortcode_hash]) && $w2gm_instance->radius_values_array[$shortcode_hash]['radius']) {
			if (!isset($defaults['order_by']) || !$defaults['order_by']) {
				$defaults['order_by'] = 'distance';
				$w2gm_instance->order_by_date = false;
				//$defaults['order'] = 'ASC';
			}
			
			if ($include_GET_params) {
				$order_by = w2gm_getValue($_REQUEST, 'order_by', w2gm_getValue($defaults, 'order_by'));
				$order = w2gm_getValue($_REQUEST, 'order', w2gm_getValue($defaults, 'order'));
			} else {
				$order_by = w2gm_getValue($defaults, 'order_by');
				$order = w2gm_getValue($defaults, 'order');
			}

			// When search by radius - order by distance by default instead of ordering by date
			if ($order_by == 'distance') {
				$args['orderby'] = 'post__in';
				unset($args['meta_key']);
				if ($order == 'DESC') {
					if (!empty($args['post__in']) && is_array($args['post__in'])) {
						$args['post__in'] = array_reverse($args['post__in']);
					}
				}
			}
		}

		return $args;
	}
	
	public function enqueue_scripts_styles() {
		global $w2gm_instance;
	
		if (function_exists('is_rtl') && is_rtl()) {
			wp_deregister_script('jquery-ui-slider');
			wp_register_script('jquery-ui-slider', W2GM_RESOURCES_URL . 'js/jquery.ui.slider-rtl.min.js', array('jquery-ui-core') , false, true);
		}
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-touch-punch');
	
		wp_localize_script(
			'jquery-ui-slider',
			'slider_params',
			array(
				'min' => get_option('w2gm_radius_search_min'),
				'max' => get_option('w2gm_radius_search_max')
			)
		);
	}
}

?>