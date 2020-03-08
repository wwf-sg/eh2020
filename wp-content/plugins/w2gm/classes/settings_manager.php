<?php

global $w2gm_wpml_dependent_options;
$w2gm_wpml_dependent_options[] = 'w2gm_listing_contact_form_7';

class w2gm_settings_manager {
	public function __construct() {
		add_action('init', array($this, 'plugin_settings'));
		
		if (!defined('W2GM_DEMO') || !W2GM_DEMO) {
			add_action('vp_w2gm_option_after_ajax_save', array($this, 'save_option'), 10, 3);
		}
	}
	
	public function plugin_settings() {
		global $w2gm_instance, $w2gm_google_maps_styles, $sitepress;
		
		if (defined('W2GM_DEMO') && W2GM_DEMO) {
			$capability = 'publish_posts';
		} else {
			$capability = 'edit_theme_options';
		}

		$ordering_items = w2gm_orderingItems();

		$listings_tabs = array(
				array('value' => 'addresses-tab', 'label' => __('Addresses tab', 'W2GM')),
				array('value' => 'comments-tab', 'label' => __('Comments tab', 'W2GM')),
				array('value' => 'videos-tab', 'label' => __('Videos tab', 'W2GM')),
				array('value' => 'contact-tab', 'label' => __('Contact tab', 'W2GM')),
				array('value' => 'report-tab', 'label' => __('Report tab', 'W2GM')));
		foreach ($w2gm_instance->content_fields->content_fields_groups_array AS $fields_group) {
			if ($fields_group->on_tab) {
				$listings_tabs[] = array('value' => 'field-group-tab-'.$fields_group->id, 'label' => $fields_group->name);
			}
		}
			
		$google_map_styles = array(array('value' => 'default', 'label' => 'Default style'));
		foreach ($w2gm_google_maps_styles AS $name=>$style) {
			$google_map_styles[] = array('value' => $name, 'label' => $name);
		}

		$country_codes = array(array('value' => 0, 'label' => 'Worldwide'));
		$w2gm_country_codes = w2gm_country_codes();
		foreach ($w2gm_country_codes AS $country=>$code)
			$country_codes[] = array('value' => $code, 'label' => $country);
		
		$theme_options = array(
				//'is_dev_mode' => true,
				'option_key' => 'vpt_option',
				'page_slug' => 'w2gm_settings',
				'template' => array(
					'title' => __('Google Maps locator Settings', 'W2GM'),
					'logo' => W2GM_RESOURCES_URL . 'images/settings.png',
					'menus' => array(
						'general' => array(
							'name' => 'general',
							'title' => __('General settings', 'W2GM'),
							'icon' => 'font-awesome:w2gm-fa-home',
							'controls' => array(
								'api_keys' => array(
									'type' => 'section',
									'title' => __('Google API keys', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'textbox',
											'name' => 'w2gm_google_api_key',
											'label' => __('Google browser API key*', 'W2GM'),
											'description' => sprintf(__('get your Google API key <a href="%s" target="_blank">here</a>, following APIs must be enabled in the console: Google Maps Directions API, Google Maps JavaScript API and Google Static Maps API.', 'W2GM'), 'https://console.developers.google.com/flows/enableapi?apiid=maps_backend,directions_backend,static_maps_backend&keyType=CLIENT_SIDE&reusekey=true'),
											'default' => get_option('w2gm_google_api_key'),
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_google_api_key_server',
											'label' => __('Google server API key*', 'W2GM'),
											'description' => sprintf(__('get your Google API key <a href="%s" target="_blank">here</a>, following APIs must be enabled in the console: Google Maps Geocoding API and Google Places API Web Service.', 'W2GM'), 'https://console.developers.google.com/flows/enableapi?apiid=geocoding_backend,places_backend&keyType=CLIENT_SIDE&reusekey=true') . ' ' . sprintf(__('Then check geolocation <a href="%s">response</a>.', 'W2GM'), admin_url('admin.php?page=w2gm_debug')),
											'default' => get_option('w2gm_google_api_key_server'),
										),
									),
								),
							),
						),
						'listings' => array(
							'name' => 'listings',
							'title' => __('Listings', 'W2GM'),
							'icon' => 'font-awesome:w2gm-fa-list-alt',
							'controls' => array(
								'listings' => array(
									'type' => 'section',
									'title' => __('Listings settings', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'toggle',
											'name' => 'w2gm_eternal_active_period',
											'label' => __('Listings will never expire', 'W2GM'),
											'default' => get_option('w2gm_eternal_active_period'),
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_active_period_days',
											'label' => __('Active period of listings (in days)', 'W2GM'),
											'description' => __('Works when listings may expire.', 'W2GM'),
											'default' => get_option('w2gm_active_period_days'),
											'validation' => 'numeric',
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_active_period_months',
											'label' => __('Active period of listings (in months)', 'W2GM'),
											'description' => __('Works when listings may expire.', 'W2GM'),
											'default' => get_option('w2gm_active_period_months'),
											'validation' => 'numeric',
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_active_period_years',
											'label' => __('Active period of listings (in years)', 'W2GM'),
											'description' => __('Works when listings may expire.', 'W2GM'),
											'default' => get_option('w2gm_active_period_years'),
											'validation' => 'numeric',
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_change_expiration_date',
											'label' => __('Allow regular users to change listings expiration dates', 'W2GM'),
											'default' => get_option('w2gm_change_expiration_date'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_renew',
											'label' => __('Allow listings to renew', 'W2GM'),
											'default' => get_option('w2gm_enable_renew'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_unlimited_categories',
											'label' => __('Allow unlimited categories', 'W2GM'),
											'default' => get_option('w2gm_unlimited_categories'),
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_categories_number',
											'label' => __('Number of categories allowed for each listing', 'W2GM'),
											'default' => get_option('w2gm_categories_number'),
											'validation' => 'numeric',
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_locations_number',
											'label' => __('Number of locations allowed for each listing', 'W2GM'),
											'default' => get_option('w2gm_locations_number'),
											'validation' => 'numeric',
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_images_number',
											'label' => __('Number of images allowed for each listing (including logo)', 'W2GM'),
											'default' => get_option('w2gm_images_number'),
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_videos_number',
											'label' => __('Number of videos allowed for each listing', 'W2GM'),
											'default' => get_option('w2gm_videos_number'),
										),
										array(
											'type' => 'radiobutton',
											'name' => 'w2gm_directions_functionality',
											'label' => __('Directions functionality in listing window', 'W2GM'),
											'items' => array(
												array(
													'value' => 'builtin',
													'label' =>__('Built-in routing', 'W2GM'),
												),
												array(
													'value' => 'google',
													'label' =>__('Link to Google Maps', 'W2GM'),
												),
											),
											'default' => array(
													get_option('w2gm_directions_functionality')
											),
										),
									),
								),
								'listings_window' => array(
									'type' => 'section',
									'title' => __('Listings window', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_map_listing',
											'label' => __('Enable map in listing window', 'W2GM'),
											'default' => get_option('w2gm_enable_map_listing'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_show_directions',
											'label' => __('Show directions panel in listing window', 'W2GM'),
											'default' => get_option('w2gm_show_directions'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_listing_contact_form',
											'label' => __('Enable contact form in listing window', 'W2GM'),
											'description' => __('Contact Form 7 or standard form will be displayed on each listing page', 'W2GM'),
											'default' => get_option('w2gm_listing_contact_form'),
										),
										array(
											'type' => 'textbox',
											'name' => w2gm_get_wpml_dependent_option_name('w2gm_listing_contact_form_7'),
											'label' => __('Contact Form 7 shortcode', 'W2GM'),
											'description' => __('This will work only when Contact Form 7 plugin enabled, otherwise standard contact form will be displayed.', 'W2GM') . w2gm_get_wpml_dependent_option_description(),
											'default' => w2gm_get_wpml_dependent_option('w2gm_listing_contact_form_7'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_hide_anonymous_contact_form',
											'label' => __('Show contact form only for logged in users', 'W2GM'),
											'default' => get_option('w2gm_hide_anonymous_contact_form'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_custom_contact_email',
											'label' => __('Allow custom contact emails', 'W2GM'),
											'description' => __('When enabled users may set up custom contact emails, otherwise messages will be sent directly to authors emails', 'W2GM'),
											'default' => get_option('w2gm_custom_contact_email'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_report_form',
											'label' => __('Enable report form', 'W2GM'),
											'default' => get_option('w2gm_report_form'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_hide_views_counter',
											'label' => __('Hide listings views counter', 'W2GM'),
											'default' => get_option('w2gm_hide_views_counter'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_hide_listings_creation_date',
											'label' => __('Hide listings creation date', 'W2GM'),
											'default' => get_option('w2gm_hide_listings_creation_date'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_hide_author_link',
											'label' => __('Hide author information', 'W2GM'),
											'description' => __('Author name and possible link to author website will be hidden on single listing pages.', 'W2GM'),
											'default' => get_option('w2gm_hide_author_link'),
										),
										array(
											'type' => 'radiobutton',
											'name' => 'w2gm_listings_comments_plugin',
											'label' => __('Listings comments system', 'W2GM'),
											'default' => array(get_option('w2gm_listings_comments_plugin')),
											'items' => array(
													array(
														'value' => 'plugin',
														'label' => esc_attr__("Use plugin's system", 'W2GM'),	
													),
													array(
														'value' => 'native',
														'label' => __('Use native wordpress comments', 'W2GM'),	
													),
											),
										),
										array(
											'type' => 'radiobutton',
											'name' => 'w2gm_listings_comments_mode',
											'label' => __('Listings comments mode', 'W2GM'),
											'default' => array(get_option('w2gm_listings_comments_mode')),
											'items' => array(
													array(
														'value' => 'enabled',
														'label' => __('Always enabled', 'W2GM'),	
													),
													array(
														'value' => 'disabled',
														'label' => __('Always disabled', 'W2GM'),	
													),
													array(
														'value' => 'wp_settings',
														'label' => __('As configured in WP settings', 'W2GM'),	
													),
											),
										),
										array(
											'type' => 'sorter',
											'name' => 'w2gm_listings_tabs_order',
											'label' => __('Listing tabs order', 'W2GM'),
									 		'items' => $listings_tabs,
											'default' => get_option('w2gm_listings_tabs_order'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_stats',
											'label' => __('Enable statistics functionality', 'W2GM'),
											'default' => get_option('w2gm_enable_stats'),
										),
									),
								),
								'logos' => array(
									'type' => 'section',
									'title' => __('Listings logos & images', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'toggle',
											'name' => 'w2gm_logo_enabled',
											'label' => __('Enable logo in infowindow', 'W2GM'),
											'default' => get_option('w2gm_logo_enabled'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_lighbox_gallery',
											'label' => __('Enable lightbox on images gallery', 'W2GM'),
											'default' => get_option('w2gm_enable_lighbox_gallery'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_auto_slides_gallery',
											'label' => __('Enable automatic rotating slideshow on images gallery', 'W2GM'),
											'default' => get_option('w2gm_auto_slides_gallery'),
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_auto_slides_gallery_delay',
											'label' => __('The delay in rotation (in ms)', 'W2GM'),
											'default' => get_option('w2gm_auto_slides_gallery_delay'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_nologo',
											'label' => __('Enable default logo image', 'W2GM'),
											'default' => get_option('w2gm_enable_nologo'),
										),
										array(
											'type' => 'upload',
											'name' => 'w2gm_nologo_url',
											'label' => __('Default logo image', 'W2GM'),
									 		'description' => __('This image will appear when listing owner did not upload own logo.', 'W2GM'),
											'default' => get_option('w2gm_nologo_url'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_100_single_logo_width',
											'label' => __('Enable 100% width of images gallery', 'W2GM'),
											'default' => get_option('w2gm_100_logo_width'),
										),
										array(
											'type' => 'slider',
											'name' => 'w2gm_single_logo_width',
											'label' => __('Images gallery width (in pixels)', 'W2GM'),
											'description' => __('This option needed only when 100% width of images gallery is switched off'),
											'min' => 100,
											'max' => 800,
											'default' => get_option('w2gm_single_logo_width'),
										),
										array(
											'type' => 'radiobutton',
											'name' => 'w2gm_big_slide_bg_mode',
											'label' => __('Do crop images gallery', 'W2GM'),
											'default' => array(get_option('w2gm_big_slide_bg_mode')),
											'items' => array(
													array(
														'value' => 'cover',
														'label' => __('Cut off image to fit width and height of main slide', 'W2GM'),	
													),
													array(
														'value' => 'contain',
														'label' => __('Full image inside main slide', 'W2GM'),	
													),
											),
										),
									),
								),
								'excerpts' => array(
									'type' => 'section',
									'title' => __('Description & Excerpt settings', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_description',
											'label' => __('Enable description field', 'W2GM'),
											'default' => get_option('w2gm_enable_description'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_summary',
											'label' => __('Enable summary field', 'W2GM'),
											'default' => get_option('w2gm_enable_summary'),
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_excerpt_length',
											'label' => __('Excerpt max length', 'W2GM'),
											'description' => __('Insert the number of words you want to show in the listings excerpts', 'W2GM'),
											'default' => get_option('w2gm_excerpt_length'),
											'validation' => 'numeric',
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_cropped_content_as_excerpt',
											'label' => __('Use cropped content as excerpt', 'W2GM'),
											'description' => __('When excerpt field is empty - use cropped main content', 'W2GM'),
											'default' => get_option('w2gm_cropped_content_as_excerpt'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_strip_excerpt',
											'label' => __('Strip HTML from excerpt', 'W2GM'),
											'description' => __('Check the box if you want to strip HTML from the excerpt content only', 'W2GM'),
											'default' => get_option('w2gm_strip_excerpt'),
										),
									),
								),
							),
						),
						'search' => array(
							'name' => 'search',
							'title' => __('Search shortcode', 'W2GM'),
							'icon' => 'font-awesome:w2gm-fa-search',
							'controls' => array(
								'search' => array(
									'type' => 'section',
									'title' => __('Search shortcode settings', 'W2GM'),
									'description' => __('These are default settings for all [webmap-search] shortcodes.', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'toggle',
											'name' => 'w2gm_show_categories_search',
											'label' => __('Enable categories search', 'W2GM'),
											'default' => get_option('w2gm_show_categories_search'),
										),
										array(
											'type' => 'slider',
											'name' => 'w2gm_categories_search_nesting_level',
											'label' => __('Categories search depth level', 'W2GM'),
											'min' => 1,
											'max' => 3,
											'default' => get_option('w2gm_categories_search_nesting_level'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_show_keywords_search',
											'label' => __('Enable keywords search', 'W2GM'),
											'default' => get_option('w2gm_show_keywords_search'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_keywords_ajax_search',
											'label' => __('Enable listings autosuggestions by keywords', 'W2GM'),
											'default' => get_option('w2gm_keywords_ajax_search'),
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_keywords_search_examples',
											'label' => __('Keywords examples', 'W2GM'),
											'description' => __('Comma-separated list of suggestions to try to search', 'W2GM'),
											'default' => get_option('w2gm_keywords_search_examples'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_show_locations_search',
											'label' => __('Enable locations search', 'W2GM'),
											'default' => get_option('w2gm_show_locations_search'),
										),
										array(
											'type' => 'slider',
											'name' => 'w2gm_locations_search_nesting_level',
											'label' => __('Locations search depth level', 'W2GM'),
											'min' => 1,
											'max' => 3,
											'default' => get_option('w2gm_locations_search_nesting_level'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_show_address_search',
											'label' => __('Enable address search', 'W2GM'),
											'default' => get_option('w2gm_show_address_search'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_show_location_count_in_search',
											'label' => __('Show listings counts in locations search dropboxes', 'W2GM'),
											'default' => get_option('w2gm_show_location_count_in_search'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_hide_empty_locations',
											'label' => __('Hide empty locations', 'W2GM'),
											'description' => __('This setting is actual for main search shortcode and search widget', 'W2GM'),
											'default' => get_option('w2gm_hide_empty_locations'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_show_category_count_in_search',
											'label' => __('Show listings counts in categories search dropboxes', 'W2GM'),
											'default' => get_option('w2gm_show_category_count_in_search'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_hide_empty_categories',
											'label' => __('Hide empty categories', 'W2GM'),
											'description' => __('This setting is actual for main search shortcode and search widget', 'W2GM'),
											'default' => get_option('w2gm_hide_empty_categories'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_show_radius_search',
											'label' => __('Show locations radius search', 'W2GM'),
											'description' => sprintf(__('Check geolocation <a href="%s">response</a>.', 'W2GM'), admin_url('admin.php?page=w2gm_debug')),
											'default' => get_option('w2gm_show_radius_search'),
										),
										array(
											'type' => 'radiobutton',
											'name' => 'w2gm_miles_kilometers_in_search',
											'label' => __('Dimension in radius search', 'W2GM'),
											'items' => array(
												array(
													'value' => 'miles',
													'label' => __('miles', 'W2GM'),
												),
												array(
													'value' => 'kilometers',
													'label' => __('kilometers', 'W2GM'),
												),
											),
											'default' => array(get_option('w2gm_miles_kilometers_in_search')),
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_radius_search_min',
											'label' => __('Minimum radius search', 'W2GM'),
											'default' => get_option('w2gm_radius_search_min'),
											'validation' => 'numeric',
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_radius_search_max',
											'label' => __('Maximum radius search', 'W2GM'),
											'default' => get_option('w2gm_radius_search_max'),
											'validation' => 'numeric',
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_radius_search_default',
											'label' => __('Default radius search', 'W2GM'),
											'description' => __('If you have problems with radius search: check your server google API key and enabled APIs. ', 'W2GM') . ' ' . sprintf(__('Check geolocation <a href="%s">response</a>.', 'W2GM'), admin_url('admin.php?page=w2gm_debug')),
											'default' => get_option('w2gm_radius_search_default'),
											'validation' => 'numeric',
										),
									),
								),
							),
						),
						'addresses' => array(
							'name' => 'addresses',
							'title' => __('Markers & Addresses', 'W2GM'),
							'icon' => 'font-awesome:w2gm-fa-map-marker',
							'controls' => array(
								'addresses' => array(
									'type' => 'section',
									'title' => __('Addresses settings', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'textbox',
											'name' => 'w2gm_default_geocoding_location',
											'label' => __('Default country/state for correct geocoding', 'W2GM'),
											'description' => __('This value needed when you build local store locator, all your listings place in one local area - country or state. This hidden string will be automatically added to the address for correct geocoding when users create/edit listings and when they search by address.', 'W2GM'),
											'default' => get_option('w2gm_default_geocoding_location'),
										),
										array(
											'type' => 'sorter',
											'name' => 'w2gm_addresses_order',
											'label' => __('Address format', 'W2GM'),
									 		'items' => array(
									 			array('value' => 'location', 'label' => __('Selected location', 'W2GM')),
									 			array('value' => 'line_1', 'label' => __('Address Line 1', 'W2GM')),
									 			array('value' => 'line_2', 'label' => __('Address Line 2', 'W2GM')),
									 			array('value' => 'zip', 'label' => __('Zip code or postal index', 'W2GM')),
									 			array('value' => 'space1', 'label' => __('-- Space ( ) --', 'W2GM')),
									 			array('value' => 'space2', 'label' => __('-- Space ( ) --', 'W2GM')),
									 			array('value' => 'space3', 'label' => __('-- Space ( ) --', 'W2GM')),
									 			array('value' => 'comma1', 'label' => __('-- Comma (,) --', 'W2GM')),
									 			array('value' => 'comma2', 'label' => __('-- Comma (,) --', 'W2GM')),
									 			array('value' => 'comma3', 'label' => __('-- Comma (,) --', 'W2GM')),
									 			array('value' => 'break1', 'label' => __('-- Line Break --', 'W2GM')),
									 			array('value' => 'break2', 'label' => __('-- Line Break --', 'W2GM')),
									 			array('value' => 'break3', 'label' => __('-- Line Break --', 'W2GM')),
									 		),
											'description' => __('Order address elements as you wish, commas and spaces help to build address line.'),
											'default' => get_option('w2gm_addresses_order'),
										),
										array(
											'type' => 'select',
											'name' => 'w2gm_address_autocomplete_code',
											'label' => __('Restriction of address fields for one specific country (autocomplete submission and search fields)', 'W2GM'),
									 		'items' => $country_codes,
											'default' => get_option('w2gm_address_autocomplete_code'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_address_line_1',
											'label' => __('Enable address line 1 field', 'W2GM'),
											'default' => get_option('w2gm_enable_address_line_1'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_address_line_2',
											'label' => __('Enable address line 2 field', 'W2GM'),
											'default' => get_option('w2gm_enable_address_line_2'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_postal_index',
											'label' => __('Enable zip code', 'W2GM'),
											'default' => get_option('w2gm_enable_postal_index'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_additional_info',
											'label' => __('Enable additional info field', 'W2GM'),
											'default' => get_option('w2gm_enable_additional_info'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_manual_coords',
											'label' => __('Enable manual coordinates fields', 'W2GM'),
											'default' => get_option('w2gm_enable_manual_coords'),
										),
										array(
											'type' => 'slider',
											'name' => 'w2gm_default_map_zoom',
											'label' => __('Default map zoom level (for submission page)', 'W2GM'),
									 		'min' => 1,
									 		'max' => 19,
											'default' => get_option('w2gm_default_map_zoom'),
										),
									),
								),
								'markers' => array(
									'type' => 'section',
									'title' => __('Map markers & InfoWindow settings', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_users_markers',
											'label' => __('Allow users to select markers', 'W2GM'),
											'default' => get_option('w2gm_enable_users_markers'),
										),
										array(
											'type' => 'radiobutton',
											'name' => 'w2gm_map_markers_type',
											'label' => __('Type of Map Markers', 'W2GM'),
											'items' => array(
												array(
													'value' => 'icons',
													'label' =>__('Font Awesome icons (recommended)', 'W2GM'),
												),
												array(
													'value' => 'images',
													'label' =>__('PNG images', 'W2GM'),
												),
											),
											'default' => array(
													get_option('w2gm_map_markers_type')
											),
										),
										array(
											'type' => 'color',
											'name' => 'w2gm_default_marker_color',
											'label' => __('Default Map Marker color', 'W2GM'),
											'default' => get_option('w2gm_default_marker_color'),
											'description' => __('For Font Awesome icons.', 'W2GM'),
											'dependency' => array(
												'field'    => 'w2gm_map_markers_type',
												'function' => 'w2gm_map_markers_icons_setting',
											),
										),
										array(
											'type' => 'fontawesome',
											'name' => 'w2gm_default_marker_icon',
											'label' => __('Default Map Marker icon'),
											'description' => __('For Font Awesome icons.', 'W2GM'),
											'default' => array(
												get_option('w2gm_default_marker_icon')
											),
											'dependency' => array(
												'field'    => 'w2gm_map_markers_type',
												'function' => 'w2gm_map_markers_icons_setting',
											),
										),
										array(
											'type' => 'slider',
											'name' => 'w2gm_map_marker_size',
											'label' => __('Map marker size (in pixels)', 'W2GM'),
											'description' => __('For Font Awesome images.', 'W2GM'),
											'default' => get_option('w2gm_map_marker_size'),
									 		'min' => 30,
									 		'max' => 70,
											'dependency' => array(
												'field'    => 'w2gm_map_markers_type',
												'function' => 'w2gm_map_markers_icons_setting',
											),
										),
										array(
											'type' => 'slider',
											'name' => 'w2gm_map_marker_width',
											'label' => __('Map marker width (in pixels)', 'W2GM'),
											'description' => __('For PNG images.', 'W2GM'),
											'default' => get_option('w2gm_map_marker_width'),
									 		'min' => 10,
									 		'max' => 64,
											'dependency' => array(
												'field'    => 'w2gm_map_markers_type',
												'function' => 'w2gm_map_markers_images_setting',
											),
										),
									 	array(
											'type' => 'slider',
											'name' => 'w2gm_map_marker_height',
											'label' => __('Map marker height (in pixels)', 'W2GM'),
									 		'description' => __('For PNG images.', 'W2GM'),
											'default' => get_option('w2gm_map_marker_height'),
									 		'min' => 10,
									 		'max' => 64,
									 		'dependency' => array(
												'field'    => 'w2gm_map_markers_type',
												'function' => 'w2gm_map_markers_images_setting',
											),
										),
									 	array(
											'type' => 'slider',
											'name' => 'w2gm_map_marker_anchor_x',
											'label' => __('Map marker anchor horizontal position (in pixels)', 'W2GM'),
									 		'description' => __('For PNG images.', 'W2GM'),
											'default' => get_option('w2gm_map_marker_anchor_x'),
									 		'min' => 0,
									 		'max' => 64,
									 		'dependency' => array(
												'field'    => 'w2gm_map_markers_type',
												'function' => 'w2gm_map_markers_images_setting',
											),
										),
									 	array(
											'type' => 'slider',
											'name' => 'w2gm_map_marker_anchor_y',
											'label' => __('Map marker anchor vertical position (in pixels)', 'W2GM'),
									 		'description' => __('For PNG images.', 'W2GM'),
											'default' => get_option('w2gm_map_marker_anchor_y'),
									 		'min' => 0,
									 		'max' => 64,
									 		'dependency' => array(
												'field'    => 'w2gm_map_markers_type',
												'function' => 'w2gm_map_markers_images_setting',
											),
										),
									 	array(
											'type' => 'slider',
											'name' => 'w2gm_map_infowindow_width',
											'label' => __('Map InfoWindow width (in pixels)', 'W2GM'),
											'default' => get_option('w2gm_map_infowindow_width'),
									 		'min' => 100,
									 		'max' => 600,
									 		'step' => 10,
										),
										array(
											'type' => 'slider',
											'name' => 'w2gm_map_infowindow_offset',
											'label' => __('Map InfoWindow vertical position above marker (in pixels)', 'W2GM'),
											'default' => get_option('w2gm_map_infowindow_offset'),
									 		'min' => 30,
									 		'max' => 120,
										),
										array(
											'type' => 'slider',
											'name' => 'w2gm_map_infowindow_logo_width',
											'label' => __('Map InfoWindow logo width (in pixels)', 'W2GM'),
											'default' => get_option('w2gm_map_infowindow_logo_width'),
									 		'min' => 40,
									 		'max' => 300,
											'step' => 10,
										),
									),
								),
							),
						),
						'notifications' => array(
							'name' => 'notifications',
							'title' => __('Email notifications', 'W2GM'),
							'icon' => 'font-awesome:w2gm-fa-envelope',
							'controls' => array(
								'notifications' => array(
									'type' => 'section',
									'title' => __('Email notifications', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'textbox',
											'name' => 'w2gm_admin_notifications_email',
											'label' => __('This email will be used for notifications to admin and in "From" field. Required to send emails.', 'W2GM'),
											'default' => get_option('w2gm_admin_notifications_email'),
										),
										array(
											'type' => 'textbox',
											'name' => 'w2gm_send_expiration_notification_days',
											'label' => __('Days before pre-expiration notification will be sent', 'W2GM'),
											'default' => get_option('w2gm_send_expiration_notification_days'),
										),
									 	array(
											'type' => 'textarea',
											'name' => 'w2gm_preexpiration_notification',
											'label' => __('Pre-expiration notification', 'W2GM'),
											'default' => get_option('w2gm_preexpiration_notification'),
									 		'description' => __('Tags allowed: ', 'W2GM') . '[listing], [days], [link]',
										),
									 	array(
											'type' => 'textarea',
											'name' => 'w2gm_expiration_notification',
											'label' => __('Expiration notification', 'W2GM'),
											'default' => get_option('w2gm_expiration_notification'),
									 		'description' => __('Tags allowed: ', 'W2GM') . '[listing], [link]',
										),
									),
								),
							),
						),
						'advanced' => array(
							'name' => 'advanced',
							'title' => __('Advanced settings', 'W2GM'),
							'icon' => 'font-awesome:w2gm-fa-gear',
							'controls' => array(
								'js_css' => array(
									'type' => 'section',
									'title' => __('JavaScript & CSS', 'W2GM'),
									'description' => __('Do not touch these settings if you do not know what they mean. It may cause lots of problems.', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'toggle',
											'name' => 'w2gm_force_include_js_css',
											'label' => __('Include plugin JS and CSS files on all pages', 'W2GM'),
											'default' => get_option('w2gm_force_include_js_css'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_images_lightbox',
											'label' => __('Include lightbox slideshow library', 'W2GM'),
											'description' =>  __('Some themes and 3rd party plugins include own Lighbox library - this may cause conflicts.', 'W2GM'),
											'default' => get_option('w2gm_images_lightbox'),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_notinclude_jqueryui_css',
											'label' => __('Do not include jQuery UI CSS', 'W2GM'),
									 		'description' =>  __('Some themes and 3rd party plugins include own jQuery UI CSS - this may cause conflicts in styles.', 'W2GM'),
											'default' => get_option('w2gm_notinclude_jqueryui_css'),
										),
									),
								),
								'miscellaneous' => array(
									'type' => 'section',
									'title' => __('Miscellaneous', 'W2GM'),
									'fields' => array(
									 	array(
											'type' => 'toggle',
											'name' => 'w2gm_prevent_users_see_other_media',
											'label' => __('Prevent users to see media items of another users', 'W2GM'),
											'default' => get_option('w2gm_prevent_users_see_other_media'),
										),
									 	array(
											'type' => 'toggle',
											'name' => 'w2gm_address_autocomplete',
											'label' => __('Enable autocomplete on addresses fields', 'W2GM'),
											'default' => get_option('w2gm_address_autocomplete'),
										),
									 	array(
											'type' => 'toggle',
											'name' => 'w2gm_address_geocode',
											'label' => __('Enable "Get my location" button on addresses fields', 'W2GM'),
											'default' => get_option('w2gm_address_geocode'),
									 		'description' => __("Requires https", "W2GM"),
										),
									),
								),
								'recaptcha' => array(
									'type' => 'section',
									'title' => __('reCaptcha settings', 'W2GM'),
									'fields' => array(
									 	array(
											'type' => 'toggle',
											'name' => 'w2gm_enable_recaptcha',
											'label' => __('Enable reCaptcha', 'W2GM'),
											'default' => get_option('w2gm_enable_recaptcha'),
										),
									 	array(
											'type' => 'textbox',
											'name' => 'w2gm_recaptcha_public_key',
											'label' => __('reCaptcha public key', 'W2GM'),
											'description' => sprintf(__('get your reCAPTCHA API Keys <a href="%s" target="_blank">here</a>', 'W2GM'), 'http://www.google.com/recaptcha'),
											'default' => get_option('w2gm_recaptcha_public_key'),
										),
									 	array(
											'type' => 'textbox',
											'name' => 'w2gm_recaptcha_private_key',
											'label' => __('reCaptcha private key', 'W2GM'),
											'default' => get_option('w2gm_recaptcha_private_key'),
										),
									),
								),
							),
						),
						'customization' => array(
							'name' => 'customization',
							'title' => __('Customization', 'W2GM'),
							'icon' => 'font-awesome:w2gm-fa-check',
							'controls' => array(
								'color_schemas' => array(
									'type' => 'section',
									'title' => __('Color palettes', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'toggle',
											'name' => 'w2gm_compare_palettes',
											'label' => __('Compare palettes at the frontend', 'W2GM'),
									 		'description' =>  __('Do not forget to switch off this setting when comparison will be completed.', 'W2GM'),
											'default' => get_option('w2gm_compare_palettes'),
										),
										array(
											'type' => 'select',
											'name' => 'w2gm_color_scheme',
											'label' => __('Color palette', 'W2GM'),
											'items' => array(
												array('value' => 'default', 'label' => __('Default', 'W2GM')),
												array('value' => 'orange', 'label' => __('Orange', 'W2GM')),
												array('value' => 'red', 'label' => __('Red', 'W2GM')),
												array('value' => 'yellow', 'label' => __('Yellow', 'W2GM')),
												array('value' => 'green', 'label' => __('Green', 'W2GM')),
												array('value' => 'gray', 'label' => __('Gray', 'W2GM')),
												array('value' => 'blue', 'label' => __('Blue', 'W2GM')),
											),
											'default' => array(get_option('w2gm_color_scheme')),
										),
										array(
											'type' => 'notebox',
											'description' => esc_attr__("Don't forget to clear cache of your browser and on server (when used) after customization changes were made.", 'W2GM'),
											'status' => 'warning',
										),
									),
								),
								'main_colors' => array(
									'type' => 'section',
									'title' => __('Main colors', 'W2GM'),
									'fields' => array(
										array(
												'type' => 'color',
												'name' => 'w2gm_primary_color',
												'label' => __('Primary color', 'W2GM'),
												'description' =>  __('The color of categories, tags labels, map info window caption, pagination elements', 'W2GM'),
												'default' => get_option('w2gm_primary_color'),
												'binding' => array(
														'field' => 'w2gm_color_scheme',
														'function' => 'w2gm_affect_setting_w2gm_primary_color'
												),
										),
										array(
												'type' => 'color',
												'name' => 'w2gm_secondary_color',
												'label' => __('Secondary color', 'W2GM'),
												'default' => get_option('w2gm_secondary_color'),
												'binding' => array(
														'field' => 'w2gm_color_scheme',
														'function' => 'w2gm_affect_setting_w2gm_secondary_color'
												),
										),
									),
								),
								'links_colors' => array(
									'type' => 'section',
									'title' => __('Links & buttons', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'color',
											'name' => 'w2gm_links_color',
											'label' => __('Links color', 'W2GM'),
											'default' => get_option('w2gm_links_color'),
											'binding' => array(
												'field' => 'w2gm_color_scheme',
												'function' => 'w2gm_affect_setting_w2gm_links_color'
											),
										),
										array(
											'type' => 'color',
											'name' => 'w2gm_links_hover_color',
											'label' => __('Links hover color', 'W2GM'),
											'default' => get_option('w2gm_links_hover_color'),
											'binding' => array(
												'field' => 'w2gm_color_scheme',
												'function' => 'w2gm_affect_setting_w2gm_links_hover_color'
											),
										),
										array(
											'type' => 'color',
											'name' => 'w2gm_button_1_color',
											'label' => __('Button primary color', 'W2GM'),
											'default' => get_option('w2gm_button_1_color'),
											'binding' => array(
												'field' => 'w2gm_color_scheme',
												'function' => 'w2gm_affect_setting_w2gm_button_1_color'
											),
										),
										array(
											'type' => 'color',
											'name' => 'w2gm_button_2_color',
											'label' => __('Button secondary color', 'W2GM'),
											'default' => get_option('w2gm_button_2_color'),
											'binding' => array(
												'field' => 'w2gm_color_scheme',
												'function' => 'w2gm_affect_setting_w2gm_button_2_color'
											),
										),
										array(
											'type' => 'color',
											'name' => 'w2gm_button_text_color',
											'label' => __('Button text color', 'W2GM'),
											'default' => get_option('w2gm_button_text_color'),
											'binding' => array(
												'field' => 'w2gm_color_scheme',
												'function' => 'w2gm_affect_setting_w2gm_button_text_color'
											),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_button_gradient',
											'label' => __('Use gradient on buttons', 'W2GM'),
											'description' => __('This will remove all icons from buttons'),
											'default' => get_option('w2gm_button_gradient'),
										),
									),
								),
								'search_colors' => array(
									'type' => 'section',
									'title' => __('Search block', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'color',
											'name' => 'w2gm_search_bg_color',
											'label' => __('Search form background color', 'W2GM'),
											'default' => get_option('w2gm_search_bg_color'),
											'binding' => array(
												'field' => 'w2gm_color_scheme',
												'function' => 'w2gm_affect_setting_w2gm_search_bg_color'
											),
										),
										array(
											'type' => 'slider',
											'name' => 'w2gm_search_bg_opacity',
											'label' => __('Opacity of search form background, in %', 'W2GM'),
											'min' => '0',
											'max' => '100',
											'default' => get_option('w2gm_search_bg_opacity'),
										),
										array(
											'type' => 'color',
											'name' => 'w2gm_search_text_color',
											'label' => __('Search form text color', 'W2GM'),
											'default' => get_option('w2gm_search_text_color'),
											'binding' => array(
												'field' => 'w2gm_color_scheme',
												'function' => 'w2gm_affect_setting_w2gm_search_text_color'
											),
										),
										array(
											'type' => 'toggle',
											'name' => 'w2gm_search_overlay',
											'label' => __('Use overlay on search form', 'W2GM'),
											'default' => get_option('w2gm_search_overlay'),
										),
									),
								),
								'misc_colors' => array(
									'type' => 'section',
									'title' => __('Misc settings', 'W2GM'),
									'fields' => array(
										array(
											'type' => 'radioimage',
											'name' => 'w2gm_jquery_ui_schemas',
											'label' => __('jQuery UI Style', 'W2GM'),
									 		'description' =>  __('Controls the color of calendar, dialogs and slider UI widgets', 'W2GM'),
									 		'items' => array(
									 			array(
									 				'value' => 'blitzer',
									 				'label' => 'Blitzer',
									 				'img' => W2GM_RESOURCES_URL . 'css/jquery-ui/themes/blitzer/thumb.png'
									 			),
									 			array(
									 				'value' => 'smoothness',
									 				'label' => 'Smoothness',
									 				'img' => W2GM_RESOURCES_URL . 'css/jquery-ui/themes/smoothness/thumb.png'
									 			),
									 			array(
									 				'value' => 'redmond',
									 				'label' => 'Redmond',
									 				'img' => W2GM_RESOURCES_URL . 'css/jquery-ui/themes/redmond/thumb.png'
									 			),
									 			array(
									 				'value' => 'ui-darkness',
									 				'label' => 'UI Darkness',
									 				'img' => W2GM_RESOURCES_URL . 'css/jquery-ui/themes/ui-darkness/thumb.png'
									 			),
									 			array(
									 				'value' => 'ui-lightness',
									 				'label' => 'UI Lightness',
									 				'img' => W2GM_RESOURCES_URL . 'css/jquery-ui/themes/ui-lightness/thumb.png'
									 			),
									 			array(
									 				'value' => 'trontastic',
									 				'label' => 'Trontastic',
									 				'img' => W2GM_RESOURCES_URL . 'css/jquery-ui/themes/trontastic/thumb.png'
									 			),
									 			array(
									 				'value' => 'start',
									 				'label' => 'Start',
									 				'img' => W2GM_RESOURCES_URL . 'css/jquery-ui/themes/start/thumb.png'
									 			),
									 			array(
									 				'value' => 'sunny',
									 				'label' => 'Sunny',
									 				'img' => W2GM_RESOURCES_URL . 'css/jquery-ui/themes/sunny/thumb.png'
									 			),
									 			array(
									 				'value' => 'overcast',
									 				'label' => 'Overcast',
									 				'img' => W2GM_RESOURCES_URL . 'css/jquery-ui/themes/overcast/thumb.png'
									 			),
									 			array(
									 				'value' => 'le-frog',
									 				'label' => 'Le Frog',
									 				'img' => W2GM_RESOURCES_URL . 'css/jquery-ui/themes/le-frog/thumb.png'
									 			),
									 			array(
									 				'value' => 'hot-sneaks',
									 				'label' => 'Hot Sneaks',
									 				'img' => W2GM_RESOURCES_URL . 'css/jquery-ui/themes/hot-sneaks/thumb.png'
									 			),
									 			array(
									 				'value' => 'excite-bike',
									 				'label' => 'Excite Bike',
									 				'img' => W2GM_RESOURCES_URL . 'css/jquery-ui/themes/excite-bike/thumb.png'
									 			),
									 		),
											'default' => array(get_option('w2gm_jquery_ui_schemas')),
											'binding' => array(
												'field' => 'w2gm_color_scheme',
												'function' => 'w2gm_affect_setting_w2gm_jquery_ui_schemas'
											),
										),
									),
								),
							),
						),
					)
				),
				//'menu_page' => 'w2gm_settings',
				'use_auto_group_naming' => true,
				'use_util_menu' => false,
				'minimum_role' => $capability,
				'layout' => 'fixed',
				'page_title' => __('Maps settings', 'W2GM'),
				'menu_label' => __('Maps settings', 'W2GM'),
		);
		
		// adapted for WPML /////////////////////////////////////////////////////////////////////////
		global $sitepress;
		if (function_exists('wpml_object_id_filter') && $sitepress) {
			$theme_options['template']['menus']['advanced']['controls']['wpml'] = array(
				'type' => 'section',
				'title' => __('WPML Settings', 'W2GM'),
				'fields' => array(
					array(
						'type' => 'toggle',
						'name' => 'w2gm_map_language_from_wpml',
						'label' => __('Force WPML language on maps', 'W2GM'),
						'description' => __("Ignore the browser's language setting and force it to display information in a particular WPML language", 'W2GM'),
						'default' => get_option('w2gm_map_language_from_wpml'),
					),
				),
			);
		}
		
		$theme_options = apply_filters('w2gm_build_settings', $theme_options);

		$VP_W2GM_Option = new VP_W2GM_Option($theme_options);
	}

	public function save_option($opts, $old_opts, $status) {
		global $w2gm_wpml_dependent_options, $sitepress;

		if ($status) {
			foreach ($opts AS $option=>$value) {
				// adapted for WPML
				if (in_array($option, $w2gm_wpml_dependent_options)) {
					if (function_exists('wpml_object_id_filter') && $sitepress) {
						if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE) {
							update_option($option.'_'.ICL_LANGUAGE_CODE, $value);
							continue;
						}
					}
				}

				if (
					$option == 'w2gm_google_api_key' ||
					$option == 'w2gm_google_api_key_server'
				) {
					$value = trim($value);
				}
				update_option($option, $value);
			}
			
			w2gm_save_dynamic_css();
		}
	}
}

function w2gm_save_dynamic_css() {
	$upload_dir = wp_upload_dir();
	$filename = trailingslashit($upload_dir['basedir']) . 'w2gm-plugin.css';
		
	ob_start();
	include W2GM_PATH . '/classes/customization/dynamic_css.php';
	$dynamic_css = ob_get_contents();
	ob_get_clean();
		
	global $wp_filesystem;
	if (empty($wp_filesystem)) {
		require_once(ABSPATH .'/wp-admin/includes/file.php');
		WP_Filesystem();
	}
		
	if ($wp_filesystem) {
		$wp_filesystem->put_contents(
				$filename,
				$dynamic_css,
				FS_CHMOD_FILE // predefined mode settings for WP files
		);
	}
}

// adapted for WPML
function w2gm_get_wpml_dependent_option_name($option) {
	global $w2gm_wpml_dependent_options, $sitepress;

	if (in_array($option, $w2gm_wpml_dependent_options))
		if (function_exists('wpml_object_id_filter') && $sitepress)
			if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE)
				if (get_option($option.'_'.ICL_LANGUAGE_CODE) !== false)
					return $option.'_'.ICL_LANGUAGE_CODE;

	return $option;
}
function w2gm_get_wpml_dependent_option($option) {
	return get_option(w2gm_get_wpml_dependent_option_name($option));
}
function w2gm_get_wpml_dependent_option_description() {
	global $sitepress;
	return ((function_exists('wpml_object_id_filter') && $sitepress) ? sprintf(__('%s This is multilingual option, each language may have own value.', 'W2GM'), '<br /><img src="'.W2GM_RESOURCES_URL . 'images/multilang.png" /><br />') : '');
}


function w2gm_google_type_setting($value) {
	if ($value == 'google') {
		return true;
	}
}
VP_W2GM_Security::instance()->whitelist_function('w2gm_google_type_setting');

function w2gm_map_markers_icons_setting($value) {
	if ($value == 'icons') {
		return true;
	}
}
VP_W2GM_Security::instance()->whitelist_function('w2gm_map_markers_icons_setting');

function w2gm_map_markers_images_setting($value) {
	if ($value == 'images') {
		return true;
	}
}
VP_W2GM_Security::instance()->whitelist_function('w2gm_map_markers_images_setting');

?>