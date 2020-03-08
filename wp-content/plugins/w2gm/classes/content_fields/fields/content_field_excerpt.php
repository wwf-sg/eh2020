<?php 

class w2gm_content_field_excerpt extends w2gm_content_field {
	protected $can_be_ordered = false;
	protected $is_categories = false;
	protected $is_slug = false;
	
	public function isNotEmpty($listing) {
		if (post_type_supports(W2GM_POST_TYPE, 'excerpt') && ($listing->post->post_excerpt || (get_option('w2gm_cropped_content_as_excerpt') && $listing->post->post_content !== '')))
			return true;
		else
			return false;
	}

	public function validateValues(&$errors, $data) {
		$listing = w2gm_getCurrentListingInAdmin();
		if (post_type_supports(W2GM_POST_TYPE, 'excerpt') && $this->is_required && (!isset($data['post_excerpt']) || !$data['post_excerpt']))
			$errors[] = __('Listing excerpt is required', 'W2GM');
		else
			return $listing->post->post_excerpt;
	}
	
	public function renderOutput($listing, $group = null) {
		if (!($template = w2gm_isTemplate('content_fields/fields/excerpt_output_'.$this->id.'.tpl.php'))) {
			$template = 'content_fields/fields/excerpt_output.tpl.php';
		}
		
		$template = apply_filters('w2gm_content_field_output_template', $template, $this, $listing, $group);
			
		w2gm_renderTemplate($template, array('content_field' => $this, 'listing' => $listing, 'group' => $group));
	}
	
	public function renderOutputForMap($location, $listing) {
		if (get_option('w2gm_cropped_content_as_excerpt') && $listing->post->post_content !== '') {
			return w2gm_crop_content($listing->post->ID, get_option('w2gm_excerpt_length'), get_option('w2gm_strip_excerpt'));
		} elseif ($listing->post->post_excerpt) {
			return $listing->post->post_excerpt;
		}
	}
}
?>