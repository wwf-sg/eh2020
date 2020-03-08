<?php 

class w2gm_content_field_string_search extends w2gm_content_field_search {
	public $search_input_mode = 'keywords';
	
	public function searchConfigure() {
		global $wpdb, $w2gm_instance;
	
		if (w2gm_getValue($_POST, 'submit') && wp_verify_nonce($_POST['w2gm_configure_content_fields_nonce'], W2GM_PATH)) {
			$validation = new w2gm_form_validation();
			$validation->set_rules('search_input_mode', __('Search input mode', 'W2GM'), 'required');
			if ($validation->run()) {
				$result = $validation->result_array();
				if ($wpdb->update($wpdb->w2gm_content_fields, array('search_options' => serialize(array('search_input_mode' => $result['search_input_mode']))), array('id' => $this->content_field->id), null, array('%d')))
					w2gm_addMessage(__('Search field configuration was updated successfully!', 'W2GM'));
	
				$w2gm_instance->content_fields_manager->showContentFieldsTable();
			} else {
				$this->search_input_mode = $validation->result_array('search_input_mode');
				w2gm_addMessage($validation->error_array(), 'error');
	
				w2gm_renderTemplate('search_fields/fields/string_textarea_configuration.tpl.php', array('search_field' => $this));
			}
		} else
			w2gm_renderTemplate('search_fields/fields/string_textarea_configuration.tpl.php', array('search_field' => $this));
	}
	
	public function buildSearchOptions() {
		if (isset($this->content_field->search_options['search_input_mode']))
			$this->search_input_mode = $this->content_field->search_options['search_input_mode'];
	}

	public function renderSearch($search_form_id, $columns = 2, $defaults = array()) {
		if ($this->search_input_mode == 'input') {
			if (is_null($this->value) && isset($defaults['field_' . $this->content_field->slug])) {
				$this->value = $defaults['field_' . $this->content_field->slug];
			}
			
			w2gm_renderTemplate('search_fields/fields/string_textarea_input.tpl.php', array('search_field' => $this, 'columns' => $columns, 'search_form_id' => $search_form_id));
		}
	}
	
	public function validateSearch(&$args, $defaults = array(), $include_GET_params = true) {
		if ($this->search_input_mode == 'input') {
			$field_index = 'field_' . $this->content_field->slug;
	
			if ($include_GET_params)
				$this->value = ((w2gm_getValue($_REQUEST, $field_index, false) !== false) ? w2gm_getValue($_REQUEST, $field_index) : w2gm_getValue($defaults, $field_index));
			else
				$this->value = w2gm_getValue($defaults, $field_index, false);
	
			if ($this->value !== false && $this->value !== "") {
				$args['meta_query']['relation'] = 'AND';
				$args['meta_query'][] = array(
						'key' => '_content_field_' . $this->content_field->id,
						'value' => stripslashes($this->value),
						'compare' => 'LIKE'
				);
			}
		} elseif ($this->search_input_mode == 'keywords' && $this->content_field->on_search_form) {
			if (!empty($args['s'])) {
				$this->value = $args['s'];
	
				//var_dump($include_GET_params);
				//if (!has_filter('posts_clauses', array($this, 'postsClauses'))) {
					//var_dump(11);
					add_filter('posts_clauses', array($this, 'postsClauses'), 11, 2);
				//}
			}
		}
	}
	
	public function postsClauses($clauses, $q) {
		global $wpdb;

		$postmeta_table = 'w2gm_postmeta_' . $this->content_field->id;
		
		if ($this->value && strpos($clauses['join'], $postmeta_table) === false) { 
		$words = explode(" ", $this->value);
			$words_like = array();
			foreach ($words AS $word) {
				$words_like[] = 'meta_value LIKE "%%%s%"';
			}
			$post_ids_results = $wpdb->get_results($wpdb->prepare('SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE meta_key = "_content_field_' . $this->content_field->id . '" AND (' . implode(" OR ", $words_like) . ') ', $words), ARRAY_A);

			$post_ids = array();
			foreach ($post_ids_results AS $id) {
				$post_ids[] = $id["post_id"];
			}
			if ($post_ids) {
				$clauses['where'] = preg_replace(
						"/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
						"(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->posts.".ID IN (" . implode(",", $post_ids) . "))", $clauses['where']);
				
				// Add GROUP BY posts.ID (for some occasions it becomes missing in the result query)
				$clauses['groupby'] = "{$wpdb->posts}.ID";
			}
		}
		
		return $clauses;
	}
	
	public function getVCParams() {
		return array(
				array(
						'type' => 'textfield',
						'param_name' => 'field_' . $this->content_field->slug,
						'heading' => $this->content_field->name,
				),
		);
	}
	
	public function getMapManagerParams() {
		return array(
				array(
						'type' => 'textbox',
						'name' => 'field_' . $this->content_field->slug,
						'label' => $this->content_field->name,
				)
		);
	}
}
?>