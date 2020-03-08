<?php

define('W2GM_DEMO_DATA_PATH', W2GM_PATH . 'demo-data/');

class w2gm_demo_data_manager {
	public function __construct() {
		add_action('admin_menu', array($this, 'menu'));
	}

	public function menu() {
		if (defined('W2GM_DEMO') && W2GM_DEMO) {
			$capability = 'publish_posts';
		} else {
			$capability = 'manage_options';
		}
		
		add_submenu_page('w2gm_settings',
		__('Demo data Import', 'W2GM'),
		__('Demo data Import', 'W2GM'),
		$capability,
		'w2gm_demo_data',
		array($this, 'w2gm_demo_data_import_page')
		);
	}
	
	public function w2gm_demo_data_import_page() {
		if (w2gm_getValue($_POST, 'submit') && wp_verify_nonce($_POST['w2gm_csv_import_nonce'], W2GM_PATH) && (!defined('W2GM_DEMO') || !W2GM_DEMO)) {
			global $w2gm_instance;
			
			$w2gm_instance->csv_manager->import_type = 'create';
			$w2gm_instance->csv_manager->columns_separator = ',';
			$w2gm_instance->csv_manager->values_separator = ';';
			$w2gm_instance->csv_manager->if_term_not_found = 'create';
			$w2gm_instance->csv_manager->selected_user = get_current_user_id();
			$w2gm_instance->csv_manager->do_geocode = false;
			$w2gm_instance->csv_manager->is_claimable = false;
			$w2gm_instance->csv_manager->collated_fields = array(
					'title',
					'content',
					'excerpt',
					'categories_list',
					'locations_list',
					'address_line_1',
					'address_line_2',
					'latitude',
					'longitude',
					'map_icon_file',
					'phone',
					'website',
					'email',
					'images',
			);
			$csv_file_name = W2GM_DEMO_DATA_PATH . 'listings.csv';
			$w2gm_instance->csv_manager->extractCsv($csv_file_name);
			$zip_images_file_name = W2GM_DEMO_DATA_PATH . 'images.zip';
			$w2gm_instance->csv_manager->extractImages($zip_images_file_name);
			
			ob_start();
			$w2gm_instance->csv_manager->processCSV();
			ob_clean();
			
			if ($w2gm_instance->csv_manager->images_dir) {
				$w2gm_instance->csv_manager->removeImagesDir($w2gm_instance->csv_manager->images_dir);
			}
			
			$listings = get_posts(array(
					'post_type' => W2GM_POST_TYPE,
					'posts_per_page' => 1,
					'orderby' => 'rand',
			));
			
			$txt_files = glob(W2GM_DEMO_DATA_PATH . 'pages/*.{txt}', GLOB_BRACE);
			$json_files = glob(W2GM_DEMO_DATA_PATH . 'maps/*.{json}', GLOB_BRACE);
			foreach ($json_files AS $json_file) {
				$title = basename($json_file, '.json');
				$map_id = wp_insert_post(array(
						'post_type' => W2GM_MAP_TYPE,
						'post_title' => $title,
						'post_content' => '',
						'post_status' => 'publish',
						'post_author' => get_current_user_id(),
				));
				$json = file_get_contents($json_file);
				$metaboxes_data = json_decode($json, true);
				if (isset($metaboxes_data[0]['data'])) {
					foreach ($metaboxes_data[0]['data'] AS $meta) {
						$meta_key = $meta['meta_key'];
						$meta_value = $meta['meta_value'];
						update_post_meta($map_id, $meta_key, unserialize($meta_value));
					}
				}
				
				$txt_file_index = array_search(W2GM_DEMO_DATA_PATH . 'pages/' . str_replace('.json', '.txt', basename($json_file)), $txt_files);
				if (isset($txt_files[$txt_file_index]) && ($txt_file = $txt_files[$txt_file_index]) && file_exists($txt_file)) {
					$content = file_get_contents($txt_file);
					$content = str_replace('[base_url]', trim(site_url(), '/'), $content);
					$content = str_replace('{map_id}', $map_id, $content);
					if ($listings) {
						$content = str_replace('{listing_id}', $listings[0]->ID, $content);
					}
					$page_id = wp_insert_post(array(
							'post_type' => 'page',
							'post_title' => $title,
							'post_content' => $content,
							'post_status' => 'publish',
							'post_author' => get_current_user_id(),
					));
				}
			}
			
			w2gm_addMessage(sprintf(__("Import of the demo data was successfully completed. Look at your <a href='%s'>listings</a>, <a href='%s'>maps</a> and <a href='%s'>demo pages</a>.", "W2GM"), admin_url('edit.php?post_type=w2gm_listing'), admin_url('edit.php?post_type=w2gm_map'), admin_url('edit.php?post_type=page')));
			
			w2gm_renderTemplate('demo_data_import.tpl.php');
		} else {
			$this->importInstructions();
		}
	}
	
	public function importInstructions() {
		w2gm_renderTemplate('demo_data_import.tpl.php');
	}
}

?>