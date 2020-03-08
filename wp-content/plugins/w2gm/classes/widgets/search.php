<?php

global $w2gm_search_widget_params;
$w2gm_search_widget_params = array(
		array(
				'type' => 'textfield',
				'param_name' => 'uid',
				'heading' => __("uID", "W2GM"),
				'description' => __("Enter unique string to connect search form with the map.", "W2GM"),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'columns',
				'value' => array('2', '1'),
				'std' => '2',
				'heading' => __('Number of columns to arrange search fields', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'advanced_open',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Advanced search panel always open', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'sticky_scroll',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Make search form to be sticky on scroll', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'sticky_scroll_toppadding',
				'value' => 0,
				'heading' => __('Sticky scroll top padding', 'W2GM'),
				'description' => __('Sticky scroll top padding in pixels.', 'W2GM'),
				'dependency' => array('element' => 'sticky_scroll', 'value' => '1'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_keywords_search',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show keywords search?', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'keywords_ajax_search',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Enable listings autosuggestions by keywords', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'keywords_search_examples',
				'heading' => __('Keywords examples', 'W2GM'),
				'description' => __('Comma-separated list of suggestions to try to search.', 'W2GM'),
		),
		/* array(
				'type' => 'textfield',
				'param_name' => 'what_search',
				'heading' => __('Default keywords', 'W2GM'),
		), */
		array(
				'type' => 'textfield',
				'param_name' => 'keywords_placeholder',
				'heading' => __('Keywords placeholder', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_categories_search',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show categories search?', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'categories_search_level',
				'value' => array('1', '2', '3'),
				'std' => '2',
				'heading' => __('Categories search depth level', 'W2GM'),
		),
		array(
				'type' => 'categoryfield',
				'param_name' => 'category',
				'heading' => __('Select certain category', 'W2GM'),
		),
		array(
				'type' => 'categoriesfield',
				'param_name' => 'exact_categories',
				'heading' => __('List of categories', 'W2GM'),
				'description' => __('Comma separated string of categories slugs or IDs. Possible to display exact categories.', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_locations_search',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show locations search?', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'locations_search_level',
				'value' => array('1', '2', '3'),
				'std' => '2',
				'heading' => __('Locations search depth level', 'W2GM'),
		),
		array(
				'type' => 'locationfield',
				'param_name' => 'location',
				'heading' => __('Select certain location', 'W2GM'),
		),
		array(
				'type' => 'locationsfield',
				'param_name' => 'exact_locations',
				'heading' => __('List of locations', 'W2GM'),
				'description' => __('Comma separated string of locations slugs or IDs. Possible to display exact locations.', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_address_search',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show address search?', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'address',
				'heading' => __('Default address', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'address_placeholder',
				'heading' => __('Adress placeholder', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_radius_search',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show locations radius search?', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'radius',
				'heading' => __('Default radius search', 'W2GM'),
		),
		array(
				'type' => 'contentfields',
				'param_name' => 'search_fields',
				'heading' => __('Select certain content fields', 'W2GM'),
		),
		array(
				'type' => 'contentfields',
				'param_name' => 'search_fields_advanced',
				'heading' => __('Select certain content fields in advanced section', 'W2GM'),
		),
		array(
				'type' => 'colorpicker',
				'param_name' => 'search_bg_color',
				'heading' => __("Background color", "W2GM"),
				'value' => get_option('w2gm_search_bg_color'),
		),
		array(
				'type' => 'colorpicker',
				'param_name' => 'search_text_color',
				'heading' => __("Text color", "W2GM"),
				'value' => get_option('w2gm_search_text_color'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'search_bg_opacity',
				'heading' => __("Opacity of search form background, in %", "W2GM"),
				'value' => 100,
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'search_overlay',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show background overlay', 'W2GM'),
				'std' => get_option('w2gm_search_overlay')
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'hide_search_button',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Hide search button', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'on_row_search_button',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Search button on one line with fields', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'scroll_to',
				'value' => array(__('No scroll', 'W2GM') => '', __('Listings', 'W2GM') => 'listings', __('Map', 'W2GM') => 'map'),
				'heading' => __('Scroll to listings, map or do not scroll after search button was pressed', 'W2GM'),
		),
);

class w2gm_search_widget extends w2gm_widget {

	public function __construct() {
		global $w2gm_instance, $w2gm_search_widget_params;

		parent::__construct(
				'w2gm_search_widget',
				__('Google Maps locator - Search', 'W2GM'),
				__('Search Form', 'W2GM')
		);

		foreach ($w2gm_instance->search_fields->filter_fields_array AS $filter_field) {
			if (method_exists($filter_field, 'getVCParams') && ($field_params = $filter_field->getVCParams())) {
				$w2gm_search_widget_params = array_merge($w2gm_search_widget_params, $field_params);
			}
		}

		$this->convertParams($w2gm_search_widget_params);
	}
	
	public function render_widget($instance, $args) {
		global $w2gm_instance;

		$title = apply_filters('widget_title', $instance['title']);

		echo $args['before_widget'];
		if (!empty($title)) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		echo '<div class="w2gm-content w2gm-widget w2gm-search-widget">';
		$controller = new w2gm_search_controller();
		$controller->init($instance);
		echo $controller->display();
		echo '</div>';
		echo $args['after_widget'];
	}
}
?>