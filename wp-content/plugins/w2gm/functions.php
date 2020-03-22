<?php 

if (!function_exists('w2gm_getValue')) {
	function w2gm_getValue($target, $key, $default = false) {
		$target = is_object($target) ? (array) $target : $target;
	
		if (is_array($target) && isset($target[$key])) {
			$value = $target[$key];
		} else {
			$value = $default;
		}
	
		$value = apply_filters('w2gm_get_value', $value, $target, $key, $default);
		return $value;
	}
}

if (!function_exists('w2gm_sessionStart')) {
	function w2gm_sessionStart() {
		// REST API does not accept session_start()
		if (strpos($_SERVER['REQUEST_URI'], '/wp-json/') === false) {
			if (version_compare(phpversion(), '5.4.0', '>=')) {
				if (session_status() == PHP_SESSION_NONE) {
					@session_start();
				}
			} else {
				if (session_id() == '') {
					@session_start();
				}
			}
		}
	}
}

if (!function_exists('w2gm_addMessage')) {
	function w2gm_addMessage($message, $type = 'updated') {
		global $w2gm_messages;
		
		if (is_array($message)) {
			foreach ($message AS $m) {
				w2gm_addMessage($m, $type);
			}
			return ;
		}
	
		if (!isset($w2gm_messages[$type]) || (isset($w2gm_messages[$type]) && !in_array($message, $w2gm_messages[$type]))) {
			$w2gm_messages[$type][] = $message;
		}
	
		if (!isset($_SESSION['w2gm_messages'][$type]) || (isset($_SESSION['w2gm_messages'][$type]) && !in_array($message, $_SESSION['w2gm_messages'][$type]))) {
			$_SESSION['w2gm_messages'][$type][] = $message;
		}
	}
}

if (!function_exists('w2gm_renderMessages')) {
	function w2gm_renderMessages() {
		global $w2gm_messages;
	
		$messages = array();
		if (isset($w2gm_messages) && is_array($w2gm_messages) && $w2gm_messages) {
			$messages = $w2gm_messages;
		}
	
		if (isset($_SESSION['w2gm_messages'])) {
			$messages = array_merge($messages, $_SESSION['w2gm_messages']);
		}
	
		$messages = w2gm_superUnique($messages);
	
		foreach ($messages AS $type=>$messages) {
			$message_class = (is_admin()) ? $type : "w2gm-" . $type;

			echo '<div class="' . $message_class . '">';
			foreach ($messages AS $message) {
				echo '<p>' . trim(preg_replace("/<p>(.*?)<\/p>/", "$1", $message)) . '</p>';
			}
			echo '</div>';
		}
		
		$w2gm_messages = array();
		if (isset($_SESSION['w2gm_messages'])) {
			unset($_SESSION['w2gm_messages']);
		}
	}
	function w2gm_superUnique($array) {
		$result = array_map("unserialize", array_unique(array_map("serialize", $array)));
		foreach ($result as $key => $value)
			if (is_array($value))
				$result[$key] = w2gm_superUnique($value);
		return $result;
	}
}

function w2gm_calcExpirationDate($date, $level) {
	$date = strtotime('+'.$level->active_period_days.' day', $date);
	$date = strtotime('+'.$level->active_period_months.' month', $date);
	$date = strtotime('+'.$level->active_period_years.' year', $date);
	
	return $date;
}

/**
 * Workaround the last day of month quirk in PHP's strtotime function.
 *
 * Adding +1 month to the last day of the month can yield unexpected results with strtotime().
 * For example:
 * - 30 Jan 2013 + 1 month = 3rd March 2013
 * - 28 Feb 2013 + 1 month = 28th March 2013
 *
 * What humans usually want is for the date to continue on the last day of the month.
 *
 * @param int $from_timestamp A Unix timestamp to add the months too.
 * @param int $months_to_add The number of months to add to the timestamp.
 */
function w2gm_addMonths($from_timestamp, $months_to_add) {
	$first_day_of_month = date('Y-m', $from_timestamp) . '-1';
	$days_in_next_month = date('t', strtotime("+ {$months_to_add} month", strtotime($first_day_of_month)));
	
	// Payment is on the last day of the month OR number of days in next billing month is less than the the day of this month (i.e. current billing date is 30th January, next billing date can't be 30th February)
	if (date('d m Y', $from_timestamp) === date('t m Y', $from_timestamp) || date('d', $from_timestamp) > $days_in_next_month) {
		for ($i = 1; $i <= $months_to_add; $i++) {
			$next_month = strtotime('+3 days', $from_timestamp); // Add 3 days to make sure we get to the next month, even when it's the 29th day of a month with 31 days
			$next_timestamp = $from_timestamp = strtotime(date('Y-m-t H:i:s', $next_month));
		}
	} else { // Safe to just add a month
		$next_timestamp = strtotime("+ {$months_to_add} month", $from_timestamp);
	}
	
	return $next_timestamp;
}

function w2gm_isResource($resource) {
	if (is_file(get_stylesheet_directory() . '/w2gm-plugin/resources/' . $resource)) {
		return get_stylesheet_directory_uri() . '/w2gm-plugin/resources/' . $resource;
	} elseif (is_file(W2GM_RESOURCES_PATH . $resource)) {
		return W2GM_RESOURCES_URL . $resource;
	}
	
	return false;
}

function w2gm_isCustomResourceDir($dir) {
	if (is_dir(get_stylesheet_directory() . '/w2gm-plugin/resources/' . $dir)) {
		return get_stylesheet_directory() . '/w2gm-plugin/resources/' . $dir;
	}
	
	return false;
}

function w2gm_getCustomResourceDirURL($dir) {
	if (is_dir(get_stylesheet_directory() . '/w2gm-plugin/resources/' . $dir)) {
		return get_stylesheet_directory_uri() . '/w2gm-plugin/resources/' . $dir;
	}
	
	return false;
}

/**
 * possible variants of templates and their paths:
 * - themes/theme/w2gm-plugin/templates/template-custom.tpl.php
 * - themes/theme/w2gm-plugin/templates/template.tpl.php
 * - plugins/w2gm/templates/template-custom.tpl.php
 * - plugins/w2gm/templates/template.tpl.php
 * 
 * templates in addons will be visible by such type of path:
 * - themes/theme/w2gm-plugin/templates/w2gm_fsubmit/template.tpl.php
 * 
 */
function w2gm_isTemplate($template) {
	$custom_template = str_replace('.tpl.php', '', $template) . '-custom.tpl.php';
	$templates = array(
			$custom_template,
			$template
	);

	foreach ($templates AS $template_to_check) {
		// check if it is exact path in $template
		if (is_file($template_to_check)) {
			return $template_to_check;
		} elseif (is_file(get_stylesheet_directory() . '/w2gm-plugin/templates/' . $template_to_check)) { // theme or child theme templates folder
			return get_stylesheet_directory() . '/w2gm-plugin/templates/' . $template_to_check;
		} elseif (is_file(W2GM_TEMPLATES_PATH . $template_to_check)) { // native plugin's templates folder
			return W2GM_TEMPLATES_PATH . $template_to_check;
		}
	}

	return false;
}

if (!function_exists('w2gm_renderTemplate')) {
	/**
	 * @param string|array $template
	 * @param array $args
	 * @param bool $return
	 * @return string
	 */
	function w2gm_renderTemplate($template, $args = array(), $return = false) {
		global $w2gm_instance;
	
		if ($args) {
			extract($args);
		}
		
		$template = apply_filters('w2gm_render_template', $template, $args);
		
		if (is_array($template)) {
			$template_path = $template[0];
			$template_file = $template[1];
			$template = $template_path . $template_file;
		}
		
		$template = w2gm_isTemplate($template);

		if ($template) {
			if ($return) {
				ob_start();
			}
		
			include($template);
			
			if ($return) {
				$output = ob_get_contents();
				ob_end_clean();
				return $output;
			}
		}
	}
}

function w2gm_getCurrentListingInAdmin() {
	global $w2gm_instance;
	
	return $w2gm_instance->current_listing;
}

function w2gm_get_term_parents($id, $tax, $link = false, $return_array = false, $separator = '/', &$chain = array()) {
	$parent = get_term($id, $tax);
	if (is_wp_error($parent) || !$parent) {
		if ($return_array) {
			return array();
		} else { 
			return '';
		}
	}

	$name = $parent->name;
	
	if ($parent->parent && ($parent->parent != $parent->term_id)) {
		w2gm_get_term_parents($parent->parent, $tax, $link, $return_array, $separator, $chain);
	}

	$url = get_term_link($parent->slug, $tax);
	if ($link && !is_wp_error($url)) {
		$chain[] = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . $url . '" title="' . esc_attr(sprintf(__('View all listings in %s', 'W2GM'), $name)) . '"><span itemprop="name">' . $name . '</span></a></li>';
	} else {
		$chain[] = $name;
	}
	
	if ($return_array) {
		return $chain;
	} else {
		return implode($separator, $chain);
	}
}

function w2gm_get_term_parents_slugs($id, $tax, &$chain = array()) {
	$parent = get_term($id, $tax);
	if (is_wp_error($parent) || !$parent) {
		return '';
	}

	$slug = $parent->slug;
	
	if ($parent->parent && ($parent->parent != $parent->term_id)) {
		w2gm_get_term_parents_slugs($parent->parent, $tax, $chain);
	}

	$chain[] = $slug;

	return $chain;
}

function w2gm_get_term_parents_ids($id, $tax, &$chain = array()) {
	$parent = get_term($id, $tax);
	if (is_wp_error($parent) || !$parent) {
		return '';
	}

	$id = $parent->term_id;
	
	if ($parent->parent && ($parent->parent != $parent->term_id)) {
		w2gm_get_term_parents_ids($parent->parent, $tax, $chain);
	}

	$chain[] = $id;

	return $chain;
}

function w2gm_checkQuickList($is_listing_id = null)
{
	if (isset($_COOKIE['favourites']))
		$favourites = explode('*', $_COOKIE['favourites']);
	else
		$favourites = array();
	$favourites = array_values(array_filter($favourites));

	if ($is_listing_id)
		if (in_array($is_listing_id, $favourites))
			return true;
		else 
			return false;

	$favourites_array = array();
	foreach ($favourites AS $listing_id)
		if (is_numeric($listing_id))
		$favourites_array[] = $listing_id;
	return $favourites_array;
}

function w2gm_getDatePickerFormat() {
	$wp_date_format = get_option('date_format');
	return str_replace(
			array('S',  'd', 'j',  'l',  'm', 'n',  'F',  'Y'),
			array('',  'dd', 'd', 'DD', 'mm', 'm', 'MM', 'yy'),
		$wp_date_format);
}

function w2gm_getDatePickerLangFile($locale) {
	if ($locale) {
		$_locale = explode('-', str_replace('_', '-', $locale));
		$lang_code = array_shift($_locale);
		if (is_file(W2GM_RESOURCES_PATH . 'js/i18n/datepicker-'.$locale.'.js'))
			return W2GM_RESOURCES_URL . 'js/i18n/datepicker-'.$locale.'.js';
		elseif (is_file(W2GM_RESOURCES_PATH . 'js/i18n/datepicker-'.$lang_code.'.js'))
			return W2GM_RESOURCES_URL . 'js/i18n/datepicker-'.$lang_code.'.js';
	}
}

function w2gm_getDatePickerLangCode($locale) {
	if ($locale) {
		$_locale = explode('-', str_replace('_', '-', $locale));
		$lang_code = array_shift($_locale);
		if (is_file(W2GM_RESOURCES_PATH . 'js/i18n/datepicker-'.$locale.'.js'))
			return $locale;
		elseif (is_file(W2GM_RESOURCES_PATH . 'js/i18n/datepicker-'.$lang_code.'.js'))
			return $lang_code;
	}
}

function w2gm_generateRandomVal($val = null) {
	if (!$val)
		return rand(1, 10000);
	else
		return $val;
}

/**
 * Fetch the IP Address
 *
 * @return	string
 */
function w2gm_ip_address()
{
	if (isset($_SERVER['REMOTE_ADDR']) && isset($_SERVER['HTTP_CLIENT_IP']))
		$ip_address = $_SERVER['HTTP_CLIENT_IP'];
	elseif (isset($_SERVER['REMOTE_ADDR']))
		$ip_address = $_SERVER['REMOTE_ADDR'];
	elseif (isset($_SERVER['HTTP_CLIENT_IP']))
		$ip_address = $_SERVER['HTTP_CLIENT_IP'];
	elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else
		return false;

	if (strstr($ip_address, ',')) {
		$x = explode(',', $ip_address);
		$ip_address = trim(end($x));
	}

	$validation = new w2gm_form_validation();
	if (!$validation->valid_ip($ip_address))
		return false;

	return $ip_address;
}

function w2gm_crop_content($post_id, $limit = 35, $strip_html = true) {
	if (has_excerpt($post_id)) {
		$raw_content = apply_filters('the_excerpt', get_the_excerpt($post_id));
	} elseif (get_option('w2gm_cropped_content_as_excerpt') && get_post($post_id)->post_content !== '') {
		$raw_content = apply_filters('the_content', get_post($post_id)->post_content);
	} else {
		return ;
	}
	
	$readmore_text = __('...', 'W2GM');

	$raw_content = str_replace(']]>', ']]&gt;', $raw_content);
	if ($strip_html) {
		$raw_content = strip_tags($raw_content);
		$pattern = get_shortcode_regex();
		// Remove shortcodes from excerpt
		$raw_content = preg_replace_callback("/$pattern/s", 'w2gm_remove_shortcodes', $raw_content);
	}

	if (!$limit) {
		return $raw_content;
	}

	$content = explode(' ', $raw_content, $limit);
	if (count($content) >= $limit) {
		array_pop($content);
		$content = implode(" ", $content) . $readmore_text;
	} else {
		$content = $raw_content;
	}

	return $content;
}

// Remove shortcodes from excerpt
function w2gm_remove_shortcodes($m) {
	if (function_exists('su_cmpt') && su_cmpt() !== false)
	if ($m[2] == su_cmpt() . 'dropcap' || $m[2] == su_cmpt() . 'highlight' || $m[2] == su_cmpt() . 'tooltip')
		return $m[0];

	// allow [[foo]] syntax for escaping a tag
	if ($m[1] == '[' && $m[6] == ']')
		return substr($m[0], 1, -1);

	return $m[1] . $m[6];
}

function w2gm_is_anyone_in_taxonomy($tax) {
	//global $wpdb;
	//return $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->term_taxonomy . ' WHERE `taxonomy`="' . $tax . '"');
	
	return count(get_categories(array('taxonomy' => $tax, 'hide_empty' => false, 'parent' => 0, 'number' => 1)));
}

function w2gm_comments_open() {
	if (get_option('w2gm_listings_comments_mode') == 'enabled' || (get_option('w2gm_listings_comments_mode') == 'wp_settings' && comments_open()))
		return true;
	else 
		return false;
}

function w2gm_get_term_by_path($term_path, $full_match = true, $output = OBJECT) {
	$term_path = rawurlencode( urldecode( $term_path ) );
	$term_path = str_replace( '%2F', '/', $term_path );
	$term_path = str_replace( '%20', ' ', $term_path );

	global $wp_rewrite;
	if ($wp_rewrite->using_permalinks()) {
		$term_paths = '/' . trim( $term_path, '/' );
		$leaf_path  = sanitize_title( basename( $term_paths ) );
		$term_paths = explode( '/', $term_paths );
		$full_path = '';
		foreach ( (array) $term_paths as $pathdir )
			$full_path .= ( $pathdir != '' ? '/' : '' ) . sanitize_title( $pathdir );
	
		//$terms = get_terms( array(W2GM_CATEGORIES_TAX, W2GM_LOCATIONS_TAX, W2GM_TAGS_TAX), array('get' => 'all', 'slug' => $leaf_path) );
		$terms = array();
		if ($term = get_term_by('slug', $leaf_path, W2GM_CATEGORIES_TAX))
			$terms[] = $term;
		if ($term = get_term_by('slug', $leaf_path, W2GM_LOCATIONS_TAX))
			$terms[] = $term;
		if ($term = get_term_by('slug', $leaf_path, W2GM_TAGS_TAX))
			$terms[] = $term;
	
		if ( empty( $terms ) )
			return null;
	
		foreach ( $terms as $term ) {
			$path = '/' . $leaf_path;
			$curterm = $term;
			while ( ( $curterm->parent != 0 ) && ( $curterm->parent != $curterm->term_id ) ) {
				$curterm = get_term( $curterm->parent, $term->taxonomy );
				if ( is_wp_error( $curterm ) )
					return $curterm;
				$path = '/' . $curterm->slug . $path;
			}

			if ( $path == $full_path ) {
				$term = get_term( $term->term_id, $term->taxonomy, $output );
				_make_cat_compat( $term );
				return $term;
			}
		}
	
		// If full matching is not required, return the first cat that matches the leaf.
		if ( ! $full_match ) {
			$term = reset( $terms );
			$term = get_term( $term->term_id, $term->taxonomy, $output );
			_make_cat_compat( $term );
			return $term;
		}
	} else {
		if ($term = get_term_by('slug', $term_path, W2GM_CATEGORIES_TAX))
			return $term;
		if ($term = get_term_by('slug', $term_path, W2GM_LOCATIONS_TAX))
			return $term;
		if ($term = get_term_by('slug', $term_path, W2GM_TAGS_TAX))
			return $term;
	}

	return null;
}

function w2gm_get_fa_icons_names() {
	$icons[] = 'w2gm-fa-adjust';
	$icons[] = 'w2gm-fa-adn';
	$icons[] = 'w2gm-fa-align-center';
	$icons[] = 'w2gm-fa-align-justify';
	$icons[] = 'w2gm-fa-align-left';
	$icons[] = 'w2gm-fa-align-right';
	$icons[] = 'w2gm-fa-ambulance';
	$icons[] = 'w2gm-fa-anchor';
	$icons[] = 'w2gm-fa-android';
	$icons[] = 'w2gm-fa-angellist';
	$icons[] = 'w2gm-fa-angle-double-down';
	$icons[] = 'w2gm-fa-angle-double-left';
	$icons[] = 'w2gm-fa-angle-double-right';
	$icons[] = 'w2gm-fa-angle-double-up';
	$icons[] = 'w2gm-fa-angle-down';
	$icons[] = 'w2gm-fa-angle-left';
	$icons[] = 'w2gm-fa-angle-right';
	$icons[] = 'w2gm-fa-angle-up';
	$icons[] = 'w2gm-fa-apple';
	$icons[] = 'w2gm-fa-archive';
	$icons[] = 'w2gm-fa-area-chart';
	$icons[] = 'w2gm-fa-arrow-circle-down';
	$icons[] = 'w2gm-fa-arrow-circle-left';
	$icons[] = 'w2gm-fa-arrow-circle-o-down';
	$icons[] = 'w2gm-fa-arrow-circle-o-left';
	$icons[] = 'w2gm-fa-arrow-circle-o-right';
	$icons[] = 'w2gm-fa-arrow-circle-o-up';
	$icons[] = 'w2gm-fa-arrow-circle-right';
	$icons[] = 'w2gm-fa-arrow-circle-up';
	$icons[] = 'w2gm-fa-arrow-down';
	$icons[] = 'w2gm-fa-arrow-left';
	$icons[] = 'w2gm-fa-arrow-right';
	$icons[] = 'w2gm-fa-arrow-up';
	$icons[] = 'w2gm-fa-arrows';
	$icons[] = 'w2gm-fa-arrows-alt';
	$icons[] = 'w2gm-fa-arrows-h';
	$icons[] = 'w2gm-fa-arrows-v';
	$icons[] = 'w2gm-fa-asterisk';
	$icons[] = 'w2gm-fa-at';
	$icons[] = 'w2gm-fa-automobile';
	$icons[] = 'w2gm-fa-backward';
	$icons[] = 'w2gm-fa-ban';
	$icons[] = 'w2gm-fa-bank';
	$icons[] = 'w2gm-fa-bar-chart';
	$icons[] = 'w2gm-fa-bar-chart-o';
	$icons[] = 'w2gm-fa-barcode';
	$icons[] = 'w2gm-fa-bars';
	$icons[] = 'w2gm-fa-bed';
	$icons[] = 'w2gm-fa-beer';
	$icons[] = 'w2gm-fa-behance';
	$icons[] = 'w2gm-fa-behance-square';
	$icons[] = 'w2gm-fa-bell';
	$icons[] = 'w2gm-fa-bell-o';
	$icons[] = 'w2gm-fa-bell-slash';
	$icons[] = 'w2gm-fa-bell-slash-o';
	$icons[] = 'w2gm-fa-bicycle';
	$icons[] = 'w2gm-fa-binoculars';
	$icons[] = 'w2gm-fa-birthday-cake';
	$icons[] = 'w2gm-fa-bitbucket';
	$icons[] = 'w2gm-fa-bitbucket-square';
	$icons[] = 'w2gm-fa-bitcoin';
	$icons[] = 'w2gm-fa-bold';
	$icons[] = 'w2gm-fa-bolt';
	$icons[] = 'w2gm-fa-bomb';
	$icons[] = 'w2gm-fa-book';
	$icons[] = 'w2gm-fa-bookmark';
	$icons[] = 'w2gm-fa-bookmark-o';
	$icons[] = 'w2gm-fa-briefcase';
	$icons[] = 'w2gm-fa-btc';
	$icons[] = 'w2gm-fa-bug';
	$icons[] = 'w2gm-fa-building';
	$icons[] = 'w2gm-fa-building-o';
	$icons[] = 'w2gm-fa-bullhorn';
	$icons[] = 'w2gm-fa-bullseye';
	$icons[] = 'w2gm-fa-bus';
	$icons[] = 'w2gm-fa-buysellads';
	$icons[] = 'w2gm-fa-cab';
	$icons[] = 'w2gm-fa-calculator';
	$icons[] = 'w2gm-fa-calendar';
	$icons[] = 'w2gm-fa-calendar-o';
	$icons[] = 'w2gm-fa-camera';
	$icons[] = 'w2gm-fa-camera-retro';
	$icons[] = 'w2gm-fa-car';
	$icons[] = 'w2gm-fa-caret-down';
	$icons[] = 'w2gm-fa-caret-left';
	$icons[] = 'w2gm-fa-caret-right';
	$icons[] = 'w2gm-fa-caret-square-o-down';
	$icons[] = 'w2gm-fa-caret-square-o-left';
	$icons[] = 'w2gm-fa-caret-square-o-right';
	$icons[] = 'w2gm-fa-caret-square-o-up';
	$icons[] = 'w2gm-fa-caret-up';
	$icons[] = 'w2gm-fa-cart-arrow-down';
	$icons[] = 'w2gm-fa-cart-plus';
	$icons[] = 'w2gm-fa-cc';
	$icons[] = 'w2gm-fa-cc-amex';
	$icons[] = 'w2gm-fa-cc-discover';
	$icons[] = 'w2gm-fa-cc-mastercard';
	$icons[] = 'w2gm-fa-cc-paypal';
	$icons[] = 'w2gm-fa-cc-stripe';
	$icons[] = 'w2gm-fa-cc-visa';
	$icons[] = 'w2gm-fa-certificate';
	$icons[] = 'w2gm-fa-chain';
	$icons[] = 'w2gm-fa-chain-broken';
	$icons[] = 'w2gm-fa-check';
	$icons[] = 'w2gm-fa-check-circle';
	$icons[] = 'w2gm-fa-check-circle-o';
	$icons[] = 'w2gm-fa-check-square';
	$icons[] = 'w2gm-fa-check-square-o';
	$icons[] = 'w2gm-fa-chevron-circle-down';
	$icons[] = 'w2gm-fa-chevron-circle-left';
	$icons[] = 'w2gm-fa-chevron-circle-right';
	$icons[] = 'w2gm-fa-chevron-circle-up';
	$icons[] = 'w2gm-fa-chevron-down';
	$icons[] = 'w2gm-fa-chevron-left';
	$icons[] = 'w2gm-fa-chevron-right';
	$icons[] = 'w2gm-fa-chevron-up';
	$icons[] = 'w2gm-fa-child';
	$icons[] = 'w2gm-fa-circle';
	$icons[] = 'w2gm-fa-circle-o';
	$icons[] = 'w2gm-fa-circle-o-notch';
	$icons[] = 'w2gm-fa-circle-thin';
	$icons[] = 'w2gm-fa-clipboard';
	$icons[] = 'w2gm-fa-clock-o';
	$icons[] = 'w2gm-fa-close';
	$icons[] = 'w2gm-fa-cloud';
	$icons[] = 'w2gm-fa-cloud-download';
	$icons[] = 'w2gm-fa-cloud-upload';
	$icons[] = 'w2gm-fa-cny';
	$icons[] = 'w2gm-fa-code';
	$icons[] = 'w2gm-fa-code-fork';
	$icons[] = 'w2gm-fa-codepen';
	$icons[] = 'w2gm-fa-coffee';
	$icons[] = 'w2gm-fa-cog';
	$icons[] = 'w2gm-fa-cogs';
	$icons[] = 'w2gm-fa-columns';
	$icons[] = 'w2gm-fa-comment';
	$icons[] = 'w2gm-fa-comment-o';
	$icons[] = 'w2gm-fa-comments';
	$icons[] = 'w2gm-fa-comments-o';
	$icons[] = 'w2gm-fa-compass';
	$icons[] = 'w2gm-fa-compress';
	$icons[] = 'w2gm-fa-connectdevelop';
	$icons[] = 'w2gm-fa-copy';
	$icons[] = 'w2gm-fa-copyright';
	$icons[] = 'w2gm-fa-credit-card';
	$icons[] = 'w2gm-fa-crop';
	$icons[] = 'w2gm-fa-crosshairs';
	$icons[] = 'w2gm-fa-css3';
	$icons[] = 'w2gm-fa-cube';
	$icons[] = 'w2gm-fa-cubes';
	$icons[] = 'w2gm-fa-cut';
	$icons[] = 'w2gm-fa-cutlery';
	$icons[] = 'w2gm-fa-dashboard';
	$icons[] = 'w2gm-fa-dashcube';
	$icons[] = 'w2gm-fa-database';
	$icons[] = 'w2gm-fa-dedent';
	$icons[] = 'w2gm-fa-delicious';
	$icons[] = 'w2gm-fa-desktop';
	$icons[] = 'w2gm-fa-deviantart';
	$icons[] = 'w2gm-fa-diamond';
	$icons[] = 'w2gm-fa-digg';
	$icons[] = 'w2gm-fa-dollar';
	$icons[] = 'w2gm-fa-dot-circle-o';
	$icons[] = 'w2gm-fa-download';
	$icons[] = 'w2gm-fa-dribbble';
	$icons[] = 'w2gm-fa-dropbox';
	$icons[] = 'w2gm-fa-drupal';
	$icons[] = 'w2gm-fa-edit';
	$icons[] = 'w2gm-fa-eject';
	$icons[] = 'w2gm-fa-ellipsis-h';
	$icons[] = 'w2gm-fa-ellipsis-v';
	$icons[] = 'w2gm-fa-empire';
	$icons[] = 'w2gm-fa-envelope';
	$icons[] = 'w2gm-fa-envelope-o';
	$icons[] = 'w2gm-fa-envelope-square';
	$icons[] = 'w2gm-fa-eraser';
	$icons[] = 'w2gm-fa-eur';
	$icons[] = 'w2gm-fa-euro';
	$icons[] = 'w2gm-fa-exchange';
	$icons[] = 'w2gm-fa-exclamation';
	$icons[] = 'w2gm-fa-exclamation-circle';
	$icons[] = 'w2gm-fa-exclamation-triangle';
	$icons[] = 'w2gm-fa-expand';
	$icons[] = 'w2gm-fa-external-link';
	$icons[] = 'w2gm-fa-external-link-square';
	$icons[] = 'w2gm-fa-eye';
	$icons[] = 'w2gm-fa-eye-slash';
	$icons[] = 'w2gm-fa-eyedropper';
	$icons[] = 'w2gm-fa-facebook';
	$icons[] = 'w2gm-fa-facebook-f';
	$icons[] = 'w2gm-fa-facebook-official';
	$icons[] = 'w2gm-fa-facebook-square';
	$icons[] = 'w2gm-fa-fast-backward';
	$icons[] = 'w2gm-fa-fast-forward';
	$icons[] = 'w2gm-fa-fax';
	$icons[] = 'w2gm-fa-female';
	$icons[] = 'w2gm-fa-fighter-jet';
	$icons[] = 'w2gm-fa-file';
	$icons[] = 'w2gm-fa-file-archive-o';
	$icons[] = 'w2gm-fa-file-audio-o';
	$icons[] = 'w2gm-fa-file-code-o';
	$icons[] = 'w2gm-fa-file-excel-o';
	$icons[] = 'w2gm-fa-file-image-o';
	$icons[] = 'w2gm-fa-file-movie-o';
	$icons[] = 'w2gm-fa-file-o';
	$icons[] = 'w2gm-fa-file-pdf-o';
	$icons[] = 'w2gm-fa-file-photo-o';
	$icons[] = 'w2gm-fa-file-picture-o';
	$icons[] = 'w2gm-fa-file-powerpoint-o';
	$icons[] = 'w2gm-fa-file-sound-o';
	$icons[] = 'w2gm-fa-file-text';
	$icons[] = 'w2gm-fa-file-text-o';
	$icons[] = 'w2gm-fa-file-video-o';
	$icons[] = 'w2gm-fa-file-word-o';
	$icons[] = 'w2gm-fa-file-zip-o';
	$icons[] = 'w2gm-fa-files-o';
	$icons[] = 'w2gm-fa-film';
	$icons[] = 'w2gm-fa-filter';
	$icons[] = 'w2gm-fa-fire';
	$icons[] = 'w2gm-fa-fire-extinguisher';
	$icons[] = 'w2gm-fa-flag';
	$icons[] = 'w2gm-fa-flag-checkered';
	$icons[] = 'w2gm-fa-flag-o';
	$icons[] = 'w2gm-fa-flash';
	$icons[] = 'w2gm-fa-flask';
	$icons[] = 'w2gm-fa-flickr';
	$icons[] = 'w2gm-fa-floppy-o';
	$icons[] = 'w2gm-fa-folder';
	$icons[] = 'w2gm-fa-folder-o';
	$icons[] = 'w2gm-fa-folder-open';
	$icons[] = 'w2gm-fa-folder-open-o';
	$icons[] = 'w2gm-fa-font';
	$icons[] = 'w2gm-fa-forumbee';
	$icons[] = 'w2gm-fa-forward';
	$icons[] = 'w2gm-fa-foursquare';
	$icons[] = 'w2gm-fa-frown-o';
	$icons[] = 'w2gm-fa-futbol-o';
	$icons[] = 'w2gm-fa-gamepad';
	$icons[] = 'w2gm-fa-gavel';
	$icons[] = 'w2gm-fa-gbp';
	$icons[] = 'w2gm-fa-ge';
	$icons[] = 'w2gm-fa-gear';
	$icons[] = 'w2gm-fa-gears';
	$icons[] = 'w2gm-fa-genderless';
	$icons[] = 'w2gm-fa-gift';
	$icons[] = 'w2gm-fa-git';
	$icons[] = 'w2gm-fa-git-square';
	$icons[] = 'w2gm-fa-github';
	$icons[] = 'w2gm-fa-github-alt';
	$icons[] = 'w2gm-fa-github-square';
	$icons[] = 'w2gm-fa-gittip';
	$icons[] = 'w2gm-fa-glass';
	$icons[] = 'w2gm-fa-globe';
	$icons[] = 'w2gm-fa-google';
	$icons[] = 'w2gm-fa-google-plus';
	$icons[] = 'w2gm-fa-google-plus-square';
	$icons[] = 'w2gm-fa-google-wallet';
	$icons[] = 'w2gm-fa-graduation-cap';
	$icons[] = 'w2gm-fa-gratipay';
	$icons[] = 'w2gm-fa-group';
	$icons[] = 'w2gm-fa-h-square';
	$icons[] = 'w2gm-fa-hacker-news';
	$icons[] = 'w2gm-fa-hand-o-down';
	$icons[] = 'w2gm-fa-hand-o-left';
	$icons[] = 'w2gm-fa-hand-o-right';
	$icons[] = 'w2gm-fa-hand-o-up';
	$icons[] = 'w2gm-fa-hdd-o';
	$icons[] = 'w2gm-fa-header';
	$icons[] = 'w2gm-fa-headphones';
	$icons[] = 'w2gm-fa-heart';
	$icons[] = 'w2gm-fa-heart-o';
	$icons[] = 'w2gm-fa-heartbeat';
	$icons[] = 'w2gm-fa-history';
	$icons[] = 'w2gm-fa-home';
	$icons[] = 'w2gm-fa-hospital-o';
	$icons[] = 'w2gm-fa-hotel';
	$icons[] = 'w2gm-fa-html5';
	$icons[] = 'w2gm-fa-ils';
	$icons[] = 'w2gm-fa-image';
	$icons[] = 'w2gm-fa-inbox';
	$icons[] = 'w2gm-fa-indent';
	$icons[] = 'w2gm-fa-info';
	$icons[] = 'w2gm-fa-info-circle';
	$icons[] = 'w2gm-fa-inr';
	$icons[] = 'w2gm-fa-instagram';
	$icons[] = 'w2gm-fa-institution';
	$icons[] = 'w2gm-fa-ioxhost';
	$icons[] = 'w2gm-fa-italic';
	$icons[] = 'w2gm-fa-joomla';
	$icons[] = 'w2gm-fa-jpy';
	$icons[] = 'w2gm-fa-jsfiddle';
	$icons[] = 'w2gm-fa-key';
	$icons[] = 'w2gm-fa-keyboard-o';
	$icons[] = 'w2gm-fa-krw';
	$icons[] = 'w2gm-fa-language';
	$icons[] = 'w2gm-fa-laptop';
	$icons[] = 'w2gm-fa-lastfm';
	$icons[] = 'w2gm-fa-lastfm-square';
	$icons[] = 'w2gm-fa-leaf';
	$icons[] = 'w2gm-fa-leanpub';
	$icons[] = 'w2gm-fa-legal';
	$icons[] = 'w2gm-fa-lemon-o';
	$icons[] = 'w2gm-fa-level-down';
	$icons[] = 'w2gm-fa-level-up';
	$icons[] = 'w2gm-fa-life-bouy';
	$icons[] = 'w2gm-fa-life-ring';
	$icons[] = 'w2gm-fa-life-saver';
	$icons[] = 'w2gm-fa-lightbulb-o';
	$icons[] = 'w2gm-fa-line-chart';
	$icons[] = 'w2gm-fa-link';
	$icons[] = 'w2gm-fa-linkedin';
	$icons[] = 'w2gm-fa-linkedin-square';
	$icons[] = 'w2gm-fa-linux';
	$icons[] = 'w2gm-fa-list';
	$icons[] = 'w2gm-fa-list-alt';
	$icons[] = 'w2gm-fa-list-ol';
	$icons[] = 'w2gm-fa-list-ul';
	$icons[] = 'w2gm-fa-location-arrow';
	$icons[] = 'w2gm-fa-lock';
	$icons[] = 'w2gm-fa-long-arrow-down';
	$icons[] = 'w2gm-fa-long-arrow-left';
	$icons[] = 'w2gm-fa-long-arrow-right';
	$icons[] = 'w2gm-fa-long-arrow-up';
	$icons[] = 'w2gm-fa-magic';
	$icons[] = 'w2gm-fa-magnet';
	$icons[] = 'w2gm-fa-mail-forward';
	$icons[] = 'w2gm-fa-mail-reply';
	$icons[] = 'w2gm-fa-mail-reply-all';
	$icons[] = 'w2gm-fa-male';
	$icons[] = 'w2gm-fa-map-marker';
	$icons[] = 'w2gm-fa-mars';
	$icons[] = 'w2gm-fa-mars-double';
	$icons[] = 'w2gm-fa-mars-stroke';
	$icons[] = 'w2gm-fa-mars-stroke-h';
	$icons[] = 'w2gm-fa-mars-stroke-v';
	$icons[] = 'w2gm-fa-maxcdn';
	$icons[] = 'w2gm-fa-meanpath';
	$icons[] = 'w2gm-fa-medium';
	$icons[] = 'w2gm-fa-medkit';
	$icons[] = 'w2gm-fa-meh-o';
	$icons[] = 'w2gm-fa-mercury';
	$icons[] = 'w2gm-fa-microphone';
	$icons[] = 'w2gm-fa-microphone-slash';
	$icons[] = 'w2gm-fa-minus';
	$icons[] = 'w2gm-fa-minus-circle';
	$icons[] = 'w2gm-fa-minus-square';
	$icons[] = 'w2gm-fa-minus-square-o';
	$icons[] = 'w2gm-fa-mobile';
	$icons[] = 'w2gm-fa-mobile-phone';
	$icons[] = 'w2gm-fa-money';
	$icons[] = 'w2gm-fa-moon-o';
	$icons[] = 'w2gm-fa-mortar-board';
	$icons[] = 'w2gm-fa-motorcycle';
	$icons[] = 'w2gm-fa-music';
	$icons[] = 'w2gm-fa-navicon';
	$icons[] = 'w2gm-fa-neuter';
	$icons[] = 'w2gm-fa-newspaper-o';
	$icons[] = 'w2gm-fa-openid';
	$icons[] = 'w2gm-fa-outdent';
	$icons[] = 'w2gm-fa-pagelines';
	$icons[] = 'w2gm-fa-paint-brush';
	$icons[] = 'w2gm-fa-paper-plane';
	$icons[] = 'w2gm-fa-paper-plane-o';
	$icons[] = 'w2gm-fa-paperclip';
	$icons[] = 'w2gm-fa-paragraph';
	$icons[] = 'w2gm-fa-paste';
	$icons[] = 'w2gm-fa-pause';
	$icons[] = 'w2gm-fa-paw';
	$icons[] = 'w2gm-fa-paypal';
	$icons[] = 'w2gm-fa-pencil';
	$icons[] = 'w2gm-fa-pencil-square';
	$icons[] = 'w2gm-fa-pencil-square-o';
	$icons[] = 'w2gm-fa-phone';
	$icons[] = 'w2gm-fa-phone-square';
	$icons[] = 'w2gm-fa-photo';
	$icons[] = 'w2gm-fa-picture-o';
	$icons[] = 'w2gm-fa-pie-chart';
	$icons[] = 'w2gm-fa-pied-piper';
	$icons[] = 'w2gm-fa-pied-piper-alt';
	$icons[] = 'w2gm-fa-pinterest';
	$icons[] = 'w2gm-fa-pinterest-p';
	$icons[] = 'w2gm-fa-pinterest-square';
	$icons[] = 'w2gm-fa-plane';
	$icons[] = 'w2gm-fa-play';
	$icons[] = 'w2gm-fa-play-circle';
	$icons[] = 'w2gm-fa-play-circle-o';
	$icons[] = 'w2gm-fa-plug';
	$icons[] = 'w2gm-fa-plus';
	$icons[] = 'w2gm-fa-plus-circle';
	$icons[] = 'w2gm-fa-plus-square';
	$icons[] = 'w2gm-fa-plus-square-o';
	$icons[] = 'w2gm-fa-power-off';
	$icons[] = 'w2gm-fa-print';
	$icons[] = 'w2gm-fa-puzzle-piece';
	$icons[] = 'w2gm-fa-qq';
	$icons[] = 'w2gm-fa-qrcode';
	$icons[] = 'w2gm-fa-question';
	$icons[] = 'w2gm-fa-question-circle';
	$icons[] = 'w2gm-fa-quote-left';
	$icons[] = 'w2gm-fa-quote-right';
	$icons[] = 'w2gm-fa-ra';
	$icons[] = 'w2gm-fa-random';
	$icons[] = 'w2gm-fa-rebel';
	$icons[] = 'w2gm-fa-recycle';
	$icons[] = 'w2gm-fa-reddit';
	$icons[] = 'w2gm-fa-reddit-square';
	$icons[] = 'w2gm-fa-refresh';
	$icons[] = 'w2gm-fa-remove';
	$icons[] = 'w2gm-fa-renren';
	$icons[] = 'w2gm-fa-reorder';
	$icons[] = 'w2gm-fa-repeat';
	$icons[] = 'w2gm-fa-reply';
	$icons[] = 'w2gm-fa-reply-all';
	$icons[] = 'w2gm-fa-retweet';
	$icons[] = 'w2gm-fa-rmb';
	$icons[] = 'w2gm-fa-road';
	$icons[] = 'w2gm-fa-rocket';
	$icons[] = 'w2gm-fa-rotate-left';
	$icons[] = 'w2gm-fa-rotate-right';
	$icons[] = 'w2gm-fa-rouble';
	$icons[] = 'w2gm-fa-rss';
	$icons[] = 'w2gm-fa-rss-square';
	$icons[] = 'w2gm-fa-rub';
	$icons[] = 'w2gm-fa-ruble';
	$icons[] = 'w2gm-fa-rupee';
	$icons[] = 'w2gm-fa-save';
	$icons[] = 'w2gm-fa-scissors';
	$icons[] = 'w2gm-fa-search';
	$icons[] = 'w2gm-fa-search-minus';
	$icons[] = 'w2gm-fa-search-plus';
	$icons[] = 'w2gm-fa-sellsy';
	$icons[] = 'w2gm-fa-send';
	$icons[] = 'w2gm-fa-send-o';
	$icons[] = 'w2gm-fa-server';
	$icons[] = 'w2gm-fa-share';
	$icons[] = 'w2gm-fa-share-alt';
	$icons[] = 'w2gm-fa-share-alt-square';
	$icons[] = 'w2gm-fa-share-square';
	$icons[] = 'w2gm-fa-share-square-o';
	$icons[] = 'w2gm-fa-shekel';
	$icons[] = 'w2gm-fa-sheqel';
	$icons[] = 'w2gm-fa-shield';
	$icons[] = 'w2gm-fa-ship';
	$icons[] = 'w2gm-fa-shirtsinbulk';
	$icons[] = 'w2gm-fa-shopping-cart';
	$icons[] = 'w2gm-fa-sign-out';
	$icons[] = 'w2gm-fa-signal';
	$icons[] = 'w2gm-fa-simplybuilt';
	$icons[] = 'w2gm-fa-sitemap';
	$icons[] = 'w2gm-fa-skyatlas';
	$icons[] = 'w2gm-fa-skype';
	$icons[] = 'w2gm-fa-slack';
	$icons[] = 'w2gm-fa-sliders';
	$icons[] = 'w2gm-fa-slideshare';
	$icons[] = 'w2gm-fa-smile-o';
	$icons[] = 'w2gm-fa-soccer-ball-o';
	$icons[] = 'w2gm-fa-sort';
	$icons[] = 'w2gm-fa-sort-alpha-asc';
	$icons[] = 'w2gm-fa-sort-alpha-desc';
	$icons[] = 'w2gm-fa-sort-amount-asc';
	$icons[] = 'w2gm-fa-sort-amount-desc';
	$icons[] = 'w2gm-fa-sort-asc';
	$icons[] = 'w2gm-fa-sort-desc';
	$icons[] = 'w2gm-fa-sort-down';
	$icons[] = 'w2gm-fa-sort-numeric-asc';
	$icons[] = 'w2gm-fa-sort-numeric-desc';
	$icons[] = 'w2gm-fa-sort-up';
	$icons[] = 'w2gm-fa-soundcloud';
	$icons[] = 'w2gm-fa-space-shuttle';
	$icons[] = 'w2gm-fa-spinner';
	$icons[] = 'w2gm-fa-spoon';
	$icons[] = 'w2gm-fa-spotify';
	$icons[] = 'w2gm-fa-square';
	$icons[] = 'w2gm-fa-square-o';
	$icons[] = 'w2gm-fa-stack-exchange';
	$icons[] = 'w2gm-fa-stack-overflow';
	$icons[] = 'w2gm-fa-star';
	$icons[] = 'w2gm-fa-star-half';
	$icons[] = 'w2gm-fa-star-half-empty';
	$icons[] = 'w2gm-fa-star-half-full';
	$icons[] = 'w2gm-fa-star-half-o';
	$icons[] = 'w2gm-fa-star-o';
	$icons[] = 'w2gm-fa-steam';
	$icons[] = 'w2gm-fa-steam-square';
	$icons[] = 'w2gm-fa-step-backward';
	$icons[] = 'w2gm-fa-step-forward';
	$icons[] = 'w2gm-fa-stethoscope';
	$icons[] = 'w2gm-fa-stop';
	$icons[] = 'w2gm-fa-street-view';
	$icons[] = 'w2gm-fa-strikethrough';
	$icons[] = 'w2gm-fa-stumbleupon';
	$icons[] = 'w2gm-fa-stumbleupon-circle';
	$icons[] = 'w2gm-fa-subscript';
	$icons[] = 'w2gm-fa-subway';
	$icons[] = 'w2gm-fa-suitcase';
	$icons[] = 'w2gm-fa-sun-o';
	$icons[] = 'w2gm-fa-superscript';
	$icons[] = 'w2gm-fa-support';
	$icons[] = 'w2gm-fa-table';
	$icons[] = 'w2gm-fa-tablet';
	$icons[] = 'w2gm-fa-tachometer';
	$icons[] = 'w2gm-fa-tag';
	$icons[] = 'w2gm-fa-tags';
	$icons[] = 'w2gm-fa-tasks';
	$icons[] = 'w2gm-fa-taxi';
	$icons[] = 'w2gm-fa-tencent-weibo';
	$icons[] = 'w2gm-fa-terminal';
	$icons[] = 'w2gm-fa-text-height';
	$icons[] = 'w2gm-fa-text-width';
	$icons[] = 'w2gm-fa-th';
	$icons[] = 'w2gm-fa-th-large';
	$icons[] = 'w2gm-fa-th-list';
	$icons[] = 'w2gm-fa-thumb-tack';
	$icons[] = 'w2gm-fa-thumbs-down';
	$icons[] = 'w2gm-fa-thumbs-o-down';
	$icons[] = 'w2gm-fa-thumbs-o-up';
	$icons[] = 'w2gm-fa-thumbs-up';
	$icons[] = 'w2gm-fa-ticket';
	$icons[] = 'w2gm-fa-times';
	$icons[] = 'w2gm-fa-times-circle';
	$icons[] = 'w2gm-fa-times-circle-o';
	$icons[] = 'w2gm-fa-tint';
	$icons[] = 'w2gm-fa-toggle-down';
	$icons[] = 'w2gm-fa-toggle-left';
	$icons[] = 'w2gm-fa-toggle-off';
	$icons[] = 'w2gm-fa-toggle-on';
	$icons[] = 'w2gm-fa-toggle-right';
	$icons[] = 'w2gm-fa-toggle-up';
	$icons[] = 'w2gm-fa-train';
	$icons[] = 'w2gm-fa-transgender';
	$icons[] = 'w2gm-fa-transgender-alt';
	$icons[] = 'w2gm-fa-trash';
	$icons[] = 'w2gm-fa-trash-o';
	$icons[] = 'w2gm-fa-tree';
	$icons[] = 'w2gm-fa-trello';
	$icons[] = 'w2gm-fa-trophy';
	$icons[] = 'w2gm-fa-truck';
	$icons[] = 'w2gm-fa-try';
	$icons[] = 'w2gm-fa-tty';
	$icons[] = 'w2gm-fa-tumblr';
	$icons[] = 'w2gm-fa-tumblr-square';
	$icons[] = 'w2gm-fa-turkish-lira';
	$icons[] = 'w2gm-fa-twitch';
	$icons[] = 'w2gm-fa-twitter';
	$icons[] = 'w2gm-fa-twitter-square';
	$icons[] = 'w2gm-fa-umbrella';
	$icons[] = 'w2gm-fa-underline';
	$icons[] = 'w2gm-fa-undo';
	$icons[] = 'w2gm-fa-university';
	$icons[] = 'w2gm-fa-unlink';
	$icons[] = 'w2gm-fa-unlock';
	$icons[] = 'w2gm-fa-unlock-alt';
	$icons[] = 'w2gm-fa-unsorted';
	$icons[] = 'w2gm-fa-upload';
	$icons[] = 'w2gm-fa-usd';
	$icons[] = 'w2gm-fa-user';
	$icons[] = 'w2gm-fa-user-md';
	$icons[] = 'w2gm-fa-user-plus';
	$icons[] = 'w2gm-fa-user-secret';
	$icons[] = 'w2gm-fa-user-times';
	$icons[] = 'w2gm-fa-users';
	$icons[] = 'w2gm-fa-venus';
	$icons[] = 'w2gm-fa-venus-double';
	$icons[] = 'w2gm-fa-venus-mars';
	$icons[] = 'w2gm-fa-viacoin';
	$icons[] = 'w2gm-fa-video-camera';
	$icons[] = 'w2gm-fa-vimeo-square';
	$icons[] = 'w2gm-fa-vine';
	$icons[] = 'w2gm-fa-vk';
	$icons[] = 'w2gm-fa-volume-down';
	$icons[] = 'w2gm-fa-volume-off';
	$icons[] = 'w2gm-fa-volume-up';
	$icons[] = 'w2gm-fa-warning';
	$icons[] = 'w2gm-fa-wechat';
	$icons[] = 'w2gm-fa-weibo';
	$icons[] = 'w2gm-fa-weixin';
	$icons[] = 'w2gm-fa-whatsapp';
	$icons[] = 'w2gm-fa-wheelchair';
	$icons[] = 'w2gm-fa-wifi';
	$icons[] = 'w2gm-fa-windows';
	$icons[] = 'w2gm-fa-won';
	$icons[] = 'w2gm-fa-wordpress';
	$icons[] = 'w2gm-fa-wrench';
	$icons[] = 'w2gm-fa-xing';
	$icons[] = 'w2gm-fa-xing-square';
	$icons[] = 'w2gm-fa-yahoo';
	$icons[] = 'w2gm-fa-yen';
	$icons[] = 'w2gm-fa-youtube';
	$icons[] = 'w2gm-fa-youtube-play';
	$icons[] = 'w2gm-fa-youtube-square';
	return $icons;
}

function w2gm_current_user_can_edit_listing($listing_id) {
	if (!current_user_can('edit_others_posts')) {
		$post = get_post($listing_id);
		$current_user = wp_get_current_user();
		if ($current_user->ID != $post->post_author)
			return false;
		if ($post->post_status == 'pending'  && !is_admin())
			return false;
	}
	return true;
}

function w2gm_get_edit_listing_link($listing_id, $context = 'display') {
	if (w2gm_current_user_can_edit_listing($listing_id)) {
		$post = get_post($listing_id);
		$current_user = wp_get_current_user();
		if (current_user_can('edit_others_posts') && $current_user->ID != $post->post_author)
			return get_edit_post_link($listing_id, $context);
		else
			return apply_filters('w2gm_get_edit_listing_link', get_edit_post_link($listing_id, $context), $listing_id);
	}
}

function w2gm_show_edit_button($listing_id) {
	global $w2gm_instance;
	if (
		w2gm_current_user_can_edit_listing($listing_id)
		&&
		(
			(get_option('w2gm_fsubmit_addon') && isset($w2gm_instance->dashboard_page_url) && $w2gm_instance->dashboard_page_url)
			||
			((!get_option('w2gm_fsubmit_addon') || !isset($w2gm_instance->dashboard_page_url) || !$w2gm_instance->dashboard_page_url) && !get_option('w2gm_hide_admin_bar') && current_user_can('edit_posts'))
		)
	)
		return true;
}

function w2gm_hex2rgba($color, $opacity = false) {
	$default = 'rgb(0,0,0)';

	//Return default if no color provided
	if(empty($color))
		return $default;

	//Sanitize $color if "#" is provided
	if ($color[0] == '#' ) {
		$color = substr( $color, 1 );
	}

	//Check if color has 6 or 3 characters and get values
	if (strlen($color) == 6) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( strlen( $color ) == 3 ) {
		$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return $default;
	}

	//Convert hexadec to rgb
	$rgb =  array_map('hexdec', $hex);

	//Check if opacity is set(rgba or rgb)
	if (abs($opacity) > 1) {
		$opacity = 1.0;
	} elseif (abs($opacity) < 0) {
		$opacity = 0;
	}
	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';

	//Return rgb(a) color string
	return $output;
}

function w2gm_adjust_brightness($hex, $steps) {
	// Steps should be between -255 and 255. Negative = darker, positive = lighter
	$steps = max(-255, min(255, $steps));

	// Normalize into a six character long hex string
	$hex = str_replace('#', '', $hex);
	if (strlen($hex) == 3) {
		$hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
	}

	// Split into three parts: R, G and B
	$color_parts = str_split($hex, 2);
	$return = '#';

	foreach ($color_parts as $color) {
		$color   = hexdec($color); // Convert to decimal
		$color   = max(0,min(255,$color + $steps)); // Adjust color
		$return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
	}

	return $return;
}

// adapted for Relevanssi
function w2gm_is_relevanssi_search($defaults = false) {
	if (
		function_exists('relevanssi_do_query') &&
		(
				(
						!$defaults &&
						w2gm_getValue($_REQUEST, 'what_search')
						/* (
							!empty($defaults['include_get_params']) &&
							(w2gm_getValue($_GET, 'what_search') || w2gm_getValue($_POST, 'what_search'))
						) */
				) ||
				($defaults && isset($defaults['what_search']) && $defaults['what_search'])
		)
	) {
		return apply_filters('w2gm_is_relevanssi_search', true, $defaults);
	}
}

/**
 * print class name to make field caption shorter
 *
 * @param object $group
 * @return boolean
 */
function w2gm_is_any_field_name_in_group($group) {
	if ($group) {
		foreach ($group->content_fields_array AS $field_id=>$field) {
			if (!$field->is_hide_name) {
				return true;
			}
		}
		echo "w2gm-field-caption-short";
		return false;
	}
}

function w2gm_error_log($wp_error) {
	w2gm_addMessage($wp_error->get_error_message(), 'error');
	error_log($wp_error->get_error_message());
}

function w2gm_country_codes() {
	$codes['Afghanistan'] = 'AF';
	$codes['Åland Islands'] = 'AX';
	$codes['Albania'] = 'AL';
	$codes['Algeria'] = 'DZ';
	$codes['American Samoa'] = 'AS';
	$codes['Andorra'] = 'AD';
	$codes['Angola'] = 'AO';
	$codes['Anguilla'] = 'AI';
	$codes['Antarctica'] = 'AQ';
	$codes['Antigua and Barbuda'] = 'AG';
	$codes['Argentina'] = 'AR';
	$codes['Armenia'] = 'AM';
	$codes['Aruba'] = 'AW';
	$codes['Australia'] = 'AU';
	$codes['Austria'] = 'AT';
	$codes['Azerbaijan'] = 'AZ';
	$codes['Bahamas'] = 'BS';
	$codes['Bahrain'] = 'BH';
	$codes['Bangladesh'] = 'BD';
	$codes['Barbados'] = 'BB';
	$codes['Belarus'] = 'BY';
	$codes['Belgium'] = 'BE';
	$codes['Belize'] = 'BZ';
	$codes['Benin'] = 'BJ';
	$codes['Bermuda'] = 'BM';
	$codes['Bhutan'] = 'BT';
	$codes['Bolivia, Plurinational State of'] = 'BO';
	$codes['Bonaire, Sint Eustatius and Saba'] = 'BQ';
	$codes['Bosnia and Herzegovina'] = 'BA';
	$codes['Botswana'] = 'BW';
	$codes['Bouvet Island'] = 'BV';
	$codes['Brazil'] = 'BR';
	$codes['British Indian Ocean Territory'] = 'IO';
	$codes['Brunei Darussalam'] = 'BN';
	$codes['Bulgaria'] = 'BG';
	$codes['Burkina Faso'] = 'BF';
	$codes['Burundi'] = 'BI';
	$codes['Cambodia'] = 'KH';
	$codes['Cameroon'] = 'CM';
	$codes['Canada'] = 'CA';
	$codes['Cape Verde'] = 'CV';
	$codes['Cayman Islands'] = 'KY';
	$codes['Central African Republic'] = 'CF';
	$codes['Chad'] = 'TD';
	$codes['Chile'] = 'CL';
	$codes['China'] = 'CN';
	$codes['Christmas Island'] = 'CX';
	$codes['Cocos (Keeling) Islands'] = 'CC';
	$codes['Colombia'] = 'CO';
	$codes['Comoros'] = 'KM';
	$codes['Congo'] = 'CG';
	$codes['Congo, the Democratic Republic of the'] = 'CD';
	$codes['Cook Islands'] = 'CK';
	$codes['Costa Rica'] = 'CR';
	$codes['Côte d\'Ivoire'] = 'CI';
	$codes['Croatia'] = 'HR';
	$codes['Cuba'] = 'CU';
	$codes['Curaçao'] = 'CW';
	$codes['Cyprus'] = 'CY';
	$codes['Czech Republic'] = 'CZ';
	$codes['Denmark'] = 'DK';
	$codes['Djibouti'] = 'DJ';
	$codes['Dominica'] = 'DM';
	$codes['Dominican Republic'] = 'DO';
	$codes['Ecuador'] = 'EC';
	$codes['Egypt'] = 'EG';
	$codes['El Salvador'] = 'SV';
	$codes['Equatorial Guinea'] = 'GQ';
	$codes['Eritrea'] = 'ER';
	$codes['Estonia'] = 'EE';
	$codes['Ethiopia'] = 'ET';
	$codes['Falkland Islands (Malvinas)'] = 'FK';
	$codes['Faroe Islands'] = 'FO';
	$codes['Fiji'] = 'FJ';
	$codes['Finland'] = 'FI';
	$codes['France'] = 'FR';
	$codes['French Guiana'] = 'GF';
	$codes['French Polynesia'] = 'PF';
	$codes['French Southern Territories'] = 'TF';
	$codes['Gabon'] = 'GA';
	$codes['Gambia'] = 'GM';
	$codes['Georgia'] = 'GE';
	$codes['Germany'] = 'DE';
	$codes['Ghana'] = 'GH';
	$codes['Gibraltar'] = 'GI';
	$codes['Greece'] = 'GR';
	$codes['Greenland'] = 'GL';
	$codes['Grenada'] = 'GD';
	$codes['Guadeloupe'] = 'GP';
	$codes['Guam'] = 'GU';
	$codes['Guatemala'] = 'GT';
	$codes['Guernsey'] = 'GG';
	$codes['Guinea'] = 'GN';
	$codes['Guinea-Bissau'] = 'GW';
	$codes['Guyana'] = 'GY';
	$codes['Haiti'] = 'HT';
	$codes['Heard Island and McDonald Islands'] = 'HM';
	$codes['Holy See (Vatican City State)'] = 'VA';
	$codes['Honduras'] = 'HN';
	$codes['Hong Kong'] = 'HK';
	$codes['Hungary'] = 'HU';
	$codes['Iceland'] = 'IS';
	$codes['India'] = 'IN';
	$codes['Indonesia'] = 'ID';
	$codes['Iran, Islamic Republic of'] = 'IR';
	$codes['Iraq'] = 'IQ';
	$codes['Ireland'] = 'IE';
	$codes['Isle of Man'] = 'IM';
	$codes['Israel'] = 'IL';
	$codes['Italy'] = 'IT';
	$codes['Jamaica'] = 'JM';
	$codes['Japan'] = 'JP';
	$codes['Jersey'] = 'JE';
	$codes['Jordan'] = 'JO';
	$codes['Kazakhstan'] = 'KZ';
	$codes['Kenya'] = 'KE';
	$codes['Kiribati'] = 'KI';
	$codes['Korea, Democratic People\'s Republic of'] = 'KP';
	$codes['Korea, Republic of'] = 'KR';
	$codes['Kuwait'] = 'KW';
	$codes['Kyrgyzstan'] = 'KG';
	$codes['Lao People\'s Democratic Republic'] = 'LA';
	$codes['Latvia'] = 'LV';
	$codes['Lebanon'] = 'LB';
	$codes['Lesotho'] = 'LS';
	$codes['Liberia'] = 'LR';
	$codes['Libya'] = 'LY';
	$codes['Liechtenstein'] = 'LI';
	$codes['Lithuania'] = 'LT';
	$codes['Luxembourg'] = 'LU';
	$codes['Macao'] = 'MO';
	$codes['Macedonia, the Former Yugoslav Republic of'] = 'MK';
	$codes['Madagascar'] = 'MG';
	$codes['Malawi'] = 'MW';
	$codes['Malaysia'] = 'MY';
	$codes['Maldives'] = 'MV';
	$codes['Mali'] = 'ML';
	$codes['Malta'] = 'MT';
	$codes['Marshall Islands'] = 'MH';
	$codes['Martinique'] = 'MQ';
	$codes['Mauritania'] = 'MR';
	$codes['Mauritius'] = 'MU';
	$codes['Mayotte'] = 'YT';
	$codes['Mexico'] = 'MX';
	$codes['Micronesia, Federated States of'] = 'FM';
	$codes['Moldova, Republic of'] = 'MD';
	$codes['Monaco'] = 'MC';
	$codes['Mongolia'] = 'MN';
	$codes['Montenegro'] = 'ME';
	$codes['Montserrat'] = 'MS';
	$codes['Morocco'] = 'MA';
	$codes['Mozambique'] = 'MZ';
	$codes['Myanmar'] = 'MM';
	$codes['Namibia'] = 'NA';
	$codes['Nauru'] = 'NR';
	$codes['Nepal'] = 'NP';
	$codes['Netherlands'] = 'NL';
	$codes['New Caledonia'] = 'NC';
	$codes['New Zealand'] = 'NZ';
	$codes['Nicaragua'] = 'NI';
	$codes['Niger'] = 'NE';
	$codes['Nigeria'] = 'NG';
	$codes['Niue'] = 'NU';
	$codes['Norfolk Island'] = 'NF';
	$codes['Northern Mariana Islands'] = 'MP';
	$codes['Norway'] = 'NO';
	$codes['Oman'] = 'OM';
	$codes['Pakistan'] = 'PK';
	$codes['Palau'] = 'PW';
	$codes['Palestine, State of'] = 'PS';
	$codes['Panama'] = 'PA';
	$codes['Papua New Guinea'] = 'PG';
	$codes['Paraguay'] = 'PY';
	$codes['Peru'] = 'PE';
	$codes['Philippines'] = 'PH';
	$codes['Pitcairn'] = 'PN';
	$codes['Poland'] = 'PL';
	$codes['Portugal'] = 'PT';
	$codes['Puerto Rico'] = 'PR';
	$codes['Qatar'] = 'QA';
	$codes['Réunion'] = 'RE';
	$codes['Romania'] = 'RO';
	$codes['Russian Federation'] = 'RU';
	$codes['Rwanda'] = 'RW';
	$codes['Saint Barthélemy'] = 'BL';
	$codes['Saint Helena, Ascension and Tristan da Cunha'] = 'SH';
	$codes['Saint Kitts and Nevis'] = 'KN';
	$codes['Saint Lucia'] = 'LC';
	$codes['Saint Martin (French part)'] = 'MF';
	$codes['Saint Pierre and Miquelon'] = 'PM';
	$codes['Saint Vincent and the Grenadines'] = 'VC';
	$codes['Samoa'] = 'WS';
	$codes['San Marino'] = 'SM';
	$codes['Sao Tome and Principe'] = 'ST';
	$codes['Saudi Arabia'] = 'SA';
	$codes['Senegal'] = 'SN';
	$codes['Serbia'] = 'RS';
	$codes['Seychelles'] = 'SC';
	$codes['Sierra Leone'] = 'SL';
	$codes['Singapore'] = 'SG';
	$codes['Sint Maarten (Dutch part)'] = 'SX';
	$codes['Slovakia'] = 'SK';
	$codes['Slovenia'] = 'SI';
	$codes['Solomon Islands'] = 'SB';
	$codes['Somalia'] = 'SO';
	$codes['South Africa'] = 'ZA';
	$codes['South Georgia and the South Sandwich Islands'] = 'GS';
	$codes['South Sudan'] = 'SS';
	$codes['Spain'] = 'ES';
	$codes['Sri Lanka'] = 'LK';
	$codes['Sudan'] = 'SD';
	$codes['Suriname'] = 'SR';
	$codes['Svalbard and Jan Mayen'] = 'SJ';
	$codes['Swaziland'] = 'SZ';
	$codes['Sweden'] = 'SE';
	$codes['Switzerland'] = 'CH';
	$codes['Syrian Arab Republic'] = 'SY';
	$codes['Taiwan, Province of China"'] = 'TW';
	$codes['Tajikistan'] = 'TJ';
	$codes['"Tanzania, United Republic of"'] = 'TZ';
	$codes['Thailand'] = 'TH';
	$codes['Timor-Leste'] = 'TL';
	$codes['Togo'] = 'TG';
	$codes['Tokelau'] = 'TK';
	$codes['Tonga'] = 'TO';
	$codes['Trinidad and Tobago'] = 'TT';
	$codes['Tunisia'] = 'TN';
	$codes['Turkey'] = 'TR';
	$codes['Turkmenistan'] = 'TM';
	$codes['Turks and Caicos Islands'] = 'TC';
	$codes['Tuvalu'] = 'TV';
	$codes['Uganda'] = 'UG';
	$codes['Ukraine'] = 'UA';
	$codes['United Arab Emirates'] = 'AE';
	$codes['United Kingdom'] = 'GB';
	$codes['United States'] = 'US';
	$codes['United States Minor Outlying Islands'] = 'UM';
	$codes['Uruguay'] = 'UY';
	$codes['Uzbekistan'] = 'UZ';
	$codes['Vanuatu'] = 'VU';
	$codes['Venezuela,  Bolivarian Republic of'] = 'VE';
	$codes['Viet Nam'] = 'VN';
	$codes['Virgin Islands, British'] = 'VG';
	$codes['Virgin Islands, U.S.'] = 'VI';
	$codes['Wallis and Futuna'] = 'WF';
	$codes['Western Sahara'] = 'EH';
	$codes['Yemen'] = 'YE';
	$codes['Zambia'] = 'ZM';
	$codes['Zimbabwe'] = 'ZW';
	return $codes;
}

function w2gm_isWooActive() {
	if (
		!get_option('w2gm_payments_addon')
		&&
		in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))
		&&
		get_option('w2gm_woocommerce_functionality')
	) 
		return true;
}

function w2gm_getAdminNotificationEmail() {
	if (get_option('w2gm_admin_notifications_email'))
		return get_option('w2gm_admin_notifications_email');
	else 
		return get_option('admin_email');
}

function w2gm_wpmlTranslationCompleteNotice() {
	global $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress && defined('WPML_ST_VERSION')) {
		echo '<p class="description">';
		_e('After save do not forget to set completed translation status for this string on String Translation page.', 'W2GM');
		echo '</p>';
	}
}

function w2gm_phpmailerInit($phpmailer) {
	$phpmailer->AltBody = wp_specialchars_decode($phpmailer->Body, ENT_QUOTES);
}
function w2gm_mail($email, $subject, $body, $headers = null) {
	// create and add HTML part into emails
	add_action('phpmailer_init', 'w2gm_phpmailerInit');

	if (!$headers) {
		$headers[] = "From: " . get_option('blogname') . " <" . w2gm_getAdminNotificationEmail() . ">";
		$headers[] = "Reply-To: " . w2gm_getAdminNotificationEmail();
		$headers[] = "Content-Type: text/html";
	}
		
	$subject = "[" . get_option('blogname') . "] " .$subject;

	$body = make_clickable(wpautop($body));
	
	$email = apply_filters('w2gm_mail_email', $email, $subject, $body, $headers);
	$subject = apply_filters('w2gm_mail_subject', $subject, $email, $body, $headers);
	$body = apply_filters('w2gm_mail_body', $body, $email, $subject, $headers);
	$headers = apply_filters('w2gm_mail_headers', $headers, $email, $subject, $body);
	
	add_action('wp_mail_failed', 'w2gm_error_log');

	return wp_mail($email, $subject, $body, $headers);
}

function w2gm_getListing($post) {
	$listing = new w2gm_listing;
	if ($listing->loadListingFromPost($post)) {
		return $listing;
	}
}

function w2gm_isMapsPageInAdmin() {
	global $pagenow;

	if (
		is_admin() &&
		(
				(($pagenow == 'edit.php' || $pagenow == 'post-new.php') && ($post_type = w2gm_getValue($_GET, 'post_type')) &&
						(in_array($post_type, array(W2GM_POST_TYPE, W2GM_MAP_TYPE)))
				) ||
				($pagenow == 'post.php' && ($post_id = w2gm_getValue($_GET, 'post')) && ($post = get_post($post_id)) &&
						(in_array($post->post_type, array(W2GM_POST_TYPE, W2GM_MAP_TYPE)))
				) ||
				(($pagenow == 'edit-tags.php' || $pagenow == 'term.php') && ($taxonomy = w2gm_getValue($_GET, 'taxonomy')) &&
						(in_array($taxonomy, array(W2GM_LOCATIONS_TAX, W2GM_CATEGORIES_TAX, W2GM_TAGS_TAX)))
				) ||
				(($page = w2gm_getValue($_GET, 'page')) &&
						(in_array($page,
								array(
										'w2gm_settings',
										'w2gm_locations_levels',
										'w2gm_content_fields',
										'w2gm_csv_import',
										'w2gm_renew',
										'w2gm_changedate',
										'w2gm_raise_up',
										'w2gm_process_claim'
								)
						))
				) ||
				($pagenow == 'widgets.php')
		)
	) {
		return true;
	}
}

function w2gm_isListingEditPageInAdmin() {
	global $pagenow;

	if (
		($pagenow == 'post-new.php' && ($post_type = w2gm_getValue($_GET, 'post_type')) &&
				(in_array($post_type, array(W2GM_POST_TYPE)))
		) ||
		($pagenow == 'post.php' && ($post_id = w2gm_getValue($_GET, 'post')) && ($post = get_post($post_id)) &&
				(in_array($post->post_type, array(W2GM_POST_TYPE)))
		)
	) {
		return true;
	}
}

function w2gm_isLocationsEditPageInAdmin() {
	global $pagenow;

	if (($pagenow == 'edit-tags.php' || $pagenow == 'term.php') && ($taxonomy = w2gm_getValue($_GET, 'taxonomy')) &&
				(in_array($taxonomy, array(W2GM_LOCATIONS_TAX)))) {
		return true;
	}
}

function w2gm_isCategoriesEditPageInAdmin() {
	global $pagenow;

	if (($pagenow == 'edit-tags.php' || $pagenow == 'term.php') && ($taxonomy = w2gm_getValue($_GET, 'taxonomy')) &&
				(in_array($taxonomy, array(W2GM_CATEGORIES_TAX)))) {
		return true;
	}
}

function w2gm_getCategoryIconFile($term_id) {
	if (($icons = get_option('w2gm_categories_icons')) && is_array($icons) && isset($icons[$term_id])) {
		return $icons[$term_id];
	}
}

function w2gm_getCategoryImageUrl($term_id, $size = 'full') {
	global $w2gm_instance;
	
	if ($image_url = $w2gm_instance->categories_manager->get_featured_image_url($term_id, $size)) {
		return $image_url;
	}
}

function w2gm_getLocationIconFile($term_id) {
	if (($icons = get_option('w2gm_locations_icons')) && is_array($icons) && isset($icons[$term_id])) {
		return $icons[$term_id];
	}
}

function w2gm_getLocationImageUrl($term_id, $size = 'full') {
	global $w2gm_instance;

	if ($image_url = $w2gm_instance->locations_manager->get_featured_image_url($term_id, $size)) {
		return $image_url;
	}
}

function w2gm_getSearchTermID($query_var, $get_var, $default_term_id) {
	if (get_query_var($query_var) && ($category_object = w2gm_get_term_by_path(get_query_var($query_var)))) {
		$term_id = $category_object->term_id;
	} elseif (isset($_GET[$get_var]) && is_numeric($_GET[$get_var])) {
		$term_id = $_GET[$get_var];
	} else {
		$term_id = $default_term_id;
	}
	return $term_id;
}

function w2gm_getMapEngine() {
		return 'google';
}

function w2gm_getAllMapStyles() {
	global $w2gm_google_maps_styles;
	
	return $w2gm_google_maps_styles;
}

function w2gm_getSelectedMapStyleName() {
	return get_option('w2gm_google_map_style');
}

function w2gm_getSelectedMapStyle($map_style = false) {
	if (!$map_style) {
		$map_style = w2gm_getSelectedMapStyleName();
	}
	
	global $w2gm_google_maps_styles;

	if (!empty($w2gm_google_maps_styles[$map_style])) {
		return $w2gm_google_maps_styles[$map_style];
	} else {
		return '';
	}
}

function w2gm_visibleSearchParam($param_text, $link) {
	$parse_url = parse_url($link, PHP_URL_QUERY);
	parse_str($parse_url, $parse_url_str);
	if (count($parse_url_str) == 1 && w2gm_getValue($parse_url_str, 'w2gm_action') == 'search') {
		$link = remove_query_arg('w2gm_action', $link);
	} elseif ($use_advanced = w2gm_getValue($_REQUEST, 'use_advanced')) {
		$link = add_query_arg('use_advanced', '1', $link);
	}
	
	return '<div class="w2gm-search-param"><a class="w2gm-search-param-delete" href="' . $link . '">×</a>' . $param_text . '</div>';
}

function w2gm_wrapKeys(&$val) {
	$val = "`".$val."`";
}
function w2gm_wrapValues(&$val) {
	$val = "'".$val."'";
}
function w2gm_wrapIntVal(&$val) {
	$val = intval($val);
}

function w2gm_utf8ize($mixed) {
	if (is_array($mixed)) {
		foreach ($mixed as $key => $value) {
			$mixed[$key] = w2gm_utf8ize($value);
		}
	} elseif (is_string($mixed)) {
		return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
	}
	return $mixed;
}
