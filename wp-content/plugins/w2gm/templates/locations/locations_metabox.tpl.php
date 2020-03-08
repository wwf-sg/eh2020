<script>
	(function($) {
		"use strict";
	
		$(function() {
			var locations_number = <?php echo $listing->level->locations_number; ?>;
	
			<?php if ($listing->level->map_markers): ?>
			<?php if (get_option('w2gm_map_markers_type') == 'images'): ?>
				var map_icon_file_input;
				$(document).on("click", ".w2gm-select-map-icon", function() {
					map_icon_file_input = $(this).parents(".w2gm-location-input").find('.w2gm-map-icon-file');
		
					var dialog = $('<div id="w2gm-select-map-icon-dialog"></div>').dialog({
						dialogClass: 'w2gm-content',
						width: ($(window).width()*0.5),
						height: ($(window).height()*0.8),
						modal: true,
						resizable: false,
						draggable: false,
						title: '<?php echo esc_js(__('Select map marker icon', 'W2GM')); ?>',
						open: function() {
							w2gm_ajax_loader_show();
							$.ajax({
								type: "POST",
								url: w2gm_js_objects.ajaxurl,
								data: {'action': 'w2gm_select_map_icon'},
								dataType: 'html',
								success: function(response_from_the_action_function){
									if (response_from_the_action_function != 0) {
										$('#w2gm-select-map-icon-dialog').html(response_from_the_action_function);
										if (map_icon_file_input.val())
											$(".w2gm-icon[icon_file='"+map_icon_file_input.val()+"']").addClass("w2gm-selected-icon");
									}
								},
								complete: function() {
									w2gm_ajax_loader_hide();
								}
							});
							$(document).on("click", ".ui-widget-overlay", function() { $('#w2gm-select-map-icon-dialog').remove(); });
						},
						close: function() {
							$('#w2gm-select-map-icon-dialog').remove();
						}
					});
				});
				$(document).on("click", ".w2gm-icon", function() {
					$(".w2gm-selected-icon").removeClass("w2gm-selected-icon");
					if (map_icon_file_input) {
						map_icon_file_input.val($(this).attr('icon_file'));
						map_icon_file_input = false;
						$(this).addClass("w2gm-selected-icon");
						$('#w2gm-select-map-icon-dialog').remove();
						w2gm_generateMap_backend();
					}
				});
				$(document).on("click", "#reset_icon", function() {
					if (map_icon_file_input) {
						$(".w2gm-selected-icon").removeClass("w2gm-selected-icon");
						map_icon_file_input.val('');
						map_icon_file_input = false;
						$('#w2gm-select-map-icon-dialog').remove();
						w2gm_generateMap_backend();
					}
				});
			<?php else: ?>
				var map_icon_file_input;
				$(document).on("click", ".w2gm-select-map-icon", function() {
					map_icon_file_input = $(this).parents(".w2gm-location-input").find('.w2gm-map-icon-file');

					var dialog = $('<div id="select_marker_icon_dialog"></div>').dialog({
						dialogClass: 'w2gm-content',
						width: ($(window).width()*0.5),
						height: ($(window).height()*0.8),
						modal: true,
						resizable: false,
						draggable: false,
						title: '<?php echo esc_js(__('Select map marker icon', 'W2GM') . ((get_option('w2gm_map_markers_type') == 'icons') ? __(' (icon and color may depend on selected categories)', 'W2GM') : '')); ?>',
						open: function() {
							w2gm_ajax_loader_show();
							$.ajax({
								type: "POST",
								url: w2gm_js_objects.ajaxurl,
								data: {'action': 'w2gm_select_fa_icon'},
								dataType: 'html',
								success: function(response_from_the_action_function){
									if (response_from_the_action_function != 0) {
										$('#select_marker_icon_dialog').html(response_from_the_action_function);
										if (map_icon_file_input.val())
											$("#"+map_icon_file_input.val()).addClass("w2gm-selected-icon");
									}
								},
								complete: function() {
									w2gm_ajax_loader_hide();
								}
							});
							$(document).on("click", ".ui-widget-overlay", function() { $('#select_marker_icon_dialog').remove(); });
						},
						close: function() {
							$('#select_marker_icon_dialog').remove();
						}
					});
				});
				$(document).on("click", ".w2gm-fa-icon", function() {
					$(".w2gm-selected-icon").removeClass("w2gm-selected-icon");
					if (map_icon_file_input) {
						map_icon_file_input.val($(this).attr('id'));
						map_icon_file_input = false;
						$(this).addClass("w2gm-selected-icon");
						$('#select_marker_icon_dialog').remove();
						w2gm_generateMap_backend();
					}
				});
				$(document).on("click", "#w2gm-reset-fa-icon", function() {
					if (map_icon_file_input) {
						$(".w2gm-selected-icon").removeClass("w2gm-selected-icon");
						map_icon_file_input.val('');
						map_icon_file_input = false;
						$('#select_marker_icon_dialog').remove();
						w2gm_generateMap_backend();
					}
				});
			<?php endif; ?>
			<?php endif; ?>
			
			$(".add_address").click(function() {
				w2gm_ajax_loader_show();
				$.ajax({
					type: "POST",
					url: w2gm_js_objects.ajaxurl,
					data: {'action': 'w2gm_add_location_in_metabox', 'post_id': <?php echo $listing->post->ID; ?>},
					success: function(response_from_the_action_function){
						if (response_from_the_action_function != 0) {
							$("#w2gm-locations-wrapper").append(response_from_the_action_function);
							$(".w2gm-delete-address").show();
							if (locations_number == $(".w2gm-location-in-metabox").length) {
								$(".add_address").hide();
							}
							if (w2gm_maps_objects.address_autocomplete) {
								w2gm_setupAutocomplete();
							}
						}
					},
					complete: function() {
						w2gm_ajax_loader_hide();
					}
				});
			});
			$(document).on("click", ".w2gm-delete-address", function() {
				$(this).parents(".w2gm-location-in-metabox").remove();
				if ($(".w2gm-location-in-metabox").length == 1)
					$(".w2gm-delete-address").hide();
	
				w2gm_generateMap_backend();
	
				if (locations_number > $(".w2gm-location-in-metabox").length)
					$(".add_address").show();
			});
	
			$(document).on("click", ".w2gm-manual-coords", function() {
	        	if ($(this).is(":checked"))
	        		$(this).parents(".w2gm-manual-coords-wrapper").find(".w2gm-manual-coords-block").slideDown(200);
	        	else
	        		$(this).parents(".w2gm-manual-coords-wrapper").find(".w2gm-manual-coords-block").slideUp(200);
	        });
	
	        if (locations_number > $(".w2gm-location-in-metabox").length)
				$(".add_address").show();
		});
	})(jQuery);
</script>

<div class="w2gm-locations-metabox w2gm-content">
	<div id="w2gm-locations-wrapper" class="w2gm-form-horizontal">
		<?php
		if ($listing->locations)
			foreach ($listing->locations AS $location)
				w2gm_renderTemplate('locations/locations_in_metabox.tpl.php', array('listing' => $listing, 'location' => $location, 'locations_levels' => $locations_levels, 'delete_location_link' => (count($listing->locations) > 1) ? true : false));
		else
			w2gm_renderTemplate('locations/locations_in_metabox.tpl.php', array('listing' => $listing, 'location' => new w2gm_location, 'locations_levels' => $locations_levels, 'delete_location_link' => false));
		?>
	</div>
	
	<?php if ($listing->level->locations_number > 1): ?>
	<div class="w2gm-row w2gm-form-group w2gm-location-input">
		<div class="w2gm-col-md-12">	
			<a class="add_address" style="display: none;" href="javascript: void(0);">
				<span class="w2gm-fa w2gm-fa-plus"></span>
				<?php _e('Add address', 'W2GM'); ?>
			</a>
		</div>
	</div>
	<?php endif; ?>

	<div class="w2gm-row w2gm-form-group w2gm-location-input">
		<div class="w2gm-col-md-12">
			<input type="hidden" name="map_zoom" class="w2gm-map-zoom" value="<?php echo $listing->map_zoom; ?>" />
			<input type="button" class="w2gm-btn w2gm-btn-primary" onClick="w2gm_generateMap_backend(); return false;" value="<?php esc_attr_e('Generate on the map', 'W2GM'); ?>" />
		</div>
	</div>
	<div class="w2gm-maps-canvas" id="w2gm-maps-canvas" style="width: auto; height: 450px;"></div>
</div>