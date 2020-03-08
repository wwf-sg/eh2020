<p><?php _e("By checking this option you allow registered users to claim this listing.", 'W2GM'); ?></p>

<div class="w2gm-content">
	<div class="w2gm-checkbox">
		<label>
			<input type="checkbox" name="is_claimable" value=1 <?php checked(1, $listing->is_claimable, true); ?> />
			<?php _e('Allow claim', 'W2GM'); ?>
		</label>
	</div>
</div>
	
<?php do_action('w2gm_claim_metabox_html', $listing); ?>