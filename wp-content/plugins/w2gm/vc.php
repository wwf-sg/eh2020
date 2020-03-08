<?php

add_action('vc_before_init', 'w2gm_vc_init');

function w2gm_vc_init() {
	global $w2gm_instance, $w2gm_fsubmit_instance;
	
	if (!isset($w2gm_instance->content_fields)) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		return ;
	}

	if (!function_exists('w2gm_ordering_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('ordering', 'w2gm_ordering_param');
		function w2gm_ordering_param($settings, $value) {
			$ordering = w2gm_orderingItems();

			$out = '<select id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value">';
			foreach ($ordering AS $ordering_item) {
				$out .= '<option value="' . $ordering_item['value'] . '" ' . selected($value, $ordering_item['value'], false) . '>' . $ordering_item['label'] . '</option>';
			}
			$out .= '</select>';
	
			return $out;
		}
	}

	if (!function_exists('w2gm_mapstyle_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('mapstyle', 'w2gm_mapstyle_param');
		function w2gm_mapstyle_param($settings, $value) {
			$out = '<select id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value">';
			$out .= '<option value="0" ' . ((!$value) ? 'selected' : 0) . '>' . __('Default', 'W2GM') . '</option>';
			$map_styles = array('default' => '');
			foreach (w2gm_getAllMapStyles() AS $name=>$style) {
				$out .= '<option value="' . $name . '" ' . selected($value, $name, false) . '>' . $name . '</option>';
			}
			$out .= '</select>';
	
			return $out;
		}
	}

	if (!function_exists('w2gm_categories_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('categoriesfield', 'w2gm_categories_param');
		function w2gm_categories_param($settings, $value) {
			$out = "<script>
				function updateTagChecked() { jQuery('#" . $settings['param_name'] . "').val(jQuery('#" . $settings['param_name'] . "_select').val()); }
		
				jQuery(function() {
					jQuery('#" . $settings['param_name'] . "_select option').click(updateTagChecked);
					updateTagChecked();
				});
			</script>";
		
			$out .= '<select multiple="multiple" id="' . $settings['param_name'] . '_select" name="' . $settings['param_name'] . '_select" style="height: 300px">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : '') . '>' . __('- Select All -', 'W2GM') . '</option>';
			ob_start();
			w2gm_renderOptionsTerms(W2GM_CATEGORIES_TAX, 0, explode(',', $value));
			$out .= ob_get_clean();
			$out .= '</select>';
			$out .= '<input type="hidden" id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value" value="' . $value . '" />';
		
			return $out;
		}
	}

	if (!function_exists('w2gm_category_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('categoryfield', 'w2gm_category_param');
		function w2gm_category_param($settings, $value) {
			$out = '<select id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : '') . '>' . __('- No category selected -', 'W2GM') . '</option>';
			ob_start();
			w2gm_renderOptionsTerms(W2GM_CATEGORIES_TAX, 0, array($value));
			$out .= ob_get_clean();
			$out .= '</select>';
		
			return $out;
		}
	}

	if (!function_exists('w2gm_locations_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('locationsfield', 'w2gm_locations_param');
		function w2gm_locations_param($settings, $value) {
			$out = "<script>
				function updateTagChecked() { jQuery('#" . $settings['param_name'] . "').val(jQuery('#" . $settings['param_name'] . "_select').val()); }
		
				jQuery(function() {
					jQuery('#" . $settings['param_name'] . "_select option').click(updateTagChecked);
					updateTagChecked();
				});
			</script>";
		
			$out .= '<select multiple="multiple" id="' . $settings['param_name'] . '_select" name="' . $settings['param_name'] . '_select" style="height: 300px">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : '') . '>' . __('- Select All -', 'W2GM') . '</option>';
			ob_start();
			w2gm_renderOptionsTerms(W2GM_LOCATIONS_TAX, 0, explode(',', $value));
			$out .= ob_get_clean();
			$out .= '</select>';
			$out .= '<input type="hidden" id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value" value="' . $value . '" />';
		
			return $out;
		}
	}

	if (!function_exists('w2gm_location_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('locationfield', 'w2gm_location_param');
		function w2gm_location_param($settings, $value) {
			$out = '<select id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : '') . '>' . __('- No location selected -', 'W2GM') . '</option>';
			ob_start();
			w2gm_renderOptionsTerms(W2GM_LOCATIONS_TAX, 0, array($value));
			$out .= ob_get_clean();
			$out .= '</select>';
		
			return $out;
		}
	}

	if (!function_exists('w2gm_content_fields_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('contentfields', 'w2gm_content_fields_param');
		function w2gm_content_fields_param($settings, $value) {
			global $w2gm_instance;
			$out = "<script>
				function updateTagChecked() { jQuery('#" . $settings['param_name'] . "').val(jQuery('#" . $settings['param_name'] . "_select').val()); }
		
				jQuery(function() {
					jQuery('#" . $settings['param_name'] . "_select option').click(updateTagChecked);
					updateTagChecked();
				});
			</script>";

			$content_fields_ids = explode(',', $value);
			$out .= '<select multiple="multiple" id="' . $settings['param_name'] . '_select" name="' . $settings['param_name'] . '_select" style="height: 300px">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : '') . '>' . __('- All content fields -', 'W2GM') . '</option>';
			$out .= '<option value="" ' . (($value == -1) ? 'selected' : '') . '>' . __('- No content fields -', 'W2GM') . '</option>';
			foreach ($w2gm_instance->search_fields->search_fields_array AS $search_field)
				$out .= '<option value="' . $search_field->content_field->id . '" ' . (in_array($search_field->content_field->id, $content_fields_ids) ? 'selected' : '') . '>' . $search_field->content_field->name . '</option>';
			$out .= '</select>';
			$out .= '<input type="hidden" id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value" value="' . $value . '" />';
		
			return $out;
		}
	}
	
	global $w2gm_levels_table_widget_params;
	if ($w2gm_fsubmit_instance) {
		$vc_submit_args = array(
			'name'                    => __('Listings submit', 'W2GM'),
			'description'             => __('Listings submission pages', 'W2GM'),
			'base'                    => 'webmap-submit',
			'icon'                    => W2GM_RESOURCES_URL . 'images/webmap.png',
			'show_settings_on_create' => false,
			'category'                => __('Maps Content', 'W2GM'),
			'params'                  => $w2gm_levels_table_widget_params
		);
		vc_map($vc_submit_args);

		vc_map( array(
			'name'                    => __('Users Dashboard', 'W2GM'),
			'description'             => __('Maps frontend dashboard', 'W2GM'),
			'base'                    => 'webmap-dashboard',
			'icon'                    => W2GM_RESOURCES_URL . 'images/webmap.png',
			'show_settings_on_create' => false,
			'category'                => __('Maps Content', 'W2GM'),
		));
		
		vc_map( array(
			'name'                    => __('Submit button', 'W2GM'),
			'description'             => __('Renders "Submit new listing" button', 'W2GM'),
			'base'                    => 'webmap-submit-button',
			'icon'                    => W2GM_RESOURCES_URL . 'images/webmap.png',
			'show_settings_on_create' => false,
			'category'                => __('Maps Content', 'W2GM'),
		));
	}

	global $w2gm_map_widget_params;
	$vc_maps_args = array(
			'name'                    => __('Google Map', 'W2GM'),
			'description'             => __('Google map and markers', 'W2GM'),
			'base'                    => 'webmap',
			'icon'                    => W2GM_RESOURCES_URL . 'images/webmap.png',
			'show_settings_on_create' => true,
			'category'                => __('Maps Content', 'W2GM'),
			'params'                  => $w2gm_map_widget_params
	);
	vc_map($vc_maps_args);

	global $w2gm_search_widget_params;
	$vc_search_args = array(
		'name'                    => __('Search form', 'W2GM'),
		'description'             => __('Maps listings search form', 'W2GM'),
		'base'                    => 'webmap-search',
		'icon'                    => W2GM_RESOURCES_URL . 'images/webmap.png',
		'show_settings_on_create' => false,
		'category'                => __('Maps Content', 'W2GM'),
		'params'                  => $w2gm_search_widget_params
	);
	vc_map($vc_search_args);

}

?>