<?php

return array(
						array(
							'type' => 'toggle',
							'name' => 'use_starting_point',
							'label' => __('Use map starting point and zoom', 'W2GM'),
							'description' => __('Use map starting point or uncheck to fit map bounds showing all markers on load', 'W2GM'),
							'default' => 0,
						),
						array(
							'type' => 'textbox',
							'name' => 'start_latitude',
							'label' => __('Starting Point Latitude', 'W2GM'),
						),
						array(
							'type' => 'textbox',
							'name' => 'start_longitude',
							'label' => __('Starting Point Longitude', 'W2GM'),
						),
						array(
							'type' => 'select',
							'name' => 'start_zoom',
							'label' => __('Starting zoom', 'W2GM'),
							'items' => array(
								array(
									'value' => '0',
									'label' => __('Auto', 'W2GM'),
								),
								array('value' => '1', 'label' => '1'),
								array('value' => '2', 'label' => '2'),
								array('value' => '3', 'label' => '3'),
								array('value' => '4', 'label' => '4'),
								array('value' => '5', 'label' => '5'),
								array('value' => '6', 'label' => '6'),
								array('value' => '7', 'label' => '7'),
								array('value' => '8', 'label' => '8'),
								array('value' => '9', 'label' => '9'),
								array('value' => '10', 'label' => '10'),
								array('value' => '11', 'label' => '11'),
								array('value' => '12', 'label' => '12'),
								array('value' => '13', 'label' => '13'),
								array('value' => '14', 'label' => '14'),
								array('value' => '15', 'label' => '15'),
								array('value' => '16', 'label' => '16'),
								array('value' => '17', 'label' => '17'),
								array('value' => '18', 'label' => '18'),
								array('value' => '19', 'label' => '19'),
							),
							'default' => array('0'),
						),
						array(
								'type' => 'textbox',
								'name' => 'radius',
								'label' => sprintf(__('Show markers in radius (%s)', 'W2GM'), get_option('w2gm_miles_kilometers_in_search')),
								'description' => __('This will display ONLY markers around starting point', 'W2GM'),
								'default' => ''
						),
						array(
								'type' => 'toggle',
								'name' => 'radius_circle',
								'label' => __('Show radius circle', 'W2GM'),
								'description' => __('Display radius circle on map when radius filter provided', 'W2GM'),
								'default' => 1
						),
				);

?>