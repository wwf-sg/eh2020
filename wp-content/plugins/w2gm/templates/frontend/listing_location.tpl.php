<article class="w2gm-listing-location w2gm-listing-has-location-<?php echo $location->id; ?>" id="post-<?php echo $location->id; ?>" data-location-id="<?php echo $location->id; ?>" style="height: auto;">
	<div class="w2gm-listing-location-content">
		<?php
		if ($listing->logo_image) {
			$img_src = $listing->get_logo_url(array(150, 150));
		} else {
			$img_src = get_option('w2gm_nologo_url');
		}
	
		?>
		<div class="w2gm-map-listing-logo-wrap">
			<figure class="w2gm-map-listing-logo">
				<div class="w2gm-map-listing-logo-img-wrap">
					<div style="background-image: url('<?php echo $img_src; ?>');" class="w2gm-map-listing-logo-img">
						<img src="<?php echo $img_src; ?>" />
					</div>
				</div>
			</figure>
		</div>
		<div class="w2gm-map-listing-content-wrap">
			<header class="w2gm-map-listing-header">
				<h2><?php echo $listing->title(); ?> <?php do_action('w2gm_listing_title_html', $listing, false); ?></h2>
			</header>
			<?php $listing->renderMapSidebarContentFields($location); ?>
		</div>
	</div>
	<?php 
		if ($show_directions_button || $show_readmore_button):
			if (!$show_directions_button || !$show_readmore_button) {
				$buttons_class = 'w2gm-map-info-window-buttons-single';
			} else {
				$buttons_class = 'w2gm-map-info-window-buttons';
			}
	?>
	<div class="<?php echo $buttons_class; ?> w2gm-clearfix">
		<?php if ($show_directions_button): ?>
			<?php if (get_option("w2gm_directions_functionality") == 'google'): ?>
				<a href="https://www.google.com/maps/dir/Current+Location/<?php echo $location->map_coords_1; ?>,<?php echo $location->map_coords_2; ?>" target="_blank" class="w2gm-btn w2gm-btn-primary w2gm-map-info-window-directions-button"><?php _e('« Directions', 'W2GM'); ?></a>
			<?php elseif (get_option("w2gm_directions_functionality") == 'builtin'): ?>
				<a href="javascript:void(0);" onClick="w2gm_open_directions(<?php echo $location->id; ?>, '<?php echo $map_id; ?>')" class="w2gm-btn w2gm-btn-primary w2gm-toggle-directions-panel"><?php _e('« Directions', 'W2GM'); ?></a>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ($show_readmore_button): ?>
		<a href="#w2gm-listing-<?php echo $listing->post->ID; ?>" onClick="w2gm_show_listing(<?php echo $listing->post->ID; ?>, '<?php echo $listing->title(); ?>')" class="w2gm-btn w2gm-btn-primary w2gm-open-listing-window"><?php _e('Read more »', 'W2GM')?></a>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</article>