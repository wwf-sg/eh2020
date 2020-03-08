<?php

function w2gm_submitUrl($path = '') {
	global $w2gm_instance;
	
	$submit_page_url = '';

	if (!empty($w2gm_instance->submit_page)) {
		$submit_page_url = $w2gm_instance->submit_page['url'];
	}
		
	// adapted for WPML
	global $sitepress;
	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_option('language_negotiation_type') == 3) {
			// remove any previous value.
			$submit_page_url = remove_query_arg('lang', $submit_page_url);
		}
	}

	if (!is_array($path)) {
		if ($path) {
			// found that on some instances of WP "native" trailing slashes may be missing
			$url = rtrim($submit_page_url, '/') . '/' . rtrim($path, '/') . '/';
		} else
			$url = $submit_page_url;
	} else
		$url = add_query_arg($path, $submit_page_url);

	// adapted for WPML
	global $sitepress;
	if (function_exists('wpml_object_id_filter') && $sitepress) {
		$url = $sitepress->convert_url($url);
	}

	return $url;
}

function w2gm_dashboardUrl($path = '') {
	global $w2gm_instance;
	
	if ($w2gm_instance->dashboard_page_url) {
		// adapted for WPML
		global $sitepress;
		if (function_exists('wpml_object_id_filter') && $sitepress) {
			if ($sitepress->get_option('language_negotiation_type') == 3) {
				// remove any previous value.
				$w2gm_instance->dashboard_page_url = remove_query_arg('lang', $w2gm_instance->dashboard_page_url);
			}
		}
	
		if (!is_array($path)) {
			if ($path) {
				// found that on some instances of WP "native" trailing slashes may be missing
				$url = rtrim($w2gm_instance->dashboard_page_url, '/') . '/' . rtrim($path, '/') . '/';
			} else
				$url = $w2gm_instance->dashboard_page_url;
		} else
			$url = add_query_arg($path, $w2gm_instance->dashboard_page_url);
	
		// adapted for WPML
		global $sitepress;
		if (function_exists('wpml_object_id_filter') && $sitepress) {
			$url = $sitepress->convert_url($url);
		}
	} else {
		$url = site_url();
	}

	return $url;
}

function w2gm_login_form($args = array()) {
	$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$current_url = remove_query_arg('redirect_to', $current_url);
	$default_redirect = $current_url;
	
	$current_url = remove_query_arg('w2gm_action', $current_url);
	
	$redirect_to = w2gm_getValue($_GET, 'redirect_to', $default_redirect);
	
	$defaults = array(
			'redirect' => $redirect_to, // Default redirect is back to the current page
			'form_id' => 'loginform',
			'label_username' => __( 'Username', 'W2GM' ),
			'label_password' => __( 'Password', 'W2GM' ),
			'label_remember' => __( 'Remember Me', 'W2GM' ),
			'label_log_in' => __( 'Log In', 'W2GM' ),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'remember' => true,
			'value_username' => '',
			'value_remember' => false, // Set this to true to default the "Remember me" checkbox to checked
	);
	$args = wp_parse_args($args, apply_filters('login_form_defaults', $defaults));

	if (defined('W2GM_DEMO') && W2GM_DEMO) {
		$login = 'demo';
		$pass = 'demo';
	} else {
		$login = esc_attr( $args['value_username'] );
		$pass = '';
	}
	
	$url = add_query_arg(array('w2gm_action' => 'login', 'redirect_to' => urlencode($args['redirect'])), $current_url);

	echo '<div class="w2gm-content">';

	echo '
		<form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . $url . '" method="post" class="w2gm_login_form" role="form">
			' . apply_filters('login_form_top', '', $args) . '
			<div class="w2gm-form-group">
				<label for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '</label>
				<input type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="w2gm-form-control" value="' . $login . '" />
			</div>
			<div class="w2gm-form-group">
				<label for="' . esc_attr( $args['id_password'] ) . '">' . esc_html( $args['label_password'] ) . '</label>
				<input type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="w2gm-form-control" value="' . $pass . '" />
			</div>
			<div class="w2gm-form-group">
			' . apply_filters( 'login_form_middle', '', $args ) . '
			' . ( $args['remember'] ? '<p class="checkbox"><label><input name="rememberme" type="checkbox" id="' . esc_attr( $args['id_remember'] ) . '" value="forever"' . ( $args['value_remember'] ? ' checked="checked"' : '' ) . ' /> ' . esc_html( $args['label_remember'] ) . '</label></p>' : '' ) . '
			</div>
			<div class="w2gm-form-group">
				<input type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="w2gm-btn w2gm-btn-primary" value="' . esc_attr( $args['label_log_in'] ) . '" />
				<input type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />
			</div>
			' . apply_filters('login_form_bottom', '', $args) . '
		</form>';

	do_action('login_form');
	do_action('login_footer');
	echo '<p id="nav">';
	if (get_option('users_can_register')) {
		echo '<a href="' . esc_url(wp_registration_url()) . '" rel="nofollow">' . __('Register', 'W2GM') . '</a> | ';
	}

	echo '<a title="' . esc_attr__('Password Lost and Found', 'W2GM') . '" href="' . esc_url(wp_lostpassword_url()) . '">' . __('Lost your password?', 'W2GM') . '</a>';
	echo '</p>';

	echo '</div>';
}

function w2gm_resetpassword_form($args = array()) {
	$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$current_url = remove_query_arg('redirect_to', $current_url);
	$default_redirect = $current_url;
	
	$current_url = remove_query_arg('w2gm_action', $current_url);
	
	$redirect_to = w2gm_getValue($_GET, 'redirect_to', $default_redirect);
	
	$defaults = array(
			'redirect' => $redirect_to, // Default redirect is back to the current page
			'form_id' => 'resetpassform',
			'label_pass1' => __( 'New password', 'W2GM' ),
			'label_pass2' => __( 'Confirm new password', 'W2GM' ),
			'label_submit' => __( 'Reset Password', 'W2GM' ),
			'id_pass1' => 'password1',
			'id_pass2' => 'password2',
			'id_submit' => 'wp-submit',
			'rp_key' => '',
	);
	$args = wp_parse_args($args, apply_filters('resetpassword_form_defaults', $defaults));
	
	$url = add_query_arg(array('w2gm_action' => 'resetpass', 'redirect_to' => $redirect_to), $current_url);

	echo '<div class="w2gm-content">';

	echo '
		<form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . $url . '" method="post" class="w2gm_login_form" role="form">
			<input type="hidden" name="rp_key" value="' . esc_attr( $args['rp_key'] ) . '" />
			' . apply_filters('resetpassword_form_top', '', $args) . '
			<div class="w2gm-form-group">
				<label for="' . esc_attr( $args['id_pass1'] ) . '">' . esc_html( $args['label_pass1'] ) . '</label>
				<div class="user-pass1-wrap">
					<button type="button" class="button w2gm-btn w2gm-btn-primary wp-generate-pw hide-if-no-js">' . __('Generate Password', 'W2GM') . '</button>
					<div class="wp-pwd hide-if-js">
						<span class="password-input-wrapper">
							<input type="password" name="pass1" id="pass1" class="regular-text" value="" autocomplete="off" data-pw="' . esc_attr(wp_generate_password(24)) . '" aria-describedby="pass-strength-result" />
						</span>
						<button type="button" class="button w2gm-btn w2gm-btn-primary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="' . esc_attr__('Hide password', 'W2GM') . '">
							<span class="dashicons dashicons-hidden"></span>
							<span class="text">' . __('Hide', 'W2GM') . '</span>
						</button>
						<button type="button" class="button w2gm-btn w2gm-btn-primary wp-cancel-pw hide-if-no-js" data-toggle="0" aria-label="' . esc_attr__('Cancel password change', 'W2GM') . '">
							<span class="text">' . __('Cancel', 'W2GM') . '</span>
						</button>
						<div style="display:none" id="pass-strength-result" aria-live="polite"></div>
					</div>
			 	</div>
			</div>
			<div class="w2gm-form-group">
				<label for="' . esc_attr( $args['id_pass2'] ) . '">' . esc_html($args['label_pass2']) . '</label>
				<input type="password" name="pass2" id="' . esc_attr($args['id_pass2']) . '" class="w2gm-form-control" value="" />
			</div>
			<div class="w2gm-form-group">
				<input type="submit" name="wp-submit" id="' . esc_attr($args['id_submit']) . '" class="w2gm-btn w2gm-btn-primary" value="' . esc_attr($args['label_submit']) . '" />
			</div>
		</form>';

	echo '</div>';
}

function w2gm_lostpassword_form($args = array()) {
	$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$current_url = remove_query_arg('redirect_to', $current_url);
	$default_redirect = $current_url;
	
	$current_url = remove_query_arg('w2gm_action', $current_url);
	
	$redirect_to = w2gm_getValue($_GET, 'redirect_to', $default_redirect);
	
	$defaults = array(
			'redirect' => $redirect_to, // Default redirect is back to the current page
			'form_id' => 'lostpasswordform',
			'label_username' => __('Username or Email Address', 'W2GM'),
			'label_submit' => __('Get New Password', 'W2GM'),
			'id_username' => 'user_login',
			'id_submit' => 'wp-submit',
	);
	$args = wp_parse_args($args, apply_filters('lostpassword_form_defaults', $defaults));
	
	$url = add_query_arg(array('w2gm_action' => 'lostpassword', 'redirect_to' => urlencode($args['redirect'])), $current_url);

	echo '<div class="w2gm-content">';

	echo '
		<form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . $url . '" method="post" class="w2gm_login_form" role="form">
			' . apply_filters('lostpassword_form_top', '', $args) . '
			<div class="w2gm-form-group">
				<label for="' . esc_attr($args['id_username']) . '">' . esc_html($args['label_username']) . '</label>
				<input type="text" name="user_login" id="' . esc_attr($args['id_username']) . '" class="w2gm-form-control" value="" />
			</div>
			<div class="w2gm-form-group">
				<input type="submit" name="wp-submit" id="' . esc_attr($args['id_submit']) . '" class="w2gm-btn w2gm-btn-primary" value="' . esc_attr($args['label_submit']) . '" />
				<input type="hidden" name="redirect_to" value="' . esc_url($args['redirect']) . '" />
			</div>
			' . apply_filters('lostpassword_form_bottom', '', $args) . '
		</form>';

	echo '<p id="nav">';
	echo '<a href="' . esc_url(wp_login_url()) . '" rel="nofollow">' . __('Log in', 'W2GM') . '</a>';
	if (get_option('users_can_register')) {
		echo ' | <a href="' . esc_url(wp_registration_url()) . '" rel="nofollow">' . __('Register', 'W2GM') . '</a>';
	}
	echo '</p>';

	echo '</div>';
}

function w2gm_registration_form($args = array()) {
	$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$current_url = remove_query_arg('redirect_to', $current_url);
	$default_redirect = $current_url;
	
	$current_url = remove_query_arg('w2gm_action', $current_url);
	
	$redirect_to = w2gm_getValue($_GET, 'redirect_to', $default_redirect);
	
	$defaults = array(
			'redirect' => $redirect_to, // Default redirect is back to the current page
			'form_id' => 'registerform',
			'label_username' => __('Username', 'W2GM'),
			'label_email' => __('Email', 'W2GM'),
			'label_submit' => __('Register', 'W2GM'),
			'id_username' => 'user_login',
			'id_email' => 'user_email',
			'id_submit' => 'wp-submit',
	);
	$args = wp_parse_args($args, apply_filters('registration_form_defaults', $defaults));
	
	$url = add_query_arg(array('w2gm_action' => 'register', 'redirect_to' => urlencode($args['redirect'])), $current_url);

	echo '<div class="w2gm-content">';

	echo '
		<form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . $url . '" method="post" class="w2gm_login_form" role="form">
			' . apply_filters('registration_form_top', '', $args) . '
			<div class="w2gm-form-group">
				<label for="' . esc_attr( $args['id_username'] ) . '">' . esc_html($args['label_username']) . '</label>
				<input type="text" name="user_login" id="' . esc_attr($args['id_username']) . '" class="w2gm-form-control" value="" />
			</div>
			<div class="w2gm-form-group">
				<label for="' . esc_attr( $args['id_email'] ) . '">' . esc_html( $args['label_email'] ) . '</label>
				<input type="text" name="user_email" id="' . esc_attr($args['id_email']) . '" class="w2gm-form-control" value="" />
			</div>
			<p id="reg_passmail">' . __('Registration confirmation will be emailed to you.', 'W2GM') . '</p>
			<div class="w2gm-form-group">
				<input type="submit" name="wp-submit" id="' . esc_attr($args['id_submit']) . '" class="w2gm-btn w2gm-btn-primary" value="' . esc_attr($args['label_submit']) . '" />
				<input type="hidden" name="redirect_to" value="' . esc_url($args['redirect']) . '" />
			</div>
			' . apply_filters('registration_form_bottom', '', $args) . '
		</form>';
	
	do_action('register_form');

	echo '<p id="nav">';
	echo '<a href="' . esc_url(wp_login_url()) . '" rel="nofollow">' . __('Log in', 'W2GM') . '</a> | ';
	echo '<a title="' . esc_attr__('Password Lost and Found', 'W2GM') . '" href="' . esc_url(wp_lostpassword_url()) . '">' . __('Lost your password?', 'W2GM') . '</a>';
	echo '</p>';

	echo '</div>';
}

function w2gm_get_login_registration_pages() {
	global $w2gm_instance;

	$pages = array();
	if (!empty($w2gm_instance->submit_page)) {
		$pages = $pages + array(array('id' => $w2gm_instance->submit_page['id']));
	}
	if (!empty($w2gm_instance->dashboard_page_id)) {
		$pages = $pages + array(array('id' => $w2gm_instance->dashboard_page_id));
	}
	$pages = apply_filters('w2gm_login_registration_pages', $pages);

	return $pages;
}

function w2gm_lostpassword_url($url) {
	$current_page_id = get_the_ID();
	
	$pages = w2gm_get_login_registration_pages();
	
	global $wp;
	$current_page_url = home_url(add_query_arg(array($_GET), $wp->request));
	
	$redirect_to = remove_query_arg('redirect_to', $current_page_url);
	
	foreach ($pages AS $page) {
		if ($page['id'] == $current_page_id) {
			return add_query_arg(array('w2dc_action' => 'lostpassword', 'redirect_to' => urlencode($redirect_to)), $current_page_url);
			break;
		}
	}
	
	return $url;
}
add_filter('lostpassword_url', 'w2gm_lostpassword_url', 100);

function w2gm_register_url($url) {
	$current_page_id = get_the_ID();
	
	$pages = w2gm_get_login_registration_pages();
	
	global $wp;
	$current_page_url = home_url(add_query_arg(array($_GET), $wp->request));
	
	$redirect_to = remove_query_arg('redirect_to', $current_page_url);
	
	foreach ($pages AS $page) {
		if ($page['id'] == $current_page_id) {
			return add_query_arg(array('w2gm_action' => 'register', 'redirect_to' => urlencode($redirect_to)), $current_page_url);
			break;
		}
	}
	
	return $url;
}
add_filter('register_url', 'w2gm_register_url', 100);

function w2gm_logout_url($url, $redirect_to) {
	$current_page_id = get_the_ID();

	$pages = w2gm_get_login_registration_pages();
	
	global $wp;
	$current_page_url = home_url(add_query_arg(array($_GET), $wp->request));
	
	$redirect_to = remove_query_arg('redirect_to', $current_page_url);
	
	foreach ($pages AS $page) {
		if ($page['id'] == $current_page_id) {
			return add_query_arg(array('w2gm_action' => 'logout', 'redirect_to' => urlencode($redirect_to)), $current_page_url);
			break;
		}
	}
	
	return $url;
}
add_filter('logout_url', 'w2gm_logout_url', 100, 2);

function w2gm_login_url($url, $redirect_to) {
	$current_page_id = get_the_ID();

	$pages = w2gm_get_login_registration_pages();
	
	global $wp;
	$current_page_url = home_url(add_query_arg(array($_GET), $wp->request));
	
	$redirect_to = remove_query_arg('redirect_to', $current_page_url);
	
	foreach ($pages AS $page) {
		if ($page['id'] == $current_page_id) {
			return add_query_arg(array('w2gm_action' => 'login', 'redirect_to' => urlencode($redirect_to)), $current_page_url);
			break;
		}
	}
	
	return $url;
}
add_filter('login_url', 'w2gm_login_url', 100, 2);

?>