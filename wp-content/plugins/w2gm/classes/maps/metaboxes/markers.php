<?php

$map_params = array(
		array(
				'type' => 'multiselect',
				'name' => 'categories',
				'label' => __('Select specific categories to display on the map', 'W2GM'),
				'items' => w2gm_getMetaboxOptionsTerms(W2GM_CATEGORIES_TAX)
		),
		array(
				'type' => 'multiselect',
				'name' => 'locations',
				'label' => __('Select specific locations to display on the map', 'W2GM'),
				'items' => w2gm_getMetaboxOptionsTerms(W2GM_LOCATIONS_TAX)
		),
		array(
				'type' => 'toggle',
				'name' => 'include_categories_children',
				'label' => __('Include categories and locations children', 'W2GM'),
				'description' => __('Include markers of all children of selected categories and locations, not only selected items', 'W2GM'),
				'default' => 0
		),
		array(
				'type' => 'textbox',
				'name' => 'author',
				'label' => __('Enter ID of author', 'W2GM'),
				'default' => ''
		),
		array(
				'type' => 'textbox',
				'name' => 'post__in',
				'label' => __('Exact listings', 'W2GM'),
				'description' => __('Comma separated string of listings IDs. Possible to display exact listings', 'W2GM'),
				'default' => ''
		),
);

global $w2gm_instance;

foreach ($w2gm_instance->search_fields->filter_fields_array AS $filter_field) {
	if (method_exists($filter_field, 'getMapManagerParams') && ($field_params = $filter_field->getMapManagerParams())) {
		$map_params = array_merge($map_params, $field_params);
	}
}

return $map_params;

?>