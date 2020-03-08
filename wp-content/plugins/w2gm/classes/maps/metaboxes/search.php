<?php


return array(
						array(
								'type' => 'toggle',
								'name' => 'show_keywords_search',
								'label' => __('Enable keywords search', 'W2GM'),
								'default' => 1
						),
						array(
								'type' => 'toggle',
								'name' => 'keywords_ajax_search',
								'label' => __('Enable listings autosuggestions by keywords', 'W2GM'),
								'default' => 1
						),
						array(
								'type' => 'textbox',
								'name' => 'keywords_placeholder',
								'label' => __('Default keywords', 'W2GM'),
								'default' => '',
						),
						array(
								'type' => 'toggle',
								'name' => 'show_categories_search',
								'label' => __('Enable categories search', 'W2GM'),
								'default' => 1
						),
						array(
								'type' => 'radiobutton',
								'name' => 'categories_search_level',
								'label' => __('Categories search depth level', 'W2GM'),
								'items' => array(
										array(
												'value' => '1',
												'label' => '1',
										),
										array(
												'value' => '2',
												'label' => '2',
										),
										array(
												'value' => '3',
												'label' => '3',
										),
								),
								'default' => array('1')
						),
						array(
								'type' => 'select',
								'name' => 'category',
								'label' => __('Select certain category in search', 'W2GM'),
								'items' => w2gm_getMetaboxOptionsTerms(W2GM_CATEGORIES_TAX)
						),
						array(
								'type' => 'multiselect',
								'name' => 'exact_categories',
								'label' => __('List of categories in search', 'W2GM'),
								'items' => w2gm_getMetaboxOptionsTerms(W2GM_CATEGORIES_TAX)
						),
						array(
								'type' => 'toggle',
								'name' => 'show_locations_search',
								'label' => __('Enable locations search', 'W2GM'),
								'default' => 1
						),
						array(
								'type' => 'radiobutton',
								'name' => 'locations_search_level',
								'label' => __('Locations search depth level', 'W2GM'),
								'items' => array(
										array(
												'value' => '1',
												'label' => '1',
										),
										array(
												'value' => '2',
												'label' => '2',
										),
										array(
												'value' => '3',
												'label' => '3',
										),
								),
								'default' => array('1')
						),
						array(
								'type' => 'select',
								'name' => 'location',
								'label' => __('Select certain location in search', 'W2GM'),
								'items' => w2gm_getMetaboxOptionsTerms(W2GM_LOCATIONS_TAX)
						),
						array(
								'type' => 'multiselect',
								'name' => 'exact_locations',
								'label' => __('List of locations in search', 'W2GM'),
								'items' => w2gm_getMetaboxOptionsTerms(W2GM_LOCATIONS_TAX)
						),
						array(
								'type' => 'toggle',
								'name' => 'show_address_search',
								'label' => __('Enable address search', 'W2GM'),
								'default' => 1
						),
						array(
								'type' => 'textbox',
								'name' => 'address_placeholder',
								'label' => __('Default address', 'W2GM'),
								'default' => '',
						),
						array(
								'type' => 'toggle',
								'name' => 'show_radius_search',
								'label' => __('Enable radius search', 'W2GM'),
								'default' => 1
						),
				);

?>