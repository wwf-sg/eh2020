<form method="POST" action="<?php the_permalink($listing->post->ID); ?>#contact-tab" id="w2gm_contact_form">
	<input type="hidden" name="listing_id" id="contact_listing_id" value="<?php echo $listing->post->ID; ?>" />
	<input type="hidden" name="contact_nonce" id="contact_nonce" value="<?php print wp_create_nonce('w2gm_contact_nonce'); ?>" />
	<h3><?php
		if (get_option('w2gm_hide_author_link'))
			_e('Send message to listing owner', 'W2GM');
		else
			printf(__('Send message to %s', 'W2GM'), get_the_author());
	?></h3>
	<h5 id="contact_warning" style="display: none; color: red;"></h5>
	<div class="w2gm-contact-form">
		<?php if (is_user_logged_in()): ?>
		<p>
			<?php printf(__('You are currently logged in as %s. Your message will be sent using your logged in name and email.', 'W2GM'), wp_get_current_user()->user_login); ?>
			<input type="hidden" name="contact_name" id="contact_name" />
			<input type="hidden" name="contact_email" id="contact_email" />
		</p>
		<?php else: ?>
		<p>
			<label for="contact_name"><?php _e('Contact Name', 'W2GM'); ?><span class="w2gm-red-asterisk">*</span></label>
			<input type="text" name="contact_name" id="contact_name" class="w2gm-form-control" value="<?php echo esc_attr(w2gm_getValue($_POST, 'contact_name')); ?>" size="35" />
		</p>
		<p>
			<label for="contact_email"><?php _e("Contact Email", "W2GM"); ?><span class="w2gm-red-asterisk">*</span></label>
			<input type="text" name="contact_email" id="contact_email" class="w2gm-form-control" value="<?php echo esc_attr(w2gm_getValue($_POST, 'contact_email')); ?>" size="35" />
		</p>
		<?php endif; ?>
		<p>
			<label for="contact_message"><?php _e("Your message", "W2GM"); ?><span class="w2gm-red-asterisk">*</span></label>
			<textarea name="contact_message" id="contact_message" class="w2gm-form-control" rows="6"><?php echo esc_textarea(w2gm_getValue($_POST, 'contact_message')); ?></textarea>
		</p>
		
		<?php echo w2gm_recaptcha(); ?>
		
		<input type="submit" name="submit" class="w2gm-send-message-button w2gm-btn w2gm-btn-primary" value="<?php esc_attr_e('Send message', 'W2GM'); ?>" />
	</div>
</form>