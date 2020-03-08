<?php

class w2gm_maps {
	public $args;
	public $controller;
	public $map_id;
	
	public $map_zoom;
	public $listings_array = array();
	public $locations_array = array();
	public $locations_option_array = array();

	public static $map_content_fields;

	public function __construct($args = array(), $controller = 'listings_controller') {
		global $w2gm_instance;
		
		$this->args = $args;
		$this->controller = $controller;
	}
	
	public function setUniqueId($unique_id) {
		$this->map_id = $unique_id;
	}

	public function collectLocations($listing) {
		global $w2gm_instance, $w2gm_address_locations, $w2gm_tax_terms_locations;

		if (count($listing->locations) == 1)
			$this->map_zoom = $listing->map_zoom;

		foreach ($listing->locations AS $location) {
			if ((!$w2gm_address_locations || in_array($location->id, $w2gm_address_locations)) && (!$w2gm_tax_terms_locations || in_array($location->selected_location, $w2gm_tax_terms_locations))) {
				if (($location->map_coords_1 && $location->map_coords_1 != '0.000000') || ($location->map_coords_2 && $location->map_coords_2 != '0.000000')) {
					$logo_image = '';
					if ($listing->level->logo_enabled) {
						if ($listing->logo_image) {
							$logo_image = $listing->get_logo_url(array(get_option('w2gm_map_infowindow_logo_width'), get_option('w2gm_map_infowindow_logo_width')));
						} elseif (get_option('w2gm_enable_nologo') && get_option('w2gm_nologo_url')) {
							$logo_image = get_option('w2gm_nologo_url');
						}
					}
	
					if ($w2gm_instance->content_fields->getMapContentFields())
						$content_fields_output = $listing->setMapContentFields($w2gm_instance->content_fields->getMapContentFields(), $location);
					else 
						$content_fields_output = '';
	
					$this->listings_array[] = $listing;
					$this->locations_array[] = $location;
					$this->locations_option_array[] = array(
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
				}
			}
		}

		if ($this->locations_option_array)
			return true;
		else
			return false;
	}
	
	public function collectLocationsForAjax($listing) {	
		global $w2gm_address_locations, $w2gm_tax_terms_locations;

		foreach ($listing->locations AS $location) {
			if ((!$w2gm_address_locations || in_array($location->id, $w2gm_address_locations))  && (!$w2gm_tax_terms_locations || in_array($location->selected_location, $w2gm_tax_terms_locations))) {
				if (($location->map_coords_1 && $location->map_coords_1 != '0.000000') || ($location->map_coords_2 && $location->map_coords_2 != '0.000000')) {
					$this->listings_array[] = $listing;
					$this->locations_array[] = $location;
					$this->locations_option_array[] = array(
							$location->id,
							$listing->post->ID,
							$location->map_coords_1,
							$location->map_coords_2,
							$location->map_icon_file,
							$location->map_icon_color,
							null,
							null,
							null,
							null,
					);
				}
			}
		}
		if ($this->locations_option_array)
			return true;
		else
			return false;
	}
	
	public function buildListingsContent($show_directions_button = true, $show_readmore_button = true) {
		$out = '';
		foreach ($this->locations_array AS $key=>$location) {
			$listing = $this->listings_array[$key];
			$listing->setContentFields();
	
			$out .= w2gm_renderTemplate('frontend/listing_location.tpl.php', array('map_id' => $this->map_id, 'listing' => $listing, 'location' => $location, 'show_directions_button' => $show_directions_button, 'show_readmore_button' => $show_readmore_button), true);
		}
		return $out;
	}

	public function display($display_args = array()) {
		$display_args = array_merge(array(
				'directions_panel' => true,
				//'radius_circle' => true,
				//'clusters' => true,
				//'show_directions_button' => true,
				//'show_readmore_button' => true,
				'width' => false,
				'height' => 400,
				'sticky_scroll' => false,
				'sticky_scroll_toppadding' => 10,
				'map_style_name' => '',
				'search_form' => false,
				//'draw_panel' => false,
				//'enable_full_screen' => true,
				//'enable_wheel_zoom' => true,
				//'enable_dragging_touchscreens' => true,
				//'center_map_onclick' => false,
				//'zoom_map_onclick' => false,
				//'use_ajax_loader' => false,
		), $display_args);
		
		$this->args['map_style'] = w2gm_getSelectedMapStyle($display_args['map_style_name']);
		
		$locations_options = json_encode($this->locations_option_array);
		$map_args = json_encode($this->args, JSON_NUMERIC_CHECK);
		w2gm_renderTemplate('maps/map.tpl.php',
				array(
						'locations_options' => $locations_options,
						'locations_array' => $this->locations_array,
						'directions_panel' => $display_args['directions_panel'],
						//'radius_circle' => $display_args['radius_circle'],
						//'clusters' => $display_args['clusters'],
						//'map_zoom' => $this->map_zoom,
						//'show_directions_button' => $display_args['show_directions_button'],
						//'show_readmore_button' => $display_args['show_readmore_button'],
						//'map_style' => w2gm_getSelectedMapStyle($display_args['map_style_name']),
						'search_form' => $display_args['search_form'],
						//'draw_panel' => $display_args['draw_panel'],
						'width' => $display_args['width'],
						'height' => $display_args['height'],
						'sticky_scroll' => $display_args['sticky_scroll'],
						'sticky_scroll_toppadding' => $display_args['sticky_scroll_toppadding'],
						'directions_sidebar' => ($this->args['show_directions_button'] && get_option("w2gm_directions_functionality") == 'builtin') ? 1 : 0,
						'directions_sidebar_open' => $this->args['directions_sidebar_open'],
						//'enable_full_screen' => $display_args['enable_full_screen'],
						//'enable_wheel_zoom' => $display_args['enable_wheel_zoom'],
						//'enable_dragging_touchscreens' => $display_args['enable_dragging_touchscreens'],
						//'center_map_onclick' => $display_args['center_map_onclick'],
						//'zoom_map_onclick' => $display_args['zoom_map_onclick'],
						//'use_ajax_loader' => $display_args['use_ajax_loader'],
						'controller' => $this->controller,
						'map_object' => $this,
						'map_id' => $this->map_id,
						'listings_content' => $this->buildListingsContent((!empty($this->args['show_directions_button']) ? 1 : 0), (!empty($this->args['show_readmore_button']) ? 1 : 0)),
						'map_args' => $map_args,
						'args' => $this->args
				));
	}
	
	public function is_ajax_loading() {
		if (isset($this->args['ajax_loading']) && $this->args['ajax_loading'] && ((isset($this->args['start_address']) && $this->args['start_address']) || ((isset($this->args['start_latitude']) && $this->args['start_latitude']) && (isset($this->args['start_longitude']) && $this->args['start_longitude']))))
			return true;
		else
			return false;
	}
}

?>