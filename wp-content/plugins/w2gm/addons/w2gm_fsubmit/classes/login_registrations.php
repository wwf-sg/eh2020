<?php 

class w2gm_login_registrations {
	
	public function is_action() {
		global $w2gm_instance;
		
		$action = $w2gm_instance->action;
		
		return in_array($action, array('lostpassword', 'resetpass', 'rp', 'register', 'logout', 'login'));
	}
	
	public function get_current_url() {
		$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$current_url = remove_query_arg('w2gm_action', $current_url);
		$current_url = remove_query_arg('redirect_to', $current_url);
		$current_url = remove_query_arg('msg', $current_url);
		
		return $current_url;
	}
	
	public function replace_url_password_message($message, $key, $user_login, $user_data) {
		$current_url = $this->get_current_url();
		
		$url = add_query_arg('w2gm_action', 'rp', $current_url);
		$url = add_query_arg('key', $key, $url);
		$url = add_query_arg('login', rawurlencode($user_login), $url);
		
		$redirect_to = w2gm_getValue($_GET, 'redirect_to', $url);
		$url = add_query_arg('redirect_to', urlencode($redirect_to), $url);
	
		$message = str_replace('<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . '>', '<' . $url . '>', $message);
		
		return $message;
	}
	
	public function replace_new_user_notification_email($wp_new_user_notification_email, $user, $blogname) {
		$current_url = $this->get_current_url();
		
		preg_match_all('/([^?&=#]+)=([^&#]*)/', $wp_new_user_notification_email['message'], $m);
		$message_url_params = array_combine($m[1], $m[2]);
		if (!empty($message_url_params['key'])) {
			$key = $message_url_params['key'];
			$url = add_query_arg('w2gm_action', 'rp', $current_url);
			$url = add_query_arg('key', $key, $url);
			$url = add_query_arg('login', rawurlencode($user->user_login), $url);
			
			$redirect_to = w2gm_getValue($_GET, 'redirect_to', $url);
			$url = add_query_arg('redirect_to', urlencode($redirect_to), $url);
		
			$wp_new_user_notification_email['message'] = str_replace('<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' ) . '>', '<' . $url . '>', $wp_new_user_notification_email['message']);
			$wp_new_user_notification_email['message'] = str_replace(wp_login_url(), '', $wp_new_user_notification_email['message']);
		}
		
		return $wp_new_user_notification_email;
	}
	
	public function process($controller = false) {
		global $w2gm_instance;

		$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);
		
		$action = $w2gm_instance->action;
		switch ($action) {
			case 'lostpassword':

				if ($http_post) {
					ob_start();
					require_once (ABSPATH . '/wp-login.php');
					ob_end_clean();
					
					add_action('wp_mail_failed', 'w2gm_error_log');
					
					add_filter('retrieve_password_message', array($this, 'replace_url_password_message'), 10, 4);
					$errors = retrieve_password();
					remove_filter('retrieve_password_message', array($this, 'replace_url_password_message'));
					if (!is_wp_error($errors)) {
						$current_url = $this->get_current_url();
						$current_url = add_query_arg('w2gm_action', 'lostpassword', $current_url);
						$current_url = add_query_arg('msg', 'checkemail', $current_url);
						
						$redirect_to = w2gm_getValue($_GET, 'redirect_to', $current_url);
						$current_url = add_query_arg('redirect_to', urlencode($redirect_to), $current_url);
						
						wp_safe_redirect($current_url);
						exit;
					} else {
						w2gm_addMessage($errors->get_error_message(), 'error');
						
						wp_redirect($_SERVER['HTTP_REFERER']);
						exit;
					}
				}
				
				if (w2gm_getValue($_GET, 'msg') == 'checkemail') {
					w2gm_addMessage(__('Check your email for the confirmation link.', 'W2GM'));
				} elseif (w2gm_getValue($_GET, 'msg') == 'expiredkey') {
					w2gm_addMessage(__('Your password reset link has expired. Please request a new link below.', 'W2GM'), 'error');
				} else if (w2gm_getValue($_GET, 'msg') == 'invalidkey') {
					w2gm_addMessage(__('Your password reset link appears to be invalid. Please request a new link below.', 'W2GM'), 'error');
				}
				
				return array(W2GM_FSUBMIT_TEMPLATES_PATH, 'lostpassword_form.tpl.php');
				
				break;
			case 'resetpass':
			case 'rp':
				list($rp_path) = explode('?', wp_unslash( $_SERVER['REQUEST_URI']));
				$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
				if (isset($_GET['key'])) {
					$value = sprintf('%s:%s', wp_unslash($_GET['login']), wp_unslash($_GET['key']));
					setcookie($rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true);
					wp_safe_redirect(remove_query_arg(array('key', 'login')));
					exit;
				}
				
				if (isset($_COOKIE[$rp_cookie]) && 0 < strpos($_COOKIE[$rp_cookie], ':')) {
					list($rp_login, $rp_key) = explode(':', wp_unslash($_COOKIE[$rp_cookie]), 2 );
					$user = check_password_reset_key($rp_key, $rp_login);
					if (isset($_POST['pass1']) && ! hash_equals($rp_key, $_POST['rp_key'])) {
						$user = false;
					}
					$controller->add_template_args(array('rp_key' => $rp_key));
				} else {
					$user = false;
				}
				
				$current_url = $this->get_current_url();
				
				if (!$user || is_wp_error($user)) {
					setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true);
					if ($user && $user->get_error_code() === 'expired_key') {
						wp_safe_redirect(add_query_arg(array('w2gm_action' => 'lostpassword', 'msg' => 'expiredkey'), $current_url));
					} else {
						wp_safe_redirect(add_query_arg(array('w2gm_action' => 'lostpassword', 'msg' => 'invalidkey'), $current_url));
					}
					exit;
				}
				
				$errors = new WP_Error();
				
				if (isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2']) {
					w2gm_addMessage(__('The passwords do not match.', 'W2GM'), 'error');
					$errors->add('password_reset_mismatch', __('The passwords do not match.', 'W2GM'));
				}
				
				do_action( 'validate_password_reset', $errors, $user );

				if ((!$errors->has_errors() ) && isset($_POST['pass1']) && ! empty( $_POST['pass1'])) {
					$redirect_to = w2gm_getValue($_GET, 'redirect_to', $current_url);
					
					reset_password($user, $_POST['pass1']);
					setcookie($rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true);
					w2gm_addMessage(__('Your password has been reset.', 'W2GM'));
					wp_safe_redirect(add_query_arg(array('w2gm_action' => 'login', 'redirect_to' => urlencode($redirect_to)), $current_url));
					exit;
				}
				
				include_once ABSPATH . 'wp-admin/includes/user.php';
				wp_enqueue_script('password-strength-meter');
				wp_enqueue_script('user-profile');
				
				return array(W2GM_FSUBMIT_TEMPLATES_PATH, 'resetpassword_form.tpl.php');
				
				break;
			case 'register':
				if ($http_post) {
					if (isset($_POST['user_login']) && is_string($_POST['user_login'])) {
						$user_login = $_POST['user_login'];
					}
				
					if (isset($_POST['user_email']) && is_string($_POST['user_email'])) {
						$user_email = wp_unslash($_POST['user_email']);
					}
					
					$redirect_to = '';
					if (!empty($_REQUEST['redirect_to'])) {
						$redirect_to = $_REQUEST['redirect_to'];
					}
					
					add_action('wp_mail_failed', 'w2gm_error_log');
				
					add_filter('wp_new_user_notification_email', array($this, 'replace_new_user_notification_email'), 10, 3);
					$errors = register_new_user($user_login, $user_email);
					remove_filter('wp_new_user_notification_email', array($this, 'replace_new_user_notification_email'));
					if (!is_wp_error($errors)) {
						w2gm_addMessage(__('Registration complete. Please check your email.', 'W2GM'));
						if ($redirect_to) {
							wp_safe_redirect($redirect_to);
							exit;
						}
					} elseif ($errors->get_error_message()) {
						w2gm_addMessage($errors->get_error_message(), 'error');
					}
				}
				
				return array(W2GM_FSUBMIT_TEMPLATES_PATH, 'registration_form.tpl.php');
				
				break;
			case 'logout':
				/* wp_logout();
				
				if ( ! empty( $_REQUEST['redirect_to'] ) ) {
					$redirect_to = $requested_redirect_to = $_REQUEST['redirect_to'];
				} else {
					$redirect_to           = home_url();
					$requested_redirect_to = '';
				} */
				
				$user = wp_get_current_user();
				
				wp_logout();
				
				if ( ! empty( $_REQUEST['redirect_to'] ) ) {
					$redirect_to           = $_REQUEST['redirect_to'];
					$requested_redirect_to = $redirect_to;
				} else {
					$redirect_to = add_query_arg(
							array(
									'loggedout' => 'true',
									'wp_lang'   => get_user_locale( $user ),
							),
							wp_login_url()
					);
				
					$requested_redirect_to = '';
				}

				$redirect_to = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $user );
				wp_safe_redirect( $redirect_to );
				exit();
				
				break;
			case 'login':
			default:
				$redirect_to = '';
				if (!empty($_REQUEST['redirect_to'])) {
					$redirect_to = $_REQUEST['redirect_to'];
				}

				if (!empty($_POST)) {
					$user = wp_signon(array());
	
					if (!is_wp_error($user)) {
						if ($redirect_to) {
							wp_safe_redirect($redirect_to);
							exit;
						}
					} elseif ($user->get_error_message()) {
						w2gm_addMessage($user->get_error_message(), 'error');
					}
				}

				return array(W2GM_FSUBMIT_TEMPLATES_PATH, 'login_form.tpl.php');
		}
	}
	
	public function login_template() {
		return array(W2GM_FSUBMIT_TEMPLATES_PATH, 'login_form.tpl.php');
	}
}

?>