<?php 

$w2gm_color_schemes = array(
		'default' => array(
				'w2gm_primary_color' => '#2393ba',
				'w2gm_secondary_color' => '#1f82a5',
				'w2gm_links_color' => '#2393ba',
				'w2gm_links_hover_color' => '#2a6496',
				'w2gm_button_1_color' => '#2393ba',
				'w2gm_button_2_color' => '#1f82a5',
				'w2gm_button_text_color' => '#FFFFFF',
				'w2gm_search_bg_color' => '#6bc8c8',
				'w2gm_search_text_color' => '#FFFFFF',
				'w2gm_jquery_ui_schemas' => 'redmond',
		),
		'blue' => array(
				'w2gm_primary_color' => '#194df2',
				'w2gm_secondary_color' => '#8895a2',
				'w2gm_links_color' => '#96a1ad',
				'w2gm_links_hover_color' => '#2a6496',
				'w2gm_button_1_color' => '#96a1ad',
				'w2gm_button_2_color' => '#8895a2',
				'w2gm_button_text_color' => '#FFFFFF',
				'w2gm_search_bg_color' => '#499df5',
				'w2gm_search_text_color' => '#FFFFFF',
				'w2gm_jquery_ui_schemas' => 'start',
		),
		'gray' => array(
				'w2gm_primary_color' => '#acc7a6',
				'w2gm_secondary_color' => '#2d8ab7',
				'w2gm_links_color' => '#3299cb',
				'w2gm_links_hover_color' => '#236b8e',
				'w2gm_button_1_color' => '#3299cb',
				'w2gm_button_2_color' => '#2d8ab7',
				'w2gm_button_text_color' => '#FFFFFF',
				'w2gm_search_bg_color' => '#cfdbc5',
				'w2gm_search_text_color' => '#FFFFFF',
				'w2gm_jquery_ui_schemas' => 'overcast',
		),
		'green' => array(
				'w2gm_primary_color' => '#6cc150',
				'w2gm_secondary_color' => '#64933d',
				'w2gm_links_color' => '#5b9d30',
				'w2gm_links_hover_color' => '#64933d',
				'w2gm_button_1_color' => '#5b9d30',
				'w2gm_button_2_color' => '#64933d',
				'w2gm_button_text_color' => '#FFFFFF',
				'w2gm_search_bg_color' => '#c3ff88',
				'w2gm_search_text_color' => '#575757',
				'w2gm_jquery_ui_schemas' => 'le-frog',
		),
		'orange' => array(
				'w2gm_primary_color' => '#ff6600',
				'w2gm_secondary_color' => '#404040',
				'w2gm_links_color' => '#4d4d4d',
				'w2gm_links_hover_color' => '#000000',
				'w2gm_button_1_color' => '#4d4d4d',
				'w2gm_button_2_color' => '#404040',
				'w2gm_button_text_color' => '#FFFFFF',
				'w2gm_search_bg_color' => '#ff8000',
				'w2gm_search_text_color' => '#FFFFFF',
				'w2gm_jquery_ui_schemas' => 'ui-lightness',
		),
		'yellow' => array(
				'w2gm_primary_color' => '#a99d1a',
				'w2gm_secondary_color' => '#868600',
				'w2gm_links_color' => '#b8b900',
				'w2gm_links_hover_color' => '#868600',
				'w2gm_button_1_color' => '#b8b900',
				'w2gm_button_2_color' => '#868600',
				'w2gm_button_text_color' => '#FFFFFF',
				'w2gm_search_bg_color' => '#ffff8d',
				'w2gm_search_text_color' => '#575757',
				'w2gm_jquery_ui_schemas' => 'sunny',
		),
		'red' => array(
				'w2gm_primary_color' => '#679acd',
				'w2gm_secondary_color' => '#cb4862',
				'w2gm_links_color' => '#ed4e6e',
				'w2gm_links_hover_color' => '#cb4862',
				'w2gm_button_1_color' => '#ed4e6e',
				'w2gm_button_2_color' => '#cb4862',
				'w2gm_button_text_color' => '#FFFFFF',
				'w2gm_search_bg_color' => '#476583',
				'w2gm_search_text_color' => '#FFFFFF',
				'w2gm_jquery_ui_schemas' => 'blitzer',
		),
);
global $w2gm_color_schemes;

function w2gm_affect_setting_w2gm_links_color($scheme) {
	global $w2gm_color_schemes;
	return $w2gm_color_schemes[$scheme]['w2gm_links_color'];
}
VP_W2GM_Security::instance()->whitelist_function('w2gm_affect_setting_w2gm_links_color');

function w2gm_affect_setting_w2gm_links_hover_color($scheme) {
	global $w2gm_color_schemes;
	return $w2gm_color_schemes[$scheme]['w2gm_links_hover_color'];
}
VP_W2GM_Security::instance()->whitelist_function('w2gm_affect_setting_w2gm_links_hover_color');

function w2gm_affect_setting_w2gm_button_1_color($scheme) {
	global $w2gm_color_schemes;
	return $w2gm_color_schemes[$scheme]['w2gm_button_1_color'];
}
VP_W2GM_Security::instance()->whitelist_function('w2gm_affect_setting_w2gm_button_1_color');

function w2gm_affect_setting_w2gm_button_2_color($scheme) {
	global $w2gm_color_schemes;
	return $w2gm_color_schemes[$scheme]['w2gm_button_2_color'];
}
VP_W2GM_Security::instance()->whitelist_function('w2gm_affect_setting_w2gm_button_2_color');

function w2gm_affect_setting_w2gm_button_text_color($scheme) {
	global $w2gm_color_schemes;
	return $w2gm_color_schemes[$scheme]['w2gm_button_text_color'];
}
VP_W2GM_Security::instance()->whitelist_function('w2gm_affect_setting_w2gm_button_text_color');

function w2gm_affect_setting_w2gm_search_bg_color($scheme) {
	global $w2gm_color_schemes;
	return $w2gm_color_schemes[$scheme]['w2gm_search_bg_color'];
}
VP_W2GM_Security::instance()->whitelist_function('w2gm_affect_setting_w2gm_search_bg_color');

function w2gm_affect_setting_w2gm_search_text_color($scheme) {
	global $w2gm_color_schemes;
	return $w2gm_color_schemes[$scheme]['w2gm_search_text_color'];
}
VP_W2GM_Security::instance()->whitelist_function('w2gm_affect_setting_w2gm_search_text_color');

function w2gm_affect_setting_w2gm_primary_color($scheme) {
	global $w2gm_color_schemes;
	return $w2gm_color_schemes[$scheme]['w2gm_primary_color'];
}
VP_W2GM_Security::instance()->whitelist_function('w2gm_affect_setting_w2gm_primary_color');

function w2gm_affect_setting_w2gm_secandary_color($scheme) {
	global $w2gm_color_schemes;
	return $w2gm_color_schemes[$scheme]['w2gm_secandary_color'];
}
VP_W2GM_Security::instance()->whitelist_function('w2gm_affect_setting_w2gm_secandary_color');

function w2gm_affect_setting_w2gm_jquery_ui_schemas($scheme) {
	global $w2gm_color_schemes;
	return $w2gm_color_schemes[$scheme]['w2gm_jquery_ui_schemas'];
}
VP_W2GM_Security::instance()->whitelist_function('w2gm_affect_setting_w2gm_jquery_ui_schemas');

function w2gm_get_dynamic_option($option_name) {
	global $w2gm_color_schemes;

	if (isset($_COOKIE['w2gm_compare_palettes']) && $_COOKIE['w2gm_compare_palettes']) {
		$scheme = $_COOKIE['w2gm_compare_palettes'];
		if (isset($w2gm_color_schemes[$scheme][$option_name]))
			return $w2gm_color_schemes[$scheme][$option_name];
		else 
			return get_option($option_name);
	} else
		return get_option($option_name);
}

?>