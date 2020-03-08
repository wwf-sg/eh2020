<?php 

class w2gm_ajax_controller {
	public $listing_id;

	public function __construct() {
		add_action('wp_ajax_w2gm_get_map_markers', array($this, 'get_map_markers'));
		add_action('wp_ajax_nopriv_w2gm_get_map_markers', array($this, 'get_map_markers'));

		add_action('wp_ajax_w2gm_get_map_marker_info', array($this, 'get_map_marker_info'));
		add_action('wp_ajax_nopriv_w2gm_get_map_marker_info', array($this, 'get_map_marker_info'));

		add_action('wp_ajax_w2gm_controller_request', array($this, 'controller_request'));
		add_action('wp_ajax_nopriv_w2gm_controller_request', array($this, 'controller_request'));

		add_action('wp_ajax_w2gm_search_by_poly', array($this, 'search_by_poly'));
		add_action('wp_ajax_nopriv_w2gm_search_by_poly', array($this, 'search_by_poly'));
		
		add_action('wp_ajax_w2gm_select_fa_icon', array($this, 'select_field_icon'));
		add_action('wp_ajax_nopriv_w2gm_select_fa_icon', array($this, 'select_field_icon'));
		
		add_action('wp_ajax_w2gm_listing_dialog', array($this, 'listing_dialog'));
		add_action('wp_ajax_nopriv_w2gm_listing_dialog', array($this, 'listing_dialog'));
		
		add_action('wp_ajax_w2gm_contact_form', array($this, 'contact_form'));
		add_action('wp_ajax_nopriv_w2gm_contact_form', array($this, 'contact_form'));

		add_action('wp_ajax_w2gm_keywords_search', array($this, 'keywords_search'));
		add_action('wp_ajax_nopriv_w2gm_keywords_search', array($this, 'keywords_search'));
		
		add_action('init', array($this, 'set_up_contact_form_7'));
	}

	public function controller_request() {
		global $w2gm_instance;

		$post_args = $_POST;

		switch ($post_args['controller']) {
			case "maps_controller":
				$shortcode_atts = array_merge(array(
						'perpage' => 10,
						'onepage' => 0,
						'order_by' => 'post_date',
						'order' => 'DESC',
						'author' => 0,
						'paged' => 1,
						'include_categories_children' => 0,
						'include_get_params' => 1,
						'template' => 'frontend/listings_block.tpl.php',
				), $post_args);
				
				$address = false;
				$radius = false;
				if (isset($post_args['address'])) {
					$address = apply_filters('w2gm_search_param_address', $post_args['address']);
				}
				if (isset($post_args['radius'])) {
					$radius = apply_filters('w2gm_search_param_radius', $post_args['radius']);
				}

				// This is required workaround
				if (isset($post_args['order_by'])) {
					$_REQUEST['order_by'] = w2gm_getValue($post_args, 'order_by', $shortcode_atts['order_by']);
					$_REQUEST['order'] = w2gm_getValue($post_args, 'order', $shortcode_atts['order']);
				} elseif ($address && $radius) {
					// When search by radius - order by distance by default instead of ordering by date
					$shortcode_atts['order_by'] = 'distance';
					$shortcode_atts['order'] = 'ASC';
				}

				// Strongly required for paginator
				set_query_var('page', $shortcode_atts['paged']);

				$controller = new w2gm_frontend_controller();
				$controller->init($post_args);
				$controller->hash = $post_args['hash'];
				$controller->args = $shortcode_atts;
				$controller->request_by = 'maps_controller';

				//$default_orderby_args = array('order_by' => $shortcode_atts['order_by'], 'order' => $shortcode_atts['order']);
				$order_args = apply_filters('w2gm_order_args', array(), $shortcode_atts, false);
				
				// while random sorting and we have to exclude already shown listings - do not limit records, we will take needed later
				if (isset($shortcode_atts['existing_listings']) && $order_args['orderby'] == 'rand') {
					$perpage = -1;
				} else {
					$perpage = $shortcode_atts['perpage'];
				}
				
				$args = array(
						'post_type' => W2GM_POST_TYPE,
						'post_status' => 'publish',
						//'meta_query' => array(array('key' => '_listing_status', 'value' => 'active')),
						'posts_per_page' => ($perpage ? $perpage : -1),
						'paged' => $shortcode_atts['paged'],
				);
				if ($shortcode_atts['author'])
					$args['author'] = $shortcode_atts['author'];
				// render just one page
				if ($shortcode_atts['onepage'])
					$args['posts_per_page'] = -1;

				$args = array_merge($args, $order_args);
				$args = apply_filters('w2gm_search_args', $args, $shortcode_atts, $shortcode_atts['include_get_params'], $controller->hash);
				if (!empty($shortcode_atts['post__in'])) {
					if (is_string($shortcode_atts['post__in'])) {
						$args = array_merge($args, array('post__in' => explode(',', $shortcode_atts['post__in'])));
					} elseif (is_array($shortcode_atts['post__in'])) {
						$args['post__in'] = $shortcode_atts['post__in'];
					}
				}
				if (!empty($shortcode_atts['post__not_in'])) {
					$args = array_merge($args, array('post__not_in' => explode(',', $shortcode_atts['post__not_in'])));
				}
				
				$args = apply_filters('w2gm_maps_query_args', $args);
					
				// found some plugins those break WP_Query by injections in pre_get_posts action, so decided to remove this hook temporarily
				global $wp_filter;
				if (isset($wp_filter['pre_get_posts'])) {
					$pre_get_posts = $wp_filter['pre_get_posts'];
					unset($wp_filter['pre_get_posts']);
				}
				$controller->query = new WP_Query($args);
				//var_dump($controller->query->request);

				// adapted for Relevanssi
				if (w2gm_is_relevanssi_search($shortcode_atts)) {
					$controller->query->query_vars['s'] = w2gm_getValue($shortcode_atts, 'what_search');
					$controller->query->query_vars['posts_per_page'] = $perpage;
					relevanssi_do_query($controller->query);
				}
				//var_dump($controller->query->request);
				
				// while random sorting - we have to exclude already shown listings, we are taking only needed
				if (isset($shortcode_atts['existing_listings']) && $order_args['orderby'] == 'rand') {
					$all_posts_count = count($controller->query->posts);
					$existing_listings = array_filter(explode(',', $shortcode_atts['existing_listings']));
					foreach ($controller->query->posts AS $key=>$post) {
						if (in_array($post->ID, $existing_listings)) {
							unset($controller->query->posts[$key]);
						}
					}
					$controller->query->posts = array_values($controller->query->posts);
					$controller->query->posts = array_slice($controller->query->posts, 0, $shortcode_atts['perpage']);

					$controller->query->post_count = count($controller->query->posts);
					$controller->query->found_posts = $all_posts_count;
					$controller->query->max_num_pages = ceil($all_posts_count/$shortcode_atts['perpage']);
				}
				
				if (!empty($post_args['with_map']) || !empty($post_args['map_listings'])) {
					$load_map_markers = true;
				} else {
					$load_map_markers = false;
				}

				$map_args = array();

				$controller->processQuery(true, $load_map_markers, $map_args);
				if (isset($pre_get_posts)) {
					$wp_filter['pre_get_posts'] = $pre_get_posts;
				}

				wp_reset_postdata();

				$out = array(
						'hash' => $controller->hash,
						'map_markers' => ((!empty($post_args['with_map']) && $controller->map) ? $controller->map->locations_option_array : ''),
						'map_listings' => ((!empty($post_args['map_listings']) && $controller->map) ? $controller->map->buildListingsContent() : ''),
						'hide_show_more_listings_button' => ($shortcode_atts['paged'] >= $controller->query->max_num_pages) ? 1 : 0,
				);
				
				if (isset($w2gm_instance->radius_values_array[$controller->hash]) && isset($w2gm_instance->radius_values_array[$controller->hash]['x_coord']) && isset($w2gm_instance->radius_values_array[$controller->hash]['y_coord'])) {
					$out['radius_params'] = array(
							'radius_value' => $w2gm_instance->radius_values_array[$controller->hash]['radius'],
							'map_coords_1' => $w2gm_instance->radius_values_array[$controller->hash]['x_coord'],
							'map_coords_2' => $w2gm_instance->radius_values_array[$controller->hash]['y_coord'],
							'dimension' => get_option('w2gm_miles_kilometers_in_search')
					);
				}
				
				if ($json = json_encode(w2gm_utf8ize($out))) {
					echo $json;
				} else {
					echo json_last_error_msg();
				}

				break;
		}
		
		die();
	}

	public function get_map_markers() {
		global $w2gm_instance;
		
		$post_args = $_POST;
		$hash = $post_args['hash'];

		$map_markers = array();
		$map_listings = '';
		if (isset($post_args['neLat']) && isset($post_args['neLng']) && isset($post_args['swLat']) && isset($post_args['swLng'])) {
			// needed to unset 'ajax_loading' parameter when it is calling by AJAX, then $args will be passed to map controller
			if (isset($post_args['ajax_loading'])) {
				unset($post_args['ajax_loading']);
			}
			
			$address = false;
			$radius = false;
			if (isset($post_args['address'])) {
				$address = apply_filters('w2gm_search_param_address', $post_args['address']);
			}
			if (isset($post_args['radius'])) {
				$radius = apply_filters('w2gm_search_param_radius', $post_args['radius']);
			}
			
			if ($radius && $address) {
				// When search by radius - order by distance by default instead of ordering by date
				$post_args['order_by'] = 'distance';
				$post_args['order'] = 'ASC';
			}

			$map_controller = new w2gm_map_controller();
			$map_controller->hash = $hash;
			$map_controller->init($post_args);
			wp_reset_postdata();
			
			$map_markers = $map_controller->map->locations_option_array;
			if (!empty($post_args['map_listings'])) {
				$map_listings = $map_controller->map->buildListingsContent((!empty($post_args['show_directions_button']) ? 1 : 0), (!empty($post_args['show_readmore_button']) ? 1 : 0));
			}
		}

		$out = array(
				'hash' => $hash,
				'map_markers' => $map_markers,
				'map_listings' => $map_listings,
		);

		if (isset($w2gm_instance->radius_values_array[$hash]) && isset($w2gm_instance->radius_values_array[$hash]['x_coord']) && isset($w2gm_instance->radius_values_array[$hash]['y_coord'])) {
			$out['radius_params'] = array(
					'radius_value' => $w2gm_instance->radius_values_array[$hash]['radius'],
					'map_coords_1' => $w2gm_instance->radius_values_array[$hash]['x_coord'],
					'map_coords_2' => $w2gm_instance->radius_values_array[$hash]['y_coord'],
					'dimension' => get_option('w2gm_miles_kilometers_in_search')
			);
		}
			
		if ($json = json_encode(w2gm_utf8ize($out))) {
			echo $json;
		} else {
			echo json_last_error_msg();
		}

		die();
	}
	
	public function search_by_poly() {
		global $w2gm_instance;

		$post_args = $_POST;
		$hash = $post_args['hash'];
		
		$out = array(
				'hash' => $hash
		);

		$map_markers = array();
		$map_listings = '';
		if (isset($post_args['geo_poly']) && $post_args['geo_poly']) {
			$map_controller = new w2gm_map_controller();
			$map_controller->hash = $hash;
			// Here we need to remove any location-based parameters, leave only content-based (like categories, content fields, ....)
			$post_args['ajax_loading'] = 0; // ajax loading always OFF
			//$post_args['ajax_markers_loading'] = 0; // ajax infowindow always OFF
			$post_args['radius'] = 0; // this is not the case for radius search
			$post_args['address'] = ''; // this is not the case for address search
			$post_args['location_id'] = 0; // this is not the case for search by location ID
			$post_args['locations'] = ''; // this is not the case for search by locations
			$map_controller->init($post_args);
			wp_reset_postdata();

			$map_markers = $map_controller->map->locations_option_array;
			if (!empty($post_args['map_listings'])) {
				$map_listings = $map_controller->map->buildListingsContent();
			}
		}

		$out['map_markers'] = $map_markers;
		$out['map_listings'] = $map_listings;

		if ($json = json_encode(w2gm_utf8ize($out))) {
			echo $json;
		} else {
			echo json_last_error_msg();
		}
	
		die();
	}
	
	public function get_map_marker_info() {
		global $w2gm_instance, $wpdb;

		if (isset($_POST['location_id']) && is_numeric($_POST['location_id'])) {
			$location_id = $_POST['location_id'];

			$row = $wpdb->get_row("SELECT * FROM {$wpdb->w2gm_locations_relationships} WHERE id=".$location_id, ARRAY_A);

			if ($row && $row['location_id'] || $row['map_coords_1'] != '0.000000' || $row['map_coords_2'] != '0.000000' || $row['address_line_1'] || $row['zip_or_postal_index']) {
				$listing = new w2gm_listing;
				if ($listing->loadListingFromPost($row['post_id'])) {
					$location = new w2gm_location($row['post_id']);
					$location_settings['id'] = w2gm_getValue($row, 'id');
					$location_settings['post_id'] = w2gm_getValue($row, 'post_id');
					$location_settings['selected_location'] = w2gm_getValue($row, 'location_id');
					$location_settings['address_line_1'] = w2gm_getValue($row, 'address_line_1');
					$location_settings['address_line_2'] = w2gm_getValue($row, 'address_line_2');
					$location_settings['zip_or_postal_index'] = w2gm_getValue($row, 'zip_or_postal_index');
					$location_settings['additional_info'] = w2gm_getValue($row, 'additional_info');
					$location_settings['manual_coords'] = w2gm_getValue($row, 'manual_coords');
					$location_settings['map_coords_1'] = w2gm_getValue($row, 'map_coords_1');
					$location_settings['map_coords_2'] = w2gm_getValue($row, 'map_coords_2');
					if ($listing->level->map_markers) {
						$location_settings['map_icon_file'] = w2gm_getValue($row, 'map_icon_file');
					}
					$location->createLocationFromArray($location_settings);
						
					$logo_image = '';
					if ($listing->logo_image) {
						$logo_image = $listing->get_logo_url(array(80, 80));
					}
						
					$content_fields_output = $listing->setMapContentFields($w2gm_instance->content_fields->getMapContentFields(), $location);

					$locations_option_array = array(
							$location->id,
							$listing->post->ID,
							$location->map_coords_1,
							$location->map_coords_2,
							$location->map_icon_file,
							$location->map_icon_color,
							$listing->map_zoom,
							$listing->title(),
							$logo_image,
							$content_fields_output,
					);
						
					if ($json = json_encode($locations_option_array)) {
						echo $json;
					} else {
						echo json_last_error_msg();
					}
				}
			}
		}
		die();
	}
	
	public function select_field_icon() {
		w2gm_renderTemplate('select_fa_icons.tpl.php', array('icons' => w2gm_get_fa_icons_names()));
		die();
	}
	
	public function listing_dialog() {
		global $w2gm_instance, $wpdb;

		if (isset($_REQUEST['listing_id']) && is_numeric($_REQUEST['listing_id'])) {
			$listing_id = $_REQUEST['listing_id'];
		
			if ($listing_id) {
				$controller = new w2gm_frontend_controller;
				$controller->init(array('uid' => time()));
				$args = array(
						'post_type' => W2GM_POST_TYPE,
						'post_status' => 'publish',
						'p' => $listing_id,
						'posts_per_page' => 1,
				);
				$controller->query = new WP_Query($args);
				
				while ($controller->query->have_posts()) {
					$controller->query->the_post();
				
					$listing = new w2gm_listing;
					$listing->loadListingFromPost(get_post());
				}
				
				if (get_option('w2gm_listings_comments_plugin') == 'plugin') {
					add_filter('comments_template', array($this, 'w2gm_comments_template'));
				}
					
				$controller->listings[$listing->post->ID] = $listing;
				$controller->listing = $listing;
				
				$this->listing_id = $listing->post->ID;

				$controller->listing->increaseClicksStats();
					
				$out = array(
						'listing_html' => w2gm_renderTemplate('frontend/listing_single.tpl.php', array('frontend_controller' => $controller), true),
						'listing_title' => $listing->title(),
						'hash' => $controller->hash
				);
				
				if ($json = json_encode(w2gm_utf8ize($out))) {
					echo $json;
				} else {
					echo json_last_error_msg();
				}
			}
		}
		
		die();
	}
	
	public function w2gm_comments_template($file){
		return W2GM_TEMPLATES_PATH . 'frontend/comments.tpl.php';
	}
	
	public function contact_form() {
		$success = '';
		$error = '';
		if (!($type = $_REQUEST['type'])) {
			$error = __('The type of message required!', 'W2GM');
		} else {
			check_ajax_referer('w2gm_' . $type . '_nonce', 'security');
	
			$validation = new w2gm_form_validation;
			if (!is_user_logged_in()) {
				$validation->set_rules('name', __('Contact name', 'W2GM'), 'required');
				$validation->set_rules('email', __('Contact email', 'W2GM'), 'required|valid_email');
			}
			$validation->set_rules('listing_id', __('Listing ID', 'W2GM'), 'required');
			$validation->set_rules('message', __('Your message', 'W2GM'), 'required|max_length[1500]');
			if ($validation->run()) {
				$listing = new w2gm_listing();
				if ($listing->loadListingFromPost($validation->result_array('listing_id'))) {
					if (!is_user_logged_in()) {
						$name = $validation->result_array('name');
						$email = $validation->result_array('email');
					} else {
						$current_user = wp_get_current_user();
						$name = $current_user->display_name;
						$email = $current_user->user_email;
					}
					$message = $validation->result_array('message');
		
					if (w2gm_is_recaptcha_passed()) {
						if ($type == 'contact') {
							if (get_option('w2gm_custom_contact_email') && $listing->contact_email) {
								$send_to_email = $listing->contact_email;
							} else {
								$listing_owner = get_userdata($listing->post->post_author);
								$send_to_email = $listing_owner->user_email;
							}
						} elseif ($type == 'report') {
							$send_to_email = get_option('admin_email');
						}
		
						$headers[] = "From: $name <$email>";
						$headers[] = "Reply-To: $email";
						$headers[] = "Content-Type: text/html";
	
						$subject = sprintf(__('%s contacted you about listing "%s"', 'W2GM'), $name, $listing->title());
		
						$body = w2gm_renderTemplate('emails/' . $type . '_form.tpl.php',
								array(
										'name' => $name,
										'email' => $email,
										'message' => $message,
										'listing_title' => $listing->title(),
										'listing_url' => get_permalink($listing->post->ID)
								), true);
	
						do_action('w2gm_send_' . $type . '_email', $listing, $send_to_email, $subject, $body, $headers);
						
						if (w2gm_mail($send_to_email, $subject, $body, $headers)) {
							unset($_POST['name']);
							unset($_POST['email']);
							unset($_POST['message']);
							$success = __('You message was sent successfully!', 'W2GM');
						} else {
							$error = esc_attr__("An error occurred and your message wasn't sent!", 'W2GM');
						}
					} else {
						$error = esc_attr__("Anti-bot test wasn't passed!", 'W2GM');
					}
				}
			} else {
				$error = $validation->error_array();
			}
		}
	
		echo json_encode(array('error' => $error, 'success' => $success));
	
		die();
	}
	
	public function keywords_search() {
		$validation = new w2gm_form_validation;
		$validation->set_rules('term', __('Search term', 'W2GM'));
		if ($validation->run()) {
			$term = $validation->result_array('term');

			$default_orderby_args = array('order_by' => get_option('w2gm_default_orderby'), 'order' => get_option('w2gm_default_order'));
			$order_args = apply_filters('w2gm_order_args', array(), $default_orderby_args);
		
			$args = array(
					'post_type' => W2GM_POST_TYPE,
					'post_status' => 'publish',
					'posts_per_page' => apply_filters('w2gm_ajax_search_listings_number', 10),
					's' => $term
			);
			$args = array_merge($args, $order_args);

			$query = new WP_Query($args);
			
			// adapted for Relevanssi
			if (w2gm_is_relevanssi_search()) {
				$query->query_vars['s'] = $term;
				$query->query_vars['posts_per_page'] = apply_filters('w2gm_ajax_search_listings_number', 10);
				relevanssi_do_query($query);
			}
			
			// disable hyphens to dashes conversion
			remove_filter('the_title', 'wptexturize');
			
			$listings_json = array();
			while ($query->have_posts()) {
				$query->the_post();
			
				$listing = new w2gm_listing;
				$listing->loadListingFromPost(get_post());
				
				$title = '<strong>' . $listing->title() . '</strong>';
				$content = w2gm_crop_content($listing->post->ID, get_option('w2gm_excerpt_length'), get_option('w2gm_strip_excerpt'));

				$listing_json_field = array();
				$listing_json_field['title'] = apply_filters('w2gm_listing_title_search_html', $title, $listing);
				$listing_json_field['name'] = $listing->title();
				$listing_json_field['url'] = get_the_permalink();
				$listing_json_field['icon'] = $listing->get_logo_url();
				$listing_json_field['sublabel'] = $content;
				$listings_json[] = $listing_json_field;
			}
			
			if ($json = json_encode(array('listings' => $listings_json))) {
				echo $json;
			} else {
				echo json_last_error_msg();
			}
		}
		
		die();
	}
	
	public function set_up_contact_form_7() {
		if (get_option('w2gm_listing_contact_form') && defined('WPCF7_VERSION') && w2gm_get_wpml_dependent_option('w2gm_listing_contact_form_7')) {
			add_filter('wpcf7_form_action_url', array($this, 'add_listing_id_to_wpcf7'));
			add_filter('wpcf7_form_hidden_fields', array($this, 'add_listing_id_to_wpcf7_field'));
		}
	}
	
	// Add listing ID to query string while rendering Contact Form 7
	public function add_listing_id_to_wpcf7($url) {
		$url = esc_url(add_query_arg('listing_id', $this->listing_id, $url));
		
		return $url;
	}
	// Add listing ID to hidden fields while rendering Contact Form 7
	public function add_listing_id_to_wpcf7_field($fields) {
		$fields["listing_id"] = $this->listing_id;
		
		return $fields;
	}
}

add_action('init', 'w2gm_handle_wpcf7');
function w2gm_handle_wpcf7() {
	if (defined('WPCF7_VERSION')) {
		if (get_option('w2gm_listing_contact_form') && defined('WPCF7_VERSION') && w2gm_get_wpml_dependent_option('w2gm_listing_contact_form_7'))
			add_filter('wpcf7_mail_components', 'w2gm_wpcf7_handle_email', 10, 2);

		function w2gm_wpcf7_handle_email($WPCF7_components, $WPCF7_currentform) {
			if (isset($_GET['listing_id'])) {
				$post = get_post($_GET['listing_id']);

				$mail = $WPCF7_currentform->prop('mail');
				// DO not touch mail_2
				if ($mail['recipient'] == $WPCF7_components['recipient'])
				if ($post && isset($_POST['_wpcf7']) && preg_match_all('/'.get_shortcode_regex().'/s', w2gm_get_wpml_dependent_option('w2gm_listing_contact_form_7'), $matches))
				foreach ($matches[2] AS $key=>$shortcode) {
					if ($shortcode == 'contact-form-7') {
						if ($attrs = shortcode_parse_atts($matches[3][$key]))
						if (isset($attrs['id']) && $attrs['id'] == $_POST['_wpcf7']) {
							if (($listing_owner = get_userdata($post->post_author)) && $listing_owner->user_email)
								$WPCF7_components['recipient'] = $listing_owner->user_email;
						}
					}
				}
			}
			return $WPCF7_components;
		}
	}
}

?>