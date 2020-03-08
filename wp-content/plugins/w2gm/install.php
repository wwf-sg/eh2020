<?php 

function w2gm_install_maps() {
	global $wpdb;
	
	if (!get_option('w2gm_installed_maps')) {
		$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->w2gm_content_fields_groups} (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`name` varchar(255) NOT NULL,
					`on_tab` tinyint(1) NOT NULL DEFAULT '0',
					`hide_anonymous` tinyint(1) NOT NULL DEFAULT '0',
					PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->w2gm_content_fields_groups} WHERE name = 'Contact Information'")) {
			$wpdb->query("INSERT INTO {$wpdb->w2gm_content_fields_groups} (`name`, `on_tab`, `hide_anonymous`) VALUES ('Contact Information', 0, 0)");
			do_action('Google Maps locator', 'The name of content fields group #1', 'Contact Information');
		}

		$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->w2gm_content_fields} (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`is_core_field` tinyint(1) NOT NULL DEFAULT '0',
					`order_num` int(11) NOT NULL,
					`name` varchar(255) NOT NULL,
					`slug` varchar(255) NOT NULL,
					`description` text NOT NULL,
					`type` varchar(255) NOT NULL,
					`icon_image` varchar(255) NOT NULL,
					`is_required` tinyint(1) NOT NULL DEFAULT '0',
					`is_configuration_page` tinyint(1) NOT NULL DEFAULT '0',
					`is_search_configuration_page` tinyint(1) NOT NULL DEFAULT '0',
					`is_ordered` tinyint(1) NOT NULL DEFAULT '0',
					`is_hide_name` tinyint(1) NOT NULL DEFAULT '0',
					`for_admin_only` tinyint(1) NOT NULL DEFAULT '0',
					`on_listing_page` tinyint(1) NOT NULL DEFAULT '0',
					`on_listing_sidebar` tinyint(1) NOT NULL DEFAULT '0',
					`on_search_form` tinyint(1) NOT NULL DEFAULT '0',
					`on_map` tinyint(1) NOT NULL DEFAULT '0',
					`advanced_search_form` tinyint(1) NOT NULL,
					`categories` text NOT NULL,
					`options` text NOT NULL,
					`search_options` text NOT NULL,
					`group_id` int(11) NOT NULL DEFAULT '0',
					PRIMARY KEY (`id`),
					KEY `group_id` (`group_id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->w2gm_content_fields} WHERE slug = 'summary'")) {
			$wpdb->query("INSERT INTO {$wpdb->w2gm_content_fields} (`is_core_field`, `order_num`, `name`, `slug`, `description`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `for_admin_only`, `on_listing_sidebar`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `search_options`, `group_id`) VALUES(1, 1, 'Summary', 'summary', '', 'excerpt', '', 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, '', '', '', '0');");
			do_action('Google Maps locator', 'The name of content field #1', 'Summary');
		}
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->w2gm_content_fields} WHERE slug = 'address'")) {
			$wpdb->query("INSERT INTO {$wpdb->w2gm_content_fields} (`is_core_field`, `order_num`, `name`, `slug`, `description`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `for_admin_only`, `on_listing_sidebar`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `search_options`, `group_id`) VALUES(1, 2, 'Address', 'address', '', 'address', 'w2gm-fa-map-marker', 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 0, '', '', '', '0');");
			do_action('Google Maps locator', 'The name of content field #2', 'Address');
		}
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->w2gm_content_fields} WHERE slug = 'content'")) {
			$wpdb->query("INSERT INTO {$wpdb->w2gm_content_fields} (`is_core_field`, `order_num`, `name`, `slug`, `description`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `for_admin_only`, `on_listing_sidebar`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `search_options`, `group_id`) VALUES(1, 3, 'Description', 'content', '', 'content', '', 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, '', '', '', '0');");
			do_action('Google Maps locator', 'The name of content field #3', 'Description');
		}
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->w2gm_content_fields} WHERE slug = 'categories_list'")) {
			$wpdb->query("INSERT INTO {$wpdb->w2gm_content_fields} (`is_core_field`, `order_num`, `name`, `slug`, `description`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `for_admin_only`, `on_listing_sidebar`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `search_options`, `group_id`) VALUES(1, 4, 'Categories', 'categories_list', '', 'categories', '', 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, '', '', '', '0');");
			do_action('Google Maps locator', 'The name of content field #4', 'Categories');
		}
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->w2gm_content_fields} WHERE slug = 'listing_tags'")) {
			$wpdb->query("INSERT INTO {$wpdb->w2gm_content_fields} (`is_core_field`, `order_num`, `name`, `slug`, `description`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `for_admin_only`, `on_listing_sidebar`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `search_options`, `group_id`) VALUES(1, 5, 'Listing Tags', 'listing_tags', '', 'tags', '', 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, '', '', '', '0');");
			do_action('Google Maps locator', 'The name of content field #5', 'Listing Tags');
		}
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->w2gm_content_fields} WHERE slug = 'phone'")) {
			$wpdb->query("INSERT INTO {$wpdb->w2gm_content_fields} (`is_core_field`, `order_num`, `name`, `slug`, `description`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `for_admin_only`, `on_listing_sidebar`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `search_options`, `group_id`) VALUES(0, 6, 'Phone', 'phone', '', 'phone', 'w2gm-fa-phone', 0, 1, 1, 0, 0, 0, 1, 1, 0, 1, 0, '', 'a:3:{s:10:\"max_length\";s:2:\"25\";s:5:\"regex\";s:0:\"\";s:10:\"phone_mode\";s:5:\"phone\";}', '', '1');");
			do_action('Google Maps locator', 'The name of content field #6', 'Phone');
		}
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->w2gm_content_fields} WHERE slug = 'website'")) {
			$wpdb->query("INSERT INTO {$wpdb->w2gm_content_fields} (`is_core_field`, `order_num`, `name`, `slug`, `description`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `for_admin_only`, `on_listing_sidebar`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `search_options`, `group_id`) VALUES(0, 7, 'Website', 'website', '', 'website', 'w2gm-fa-globe', 0, 1, 0, 0, 0, 0, 1, 1, 0, 1, 0, '', 'a:5:{s:8:\"is_blank\";i:1;s:11:\"is_nofollow\";i:1;s:13:\"use_link_text\";i:1;s:17:\"default_link_text\";s:13:\"view our site\";s:21:\"use_default_link_text\";i:0;}', '', '1');");
			do_action('Google Maps locator', 'The name of content field #7', 'Website');
		}
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->w2gm_content_fields} WHERE slug = 'email'")) {
			$wpdb->query("INSERT INTO {$wpdb->w2gm_content_fields} (`is_core_field`, `order_num`, `name`, `slug`, `description`, `type`, `icon_image`, `is_required`, `is_configuration_page`, `is_search_configuration_page`, `is_ordered`, `is_hide_name`, `for_admin_only`, `on_listing_sidebar`, `on_listing_page`, `on_search_form`, `on_map`, `advanced_search_form`, `categories`, `options`, `search_options`, `group_id`) VALUES(0, 8, 'Email', 'email', '', 'email', 'w2gm-fa-envelope-o', 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, '', '', '', '1');");
			do_action('Google Maps locator', 'The name of content field #8', 'Email');
		}

		$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->w2gm_locations_levels} (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`name` varchar(255) NOT NULL,
					`in_address_line` tinyint(1) NOT NULL,
					PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->w2gm_locations_levels} WHERE name = 'Country'"))
			$wpdb->query("INSERT INTO {$wpdb->w2gm_locations_levels} (`name`, `in_address_line`) VALUES ('Country', 1);");
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->w2gm_locations_levels} WHERE name = 'State'"))
			$wpdb->query("INSERT INTO {$wpdb->w2gm_locations_levels} (`name`, `in_address_line`) VALUES ('State', 1);");
		if (!$wpdb->get_var("SELECT id FROM {$wpdb->w2gm_locations_levels} WHERE name = 'City'"))
			$wpdb->query("INSERT INTO {$wpdb->w2gm_locations_levels} (`name`, `in_address_line`) VALUES ('City', 1);");

		$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->w2gm_locations_relationships} (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`post_id` int(11) NOT NULL,
					`location_id` int(11) NOT NULL,
					`address_line_1` varchar(255) NOT NULL,
					`address_line_2` varchar(255) NOT NULL,
					`zip_or_postal_index` varchar(25) NOT NULL,
					`additional_info` text NOT NULL,
					`manual_coords` tinyint(1) NOT NULL,
					`map_coords_1` float(10,6) NOT NULL,
					`map_coords_2` float(10,6) NOT NULL,
					`map_icon_file` varchar(255) NOT NULL,
					PRIMARY KEY (`id`),
					KEY `location_id` (`location_id`),
					KEY `post_id` (`post_id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	
		if (!is_array(get_terms(W2GM_LOCATIONS_TAX)) || !count(get_terms(W2GM_LOCATIONS_TAX))) {
			if (($parent_term = wp_insert_term('United States', W2GM_LOCATIONS_TAX)) && !is_a($parent_term, 'WP_Error')) {
				wp_insert_term('Alabama', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Alaska', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Arkansas', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Arizona', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('California', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Colorado', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Connecticut', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Delaware', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('District of Columbia', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Florida', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Georgia', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Hawaii', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Idaho', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Illinois', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Indiana', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Iowa', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Kansas', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Kentucky', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Louisiana', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Maine', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Maryland', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Massachusetts', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Michigan', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Minnesota', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Mississippi', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Missouri', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Montana', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Nebraska', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Nevada', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('New Hampshire', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('New Jersey', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('New Mexico', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('New York', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('North Carolina', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('North Dakota', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Ohio', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Oklahoma', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Oregon', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Pennsylvania', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Rhode Island', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('South Carolina', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('South Dakota', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Tennessee', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Texas', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Utah', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Vermont', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Virginia', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Washington state', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('West Virginina', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Wisconsin', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
				wp_insert_term('Wyoming', W2GM_LOCATIONS_TAX, array('parent' => $parent_term['term_id']));
			}
		}
		
		add_option('w2gm_google_api_key', '');
		add_option('w2gm_google_api_key_server', '');
		add_option('w2gm_fsubmit_addon', 0);
		add_option('w2gm_ratings_addon', 0);
		add_option('w2gm_eternal_active_period', 1);
		add_option('w2gm_active_period_days', '');
		add_option('w2gm_active_period_months', '');
		add_option('w2gm_active_period_years', '');
		add_option('w2gm_change_expiration_date', 0);
		add_option('w2gm_enable_renew', 1);
		add_option('w2gm_unlimited_categories', 1);
		add_option('w2gm_categories_number', 0);
		add_option('w2gm_locations_number', 3);
		add_option('w2gm_images_number', 10);
		add_option('w2gm_videos_number', 3);
		add_option('w2gm_enable_map_listing', 1);
		add_option('w2gm_show_directions', 1);
		add_option('w2gm_directions_functionality', 'builtin');
		add_option('w2gm_listing_contact_form', 1);
		add_option('w2gm_listing_contact_form_7', '');
		add_option('w2gm_custom_contact_email', 0);
		add_option('w2gm_hide_views_counter', 0);
		add_option('w2gm_hide_listings_creation_date', 0);
		add_option('w2gm_hide_author_link', 0);
		add_option('w2gm_listings_comments_plugin', 'plugin');
		add_option('w2gm_listings_comments_mode', 'wp_settings');
		add_option('w2gm_listings_tabs_order', array("addresses-tab", "comments-tab", "videos-tab", "contact-tab", "report-tab"));
		add_option('w2gm_enable_stats', 1);
		add_option('w2gm_logo_enabled', 1);
		add_option('w2gm_enable_lighbox_gallery', 1);
		add_option('w2gm_auto_slides_gallery', 0);
		add_option('w2gm_auto_slides_gallery_delay', 3000);
		add_option('w2gm_enable_nologo', 3000);
		add_option('w2gm_nologo_url', W2GM_URL . 'resources/images/nologo.png');
		add_option('w2gm_100_single_logo_width', 1);
		add_option('w2gm_single_logo_width', 400);
		add_option('w2gm_big_slide_bg_mode', 'cover');
		add_option('w2gm_enable_description', 1);
		add_option('w2gm_enable_summary', 1);
		add_option('w2gm_excerpt_length', 25);
		add_option('w2gm_cropped_content_as_excerpt', 1);
		add_option('w2gm_strip_excerpt', 1);
		add_option('w2gm_show_categories_search', 1);
		add_option('w2gm_categories_search_nesting_level', 1);
		add_option('w2gm_show_keywords_search', 1);
		add_option('w2gm_keywords_ajax_search', 1);
		add_option('w2gm_keywords_search_examples', 'sport, business');
		add_option('w2gm_show_locations_search', 1);
		add_option('w2gm_locations_search_nesting_level', 2);
		add_option('w2gm_show_address_search', 1);
		add_option('w2gm_show_location_count_in_search', 1);
		add_option('w2gm_hide_empty_locations', 0);
		add_option('w2gm_show_category_count_in_search', 1);
		add_option('w2gm_hide_empty_categories', 0);
		add_option('w2gm_show_radius_search', 1);
		add_option('w2gm_miles_kilometers_in_search', 'miles');
		add_option('w2gm_radius_search_min', 0);
		add_option('w2gm_radius_search_max', 10);
		add_option('w2gm_radius_search_default', 0);
		add_option('w2gm_default_geocoding_location', '');
		add_option('w2gm_addresses_order', array("line_1", "comma1", "line_2", "comma2", "location", "space1", "zip"));
		add_option('w2gm_address_autocomplete_code', "0");
		add_option('w2gm_enable_address_line_1', 1);
		add_option('w2gm_enable_address_line_2', 1);
		add_option('w2gm_enable_postal_index', 1);
		add_option('w2gm_enable_additional_info', 1);
		add_option('w2gm_enable_manual_coords', 1);
		add_option('w2gm_enable_users_markers', 1);
		add_option('w2gm_map_markers_type', 'icons');
		add_option('w2gm_default_marker_color', '#2393ba');
		add_option('w2gm_default_marker_icon', '');
		add_option('w2gm_map_marker_width', 48);
		add_option('w2gm_map_marker_height', 48);
		add_option('w2gm_map_marker_anchor_x', 24);
		add_option('w2gm_map_marker_anchor_y', 48);
		add_option('w2gm_map_infowindow_width', 350);
		add_option('w2gm_map_infowindow_offset', 50);
		add_option('w2gm_map_infowindow_logo_width', 110);
		add_option('w2gm_orderby_exclude_null', 0); // Exclude listings with empty values from sorted results
		add_option('w2gm_admin_notifications_email', get_option('admin_email'));
		add_option('w2gm_send_expiration_notification_days', 1);
		add_option('w2gm_preexpiration_notification', 'Your listing "[listing]" will expire in [days] days. You can renew it here [link]');
		add_option('w2gm_expiration_notification', 'Your listing "[listing]" had expired. You can renew it here [link]');
		add_option('w2gm_force_include_js_css', 0);
		add_option('w2gm_images_lightbox', 1);
		add_option('w2gm_notinclude_jqueryui_css', 0);
		add_option('w2gm_prevent_users_see_other_media', 1);
		add_option('w2gm_address_autocomplete', 1);
		add_option('w2gm_address_geocode', 0);
		add_option('w2gm_enable_recaptcha');
		add_option('w2gm_recaptcha_public_key');
		add_option('w2gm_recaptcha_private_key');
		add_option('w2gm_compare_palettes', 0);
		add_option('w2gm_color_scheme', 'default');
		add_option('w2gm_primary_color', '#2393ba');
		add_option('w2gm_secondary_color', '#1f82a5');
		add_option('w2gm_links_color', '#2393ba');
		add_option('w2gm_links_hover_color', '#2a6496');
		add_option('w2gm_button_1_color', '#2393ba');
		add_option('w2gm_button_2_color', '#1f82a5');
		add_option('w2gm_button_text_color', '#FFFFFF');
		add_option('w2gm_button_gradient', 0);
		add_option('w2gm_search_bg_color', '#6bc8c8');
		add_option('w2gm_search_bg_opacity', 100);
		add_option('w2gm_search_text_color', '#FFFFFF');
		add_option('w2gm_search_overlay', 1);
		add_option('w2gm_jquery_ui_schemas', 'redmond');
		add_option('w2gm_categories_icons');
		add_option('w2gm_locations_icons');
		add_option('w2gm_default_map_zoom', 11);
		add_option('w2gm_report_form', 1);
		add_option('w2gm_hide_anonymous_contact_form', 0);
	
		add_option('w2gm_installed_maps', true);
		add_option('w2gm_installed_maps_version', W2GM_VERSION);
	} elseif (get_option('w2gm_installed_maps_version') != W2GM_VERSION) {
		$upgrades_list = array(
				'2.0.0',
				'2.1.0',
				'2.1.1',
				'2.1.5',
				'2.2.6',
		);

		$old_version = get_option('w2gm_installed_maps_version');
		foreach ($upgrades_list AS $upgrade_version) {
			if (!$old_version || version_compare($old_version, $upgrade_version, '<')) {
				$upgrade_function_name = 'w2gm_upgrade_to_' . str_replace('.', '_', $upgrade_version);
				if (function_exists($upgrade_function_name))
					$upgrade_function_name();
				do_action('w2gm_version_upgrade', $upgrade_version);
			}
		}

		w2gm_save_dynamic_css();

		update_option('w2gm_installed_maps_version', W2GM_VERSION);
		
		echo '<script>location.reload();</script>';
		exit;
	}
	
	global $w2gm_instance;
	$w2gm_instance->loadClasses();
}

function w2gm_upgrade_to_2_0_0() {
	global $w2gm_instance, $wpdb;
	
	add_option('w2gm_categories_icons');
	add_option('w2gm_locations_icons');
	add_option('w2gm_hide_empty_locations', 0);
	add_option('w2gm_hide_empty_categories', 0);
	add_option('w2gm_prevent_users_see_other_media', 1);
	add_option('w2gm_listing_logo_bg_mode', 'cover');
	add_option('w2gm_search_bg_opacity', 100);
	add_option('w2gm_search_overlay', 1);
	add_option('w2gm_keywords_ajax_search', 1);
	add_option('w2gm_keywords_search_examples', 'sport, business');
	add_option('w2gm_default_map_zoom', 11);

	$posts_ids = $wpdb->get_col("
				SELECT
					wp_pm.post_id
				FROM
					{$wpdb->postmeta} AS wp_pm
				WHERE
					wp_pm.meta_key = '_listing_status' AND
					wp_pm.meta_value != 'active'
			");
	foreach ($posts_ids AS $listing_id) {
		wp_update_post(array('ID' => $listing_id, 'post_status' => 'pending'));
	}
	
	foreach ($w2gm_instance->content_fields->content_fields_array AS $content_field) {
		if ($content_field->type == 'datetime') {
			$dates = $wpdb->get_results("SELECT * FROM {$wpdb->postmeta} WHERE `meta_key` = '_content_field_{$content_field->id}_date'", ARRAY_A);
			foreach ($dates AS $date_row) {
				$wpdb->update($wpdb->postmeta, array('meta_key' => '_content_field_' . $content_field->id . '_date_start'), array('meta_id' => $date_row['meta_id']));
				$wpdb->insert($wpdb->postmeta, array('post_id' => $date_row['post_id'], 'meta_key' => '_content_field_' . $content_field->id . '_date_end', 'meta_value' => $date_row['meta_value']));
			}
		}
	}
	
	$for_admin_only = $wpdb->get_results("SHOW COLUMNS FROM {$wpdb->w2gm_content_fields} LIKE 'for_admin_only'", ARRAY_A);
	if (empty($for_admin_only)) {
		$wpdb->query("ALTER TABLE {$wpdb->w2gm_content_fields} ADD `for_admin_only` TINYINT(1) NOT NULL AFTER `is_hide_name`");
	}
	
	add_option('w2gm_categories_search_nesting_level', 1);
	add_option('w2gm_locations_search_nesting_level', 2);
	add_option('w2gm_search_bg_color', get_option('w2gm_search_1_color'));
	add_option('w2gm_secondary_color', '#1f82a5');
	
	if (get_option('w2gm_fsubmit_default_status') == 1 || get_option('w2gm_fsubmit_default_status') == 2) {
		update_option('w2gm_fsubmit_moderation', 1);
	}
	if (get_option('w2gm_fsubmit_default_status') == 3) {
		update_option('w2gm_fsubmit_moderation', 0);
	}
	
	if (get_option('w2gm_fsubmit_edit_status') == 1 || get_option('w2gm_fsubmit_edit_status') == 2) {
		update_option('w2gm_fsubmit_edit_moderation', 1);
	}
	if (get_option('w2gm_fsubmit_edit_status') == 3) {
		update_option('w2gm_fsubmit_edit_moderation', 0);
	}
	
	if (($widgets_array = get_option('widget_w2gm_search_widget')) && is_array($widgets_array)) {
		foreach ($widgets_array AS &$widget) {
			$widget['columns'] = 1;
		}
		update_option('widget_w2gm_search_widget', $widgets_array);
	}
	
	$wpdb->query("UPDATE {$wpdb->w2gm_content_fields} SET `is_search_configuration_page`=1 WHERE `type` IN ('string','textarea')");

	$posts = $wpdb->get_results("
				SELECT
					wp_p.ID,
					wp_p.post_date
				FROM
					{$wpdb->posts} AS wp_p
				WHERE
					wp_p.post_type = '" . W2GM_POST_TYPE . "'
	", ARRAY_A);
	foreach ($posts AS $post) {
		update_post_meta($post['ID'], '_order_date', mysql2date('U', $post['post_date']));
	}
}

function w2gm_upgrade_to_2_1_0() {
	add_option('w2gm_report_form', 1);

	$listings_tabs = get_option('w2gm_listings_tabs_order');
	$listings_tabs[] = 'report-tab';
	update_option('w2gm_listings_tabs_order', $listings_tabs);

	$vpt_option = get_option('vpt_option');
	$vpt_option['w2gm_listings_tabs_order'][] = 'report-tab';
	update_option('vpt_option', $vpt_option);
}

function w2gm_upgrade_to_2_1_1() {
	add_option('w2gm_hide_anonymous_contact_form', 0);
}

function w2gm_upgrade_to_2_1_5() {
	global $w2gm_instance, $wpdb;
	
	foreach ($w2gm_instance->content_fields->content_fields_array AS $content_field) {
		if ($content_field->type == 'string') {
			if (!empty($content_field->options['is_phone'])) {
				unset($content_field->options['is_phone']);
				$content_field->options['phone_mode'] = 'phone';
				$wpdb->update($wpdb->w2gm_content_fields, array('type' => 'phone', 'options' => $content_field->options), array('id' => $content_field->id));
			}
		}
	}
}

function w2gm_upgrade_to_2_2_6() {
	add_option('w2gm_map_marker_size', 40);
}

?>