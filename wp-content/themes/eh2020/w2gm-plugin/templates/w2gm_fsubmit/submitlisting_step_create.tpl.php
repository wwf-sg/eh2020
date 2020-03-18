<?php $listing = $w2gm_instance->current_listing; ?>
<div class="w2gm-content">
	<?php w2gm_renderMessages(); ?>

	<h3 style="color:white;"><?php echo apply_filters('w2gm_create_option', __('', 'W2GM')); ?></h3>

	<div class="w2gm-create-listing-wrapper w2gm-row">
		<div class="w2gm-create-listing-form w2gm-col-md-12">
			<form id="acField" action="<?php echo w2gm_submitUrl(); ?>" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="listing_id" value="<?php echo $listing->post->ID; ?>" />
				<input type="hidden" name="listing_id_hash" value="<?php echo md5($listing->post->ID . wp_salt()); ?>" />
				<?php wp_nonce_field('w2gm_submit', '_submit_nonce'); ?>


				<div class="w2gm-submit-section w2gm-submit-section-title">
					<h3 class="w2gm-submit-section-label"><?php _e('Listing title', 'W2GM'); ?><span class="w2gm-red-asterisk">*</span></h3>
					<div class="w2gm-submit-section-inside">
						<input type="text" name="post_title" style="width: 100%" class="w2gm-form-control" value="<?php if ($listing->post->post_title != __('Auto Draft', 'W2GM')) echo esc_attr($listing->post->post_title); ?>" />
					</div>
				</div>
				<?php if (!is_user_logged_in() && (get_option('w2gm_fsubmit_login_mode') == 2 || get_option('w2gm_fsubmit_login_mode') == 3)) : ?>
					<div class="w2gm-submit-section w2gm-submit-section-contact-info">
						<h3 class="w2gm-submit-section-label"><?php _e('User Information', 'W2GM'); ?></h3>
						<div class="w2gm-submit-section-inside">
							<label class="w2gm-fsubmit-contact"><?php _e('Your Name', 'W2GM'); ?><?php if (get_option('w2gm_fsubmit_login_mode') == 2) : ?><span class="w2gm-red-asterisk">*</span><?php endif; ?></label>
							<input id="wwf-c-name" type="text" name="w2gm_user_contact_name" value="<?php echo esc_attr($frontend_controller->w2gm_user_contact_name); ?>" class="w2gm-form-control" style="width: 100%;" />

							<label class="w2gm-fsubmit-contact"><?php _e('Email Address', 'W2GM'); ?><?php if (get_option('w2gm_fsubmit_login_mode') == 2) : ?><span class="w2gm-red-asterisk">*</span><?php endif; ?></label>
							<input id="wwf-c-mail" type="text" name="w2gm_user_contact_email" value="<?php echo esc_attr($frontend_controller->w2gm_user_contact_email); ?>" class="w2gm-form-control" style="width: 100%;" />
						</div>
					</div>
				<?php endif; ?>

				<?php if (false) : ?>
					<?php // if ($listing->level->categories_number > 0 || $listing->level->unlimited_categories) : 
					?>
					<div class="w2gm-submit-section w2gm-submit-section-categories">
						<h3 class="w2gm-submit-section-label"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('categories_list')->name; ?><?php if ($w2gm_instance->content_fields->getContentFieldBySlug('categories_list')->is_required) : ?><span class="w2gm-red-asterisk">*</span><?php endif; ?></h3>
						<div class="w2gm-submit-section-inside">
							<div class="w2gm-categories-tree-panel w2gm-editor-class" id="<?php echo W2GM_CATEGORIES_TAX; ?>-all">
								<?php w2gm_terms_checklist($listing->post->ID); ?>
							</div>
							<?php if ($w2gm_instance->content_fields->getContentFieldBySlug('categories_list')->description) : ?><p class="w2gm-description"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('categories_list')->description; ?></p><?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if (get_option('w2gm_listing_contact_form') && get_option('w2gm_custom_contact_email')) : ?>
					<div class="w2gm-submit-section w2gm-submit-section-contact-email">
						<h3 class="w2gm-submit-section-label"><?php _e('Contact email', 'W2GM'); ?></h3>
						<div class="w2gm-submit-section-inside">
							<?php $w2gm_instance->listings_manager->listingContactEmailMetabox($listing->post); ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ($w2gm_instance->content_fields->getNotCoreContentFields()) : ?>
					<?php $w2gm_instance->content_fields->renderInputByGroups($listing->post); ?>
				<?php endif; ?>

				<?php if ($listing->level->images_number > 0 || $listing->level->videos_number > 0) : ?>
					<div class="w2gm-submit-section w2gm-submit-section-media">
						<h3 class="w2gm-submit-section-label"><?php _e('Upload Company Logo', 'W2GM'); ?></h3>
						<div style="padding-left:10px;">Note* Image Size - limit of 1MB and below. JPEG and PNG image only.</div>
						<div class="w2gm-submit-section-inside">
							<?php $w2gm_instance->media_manager->mediaMetabox($listing->post, array('args' => array('target' => 'listings'))); ?>
						</div>

					</div>
				<?php endif; ?>

				<?php do_action('w2gm_create_listing_metaboxes_post', $listing); ?>

				<?php if (get_option('w2gm_enable_recaptcha')) : ?>
					<div class="w2gm-submit-section-adv">
						<?php echo w2gm_recaptcha(); ?>
					</div>
				<?php endif; ?>

				<?php
				if ($tos_page = w2gm_get_wpml_dependent_option('w2gm_tospage')) : ?>
					<div class="w2gm-submit-section-adv">
						<label><input type="checkbox" name="w2gm_tospage" value="1" /> <?php printf(__('I agree to the ', 'W2GM') . '<a href="%s" target="_blank">%s</a>', get_permalink($tos_page), __('Terms of Services', 'W2GM')); ?></label>
					</div>
				<?php endif; ?>

				<?php if (false) : ?>
					<?php // if (post_type_supports(W2GM_POST_TYPE, 'editor')) : 
					?>
					<div class="w2gm-submit-section w2gm-submit-section-description">
						<h3 class="w2gm-submit-section-label"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('content')->name; ?><?php if ($w2gm_instance->content_fields->getContentFieldBySlug('content')->is_required) : ?><span class="w2gm-red-asterisk">*</span><?php endif; ?></h3>
						<div class="w2gm-submit-section-inside">
							<?php wp_editor($listing->post->post_content, 'post_content', array('media_buttons' => false, 'editor_class' => 'w2gm-editor-class')); ?>
							<?php if ($w2gm_instance->content_fields->getContentFieldBySlug('content')->description) : ?><p class="w2gm-description"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('content')->description; ?></p><?php endif; ?>
						</div>
					</div>
				<?php endif; ?>


				<?php do_action('w2gm_create_listing_metaboxes_pre', $listing); ?>

				<?php if (!$listing->level->eternal_active_period && (get_option('w2gm_change_expiration_date') || current_user_can('manage_options'))) : ?>
					<div class="w2gm-submit-section w2gm-submit-section-expiration-date">
						<h3 class="w2gm-submit-section-label"><?php _e('Listing expiration date', 'W2GM'); ?></h3>
						<div class="w2gm-submit-section-inside">
							<?php $w2gm_instance->listings_manager->listingExpirationDateMetabox($listing->post); ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ($listing->level->locations_number > 0) : ?>
					<div class="w2gm-submit-section w2gm-submit-section-locations">
						<h3 class="w2gm-submit-section-label"><?php _e('Listing locations', 'W2GM'); ?><?php if ($w2gm_instance->content_fields->getContentFieldBySlug('address')->is_required) : ?><span class="w2gm-red-asterisk">*</span><?php endif; ?></h3>
						<div class="w2gm-submit-section-inside">
							<?php if ($w2gm_instance->content_fields->getContentFieldBySlug('address')->description) : ?><p class="w2gm-description"><?php echo $w2gm_instance->content_fields->getContentFieldBySlug('address')->description; ?></p><?php endif; ?>
							<?php $w2gm_instance->locations_manager->listingLocationsMetabox($listing->post); ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="text-left custom-control custom-checkbox mx-2" style="margin-top: -50px; margin-bottom: 20px">
					<div>
						<input type="checkbox" value="Yes" id="check_pdpc" class="custom-control-input">
						<label for="check_pdpc" class="custom-control-label pt-1">
							By submitting this form, you agree to WWF’s <a href="https://www.wwf.sg/wwf_singapore/pdp_policy/" target="_blank">Privacy Policy</a> and Terms and Conditions. By accepting, you acknowledge and consent to WWF sending you such updates.
						</label>
					</div>
					<div class="mt-2">
						<p class="error mb-0" style="display: none;">Please accept privacy policy and terms and conditions</p>
					</div>
				</div>


				<?php require_once(ABSPATH . 'wp-admin/includes/template.php'); ?>
				<?php submit_button(__('Submit', 'W2GM'), 'w2gm-btn w2gm-btn-primary') ?>
			</form>
			<form method="POST" action="https://wwf-worldwidefundfornaturesingaporelimited1552298160.activehosted.com/proc.php" id="_form_1_" class="_form _form_1 _inline-form  _dark" novalidate>
				<input type="hidden" name="u" value="1" />
				<input type="hidden" name="f" value="1" />
				<input type="hidden" name="s" />
				<input type="hidden" name="c" value="0" />
				<input type="hidden" name="m" value="0" />
				<input type="hidden" name="act" value="sub" />
				<input type="hidden" name="v" value="2" />
				<div class="_form-content">
					<div class="_form_element _x08166466 _full_width ">
						<div class="_field-wrapper">
							<input type="text" name="fullname" placeholder="Type your name" />
						</div>
					</div>
					<div class="_form_element _x38734575 _full_width ">
						<div class="_field-wrapper">
							<input type="text" name="email" placeholder="Type your email" required />
						</div>
					</div>
					<button id="_form_1_submit" class="_submit" type="submit">
						Submit
					</button>
				</div>
			</form>
			<script type="text/javascript">
				window.cfields = [];
				window._show_thank_you = function(id, message, trackcmp_url) {
					var form = document.getElementById('_form_' + id + '_'),
						thank_you = form.querySelector('._form-thank-you');
					form.querySelector('._form-content').style.display = 'none';
					thank_you.innerHTML = message;
					thank_you.style.display = 'block';
					if (typeof(trackcmp_url) != 'undefined' && trackcmp_url) {
						// Site tracking URL to use after inline form submission.
						_load_script(trackcmp_url);
					}
					if (typeof window._form_callback !== 'undefined') window._form_callback(id);
				};
				window._show_error = function(id, message, html) {
					var form = document.getElementById('_form_' + id + '_'),
						err = document.createElement('div'),
						button = form.querySelector('button'),
						old_error = form.querySelector('._form_error');
					if (old_error) old_error.parentNode.removeChild(old_error);
					err.innerHTML = message;
					err.className = '_error-inner _form_error _no_arrow';
					var wrapper = document.createElement('div');
					wrapper.className = '_form-inner';
					wrapper.appendChild(err);
					button.parentNode.insertBefore(wrapper, button);
					document.querySelector('[id^="_form"][id$="_submit"]').disabled = false;
					if (html) {
						var div = document.createElement('div');
						div.className = '_error-html';
						div.innerHTML = html;
						err.appendChild(div);
					}
				};
				window._load_script = function(url, callback) {
					var head = document.querySelector('head'),
						script = document.createElement('script'),
						r = false;
					script.type = 'text/javascript';
					script.charset = 'utf-8';
					script.src = url;
					if (callback) {
						script.onload = script.onreadystatechange = function() {
							if (!r && (!this.readyState || this.readyState == 'complete')) {
								r = true;
								callback();
							}
						};
					}
					head.appendChild(script);
				};
				(function() {
					if (window.location.search.search("excludeform") !== -1) return false;
					var getCookie = function(name) {
						var match = document.cookie.match(new RegExp('(^|; )' + name + '=([^;]+)'));
						return match ? match[2] : null;
					}
					var setCookie = function(name, value) {
						var now = new Date();
						var time = now.getTime();
						var expireTime = time + 1000 * 60 * 60 * 24 * 365;
						now.setTime(expireTime);
						document.cookie = name + '=' + value + '; expires=' + now + ';path=/';
					}
					var addEvent = function(element, event, func) {
						if (element.addEventListener) {
							element.addEventListener(event, func);
						} else {
							var oldFunc = element['on' + event];
							element['on' + event] = function() {
								oldFunc.apply(this, arguments);
								func.apply(this, arguments);
							};
						}
					}
					var _removed = false;
					var form_to_submit = document.getElementById('_form_1_');
					var allInputs = form_to_submit.querySelectorAll('input, select, textarea'),
						tooltips = [],
						submitted = false;

					var getUrlParam = function(name) {
						var regexStr = '[\?&]' + name + '=([^&#]*)';
						var results = new RegExp(regexStr, 'i').exec(window.location.href);
						return results != undefined ? decodeURIComponent(results[1]) : false;
					};

					for (var i = 0; i < allInputs.length; i++) {
						var regexStr = "field\\[(\\d+)\\]";
						var results = new RegExp(regexStr).exec(allInputs[i].name);
						if (results != undefined) {
							allInputs[i].dataset.name = window.cfields[results[1]];
						} else {
							allInputs[i].dataset.name = allInputs[i].name;
						}
						var fieldVal = getUrlParam(allInputs[i].dataset.name);

						if (fieldVal) {
							if (allInputs[i].dataset.autofill === "false") {
								continue;
							}
							if (allInputs[i].type == "radio" || allInputs[i].type == "checkbox") {
								if (allInputs[i].value == fieldVal) {
									allInputs[i].checked = true;
								}
							} else {
								allInputs[i].value = fieldVal;
							}
						}
					}

					var remove_tooltips = function() {
						for (var i = 0; i < tooltips.length; i++) {
							tooltips[i].tip.parentNode.removeChild(tooltips[i].tip);
						}
						tooltips = [];
					};
					var remove_tooltip = function(elem) {
						for (var i = 0; i < tooltips.length; i++) {
							if (tooltips[i].elem === elem) {
								tooltips[i].tip.parentNode.removeChild(tooltips[i].tip);
								tooltips.splice(i, 1);
								return;
							}
						}
					};
					var create_tooltip = function(elem, text) {
						var tooltip = document.createElement('div'),
							arrow = document.createElement('div'),
							inner = document.createElement('div'),
							new_tooltip = {};
						if (elem.type != 'radio' && elem.type != 'checkbox') {
							tooltip.className = '_error';
							arrow.className = '_error-arrow';
							inner.className = '_error-inner';
							inner.innerHTML = text;
							tooltip.appendChild(arrow);
							tooltip.appendChild(inner);
							elem.parentNode.appendChild(tooltip);
						} else {
							tooltip.className = '_error-inner _no_arrow';
							tooltip.innerHTML = text;
							elem.parentNode.insertBefore(tooltip, elem);
							new_tooltip.no_arrow = true;
						}
						new_tooltip.tip = tooltip;
						new_tooltip.elem = elem;
						tooltips.push(new_tooltip);
						return new_tooltip;
					};
					var resize_tooltip = function(tooltip) {
						var rect = tooltip.elem.getBoundingClientRect();
						var doc = document.documentElement,
							scrollPosition = rect.top - ((window.pageYOffset || doc.scrollTop) - (doc.clientTop || 0));
						if (scrollPosition < 40) {
							tooltip.tip.className = tooltip.tip.className.replace(/ ?(_above|_below) ?/g, '') + ' _below';
						} else {
							tooltip.tip.className = tooltip.tip.className.replace(/ ?(_above|_below) ?/g, '') + ' _above';
						}
					};
					var resize_tooltips = function() {
						if (_removed) return;
						for (var i = 0; i < tooltips.length; i++) {
							if (!tooltips[i].no_arrow) resize_tooltip(tooltips[i]);
						}
					};
					var validate_field = function(elem, remove) {
						var tooltip = null,
							value = elem.value,
							no_error = true;
						remove ? remove_tooltip(elem) : false;
						if (elem.type != 'checkbox') elem.className = elem.className.replace(/ ?_has_error ?/g, '');
						if (elem.getAttribute('required') !== null) {
							if (elem.type == 'radio' || (elem.type == 'checkbox' && /any/.test(elem.className))) {
								var elems = form_to_submit.elements[elem.name];
								if (!(elems instanceof NodeList || elems instanceof HTMLCollection) || elems.length <= 1) {
									no_error = elem.checked;
								} else {
									no_error = false;
									for (var i = 0; i < elems.length; i++) {
										if (elems[i].checked) no_error = true;
									}
								}
								if (!no_error) {
									tooltip = create_tooltip(elem, "Please select an option.");
								}
							} else if (elem.type == 'checkbox') {
								var elems = form_to_submit.elements[elem.name],
									found = false,
									err = [];
								no_error = true;
								for (var i = 0; i < elems.length; i++) {
									if (elems[i].getAttribute('required') === null) continue;
									if (!found && elems[i] !== elem) return true;
									found = true;
									elems[i].className = elems[i].className.replace(/ ?_has_error ?/g, '');
									if (!elems[i].checked) {
										no_error = false;
										elems[i].className = elems[i].className + ' _has_error';
										err.push("Checking %s is required".replace("%s", elems[i].value));
									}
								}
								if (!no_error) {
									tooltip = create_tooltip(elem, err.join('<br/>'));
								}
							} else if (elem.tagName == 'SELECT') {
								var selected = true;
								if (elem.multiple) {
									selected = false;
									for (var i = 0; i < elem.options.length; i++) {
										if (elem.options[i].selected) {
											selected = true;
											break;
										}
									}
								} else {
									for (var i = 0; i < elem.options.length; i++) {
										if (elem.options[i].selected && !elem.options[i].value) {
											selected = false;
										}
									}
								}
								if (!selected) {
									elem.className = elem.className + ' _has_error';
									no_error = false;
									tooltip = create_tooltip(elem, "Please select an option.");
								}
							} else if (value === undefined || value === null || value === '') {
								elem.className = elem.className + ' _has_error';
								no_error = false;
								tooltip = create_tooltip(elem, "This field is required.");
							}
						}
						if (no_error && elem.name == 'email') {
							if (!value.match(/^[\+_a-z0-9-'&=]+(\.[\+_a-z0-9-']+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i)) {
								elem.className = elem.className + ' _has_error';
								no_error = false;
								tooltip = create_tooltip(elem, "Enter a valid email address.");
							}
						}
						if (no_error && /date_field/.test(elem.className)) {
							if (!value.match(/^\d\d\d\d-\d\d-\d\d$/)) {
								elem.className = elem.className + ' _has_error';
								no_error = false;
								tooltip = create_tooltip(elem, "Enter a valid date.");
							}
						}
						tooltip ? resize_tooltip(tooltip) : false;
						return no_error;
					};
					var needs_validate = function(el) {
						return el.name == 'email' || el.getAttribute('required') !== null;
					};
					var validate_form = function(e) {
						var err = form_to_submit.querySelector('._form_error'),
							no_error = true;
						if (!submitted) {
							submitted = true;
							for (var i = 0, len = allInputs.length; i < len; i++) {
								var input = allInputs[i];
								if (needs_validate(input)) {
									if (input.type == 'text') {
										addEvent(input, 'blur', function() {
											this.value = this.value.trim();
											validate_field(this, true);
										});
										addEvent(input, 'input', function() {
											validate_field(this, true);
										});
									} else if (input.type == 'radio' || input.type == 'checkbox') {
										(function(el) {
											var radios = form_to_submit.elements[el.name];
											for (var i = 0; i < radios.length; i++) {
												addEvent(radios[i], 'click', function() {
													validate_field(el, true);
												});
											}
										})(input);
									} else if (input.tagName == 'SELECT') {
										addEvent(input, 'change', function() {
											validate_field(this, true);
										});
									} else if (input.type == 'textarea') {
										addEvent(input, 'input', function() {
											validate_field(this, true);
										});
									}
								}
							}
						}
						remove_tooltips();
						for (var i = 0, len = allInputs.length; i < len; i++) {
							var elem = allInputs[i];
							if (needs_validate(elem)) {
								if (elem.tagName.toLowerCase() !== "select") {
									elem.value = elem.value.trim();
								}
								validate_field(elem) ? true : no_error = false;
							}
						}
						if (!no_error && e) {
							e.preventDefault();
						}
						resize_tooltips();
						return no_error;
					};
					addEvent(window, 'resize', resize_tooltips);
					addEvent(window, 'scroll', resize_tooltips);
					window._old_serialize = null;
					if (typeof serialize !== 'undefined') window._old_serialize = window.serialize;
					_load_script("//d3rxaij56vjege.cloudfront.net/form-serialize/0.3/serialize.min.js", function() {
						window._form_serialize = window.serialize;
						if (window._old_serialize) window.serialize = window._old_serialize;
					});
					var form_submit = function(e) {
						e.preventDefault();
						if (validate_form()) {
							// use this trick to get the submit button & disable it using plain javascript
							document.querySelector('#_form_1_submit').disabled = true;
							var serialized = _form_serialize(document.getElementById('_form_1_'));
							var err = form_to_submit.querySelector('._form_error');
							err ? err.parentNode.removeChild(err) : false;
							_load_script('https://wwf-worldwidefundfornaturesingaporelimited1552298160.activehosted.com/proc.php?' + serialized + '&jsonp=true');
						}
						return false;
					};
					addEvent(form_to_submit, 'submit', form_submit);
				})();
				//active campagin
				jQuery(function($) {
					$("input[name='w2gm-field-input-8']").on('keyup', function() {
						$("input[name='email']").val($(this).val());
					});
					$("input[name='w2gm-field-input-12']").on('keyup', function() {
						$("input[name='fullname']").val($(this).val());
					});
					$('#_form_1_submit').click(function() {
						$("#submit").click();
					});
				});
			</script>
		</div>
	</div>
</div>