<?php 

class w2gm_content_field_phone extends w2gm_content_field_string {
	public $max_length = 255;
	public $regex;
	public $phone_mode = 'phone';
	
	protected $can_be_searched = true;
	protected $is_configuration_page = true;
	protected $is_search_configuration_page = true;

	public function configure() {
		global $wpdb, $w2gm_instance;

		if (w2gm_getValue($_POST, 'submit') && wp_verify_nonce($_POST['w2gm_configure_content_fields_nonce'], W2GM_PATH)) {
			$validation = new w2gm_form_validation();
			$validation->set_rules('max_length', __('Max length', 'W2GM'), 'required|is_natural_no_zero');
			$validation->set_rules('regex', __('PHP RegEx template', 'W2GM'));
			$validation->set_rules('phone_mode', __('Phone mode', 'W2GM'));
			if ($validation->run()) {
				$result = $validation->result_array();
				if ($wpdb->update($wpdb->w2gm_content_fields, array('options' => serialize(array('max_length' => $result['max_length'], 'regex' => $result['regex'], 'phone_mode' => $result['phone_mode']))), array('id' => $this->id), null, array('%d'))) {
					w2gm_addMessage(__('Field configuration was updated successfully!', 'W2GM'));
				}
				
				$w2gm_instance->content_fields_manager->showContentFieldsTable();
			} else {
				$this->max_length = $validation->result_array('max_length');
				$this->regex = $validation->result_array('regex');
				$this->phone_mode = $validation->result_array('phone_mode');
				w2gm_addMessage($validation->error_array(), 'error');

				w2gm_renderTemplate('content_fields/fields/phone_configuration.tpl.php', array('content_field' => $this));
			}
		} else
			w2gm_renderTemplate('content_fields/fields/phone_configuration.tpl.php', array('content_field' => $this));
	}
	
	public function buildOptions() {
		if (isset($this->options['max_length'])) {
			$this->max_length = $this->options['max_length'];
		}

		if (isset($this->options['regex'])) {
			$this->regex = $this->options['regex'];
		}
		
		if (isset($this->options['phone_mode'])) {
			$this->phone_mode = $this->options['phone_mode'];
		}
	}
	
	public function renderInput() {
		if (!($template = w2gm_isTemplate('content_fields/fields/phone_input_'.$this->id.'.tpl.php'))) {
			$template = 'content_fields/fields/phone_input.tpl.php';
		}
		
		$template = apply_filters('w2gm_content_field_input_template', $template, $this);
			
		w2gm_renderTemplate($template, array('content_field' => $this));
	}
	
	public function renderOutput($listing, $group = null) {
		if (!($template = w2gm_isTemplate('content_fields/fields/phone_output_'.$this->id.'.tpl.php'))) {
			$template = 'content_fields/fields/phone_output.tpl.php';
		}
		
		$template = apply_filters('w2gm_content_field_output_template', $template, $this, $listing, $group);
			
		w2gm_renderTemplate($template, array('content_field' => $this, 'listing' => $listing, 'group' => $group));
	}
}
?>