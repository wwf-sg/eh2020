<form method="POST" action="<?php the_permalink($listing->post->ID); ?>#report-tab" id="w2gm_report_form">
	<input type="hidden" name="listing_id" id="report_listing_id" value="<?php echo $listing->post->ID; ?>" />
	<input type="hidden" name="report_nonce" id="report_nonce" value="<?php print wp_create_nonce('w2gm_report_nonce'); ?>" />
	<h3><?php _e('Send message to moderator', 'W2GM'); ?></h3>
	<h5 id="report_warning" style="display: none; color: red;"></h5>
	<div class="w2gm-report-form">
		<?php if (is_user_logged_in()): ?>
		<p>
			<?php printf(__('You are currently logged in as %s. Your message will be sent using your logged in name and email.', 'W2GM'), wp_get_current_user()->user_login); ?>
			<input type="hidden" name="report_name" id="report_name" />
			<input type="hidden" name="report_email" id="report_email" />
		</p>
		<?php else: ?>
		<p>
			<label for="report_name"><?php _e('Contact Name', 'W2GM'); ?><span class="w2gm-red-asterisk">*</span></label>
			<input type="text" name="report_name" id="report_name" class="w2gm-form-control" value="<?php echo esc_attr(w2gm_getValue($_POST, 'report_name')); ?>" size="35" />
		</p>
		<p>
			<label for="report_email"><?php _e("Contact Email", "W2GM"); ?><span class="w2gm-red-asterisk">*</span></label>
			<input type="text" name="report_email" id="report_email" class="w2gm-form-control" value="<?php echo esc_attr(w2gm_getValue($_POST, 'report_email')); ?>" size="35" />
		</p>
		<?php endif; ?>
		<p>
			<label for="report_message"><?php _e("Your message", "W2GM"); ?><span class="w2gm-red-asterisk">*</span></label>
			<textarea name="report_message" id="report_message" class="w2gm-form-control" rows="6"><?php echo esc_textarea(w2gm_getValue($_POST, 'report_message')); ?></textarea>
		</p>
		
		<?php echo w2gm_recaptcha(); ?>
		
		<input type="submit" name="submit" class="w2gm-send-message-button w2gm-btn w2gm-btn-primary" value="<?php esc_attr_e('Send message', 'W2GM'); ?>" />
	</div>
</form>