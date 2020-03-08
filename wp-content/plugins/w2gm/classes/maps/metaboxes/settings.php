<?php

return array(
		array(
				'type' => 'textbox',
				'name' => 'uid',
				'label' => __('uID', 'W2GM'),
				'description' => __('Enter the same string in [webmap-search] shortcode to connect both shortcodes', 'W2GM'),
				'default' => ''
		),
		array(
				'type' => 'textbox',
				'name' => 'num',
				'label' => __('Max number of markers', 'W2GM'),
				'description' => __('Number of markers to display on map (empty field gives all markers)', 'W2GM'),
				'default' => ''
		),
		array(
				'type' => 'textbox',
				'name' => 'width',
				'label' => __('Map width', 'W2GM'),
				'description' => __('Set map width in pixels. With empty field the map will take all possible width', 'W2GM'),
				'default' => ''
		),
		array(
				'type' => 'textbox',
				'name' => 'height',
				'label' => __('Map height', 'W2GM'),
				'description' => __('Set map height in pixels, also possible to set 100% value', 'W2GM'),
				'default' => '400'
		),
		array(
				'type' => 'select',
				'name' => 'map_style',
				'label' => __('Google Map style', 'W2GM'),
				'items' => w2gm_getMetaboxMapsStyles(),
				'default' => array('default')
		),
		array(
				'type' => 'toggle',
				'name' => 'sticky_scroll',
				'label' => __('Make map to be sticky on scroll', 'W2GM'),
				'default' => 0
		),
		array(
				'type' => 'textbox',
				'name' => 'sticky_scroll_toppadding',
				'label' => __('Sticky scroll top padding', 'W2GM'),
				'description' => __('Top padding in pixels.', 'W2GM'),
				'default' => '0'
		),
		array(
				'type' => 'toggle',
				'name' => 'clusters',
				'label' => __('Group map markers in clusters', 'W2GM'),
				'default' => 0
		),
		array(
				'type' => 'toggle',
				'name' => 'geolocation',
				'label' => __('Enable automatic user Geolocation', 'W2GM'),
				'description' => __("Requires https", "W2GM"),
				'default' => 0
		),
		array(
				'type' => 'toggle',
				'name' => 'show_readmore_button',
				'label' => __('Show "Read more" button', 'W2GM'),
				'default' => 1
		),
		array(
				'type' => 'toggle',
				'name' => 'show_directions_button',
				'label' => __('Show "Directions" button', 'W2GM'),
				'default' => 1
		),
		array(
				'type' => 'toggle',
				'name' => 'directions_sidebar_open',
				'label' => __('Directions sidebar opened by default', 'W2GM'),
				'default' => 0
		),
);

?>