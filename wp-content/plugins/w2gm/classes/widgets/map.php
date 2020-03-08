<?php

global $w2gm_map_widget_params;
$w2gm_map_widget_params = array(
		array(
				'type' => 'textfield',
				'param_name' => 'id',
				'value' => '',
				'heading' => __('This is ID of the map created in the Maps Manager.', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'uid',
				'value' => '',
				'heading' => __('uID. Enter unique string to connect this shortcode with another shortcodes.', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'draw_panel',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Enable Draw Panel', 'W2GM'),
				'description' => __('Very important: MySQL version must be 5.6.1 and higher or MySQL server variable "thread stack" must be 256K and higher. Ask your hoster about it if "Draw Area" does not work.', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'num',
				'value' => -1,
				'heading' => __('Number of markers', 'W2GM'),
				'description' => __('Number of markers to display on map (-1 gives all markers).', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'width',
				'heading' => __('Width', 'W2GM'),
				'description' => __('Set map width in pixels. With empty field the map will take all possible width.', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'height',
				'value' => 400,
				'heading' => __('Height', 'W2GM'),
				'description' => __('Set map height in pixels, also possible to set 100% value.', 'W2GM'),
		),
		array(
				'type' => 'mapstyle',
				'param_name' => 'map_style',
				'heading' => __('Maps style', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'sticky_scroll',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Make map to be sticky on scroll', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'sticky_scroll_toppadding',
				'value' => 0,
				'heading' => __('Sticky scroll top padding', 'W2GM'),
				'description' => __('Top padding in pixels.', 'W2GM'),
				'dependency' => array('element' => 'sticky_scroll', 'value' => '1'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_directions_button',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Show summary button', 'W2GM'),
				'description' => __('Show summary button in InfoWindow.', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_readmore_button',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show readmore button', 'W2GM'),
				'description' => __('Show read more button in InfoWindow.', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'directions_sidebar_open',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1',),
				'heading' => __('Directions sidebar opened by default.', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'geolocation',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('GeoLocation', 'W2GM'),
				'description' => __('Geolocate user and center map. Requires https.', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'ajax_loading',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('AJAX loading', 'W2GM'),
				'description' => __('When map contains lots of markers - this may slow down map markers loading. Select AJAX to speed up loading. Requires Starting Address or Starting Point coordinates Latitude and Longitude.', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'ajax_markers_loading',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Maps info window AJAX loading', 'W2GM'),
				'description' => __('This may additionaly speed up loading.', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'use_ajax_loader',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Show spinner on AJAX requests.', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'start_address',
				'heading' => __('Starting Address', 'W2GM'),
				'description' => __('When map markers load by AJAX - it should have starting point and starting zoom. Enter start address or select latitude and longitude (recommended). Example: 1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'start_latitude',
				'heading' => __('Starting Point Latitude', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'start_longitude',
				'heading' => __('Starting Point Longitude', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'start_zoom',
				'heading' => __('Default zoom', 'W2GM'),
				'value' => array(__("Auto", "W2GM") => '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19'),
				'std' => '0',
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'counter',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show locations counter', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'counter_text',
				'heading' => __('Counter text', 'W2GM'),
				'description' => __('Example: Number of locations %d', 'W2GM'),
				'std' => __('Number of locations %d', 'W2GM'),
		),
		array(
				'type' => 'ordering',
				'param_name' => 'order_by',
				'heading' => __('Order by', 'W2GM'),
				'description' => __('Order listings by any of these parameter.', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'order',
				'value' => array(__('Ascending', 'W2GM') => 'ASC', __('Descending', 'W2GM') => 'DESC'),
				'description' => __('Direction of sorting.', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'search_on_map',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Show search form and listings panel on the map', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'search_on_map_open',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Search form open by default', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_keywords_search',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show keywords search?', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'keywords_ajax_search',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Enable listings autosuggestions by keywords', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		/* array(
				'type' => 'textfield',
				'param_name' => 'what_search',
				'heading' => __('Default keywords', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		), */
		array(
				'type' => 'textfield',
				'param_name' => 'keywords_placeholder',
				'heading' => __('Keywords placeholder', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_categories_search',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show categories search?', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'categories_search_level',
				'value' => array('1', '2', '3'),
				'std' => '2',
				'heading' => __('Categories search depth level in search', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'categoryfield',
				'param_name' => 'category',
				'heading' => __('Select certain category in search', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'categoriesfield',
				'param_name' => 'exact_categories',
				'heading' => __('List of categories in search', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_locations_search',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show locations search?', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'locations_search_level',
				'value' => array('1', '2', '3'),
				'std' => '2',
				'heading' => __('Locations search depth level', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'locationfield',
				'param_name' => 'location',
				'heading' => __('Select certain location on the search form.', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'locationsfield',
				'param_name' => 'exact_locations',
				'heading' => __('List of locations on the search form.', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_address_search',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show address search', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'address',
				'heading' => __('Default address in the search form, recommended to set default radius.', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'address_placeholder',
				'heading' => __('Adress placeholder', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'show_radius_search',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show locations radius search', 'W2GM'),
				'dependency' => array('element' => 'search_on_map', 'value' => '1'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'radius',
				'heading' => __('Default radius search. Display listings near provided address within this radius in miles or kilometers.', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'radius_circle',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Show radius circle', 'W2GM'),
				'description' => __('Display radius circle on map when radius filter provided.', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'clusters',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Group map markers in clusters', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'enable_full_screen',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Enable full screen button', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'enable_full_screen_by_default',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Map full screen opened by default', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'enable_wheel_zoom',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Enable zoom by mouse wheel', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'enable_dragging_touchscreens',
				'value' => array(__('Yes', 'W2GM') => '1', __('No', 'W2GM') => '0'),
				'heading' => __('Enable map dragging on touch screen devices', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'center_map_onclick',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Center map on marker click', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'zoom_map_onclick',
				'heading' => __('Zoom map on marker click', 'W2GM'),
				'description' => __('Does not work on AJAX loading maps', 'W2GM'),
				'value' => array(__("Auto", "W2GM") => '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19'),
				'std' => '0',
		),
		array(
				'type' => 'textfield',
				'param_name' => 'author',
				'heading' => __('Author', 'W2GM'),
				'description' => __('Enter ID of author', 'W2GM'),
		),
		array(
				'type' => 'categoriesfield',
				'param_name' => 'categories',
				'heading' => __('Select listings categories to display on map', 'W2GM'),
		),
		array(
				'type' => 'locationsfield',
				'param_name' => 'locations',
				'heading' => __('Select listings locations to display on map', 'W2GM'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'include_categories_children',
				'value' => array(__('No', 'W2GM') => '0', __('Yes', 'W2GM') => '1'),
				'heading' => __('Include children of selected categories and locations', 'W2GM'),
				'description' => __('When enabled - any subcategories or sublocations will be included as well.', 'W2GM'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'post__in',
				'heading' => __('Exact listings', 'W2GM'),
				'description' => __('Comma separated string of listings IDs. Possible to display exact listings.', 'W2GM'),
		),
);

class w2gm_map_widget extends w2gm_widget {

	public function __construct() {
		global $w2gm_instance, $w2gm_map_widget_params;

		parent::__construct(
				'w2gm_map_widget',
				__('Google Maps locator - Map', 'W2GM'),
				__('Map', 'W2GM')
		);

		foreach ($w2gm_instance->search_fields->filter_fields_array AS $filter_field) {
			if (method_exists($filter_field, 'getVCParams') && ($field_params = $filter_field->getVCParams())) {
				$w2gm_map_widget_params = array_merge($w2gm_map_widget_params, $field_params);
			}
		}

		$this->convertParams($w2gm_map_widget_params);
	}
	
	public function render_widget($instance, $args) {
		global $w2gm_instance;
		
		$instance['include_get_params'] = 0;
	
		$title = apply_filters('widget_title', $instance['title']);
	
		echo $args['before_widget'];
		if (!empty($title)) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		echo '<div class="w2gm-content w2gm-widget w2gm-map-widget">';
		$controller = new w2gm_map_controller();
		$controller->init($instance);
		echo $controller->display();
		echo '</div>';
		echo $args['after_widget'];
	}
}
?>