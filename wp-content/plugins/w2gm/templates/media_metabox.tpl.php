<?php if ($images_number) : ?>
	<script>
		var images_number = <?php echo $images_number; ?>;

		(function($) {
			"use strict";

			window.w2gm_image_attachment_tpl = function(attachment_id, uploaded_file, title, size, width, height) {
				var image_attachment_tpl = '<div class="w2gm-attached-item w2gm-move-label">' +
					'<input type="hidden" name="attached_image_id[]" class="w2gm-attached-item-id" value="' + attachment_id + '" />' +
					'<a href="' + uploaded_file + '" data-lightbox="listing_images" class="w2gm-attached-item-img" style="background-image: url(' + uploaded_file + ')"></a>' +
					'<div class="w2gm-attached-item-input">' +
					'<input type="text" name="attached_image_title[]" class="w2gm-form-control" value="" placeholder="<?php esc_attr_e('optional image title', 'W2GM'); ?>" />' +
					'</div>' +
					<?php if ($logo_enabled) : ?> '<div class="w2gm-attached-item-logo w2gm-radio">' +
						'<label>' +
						'<input type="radio" name="attached_image_as_logo" value="' + attachment_id + '"> <?php esc_attr_e('set this image as logo', 'W2GM'); ?>' +
						'</label>' +
						'</div>' +
					<?php endif; ?> '<div class="w2gm-attached-item-delete w2gm-fa w2gm-fa-trash-o" title="<?php esc_attr_e("remove", "W2GM"); ?>"></div>' +
					'<div class="w2gm-attached-item-metadata">' + size + ' (' + width + ' x ' + height + ')</div>' +
					'</div>';

				return image_attachment_tpl;
			};

			window.w2gm_update_images_attachments_order = function() {
				$("#w2gm-attached-images-order").val($(".w2gm-attached-item-id").map(function() {
					return $(this).val();
				}).get());
			}
			window.w2gm_check_images_attachments_number = function() {
				if (images_number > $("#w2gm-images-upload-wrapper .w2gm-attached-item").length) {
					<?php if (is_admin()) : ?>
						$("#w2gm-admin-upload-functions").show();
					<?php else : ?>
						$(".w2gm-upload-item").show();
					<?php endif; ?>
					return true;
				} else {
					<?php if (is_admin()) : ?>
						$("#w2gm-admin-upload-functions").hide();
					<?php else : ?>
						$(".w2gm-upload-item").hide();
					<?php endif; ?>
					return false;
				}
			}

			$(function() {
				var sortable_images = $("#w2gm-attached-images-wrapper").sortable({
					delay: 50,
					placeholder: "ui-sortable-placeholder",
					items: ".w2gm-attached-item",
					helper: function(e, ui) {
						ui.children().each(function() {
							$(this).width($(this).width());
						});
						return ui;
					},
					start: function(e, ui) {
						ui.placeholder.width(ui.item.width());
						ui.placeholder.height(ui.item.height());
					},
					update: function(event, ui) {
						w2gm_update_images_attachments_order();
					}
				});

				// disable sortable on android, otherwise it breaks click events on image, radio and delete button
				var ua = navigator.userAgent.toLowerCase();
				if (ua.indexOf("android") > -1) {
					sortable_images.sortable("disable");
				};

				w2gm_check_images_attachments_number();

				$("#w2gm-attached-images-wrapper").on("click", ".w2gm-attached-item-delete", function() {
					$(this).parents(".w2gm-attached-item").remove();

					$.ajax({
						url: w2gm_js_objects.ajaxurl,
						type: "POST",
						dataType: "json",
						data: {
							action: 'w2gm_remove_image',
							post_id: <?php echo $object_id; ?>,
							attachment_id: $(this).parent().find(".w2gm-attached-item-id").val(),
							_wpnonce: '<?php echo wp_create_nonce('remove_image'); ?>'
						}
					});

					w2gm_check_images_attachments_number();
					w2gm_update_images_attachments_order();
				});

				<?php if (!is_admin()) : ?>
					$(document).on("click", ".w2gm-upload-item-button", function(e) {
						e.preventDefault();

						$(this).parent().find("input").click();
					});

					$('.w2gm-upload-item').fileupload({
						sequentialUploads: true,
						dataType: 'json',
						url: '<?php echo admin_url('admin-ajax.php?action=w2gm_upload_image&post_id=' . $object_id . '&_wpnonce=' . wp_create_nonce('upload_images')); ?>',
						dropZone: $('.w2gm-drop-attached-item'),
						add: function(e, data) {
							if (w2gm_check_images_attachments_number()) {
								var jqXHR = data.submit();
							} else {
								return false;
							}
						},
						send: function(e, data) {
							w2gm_add_iloader_on_element($(this).find(".w2gm-drop-attached-item"));
						},
						done: function(e, data) {
							var result = data.result;
							if (result.uploaded_file) {
								var size = result.metadata.size;
								var width = result.metadata.width;
								var height = result.metadata.height;
								$(this).before(w2gm_image_attachment_tpl(result.attachment_id, result.uploaded_file, data.files[0].name, size, width, height));
								w2gm_custom_input_controls();
							} else {
								$(this).find(".w2gm-drop-attached-item").append("<p>" + result.error_msg + "</p>");
							}
							$(this).find(".w2gm-drop-zone").show();
							w2gm_delete_iloader_from_element($(this).find(".w2gm-drop-attached-item"));

							w2gm_check_images_attachments_number();
							w2gm_update_images_attachments_order();
						}
					});
				<?php endif; ?>
			});
		})(jQuery);
	</script>

	<div id="w2gm-images-upload-wrapper" class="w2gm-content w2gm-media-upload-wrapper">
		<input type="hidden" id="w2gm-attached-images-order" name="attached_images_order" value="<?php echo implode(',', array_keys($images)); ?>">
		<h4><?php _e('Attach image', 'W2GM'); ?></h4>

		<div id="w2gm-attached-images-wrapper">
			<?php foreach ($images as $attachment_id => $attachment) : ?>
				<?php $src = wp_get_attachment_image_src($attachment_id, array(250, 250)); ?>
				<?php $src_full = wp_get_attachment_image_src($attachment_id, 'full'); ?>
				<?php $metadata = wp_get_attachment_metadata($attachment_id); ?>
				<?php $metadata['size'] = size_format(filesize(get_attached_file($attachment_id))); ?>
				<div class="w2gm-attached-item w2gm-move-label">
					<input type="hidden" name="attached_image_id[]" class="w2gm-attached-item-id" value="<?php echo $attachment_id; ?>" />
					<a href="<?php echo $src_full[0]; ?>" data-lightbox="listing_images" class="w2gm-attached-item-img" style="background-image: url('<?php echo $src[0]; ?>')"></a>
					<div class="w2gm-attached-item-input">
						<input type="text" name="attached_image_title[]" class="w2gm-form-control" value="<?php esc_attr_e($attachment['post_title']); ?>" placeholder="<?php esc_attr_e('optional image title', 'W2GM'); ?>" />
					</div>
					<?php if ($logo_enabled) : ?>
						<div class="w2gm-attached-item-logo w2gm-radio">
							<label>
								<input type="radio" name="attached_image_as_logo" value="<?php echo $attachment_id; ?>" <?php checked($logo_image, $attachment_id); ?>> <?php _e('set this image as logo', 'W2GM'); ?>
							</label>
						</div>
					<?php endif; ?>
					<div class="w2gm-attached-item-delete w2gm-fa w2gm-fa-trash-o" title="<?php esc_attr_e("delete", "W2GM"); ?>"></div>
					<div class="w2gm-attached-item-metadata"><?php echo $metadata['size']; ?> (<?php echo $metadata['width']; ?> x <?php echo $metadata['height']; ?>)</div>
				</div>
			<?php endforeach; ?>
			<?php if (!is_admin()) : ?>
				<div class="w2gm-upload-item">
					<div class="w2gm-drop-attached-item">
						<div class="w2gm-drop-zone">
							<?php _e("Drop here", "W2GM"); ?>
							<button class="w2gm-upload-item-button w2gm-btn w2gm-btn-primary"><?php _e("Browse", "W2GM"); ?></button>
							<input type="file" name="browse_file" multiple />
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<div class="w2gm-clearfix"></div>

		<?php if (is_admin() && current_user_can('upload_files')) : ?>
			<script>
				(function($) {
					"use strict";

					$(function() {
						$('#w2gm-admin-upload-image').click(function(event) {
							event.preventDefault();

							var frame = wp.media({
								title: '<?php echo esc_js(sprintf(__('Upload image (%d maximum)', 'W2GM'), $images_number)); ?>',
								multiple: true,
								library: {
									type: 'image'
								},
								button: {
									text: '<?php echo esc_js(__('Insert', 'W2GM')); ?>'
								},
							});
							frame.on('select', function() {
								var selected_images = [];
								var selection = frame.state().get('selection');
								selection.each(function(attachment) {
									attachment = attachment.toJSON();
									if (attachment.type == 'image') {

										if (w2gm_check_images_attachments_number()) {
											w2gm_ajax_loader_show();

											$.ajax({
												type: "POST",
												async: false,
												url: w2gm_js_objects.ajaxurl,
												data: {
													'action': 'w2gm_upload_media_image',
													'attachment_id': attachment.id,
													'post_id': <?php echo $object_id; ?>,
													'_wpnonce': '<?php echo wp_create_nonce('upload_images'); ?>',
												},
												attachment_id: attachment.id,
												attachment_url: attachment.sizes.full.url,
												attachment_title: attachment.title,
												dataType: "json",
												success: function(response_from_the_action_function) {
													if (response_from_the_action_function != 0) {
														var size = response_from_the_action_function.metadata.size;
														var width = response_from_the_action_function.metadata.width;
														var height = response_from_the_action_function.metadata.height;
														$("#w2gm-attached-images-wrapper").append(w2gm_image_attachment_tpl(this.attachment_id, this.attachment_url, this.attachment_title, size, width, height));
														w2gm_check_images_attachments_number();
														w2gm_update_images_attachments_order();
													}

													w2gm_ajax_loader_hide();
												}
											});
										}
									}

								});
							});
							frame.open();
						});
					});
				})(jQuery);
			</script>
			<div id="w2gm-admin-upload-functions">
				<div class="w2gm-upload-option">
					<input type="button" id="w2gm-admin-upload-image" class="w2gm-btn w2gm-btn-primary" value="<?php esc_attr_e('Upload image', 'W2GM'); ?>" />
				</div>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>


<?php if ($videos_number) : ?>
	<script>
		var videos_number = <?php echo $videos_number; ?>;

		(function($) {
			"use strict";

			window.w2gm_video_attachment_tpl = function(video_id, image_url) {
				var video_attachment_tpl = '<div class="w2gm-attached-item">' +
					'<input type="hidden" name="attached_video_id[]" value="' + video_id + '" />' +
					'<div class="w2gm-attached-item-img" style="background-image: url(' + image_url + ')"></div>' +
					'<div class="w2gm-attached-item-delete w2gm-fa w2gm-fa-trash-o" title="<?php esc_attr_e("delete", "W2GM"); ?>"></div>' +
					'</div>';

				return video_attachment_tpl;
			};

			window.w2gm_check_videos_attachments_number = function() {
				if (videos_number > $("#w2gm-attached-videos-wrapper .w2gm-attached-item").length) {
					$("#w2gm-attach-videos-functions").show();
				} else {
					$("#w2gm-attach-videos-functions").hide();
				}
			}

			$(function() {
				w2gm_check_videos_attachments_number();

				$("#w2gm-attached-videos-wrapper").on("click", ".w2gm-attached-item-delete", function() {
					$(this).parents(".w2gm-attached-item").remove();

					w2gm_check_videos_attachments_number();
				});
			});
		})(jQuery);
	</script>

	<div id="w2gm-video-attach-wrapper" class="w2gm-content w2gm-media-upload-wrapper">
		<h4><?php _e("Attach videos", "W2GM"); ?></h4>

		<div id="w2gm-attached-videos-wrapper">
			<?php foreach ($videos as $video) : ?>
				<div class="w2gm-attached-item">
					<input type="hidden" name="attached_video_id[]" value="<?php echo $video['id']; ?>" />
					<?php
					if (strlen($video['id']) == 11) {
						$image_url = "http://i.ytimg.com/vi/" . $video['id'] . "/0.jpg";
					} elseif (strlen($video['id']) == 8 || strlen($video['id']) == 9) {
						$data = file_get_contents("http://vimeo.com/api/v2/video/" . $video['id'] . ".json");
						$data = json_decode($data);
						$image_url = $data[0]->thumbnail_medium;
					} ?>
					<div class="w2gm-attached-item-img" style="background-image: url('<?php echo $image_url; ?>')"></div>
					<div class="w2gm-attached-item-delete w2gm-fa w2gm-fa-trash-o" title="<?php esc_attr_e("delete", "W2GM"); ?>"></div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="w2gm-clearfix"></div>

		<script>
			(function($) {
				"use strict";

				window.attachVideo = function() {
					if ($("#w2gm-attach-video-input").val()) {
						var regExp_youtube = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
						var regExp_vimeo = /https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/;
						var matches_youtube = $("#w2gm-attach-video-input").val().match(regExp_youtube);
						var matches_vimeo = $("#w2gm-attach-video-input").val().match(regExp_vimeo);
						if (matches_youtube && matches_youtube[2].length == 11) {
							var video_id = matches_youtube[2];
							var image_url = 'http://i.ytimg.com/vi/' + video_id + '/0.jpg';
							$("#w2gm-attached-videos-wrapper").append(w2gm_video_attachment_tpl(video_id, image_url));

							w2gm_check_videos_attachments_number();
						} else if (matches_vimeo && (matches_vimeo[3].length == 8 || matches_vimeo[3].length == 9)) {
							var video_id = matches_vimeo[3];
							var url = "//vimeo.com/api/v2/video/" + video_id + ".json?callback=showVimeoThumb";
							var script = document.createElement('script');
							script.src = url;
							$("#w2gm-attach-videos-functions").before(script);
						} else {
							alert("<?php esc_attr_e('Wrong URL or this video is unavailable', 'W2GM'); ?>");
						}
					}
				};

				window.showVimeoThumb = function(data) {
					var video_id = data[0].id;
					var image_url = data[0].thumbnail_medium;
					$("#w2gm-attached-videos-wrapper").append(w2gm_video_attachment_tpl(video_id, image_url));

					w2gm_check_videos_attachments_number();
				};
			})(jQuery);
		</script>
		<div id="w2gm-attach-videos-functions">
			<div class="w2gm-upload-option">
				<label><?php _e('Enter full YouTube or Vimeo video link', 'W2GM'); ?></label>
			</div>
			<div class="w2gm-upload-option">
				<input type="text" id="w2gm-attach-video-input" class="w2gm-form-control" placeholder="https://youtu.be/XXXXXXXXXXXX" />
			</div>
			<div class="w2gm-upload-option">
				<input type="button" class="w2gm-btn w2gm-btn-primary" onclick="return attachVideo(); " value="<?php esc_attr_e('Attach video', 'W2GM'); ?>" />
			</div>
		</div>
	</div>
<?php endif; ?>