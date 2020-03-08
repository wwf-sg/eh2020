<?php 

class w2gm_level {
	public $active_period_days;
	public $active_period_months;
	public $active_period_years;
	public $eternal_active_period = 1;
	public $listings_own_page = 0;
	public $categories_number = 0;
	public $unlimited_categories = 1;
	public $locations_number = 1;
	public $map = 1;
	public $map_markers = 1;
	public $logo_enabled;
	public $images_number = 1;
	public $videos_number = 1;
	public $categories = array();
	public $locations = array();
	public $content_fields = array();
	public $upgrade_meta = array();

	public function __construct() {
		$this->active_period_days = get_option("w2gm_active_period_days");
		$this->active_period_months = get_option("w2gm_active_period_months");
		$this->active_period_years = get_option("w2gm_active_period_years");
		$this->eternal_active_period = get_option("w2gm_eternal_active_period");
		$this->categories_number = get_option("w2gm_categories_number");
		$this->unlimited_categories = get_option("w2gm_unlimited_categories");
		$this->locations_number = get_option("w2gm_locations_number");
		$this->map = get_option('w2gm_enable_map_listing');
		$this->map_markers = get_option('w2gm_enable_users_markers');
		$this->logo_enabled = get_option('w2gm_logo_enabled');
		$this->images_number = get_option("w2gm_images_number");
		$this->videos_number = get_option("w2gm_videos_number");		
		
		apply_filters('w2gm_levels_loading', $this);
	}
}

?>