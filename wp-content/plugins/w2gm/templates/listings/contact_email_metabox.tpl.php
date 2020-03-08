<p><?php _e("When field is empty contact messages from contact form will be sent directly to author email.", 'W2GM'); ?></p>

<div class="w2gm-content">
	<input class="w2gm-field-input-string w2gm-form-control" type="text" name="contact_email" value="<?php echo esc_attr($listing->contact_email); ?>" />
</div>
	
<?php do_action('w2gm_contact_email_metabox_html', $listing); ?>