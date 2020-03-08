<?php

function w2gm_tax_dropdowns_menu_init($params) {
	$attrs = array_merge(array(
			'uID' => 0,
			'field_name' => '',
			'count' => true,
			'tax' => 'category',
			'hide_empty' => false,
			'exact_terms' => array(),
			'autocomplete_field' => '',
			'autocomplete_field_value' => '',
			'autocomplete_ajax' => false,
			'placeholder' => '',
			'depth' => 1,
			'term_id' => 0,
	), $params);
	extract($attrs);
	
	// unique ID need when we place some dropdowns groups on one page
	if (!$uID) {
		$uID = rand(1, 10000);
	}
	
	if (!$field_name) {
		$field_name = 'selected_tax[' . $uID . ']';
	}
	
	// we use array_merge with empty array because we need to flush keys in terms array
	if ($count) {
		$terms = array_merge(
				// there is a wp bug with pad_counts in get_terms function - so we use this construction
				wp_list_filter(
						get_categories(array(
								'taxonomy' => $tax,
								'pad_counts' => true,
								'hide_empty' => $hide_empty,
						)),
						array('parent' => 0)
				), array());
	} else {
		$terms = array_merge(
				get_categories(array(
						'taxonomy' => $tax,
						'pad_counts' => true,
						'hide_empty' => $hide_empty,
						'parent' => 0,
				)), array());
	}
	
	if ($terms) {
		foreach ($terms AS $id=>$term) {
			if ($exact_terms && (!in_array($term->term_id, $exact_terms) && !in_array($term->slug, $exact_terms))) {
				unset($terms[$id]);
			}
		}
		
		// when selected exact sub-categories of non-root category
		if (empty($terms) && !empty($exact_terms)) {
			if ($count) {
				// there is a wp bug with pad_counts in get_terms function - so we use this construction
				$terms = wp_list_filter(get_categories(array('taxonomy' => $tax, 'include' => $exact_terms, 'pad_counts' => true, 'hide_empty' => $hide_empty)));
			} else {
				$terms = get_categories(array('taxonomy' => $tax, 'include' => $exact_terms, 'pad_counts' => true, 'hide_empty' => $hide_empty));
			}
		}
		
		$selected_tax_text = '';
		if ($term_id) {
			if ($term = get_term($term_id)) {
				$selected_tax_text = $term->name;
				$parents = w2gm_get_term_parents($term_id, $tax, false, false, ', ');
				if ($parents) {
					$selected_tax_text .= ', ' . $parents;
				}
			}
		}
		
		echo '<div id="w2gm-tax-dropdowns-wrap-' . $uID . '" class="w2gm-tax-dropdowns-wrap">';
		echo '<input type="hidden" name="' . $field_name . '" id="selected_tax[' . $uID . ']" class="selected_tax_' . $tax . '" value="' . $term_id . '" />';
		echo '<input type="hidden" name="' . $field_name . '_text" id="selected_tax_text[' . $uID . ']" class="selected_tax_text_' . $tax . '" value="' . $selected_tax_text . '" />';
		if ($exact_terms) {
			echo '<input type="hidden" id="exact_terms[' . $uID . ']" value="' . addslashes(implode(',', $exact_terms)) . '" />';
		}
		if ($autocomplete_field) {
			$autocomplete_data = 'data-autocomplete-name="' . esc_attr($autocomplete_field) . '" data-autocomplete-value="' . esc_attr($autocomplete_field_value) . '" data-default-icon="' . w2gm_getDefaultTermIconUrl($tax) . '"';
			if ($autocomplete_ajax) {
				$autocomplete_data .= ' data-ajax-search=1';
			}
		} else {
			$autocomplete_data = '';
		}
		echo '<select class="w2gm-form-control w2gm-selectmenu-' . $tax . '" data-id="' . $uID . '" data-placeholder="' . esc_attr($placeholder) . '" ' . $autocomplete_data . '>';
		foreach ($terms AS $term) {
			
			$term_count = '';
			if ($count) {
				$term_count = 'data-count="' . $term->count . ' ' . _n("result", "results", $term->count, "W2GM") . '"';
			}
			
			$selected = '';
			if ($term->term_id == $term_id) {
				$selected = 'data-selected="selected"';
			}
			
			$icon = '';
			if ($icon_file = w2gm_getTermIconUrl($term->term_id)) {
				$icon = 'data-icon="' . $icon_file . '"';
			}

			echo '<option id="' . $term->slug . '" value="' . $term->term_id . '" data-name="' . $term->name  . '" data-sublabel="" ' . $selected . ' ' . $icon . ' ' . $term_count . '>' . $term->name . '</option>';
			if ($depth > 1) {
				echo _w2gm_tax_dropdowns_menu($tax, $term->term_id, $depth, 1, $term_id, $count, $exact_terms, $hide_empty);
			}
		}
		echo '</select>';
		echo '</div>';
	}
}

function _w2gm_tax_dropdowns_menu($tax, $parent = 0, $depth = 2, $current_level = 1, $term_id = null, $count = false, $exact_terms = array(), $hide_empty = false) {
	if ($count) {
		// there is a wp bug with pad_counts in get_terms function - so we use this construction
		$terms = wp_list_filter(
				get_categories(array(
						'taxonomy' => $tax,
						'pad_counts' => true,
						'hide_empty' => $hide_empty,
				)),
				array('parent' => $parent)
		);
	} else {
		$terms = get_categories(array(
				'taxonomy' => $tax,
				'pad_counts' => true,
				'hide_empty' => $hide_empty,
				'parent' => $parent,
		));
	}
	
	$html = '';
	if ($terms && ($depth == 0 || !is_numeric($depth) || $depth > $current_level)) {
		foreach ($terms AS $key=>$term) {
			if ($exact_terms && (!in_array($term->term_id, $exact_terms) && !in_array($term->slug, $exact_terms))) {
				unset($terms[$key]);
			}
		}
	
		if ($terms) {
			$current_level++;
			
			$sublabel = w2gm_get_term_parents($term->parent, $tax, false, false, ', ');

			foreach ($terms AS $term) {
				
				$term_count = '';
				if ($count) {
					$term_count = 'data-count="' . $term->count . ' ' . _n("result", "results", $term->count, "W2GM") . '"';
				}
				
				$selected = '';
				if ($term->term_id == $term_id) {
					$selected = 'data-selected="selected"';
				}
				
				$icon = '';
				if ($icon_file = w2gm_getTermIconUrl($term->term_id)) {
					$icon = 'data-icon="' . $icon_file . '"';
				}
			
				echo '<option id="' . $term->slug . '" value="' . $term->term_id . '" data-name="' . $term->name  . '" data-sublabel="' . $sublabel . '" ' . $selected . ' ' . $icon . ' ' . $term_count . '>' . $term->name . '</option>';
				if ($depth > $current_level) {
					echo _w2gm_tax_dropdowns_menu($tax, $term->term_id, $depth, $current_level, $term_id, $count, $exact_terms, $hide_empty);
				}
			}
		}
	}
	return $html;
}

function w2gm_tax_dropdowns_init($tax = 'category', $field_name = null, $term_id = null, $count = true, $labels = array(), $titles = array(), $uID = null, $exact_terms = array(), $hide_empty = false) {
	// unique ID need when we place some dropdowns groups on one page
	if (!$uID) {
		$uID = rand(1, 10000);
	}

	$localized_data[$uID] = array(
			'labels' => $labels,
			'titles' => $titles
	);
	echo "<script>w2gm_js_objects['tax_dropdowns_" . $uID . "'] = " . json_encode($localized_data) . "</script>";

	if (!is_null($term_id) && $term_id != 0) {
		$chain = array();
		$parent_id = $term_id;
		while ($parent_id != 0) {
			if ($term = get_term($parent_id, $tax)) {
				$chain[] = $term->term_id;
				$parent_id = $term->parent;
			} else {
				break;
			}
		}
	}
	$chain[] = 0;
	$chain = array_reverse($chain);

	if (!$field_name) {
		$field_name = 'selected_tax[' . $uID . ']';
	}

	echo '<div id="w2gm-tax-dropdowns-wrap-' . $uID . '" class="' . $tax . ' cs_count_' . (int)$count . ' cs_hide_empty_' . (int)$hide_empty . ' w2gm-tax-dropdowns-wrap">';
	echo '<input type="hidden" name="' . $field_name . '" id="selected_tax[' . $uID . ']" class="selected_tax_' . $tax . '" value="' . $term_id . '" />';
	echo '<input type="hidden" id="exact_terms[' . $uID . ']" value="' . addslashes(implode(',', $exact_terms)) . '" />';
	foreach ($chain AS $key=>$term_id) {
		if ($count) {
			// there is a wp bug with pad_counts in get_terms function - so we use this construction
			$terms = wp_list_filter(get_categories(array('taxonomy' => $tax, 'pad_counts' => true, 'hide_empty' => $hide_empty)), array('parent' => $term_id));
		} else {
			$terms = get_categories(array('taxonomy' => $tax, 'pad_counts' => true, 'hide_empty' => $hide_empty, 'parent' => $term_id));
		}

		if (!empty($terms)) {
			foreach ($terms AS $id=>$term) {
				if ($exact_terms && (!in_array($term->term_id, $exact_terms) && !in_array($term->slug, $exact_terms))) {
					unset($terms[$id]);
				}
			}

			// when selected exact sub-categories of non-root category
			if (empty($terms) && !empty($exact_terms)) {
				if ($count) {
					// there is a wp bug with pad_counts in get_terms function - so we use this construction
					$terms = wp_list_filter(get_categories(array('taxonomy' => $tax, 'include' => $exact_terms, 'pad_counts' => true, 'hide_empty' => $hide_empty)));
				} else {
					$terms = get_categories(array('taxonomy' => $tax, 'include' => $exact_terms, 'pad_counts' => true, 'hide_empty' => $hide_empty));
				}
			}

			if (!empty($terms)) {
				$level_num = $key + 1;
				echo '<div id="wrap_chainlist_' . $level_num . '_' .$uID . '" class="w2gm-row w2gm-form-group w2gm-location-input">';
	
					$label_name = '';
					if (isset($labels[$key])) {
						$label_name = $labels[$key];
					}
					echo '<div class="w2gm-col-md-2">';
					echo '<label class="w2gm-control-label" for="chainlist_' . $level_num . '_' . $uID . '">' . $label_name . '</label>';
					echo '</div>';
	
					if (isset($labels[$key])) {
					echo '<div class="w2gm-col-md-10">';
					} else {
					echo '<div class="w2gm-col-md-12">';
					}
						echo '<select id="chainlist_' . $level_num . '_' . $uID . '" class="w2gm-form-control w2gm-selectmenu">';
						echo '<option value="">- ' . ((isset($titles[$key])) ? $titles[$key] : __('Select term', 'W2GM')) . ' -</option>';
						foreach ($terms AS $term) {
							if ($count)
								$term_count = " ($term->count)";
							else
								 $term_count = '';
							if (isset($chain[$key+1]) && $term->term_id == $chain[$key+1]) {
								$selected = 'selected';
							} else
								$selected = '';
									
							if ($icon_file = w2gm_getTermIconUrl($term->term_id))
								$icon = 'data-class="term-icon" data-icon="' . $icon_file . '"';
							else
								$icon = '';
	
							echo '<option id="' . $term->slug . '" value="' . $term->term_id . '" ' . $selected . ' ' . $icon . '>' . $term->name . $term_count . '</option>';
						}
						echo '</select>';
					echo '</div>';
				echo '</div>';
			}
		}
	}
	echo '</div>';
}

function w2gm_tax_dropdowns_updateterms() {
	$parentid = w2gm_getValue($_POST, 'parentid');
	$next_level = w2gm_getValue($_POST, 'next_level');
	$tax = w2gm_getValue($_POST, 'tax');
	$count = w2gm_getValue($_POST, 'count');
	$hide_empty = w2gm_getValue($_POST, 'hide_empty');
	$exact_terms = array_filter(explode(',', w2gm_getValue($_POST, 'exact_terms')));
	if (!$label = w2gm_getValue($_POST, 'label'))
		$label = '';
	if (!$title = w2gm_getValue($_POST, 'title'))
		$title = __('Select term', 'W2GM');
	$uID = w2gm_getValue($_POST, 'uID');
	
	if ($hide_empty == 'cs_hide_empty_1') {
		$hide_empty = true;
	} else {
		$hide_empty = false;
	}

	if ($count == 'cs_count_1') {
		// there is a wp bug with pad_counts in get_terms function - so we use this construction
		$terms = wp_list_filter(get_categories(array('taxonomy' => $tax, 'pad_counts' => true, 'hide_empty' => $hide_empty)), array('parent' => $parentid));
	} else {
		$terms = get_categories(array('taxonomy' => $tax, 'pad_counts' => true, 'hide_empty' => $hide_empty, 'parent' => $parentid));
	}
	if (!empty($terms)) {
		foreach ($terms AS $id=>$term) {
			if ($exact_terms && (!in_array($term->term_id, $exact_terms) && !in_array($term->slug, $exact_terms))) {
				unset($terms[$id]);
			}
		}

		if (!empty($terms)) {
			echo '<div id="wrap_chainlist_' . $next_level . '_' . $uID . '" class="w2gm-row w2gm-form-group w2gm-location-input">';
	
				if ($label) {
					echo '<div class="w2gm-col-md-2">';
					echo '<label class="w2gm-control-label" for="chainlist_' . $next_level . '_' . $uID . '">' . $label . '</label>';
					echo '</div>';
				}
	
				if ($label) {
				echo '<div class="w2gm-col-md-10">';
				} else { 
				echo '<div class="w2gm-col-md-12">';
				}
					echo '<select id="chainlist_' . $next_level . '_' . $uID . '" class="w2gm-form-control w2gm-selectmenu">';
					echo '<option value="">- ' . $title . ' -</option>';
					foreach ($terms as $term) {
						if (!$exact_terms || (in_array($term->term_id, $exact_terms) || in_array($term->slug, $exact_terms))) {
							if ($count == 'cs_count_1') {
								$term_count = " ($term->count)";
							} else {
								$term_count = '';
							}
							
							if ($icon_file = w2gm_getTermIconUrl($term->term_id))
								$icon = 'data-class="term-icon" data-icon="' . $icon_file . '"';
							else
								$icon = '';
							
							echo '<option id="' . $term->slug . '" value="' . $term->term_id . '" ' . $icon . '>' . $term->name . $term_count . '</option>';
						}
					}
					echo '</select>';
				echo '</div>';
			echo '</div>';
		}
	}
	die();
}

function w2gm_renderOptionsTerms($tax, $parent, $selected_terms, $level = 0) {
	$terms = get_terms($tax, array('parent' => $parent, 'hide_empty' => false));

	foreach ($terms AS $term) {
		echo '<option value="' . $term->term_id . '" ' . (($selected_terms && (in_array($term->term_id, $selected_terms) || in_array($term->slug, $selected_terms))) ? 'selected' : '') . '>' . (str_repeat('&nbsp;&nbsp;&nbsp;', $level)) . $term->name . '</option>';
		w2gm_renderOptionsTerms($tax, $term->term_id, $selected_terms, $level+1);
	}
	return $terms;
}
function w2gm_termsSelectList($name, $tax = 'category', $selected_terms = array()) {
	echo '<select multiple="multiple" name="' . $name . '[]" class="selected_terms_list w2gm-form-control w2gm-form-group" style="height: 300px">';
	echo '<option value="" ' . ((!$selected_terms) ? 'selected' : '') . '>' . __('- Select All -', 'W2GM') . '</option>';

	w2gm_renderOptionsTerms($tax, 0, $selected_terms);

	echo '</select>';
}

function w2gm_recaptcha() {
	if (get_option('w2gm_enable_recaptcha') && get_option('w2gm_recaptcha_public_key') && get_option('w2gm_recaptcha_private_key')) {
		return '<div class="g-recaptcha" data-sitekey="'.get_option('w2gm_recaptcha_public_key').'"></div>';
	}
}

function w2gm_is_recaptcha_passed() {
	if (get_option('w2gm_enable_recaptcha') && get_option('w2gm_recaptcha_public_key') && get_option('w2gm_recaptcha_private_key')) {
		if (isset($_POST['g-recaptcha-response']))
			$captcha = $_POST['g-recaptcha-response'];
		else
			return false;
		
		$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=".get_option('w2gm_recaptcha_private_key')."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
		if (!is_wp_error($response)) {
			$body = wp_remote_retrieve_body($response);
			$json = json_decode($body);
			if ($json->success === false)
				return false;
			else
				return true;
		} else
			return false;
	} else
		return true;
}

function w2gm_orderingItems() {
	global $w2gm_instance;

	$ordering = array('post_date' => __('Date', 'W2GM'), 'title' => __('Title', 'W2GM'), 'rand' => __('Random', 'W2GM'));
	$content_fields = $w2gm_instance->content_fields->getOrderingContentFields();
	foreach ($content_fields AS $content_field) {
		$ordering[$content_field->slug] = $content_field->name;
	}
	$ordering = apply_filters('w2gm_default_orderby_options', $ordering);
	$ordering_items = array();
	foreach ($ordering AS $field_slug=>$field_name) {
		$ordering_items[] = array('value' => $field_slug, 'label' => $field_name);
	}
	
	return $ordering_items;
}

function w2gm_terms_checklist($post_id) {
	if ($terms = get_categories(array('taxonomy' => W2GM_CATEGORIES_TAX, 'pad_counts' => true, 'hide_empty' => false, 'parent' => 0))) {
		$checked_categories_ids = array();
		$checked_categories = wp_get_object_terms($post_id, W2GM_CATEGORIES_TAX);
		foreach ($checked_categories AS $term)
			$checked_categories_ids[] = $term->term_id;

		echo '<ul id="w2gm-categorychecklist" class="w2gm-categorychecklist">';
		foreach ($terms AS $term) {
			$classes = '';
			$checked = '';
			if (in_array($term->term_id, $checked_categories_ids)) {
				$checked = 'checked';
			}
			
			if (defined('W2GM_EXPANDED_CATEGORIES_TREE') && W2GM_EXPANDED_CATEGORIES_TREE) {
				$classes .= 'active ';
			}
				
			echo '<li id="' . W2GM_CATEGORIES_TAX . '-' . $term->term_id . '" class="' . $classes . '">';
			echo '<label class="selectit"><input type="checkbox" ' . $checked . ' id="in-' . W2GM_CATEGORIES_TAX . '-' . $term->term_id . '" name="tax_input[' . W2GM_CATEGORIES_TAX . '][]" value="' . $term->term_id . '"> ' . $term->name . '</label>';
			echo _w2gm_terms_checklist($term->term_id, $checked_categories_ids);
			echo '</li>';
		}
		echo '</ul>';
	}
}
function _w2gm_terms_checklist($parent = 0, $checked_categories_ids = array()) {
	$html = '';
	if ($terms = get_categories(array('taxonomy' => W2GM_CATEGORIES_TAX, 'pad_counts' => true, 'hide_empty' => false, 'parent' => $parent))) {
		$html .= '<ul class="children">';
		foreach ($terms AS $term) {
			$checked = '';
			if (in_array($term->term_id, $checked_categories_ids)) {
				$checked = 'checked';
			}
			
			$classes = '';
			if (defined('W2GM_EXPANDED_CATEGORIES_TREE') && W2GM_EXPANDED_CATEGORIES_TREE) {
				$classes .= 'active ';
			}

			$html .= '<li id="' . W2GM_CATEGORIES_TAX . '-' . $term->term_id . '" class="' . $classes . '">';
			$html .= '<label class="selectit"><input type="checkbox" ' . $checked . ' id="in-' . W2GM_CATEGORIES_TAX . '-' . $term->term_id . '" name="tax_input[' . W2GM_CATEGORIES_TAX . '][]" value="' . $term->term_id . '"> ' . $term->name . '</label>';
			$html .= _w2gm_terms_checklist($term->term_id, $checked_categories_ids);
			$html .= '</li>';
		}
		$html .= '</ul>';
	}
	return $html;
}

function w2gm_tags_selectbox($post_id) {
	$terms = get_categories(array('taxonomy' => W2GM_TAGS_TAX, 'pad_counts' => true, 'hide_empty' => false));
	$checked_tags_ids = array();
	$checked_tags_names = array();
	$checked_tags = wp_get_object_terms($post_id, W2GM_TAGS_TAX);
	foreach ($checked_tags AS $term) {
		$checked_tags_ids[] = $term->term_id;
		$checked_tags_names[] = $term->name;
	}

	echo '<select name="' . W2GM_TAGS_TAX . '[]" multiple="multiple" class="w2gm-tokenizer">';
	foreach ($terms AS $term) {
		$checked = '';
		if (in_array($term->term_id, $checked_tags_ids))
			$checked = 'selected';
		echo '<option value="' . esc_attr($term->name) . '" ' . $checked . '>' . $term->name . '</option>';
	}
	echo '</select>';
}

function w2gm_getTermIconUrl($term_id) {
	$term = get_term($term_id);

	if (!is_wp_error($term)) {
		if ($term->taxonomy == W2GM_CATEGORIES_TAX && ($category_icon = w2gm_getCategoryIconFile($term_id))) {
			return W2GM_CATEGORIES_ICONS_URL . $category_icon;
		}
		if ($term->taxonomy == W2GM_LOCATIONS_TAX && ($location_icon = w2gm_getLocationIconFile($term_id))) {
			return W2GM_LOCATIONS_ICONS_URL . $location_icon;
		}
	}
}

function w2gm_getDefaultTermIconUrl($tax) {
	if ($tax == W2GM_CATEGORIES_TAX) {
		return W2GM_CATEGORIES_ICONS_URL . 'search.png';
	}
	if ($tax == W2GM_LOCATIONS_TAX) {
		return W2GM_LOCATIONS_ICONS_URL . 'icon1.png';
	}
}

function w2gm_show_404() {
	status_header(404);
	nocache_headers();
	include(get_404_template());
	exit;
}


if (!function_exists('w2gm_renderPaginator')) {
	function w2gm_renderPaginator($query) {
		global $w2gm_instance;

		if (get_class($query) == 'WP_Query') {
			if (get_query_var('page'))
				$paged = get_query_var('page');
			elseif (get_query_var('paged'))
				$paged = get_query_var('paged');
			else
				$paged = 1;

			$total_pages = $query->max_num_pages;
			$total_lines = ceil($total_pages/10);
		
			if ($total_pages > 1){
				$current_page = max(1, $paged);
				$current_line = floor(($current_page-1)/10) + 1;
		
				$previous_page = $current_page - 1;
				$next_page = $current_page + 1;
				$previous_line_page = floor(($current_page-1)/10)*10;
				$next_line_page = ceil($current_page/10)*10 + 1;
				
				echo '<div class="w2gm-pagination-wrapper">';
				echo '<ul class="w2gm-pagination">';
				if ($total_pages > 10 && $current_page > 10)
					echo '<li class="w2gm-inactive previous_line"><a href="' . get_pagenum_link($previous_line_page) . '" title="' . esc_attr__('Previous Line', 'W2GM') . '" data-page=' . $previous_line_page . '><<</a></li>' ;
			
				if ($total_pages > 3 && $current_page > 1)
					echo '<li class="w2gm-inactive previous"><a href="' . get_pagenum_link($previous_page) . '" title="' . esc_attr__('Previous Page', 'W2GM') . '" data-page=' . $previous_page . '><</i></a></li>' ;
			
				$count = ($current_line-1)*10;
				$end = ($total_pages < $current_line*10) ? $total_pages : $current_line*10;
				while ($count < $end) {
					$count = $count + 1;
					if ($count == $current_page)
						echo '<li class="w2gm-active"><a href="' . get_pagenum_link($count) . '">' . $count . '</a></li>' ;
					else
						echo '<li class="w2gm-inactive"><a href="' . get_pagenum_link($count) . '" data-page=' . $count . '>' . $count . '</a></li>' ;
				}
			
				if ($total_pages > 3 && $current_page < $total_pages)
					echo '<li class="w2gm-inactive next"><a href="' . get_pagenum_link($next_page) . '" title="' . esc_attr__('Next Page', 'W2GM') . '" data-page=' . $next_page . '>></i></a></li>' ;
			
				if ($total_pages > 10 && $current_line < $total_lines)
					echo '<li class="w2gm-inactive next_line"><a href="' . get_pagenum_link($next_line_page) . '" title="' . esc_attr__('Next Line', 'W2GM') . '" data-page=' . $next_line_page . '>>></a></li>' ;
			
				echo '</ul>';
				echo '</div>';
			}
		}
	}
}

function w2gm_renderSharingButton($post_id, $button) {
	global $w2gm_social_services;

	$post_title = urlencode(get_the_title($post_id));
	$thumb_url = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), array(200, 200));
	$post_thumbnail = urlencode($thumb_url[0]);
	if (get_post_type($post_id) == W2GM_POST_TYPE) {
		$listing = new w2gm_listing;
		if ($listing->loadListingFromPost($post_id))
			$post_title = urlencode($listing->title());
	}
	$post_url = urlencode(get_permalink($post_id));

	if (isset($w2gm_social_services[$button])) {
		$share_url = false;
		$share_counter = false;
		switch ($button) {
			case 'facebook':
				$share_url = 'http://www.facebook.com/sharer.php?u=' . $post_url;
				if (get_option('w2gm_share_counter')) {
					$response = wp_remote_get('http://graph.facebook.com/?id=' . $post_url);
					if (!is_wp_error($response)) {
						$body = wp_remote_retrieve_body($response);
						$json = json_decode($body);
						$share_counter = (isset($json->share->share_count)) ? intval($json->share->share_count) : 0;
					}
				}
			break;
			case 'twitter':
				$share_url = 'http://twitter.com/share?url=' . $post_url . '&amp;text=' . $post_title;
				/* if (get_option('w2gm_share_counter')) {
					$response = wp_remote_get('https://urls.api.twitter.com/1/urls/count.json?url=' . $post_url);
					if (!is_wp_error($response)) {
						$body = wp_remote_retrieve_body($response);
						$json = json_decode($body);
						$share_counter = (isset($json->count)) ? intval($json->count) : 0;
					}
				} */
			break;
			case 'google':
				$share_url = 'https://plus.google.com/share?url=' . $post_url;
				if (get_option('w2gm_share_counter')) {
					$args = array(
				            'method' => 'POST',
				            'headers' => array(
				                'Content-Type' => 'application/json'
				            ),
				            'body' => json_encode(array(
				                'method' => 'pos.plusones.get',
				                'id' => 'p',
				                'method' => 'pos.plusones.get',
				                'jsonrpc' => '2.0',
				                'key' => 'p',
				                'apiVersion' => 'v1',
				                'params' => array(
				                    'nolog' => true,
				                    'id' => $post_url,
				                    'source' => 'widget',
				                    'userId' => '@viewer',
				                    'groupId' => '@self'
				                ) 
				             )),          
				            'sslverify'=>false
				        ); 
				    $response = wp_remote_post("https://clients6.google.com/rpc", $args);
					if (!is_wp_error($response)) {
						$body = wp_remote_retrieve_body($response);
						$json = json_decode($body);
						$share_counter = (isset($json->result->metadata->globalCounts->count)) ? intval($json->result->metadata->globalCounts->count) : 0;
					}
				}
			break;
			case 'digg':
				$share_url = 'http://www.digg.com/submit?url=' . $post_url;
			break;
			case 'reddit':
				$share_url = 'http://reddit.com/submit?url=' . $post_url . '&amp;title=' . $post_title;
				if (get_option('w2gm_share_counter')) {
					$response = wp_remote_get('https://www.reddit.com/api/info.json?url=' . $post_url);
					if (!is_wp_error($response)) {
						$body = wp_remote_retrieve_body($response);
						$json = json_decode($body);
						$share_counter = (isset($json->data->children[0]->data->score)) ? intval($json->data->children[0]->data->score) : 0;
					}
				}
			break;
			case 'linkedin':
				$share_url = 'http://www.linkedin.com/shareArticle?mini=true&amp;url=' . $post_url;
				if (get_option('w2gm_share_counter')) {
					$response = wp_remote_get('https://www.linkedin.com/countserv/count/share?url=' . $post_url . '&format=json');
					if (!is_wp_error($response)) {
						$body = wp_remote_retrieve_body($response);
						$json = json_decode($body);
						$share_counter = (isset($json->count)) ? intval($json->count) : 0;
					}
				}
			break;
			case 'pinterest':
				$share_url = 'https://www.pinterest.com/pin/create/button/?url=' . $post_url . '&amp;media=' . $post_thumbnail . '&amp;description=' . $post_title;
				if (get_option('w2gm_share_counter')) {
					$response = wp_remote_get('https://api.pinterest.com/v1/urls/count.json?url=' . $post_url);
					if (!is_wp_error($response)) {
						$body = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $response['body']);
						$json = json_decode($body);
						$share_counter = (isset($json->count)) ? intval($json->count) : 0;
					}
				}
			break;
			case 'stumbleupon':
				$share_url = 'http://www.stumbleupon.com/submit?url=' . $post_url . '&amp;title=' . $post_title;
				if (get_option('w2gm_share_counter')) {
					$response = wp_remote_get('https://www.stumbleupon.com/services/1.01/badge.getinfo?url=' . $post_url);
					if (!is_wp_error($response)) {
						$body = wp_remote_retrieve_body($response);
						$json = json_decode($body);
						$share_counter = (isset($json->result->views)) ? intval($json->result->views) : 0;
					}
				}
			break;
			case 'tumblr':
				$share_url = 'http://www.tumblr.com/share/link?url=' . str_replace('http://', '', str_replace('https://', '', $post_url)) . '&amp;name=' . $post_title;
			break;
			case 'vk':
				$share_url = 'http://vkontakte.ru/share.php?url=' . $post_url;
				if (get_option('w2gm_share_counter')) {
					$response = wp_remote_get('https://vkontakte.ru/share.php?act=count&index=1&url=' . $post_url);
					if (!is_wp_error($response)) {
						$tmp = array();
						preg_match('/^VK.Share.count\(1, (\d+)\);$/i', $response['body'], $tmp);
						$share_counter = (isset($tmp[1])) ? intval($tmp[1]) : 0;
					}
				}
			break;
			case 'whatsapp':
				$share_url = 'https://wa.me/?text=' . $post_url;
			break;
			case 'telegram':
				$share_url = 'https://telegram.me/share/url?url=' . $post_url . '&text=' . $post_title;
			break;
			case 'viber':
				$share_url = 'viber://forward?text=' . $post_url;
			break;
			case 'email':
				$share_url = 'mailto:?Subject=' . $post_title . '&amp;Body=' . $post_url;
			break;
		}

		if ($share_url !== false) {
			echo '<a href="'.$share_url.'" data-toggle="w2gm-tooltip" data-placement="top" title="'.sprintf(__('Share on %s', 'W2GM'),  $w2gm_social_services[$button]['label']).'" target="_blank"><img src="'.W2GM_RESOURCES_URL.'images/social/'.get_option('w2gm_share_buttons_style').'/'.$button.'.png" /></a>';
			if (get_option('w2gm_share_counter') && $share_counter !== false)
				echo '<span class="w2gm-share-count">'.number_format($share_counter).'</span>';
		}
	}
}

function w2gm_hintMessage($message, $placement = 'auto', $return = false) {
	$out = '<a class="w2gm-hint-icon" href="javascript:void(0);" data-content="' . esc_attr($message) . '" data-html="true" rel="popover" data-placement="' . $placement . '" data-trigger="hover"></a>';
	if ($return) {
		return $out;
	} else {
		echo $out;
	}
}

?>