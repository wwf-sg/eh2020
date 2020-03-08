<?php if (get_option('w2gm_manage_ratings') || current_user_can('edit_others_posts')): ?>
<script>
	jQuery(document).ready(function($) {
		$("#w2gm-flush-all-ratings").on('click', function() {
			if (confirm('<?php echo esc_js(__('Are you sure you want to flush all ratings of this listing?', 'W2GM')); ?>')) {
				w2gm_ajax_loader_show();
				$.ajax({
					type: "POST",
					url: w2gm_js_objects.ajaxurl,
					data: {'action': 'w2gm_flush_ratings', 'post_id': <?php echo $listing->post->ID; ?>},
					success: function(){
						$(".w2gm-ratings-counts").html('0');
						$(".w2gm-admin-avgvalue").remove();
					},
					complete: function() {
						w2gm_ajax_loader_hide();
					}
				});
			    
			}
		});
	});
</script>
<?php endif; ?>
<div class="w2gm-content w2gm-ratings-metabox">
	<div class="w2gm-admin-avgvalue">
		<span class="w2gm-admin-stars">
			<?php echo _e('Average', 'W2GM'); ?>
		</span>
		<?php w2gm_renderTemplate(array(W2GM_RATINGS_TEMPLATES_PATH, 'avg_rating.tpl.php'), array('listing' => $listing, 'meta_tags' => false, 'active' => false, 'show_avg' => true)); ?>
	</div>
	<?php foreach ($total_counts AS $rating=>$counts): ?>
	<div class="w2gm-admin-rating">
		<span class="w2gm-admin-stars">
			<?php echo $rating; ?> <?php echo _n('Star ', 'Stars', $rating, 'W2GM'); ?>
		</span>
		<div class="w2gm-rating">
			<div class="w2gm-rating-stars">
				<label class="w2gm-rating-icon w2gm-fa <?php echo ($rating >= 5) ? 'w2gm-fa-star' : 'w2gm-fa-star-o' ?>"></label>
				<label class="w2gm-rating-icon w2gm-fa <?php echo ($rating >= 4) ? 'w2gm-fa-star' : 'w2gm-fa-star-o' ?>"></label>
				<label class="w2gm-rating-icon w2gm-fa <?php echo ($rating >= 3) ? 'w2gm-fa-star' : 'w2gm-fa-star-o' ?>"></label>
				<label class="w2gm-rating-icon w2gm-fa <?php echo ($rating >= 2) ? 'w2gm-fa-star' : 'w2gm-fa-star-o' ?>"></label>
				<label class="w2gm-rating-icon w2gm-fa <?php echo ($rating >= 1) ? 'w2gm-fa-star' : 'w2gm-fa-star-o' ?>"></label>
			</div>
		</div>
	 	&nbsp;&nbsp; - &nbsp;&nbsp;<span class="w2gm-ratings-counts"><?php echo $counts; ?></span>
	 </div>
	<?php endforeach; ?>
	
	<?php if (get_option('w2gm_manage_ratings') || current_user_can('edit_others_posts')): ?>
	<br />
	<input id="w2gm-flush-all-ratings" type="button" class="w2gm-btn w2gm-btn-primary" onClick="" value="<?php esc_attr_e('Flush all ratings', 'W2GM'); ?>" />
	<?php endif; ?>
</div>