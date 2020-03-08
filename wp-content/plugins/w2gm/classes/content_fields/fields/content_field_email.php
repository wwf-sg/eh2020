<?php 

class w2gm_content_field_email extends w2gm_content_field {
	protected $can_be_ordered = false;
	
	public function isNotEmpty($listing) {
		if ($this->value)
			return true;
		else
			return false;
	}

	public function renderInput() {
		if (!($template = w2gm_isTemplate('content_fields/fields/email_input_'.$this->id.'.tpl.php'))) {
			$template = 'content_fields/fields/email_input.tpl.php';
		}
		
		$template = apply_filters('w2gm_content_field_input_template', $template, $this);
			
		w2gm_renderTemplate($template, array('content_field' => $this));
	}
	
	public function validateValues(&$errors, $data) {
		$field_index = 'w2gm-field-input-' . $this->id;

		$validation = new w2gm_form_validation();
		$rules = 'valid_email';
		if ($this->canBeRequired() && $this->is_required)
			$rules .= '|required';
		$validation->set_rules($field_index, $this->name, $rules);
		if (!$validation->run())
			$errors[] = $validation->error_array();
	
		return $validation->result_array($field_index);
	}
	
	public function saveValue($post_id, $validation_results) {
		return update_post_meta($post_id, '_content_field_' . $this->id, $validation_results);
	}
	
	public function loadValue($post_id) {
		$this->value = get_post_meta($post_id, '_content_field_' . $this->id, true);
		
		$this->value = apply_filters('w2gm_content_field_load', $this->value, $this, $post_id);
		return $this->value;
	}
	
	public function renderOutput($listing, $group = null) {
		if (!($template = w2gm_isTemplate('content_fields/fields/email_output_'.$this->id.'.tpl.php'))) {
			$template = 'content_fields/fields/email_output.tpl.php';
		}
		
		$template = apply_filters('w2gm_content_field_output_template', $template, $this, $listing, $group);
			
		w2gm_renderTemplate($template, array('content_field' => $this, 'listing' => $listing, 'group' => $group));
	}
	
	public function validateCsvValues($value, &$errors) {
		$validation = new w2gm_form_validation();
		if (!$validation->valid_email($value))
			$errors[] = __("Email field is invalid", "W2GM");
		return $value;
	}
	
	public function renderOutputForMap($location, $listing) {
		$email = antispambot($this->value);
		if (function_exists('iconv') && function_exists('mb_detect_encoding') && function_exists('mb_detect_order')) {
			$email = iconv(mb_detect_encoding($email, mb_detect_order(), true), "UTF-8", $email);
		}

		return w2gm_renderTemplate('content_fields/fields/email_output_map.tpl.php', array('content_field' => $this, 'listing' => $listing, 'email' => $email), true);
	}
}
?>