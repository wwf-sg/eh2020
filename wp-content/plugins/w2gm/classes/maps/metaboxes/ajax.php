<?php

return array(
		array(
				'type' => 'toggle',
				'name' => 'ajax_loading',
				'label' => __('AJAX loading', 'W2GM'),
				'description' => __('When map contains lots of markers - this may slow down map markers loading. Select AJAX to speed up loading. Requires Starting Point coordinates Latitude and Longitude', 'W2GM'),
				'default' => 0
		),
		array(
				'type' => 'toggle',
				'name' => 'ajax_markers_loading',
				'label' => __('Maps info window AJAX loading', 'W2GM'),
				'description' => __('This may additionaly speed up loading', 'W2GM'),
				'default' => 0
		),
		array(
				'type' => 'toggle',
				'name' => 'use_ajax_loader',
				'label' => __('Show spinner on AJAX requests', 'W2GM'),
				'default' => 0
		),
);

?>