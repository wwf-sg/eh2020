<?php

return array(
						array(
							'type' => 'toggle',
							'name' => 'draw_panel',
							'label' => __('Enable Draw Panel', 'W2GM'),
							'description' => __('Very important: MySQL version must be 5.6.1 and higher or MySQL server variable "thread stack" must be 256K and higher. Ask your hoster about it if "Draw Area" does not work.', 'W2GM'),
							'default' => 0
						),
						array(
							'type' => 'toggle',
							'name' => 'enable_full_screen',
							'label' => __('Enable full screen button', 'W2GM'),
							'default' => 1
						),
						array(
							'type' => 'toggle',
							'name' => 'enable_full_screen_by_default',
							'label' => __('Map full screen opened by default', 'W2GM'),
							'default' => 0
						),
						array(
							'type' => 'toggle',
							'name' => 'enable_wheel_zoom',
							'label' => __('Enable zoom by mouse wheel', 'W2GM'),
							'default' => 1
						),
						array(
							'type' => 'toggle',
							'name' => 'enable_dragging_touchscreens',
							'label' => __('Enable map dragging on touch screen devices', 'W2GM'),
							'default' => 1
						),
						array(
							'type' => 'toggle',
							'name' => 'center_map_onclick',
							'label' => __('Center map on marker click', 'W2GM'),
							'description' => __('Does not work on AJAX loading maps', 'W2GM'),
							'default' => 0
						),
						array(
							'type' => 'select',
							'name' => 'zoom_map_onclick',
							'label' => __('Zoom map on marker click', 'W2GM'),
							'description' => __('Does not work on AJAX loading maps', 'W2GM'),
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
								'type' => 'toggle',
								'name' => 'counter',
								'label' => __('Show locations counter', 'W2GM'),
								'default' => 1
						),
						array(
								'type' => 'textbox',
								'name' => 'counter_text',
								'label' => __('Counter text', 'W2GM'),
								'description' => __('Example: Number of locations %d', 'W2GM'),
								'default' => 'Number of locations %d'
						),
				);

?>