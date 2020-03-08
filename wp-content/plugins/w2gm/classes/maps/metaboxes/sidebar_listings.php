<?php

return array(
						array(
								'type' => 'toggle',
								'name' => 'search_on_map',
								'label' => __('Enable search and listings sidebar', 'W2GM'),
								'default' => 1
						),
						array(
								'type' => 'toggle',
								'name' => 'search_on_map_open',
								'label' => __('Listings sidebar open by default', 'W2GM'),
								'default' => 0
						),
						array(
								'type' => 'select',
								'name' => 'order_by',
								'label' => __('Listings order by', 'W2GM'),
								'items' => w2gm_orderingItems(),
								'default' => array('post_date'),
						),
						array(
								'type' => 'select',
								'name' => 'order',
								'label' => __('Order direction', 'W2GM'),
								'items' => array(
										array(
												'value' => 'ASC',
												'label' => __('Ascending', 'W2GM'),
										),
										array(
												'value' => 'DESC',
												'label' => __('Descending', 'W2GM'),
										),
								),
								'default' => array('DESC'),
						),
				);

?>