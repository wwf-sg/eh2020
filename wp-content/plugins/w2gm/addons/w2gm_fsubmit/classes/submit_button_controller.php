<?php 

class w2gm_submit_button_controller extends w2gm_frontend_controller {

	public function init($args = array()) {
		parent::init($args);

		$shortcode_atts = array_merge(array(
				'hide_button_text' => false,
				'buttons' => 'submit',
		), $args);

		$this->args = $shortcode_atts;
		
		$this->frontpanel_buttons = new w2gm_frontpanel_buttons($this->args);

		apply_filters('w2gm_frontend_controller_construct', $this);
	}

	public function display() {
		global $w2gm_fsubmit_instance;
		
		ob_start();
		echo '<div class="w2gm-content w2gm-submit-listing-link-wrapper">';
		$w2gm_fsubmit_instance->add_submit_button($this->frontpanel_buttons);
		echo '</div>';
		$output = ob_get_clean();
		
		return $output;
	}
}

?>