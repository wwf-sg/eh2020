<?php

class w2gm_search_map_form extends w2gm_search_form {
	public $listings_content = '';

	public function __construct($uid = null, $controller = 'maps_controller', $args, $listings_content = '', $locations_array = array()) {
		global $w2gm_instance;
		
		$this->args = array_merge(array(
				'exact_categories' => array(),
				'exact_locations' => array(),
				'search_on_map_open' => 0,
		), $args);
		$this->uid = $uid;
		$this->controller = $controller;
		$this->listings_content = $listings_content;
		$this->locations_array = $locations_array;
		
		if (isset($this->args['exact_categories']) && !is_array($this->args['exact_categories'])) {
			if ($categories = array_filter(explode(',', $this->args['exact_categories']), 'trim')) {
				$this->args['exact_categories'] = $categories;
			}
		}

		if (isset($this->args['exact_locations']) && !is_array($this->args['exact_locations'])) {
			if ($locations = array_filter(explode(',', $this->args['exact_locations']), 'trim')) {
				$this->args['exact_locations'] = $locations;
			}
		}
	}

	public function printClasses() {
		$classes = array();
		if (!empty($this->args['search_on_map_open'])) {
			$classes[] = 'w2gm-sidebar-open';
		}
		if (!$this->isCategoriesOrKeywords() && !$this->isLocationsOrAddress()) {
			$classes[] = 'w2gm-no-map-search-form';
		} elseif (!$this->isCategoriesOrKeywords()) {
			$classes[] = 'w2gm-no-map-search-categories';
		} elseif (!$this->isLocationsOrAddress()) {
			$classes[] = 'w2gm-no-map-search-locations';
		}
		$classes = apply_filters("w2gm_map_search_form_classes", $classes, $this);
		
		echo implode(" ", $classes);
	}

	public function display($columns = 2, $advanced_open = false) {
		global $w2gm_instance;

		// random ID needed because there may be more than 1 search form on one page
		$search_form_id = w2gm_generateRandomVal();
		
		w2gm_renderTemplate('search_map_form.tpl.php',
			array(
				'search_form_id' => $search_form_id,
				'uid' => $this->uid,
				'args' => $this->args,
				'search_form' => $this,
				'controller' => $this->controller,
				'locations_array' => $this->locations_array,
			)
		);
	}
}
?>