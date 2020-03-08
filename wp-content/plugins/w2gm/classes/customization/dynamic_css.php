<?php if (w2gm_get_dynamic_option('w2gm_listing_title_font')): ?>
header.w2gm-listing-header h2 {
	font-size: <?php echo w2gm_get_dynamic_option('w2gm_listing_title_font'); ?>px;
}
<?php endif; ?>

<?php if (w2gm_get_dynamic_option('w2gm_links_color')): ?>
div.w2gm-content a,
div.w2gm-content a:visited,
div.w2gm-content a:focus,
div.w2gm-content .w2gm-pagination > li > a,
div.w2gm-content .w2gm-pagination > li > a:visited,
div.w2gm-content .w2gm-pagination > li > a:focus,
div.w2gm-content .w2gm-btn-default, div.w2gm-content div.w2gm-btn-default:visited, div.w2gm-content .w2gm-btn-default:focus,
div.w2gm-content.w2gm-terms-menu .w2gm-categories-root a,
div.w2gm-content.w2gm-terms-menu .w2gm-categories-root a:visited,
div.w2gm-content.w2gm-terms-menu .w2gm-categories-root a:focus,
div.w2gm-content.w2gm-terms-menu .w2gm-locations-root a,
div.w2gm-content.w2gm-terms-menu .w2gm-locations-root a:visited,
div.w2gm-content.w2gm-terms-menu .w2gm-locations-root a:focus {
	color: <?php echo w2gm_get_dynamic_option('w2gm_links_color'); ?>;
}
<?php endif; ?>
<?php if (w2gm_get_dynamic_option('w2gm_links_hover_color')): ?>
div.w2gm-content a:hover,
div.w2gm-content .w2gm-pagination > li > a:hover,
div.w2gm-content.w2gm-terms-menu .w2gm-categories-root a:hover,
div.w2gm-content.w2gm-terms-menu .w2gm-locations-root a:hover {
	color: <?php echo w2gm_get_dynamic_option('w2gm_links_hover_color'); ?>;
}
<?php endif; ?>

<?php if (w2gm_get_dynamic_option('w2gm_button_1_color') && w2gm_get_dynamic_option('w2gm_button_2_color') && w2gm_get_dynamic_option('w2gm_button_text_color')): ?>
<?php if (!w2gm_get_dynamic_option('w2gm_button_gradient')): ?>
div.w2gm-content .w2gm-btn-primary,
div.w2gm-content a.w2gm-btn-primary,
div.w2gm-content input[type="submit"],
div.w2gm-content input[type="button"],
div.w2gm-content .w2gm-btn-primary:visited,
div.w2gm-content a.w2gm-btn-primary:visited,
div.w2gm-content input[type="submit"]:visited,
div.w2gm-content input[type="button"]:visited,
div.w2gm-content .w2gm-btn-primary:focus,
div.w2gm-content a.w2gm-btn-primary:focus,
div.w2gm-content input[type="submit"]:focus,
div.w2gm-content input[type="button"]:focus,
div.w2gm-content .w2gm-btn-primary:disabled,
div.w2gm-content a.w2gm-btn-primary:disabled,
div.w2gm-content .w2gm-btn-primary:disabled:focus,
div.w2gm-content a.w2gm-btn-primary:disabled:focus,
div.w2gm-content .w2gm-btn-primary:disabled:hover,
div.w2gm-content a.w2gm-btn-primary:disabled:hover,
form.w2gm-content .w2gm-btn-primary,
form.w2gm-content a.w2gm-btn-primary,
form.w2gm-content input[type="submit"],
form.w2gm-content input[type="button"],
form.w2gm-content .w2gm-btn-primary:visited,
form.w2gm-content a.w2gm-btn-primary:visited,
form.w2gm-content input[type="submit"]:visited,
form.w2gm-content input[type="button"]:visited,
form.w2gm-content .w2gm-btn-primary:focus,
form.w2gm-content a.w2gm-btn-primary:focus,
form.w2gm-content input[type="submit"]:focus,
form.w2gm-content input[type="button"]:focus,
form.w2gm-content .w2gm-btn-primary:disabled,
form.w2gm-content a.w2gm-btn-primary:disabled,
form.w2gm-content .w2gm-btn-primary:disabled:focus,
form.w2gm-content a.w2gm-btn-primary:disabled:focus,
form.w2gm-content .w2gm-btn-primary:disabled:hover,
form.w2gm-content a.w2gm-btn-primary:disabled:hover,
div.w2gm-content .wpcf7-form .wpcf7-submit,
div.w2gm-content .wpcf7-form .wpcf7-submit:visited,
div.w2gm-content .wpcf7-form .wpcf7-submit:focus {
	color: <?php echo w2gm_get_dynamic_option('w2gm_button_text_color'); ?>;
	background-color: <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?>;
	background-image: none;
	border-color: <?php echo w2gm_adjust_brightness(w2gm_get_dynamic_option('w2gm_button_1_color'), -20); ?>;
}
div.w2gm-content .w2gm-btn-primary:hover,
div.w2gm-content a.w2gm-btn-primary:hover,
div.w2gm-content input[type="submit"]:hover,
div.w2gm-content input[type="button"]:hover,
form.w2gm-content .w2gm-btn-primary:hover,
form.w2gm-content a.w2gm-btn-primary:hover,
form.w2gm-content input[type="submit"]:hover,
form.w2gm-content input[type="button"]:hover,
div.w2gm-content .wpcf7-form .wpcf7-submit:hover {
	color: <?php echo w2gm_get_dynamic_option('w2gm_button_text_color'); ?>;
	background-color: <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?>;
	background-image: none;
	border-color: <?php echo w2gm_adjust_brightness(w2gm_get_dynamic_option('w2gm_button_2_color'), -20); ?>;
	text-decoration: none;
}
<?php else: ?>
div.w2gm-content .w2gm-btn-primary,
div.w2gm-content a.w2gm-btn-primary,
div.w2gm-content input[type="submit"],
div.w2gm-content input[type="button"],
div.w2gm-content .w2gm-btn-primary:visited,
div.w2gm-content a.w2gm-btn-primary:visited,
div.w2gm-content input[type="submit"]:visited,
div.w2gm-content input[type="button"]:visited,
div.w2gm-content .w2gm-btn-primary:focus,
div.w2gm-content a.w2gm-btn-primary:focus,
div.w2gm-content input[type="submit"]:focus,
div.w2gm-content input[type="button"]:focus,
div.w2gm-content .w2gm-btn-primary:disabled,
div.w2gm-content a.w2gm-btn-primary:disabled,
div.w2gm-content .w2gm-btn-primary:disabled:focus,
div.w2gm-content a.w2gm-btn-primary:disabled:focus,
form.w2gm-content .w2gm-btn-primary,
form.w2gm-content a.w2gm-btn-primary,
form.w2gm-content input[type="submit"],
form.w2gm-content input[type="button"],
form.w2gm-content .w2gm-btn-primary:visited,
form.w2gm-content a.w2gm-btn-primary:visited,
form.w2gm-content input[type="submit"]:visited,
form.w2gm-content input[type="button"]:visited,
form.w2gm-content .w2gm-btn-primary:focus,
form.w2gm-content a.w2gm-btn-primary:focus,
form.w2gm-content input[type="submit"]:focus,
form.w2gm-content input[type="button"]:focus,
form.w2gm-content .w2gm-btn-primary:disabled,
form.w2gm-content a.w2gm-btn-primary:disabled,
form.w2gm-content .w2gm-btn-primary:disabled:focus,
form.w2gm-content a.w2gm-btn-primary:disabled:focus,
div.w2gm-content .w2gm-listing-frontpanel input[type="button"],
div.w2gm-content .wpcf7-form .wpcf7-submit,
div.w2gm-content .wpcf7-form .wpcf7-submit:visited,
div.w2gm-content .wpcf7-form .wpcf7-submit:focus {
	background: <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?> !important;
	background: -moz-linear-gradient(top, <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?> 0%, <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?> 100%) !important;
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?>), color-stop(100%, <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?>)) !important;
	background: -webkit-linear-gradient(top, <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?> 0%, <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?> 100%) !important;
	background: -o-linear-gradient(top, <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?> 0%, <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?> 100%) !important;
	background: -ms-linear-gradient(top, <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?> 0%, <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?> 100%) !important;
	background: linear-gradient(to bottom, <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?> 0%, <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?> 100%) !important;
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr= <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?> , endColorstr= <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?> ,GradientType=0 ) !important;
	color: <?php echo w2gm_get_dynamic_option('w2gm_button_text_color'); ?>;
	background-position: center !important;
	border: none;
}
div.w2gm-content .w2gm-btn-primary:hover,
div.w2gm-content a.w2gm-btn-primary:hover,
div.w2gm-content input[type="submit"]:hover,
div.w2gm-content input[type="button"]:hover,
form.w2gm-content .w2gm-btn-primary:hover,
form.w2gm-content a.w2gm-btn-primary:hover,
form.w2gm-content input[type="submit"]:hover,
form.w2gm-content input[type="button"]:hover,
div.w2gm-content .w2gm-listing-frontpanel input[type="button"]:hover,
div.w2gm-content .wpcf7-form .wpcf7-submit:hover {
	background: <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?> !important;
	background: -moz-linear-gradient(top, <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?> 0%, <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?> 100%) !important;
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?>), color-stop(100%, <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?>)) !important;
	background: -webkit-linear-gradient(top, <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?> 0%, <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?> 100%) !important;
	background: -o-linear-gradient(top, <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?> 0%, <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?> 100%) !important;
	background: -ms-linear-gradient(top, <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?> 0%, <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?> 100%) !important;
	background: linear-gradient(to bottom, <?php echo w2gm_get_dynamic_option('w2gm_button_2_color'); ?> 0%, <?php echo w2gm_get_dynamic_option('w2gm_button_1_color'); ?> 100%) !important;
	color: <?php echo w2gm_get_dynamic_option('w2gm_button_text_color'); ?>;
	background-position: center !important;
	border: none;
	text-decoration: none;
}
<?php endif; ?>
<?php endif; ?>

<?php if (!w2gm_get_dynamic_option('w2gm_search_overlay')): ?>
.w2gm-search-overlay {
	background: none;
}
<?php endif; ?>
<?php if (w2gm_get_dynamic_option('w2gm_search_bg_color')): ?>
.w2gm-content.w2gm-search-form {
	background: <?php echo w2gm_hex2rgba(w2gm_get_dynamic_option('w2gm_search_bg_color'), w2gm_get_dynamic_option('w2gm_search_bg_opacity')/100); ?>;
}
<?php endif; ?>
<?php if (w2gm_get_dynamic_option('w2gm_search_text_color')): ?>
form.w2gm-content.w2gm-search-form,
form.w2gm-content.w2gm-search-form a,
form.w2gm-content.w2gm-search-form a:hover,
form.w2gm-content.w2gm-search-form a:visited,
form.w2gm-content.w2gm-search-form a:focus,
form.w2gm-content a.w2gm-advanced-search-label,
form.w2gm-content a.w2gm-advanced-search-label:hover,
form.w2gm-content a.w2gm-advanced-search-label:visited,
form.w2gm-content a.w2gm-advanced-search-label:focus {
	color: <?php echo w2gm_get_dynamic_option('w2gm_search_text_color'); ?>;
}
<?php endif; ?>

<?php if (w2gm_get_dynamic_option('w2gm_primary_color') && w2gm_get_dynamic_option('w2gm_secondary_color')): ?>
.w2gm-field-caption .w2gm-field-icon {
	color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
}
.w2gm-content select:not(.w2gm-week-day-input),
.w2gm-content select:not(.w2gm-week-day-input):focus {
	background-image:
	linear-gradient(50deg, transparent 50%, #FFFFFF 50%),
	linear-gradient(130deg, #FFFFFF 50%, transparent 50%),
	linear-gradient(to right, <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>, <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>) !important;
}
.w2gm-content .w2gm-checkbox .w2gm-control-indicator,
.w2gm-content .w2gm-radio .w2gm-control-indicator {
	border-color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
}
.w2gm-content .w2gm-checkbox label input:checked ~ .w2gm-control-indicator,
.w2gm-content .w2gm-radio label input:checked ~ .w2gm-control-indicator {
	background: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
}
.w2gm-content .ui-slider.ui-slider-horizontal .ui-widget-header {
	background-color: <?php echo w2gm_get_dynamic_option('w2gm_secondary_color'); ?>;
}
.w2gm-content .ui-slider.ui-widget-content .ui-slider-handle.ui-state-default,
.w2gm-content .ui-slider.ui-widget-content .ui-slider-handle.ui-state-default:focus,
.w2gm-content .ui-slider.ui-widget-content .ui-slider-handle.ui-state-default:active,
.w2gm-content .ui-slider.ui-widget-content .ui-slider-handle.ui-state-focus,
.w2gm-content .ui-slider.ui-widget-content .ui-slider-handle.ui-state-hover {
	border: 1px solid <?php echo w2gm_get_dynamic_option('w2gm_secondary_color'); ?>;
	background-color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
}
.w2gm-content .w2gm-map-info-window-title {
	background-color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
}
.w2gm-content .w2gm-category-label,
.w2gm-content .w2gm-tag-label {
	color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
	border-color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
}
div.w2gm-content .w2gm-pagination > li.w2gm-active > a,
div.w2gm-content .w2gm-pagination > li.w2gm-active > span,
div.w2gm-content .w2gm-pagination > li.w2gm-active > a:hover,
div.w2gm-content .w2gm-pagination > li.w2gm-active > span:hover,
div.w2gm-content .w2gm-pagination > li.w2gm-active > a:focus,
div.w2gm-content .w2gm-pagination > li.w2gm-active > span:focus {
	background-color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
	border-color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
	color: #FFFFFF;
}
.w2gm-found-listings .w2gm-badge {
	background-color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
}
.w2gm-rating-avgvalue span {
	background-color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
}
.w2gm-content.w2gm-search-map-form .w2gm-search-overlay {
	background-color: <?php echo w2gm_hex2rgba(w2gm_get_dynamic_option('w2gm_primary_color'), 0.8); ?>;
}
.w2gm-field-output-block-string .w2gm-field-phone-content,
.w2gm-field-output-block-website .w2gm-field-content {
	color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
	font-weight: bold;
}
.w2gm-loader:before {
	border-top-color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
	border-bottom-color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
}
.w2gm-listing-dialog.ui-dialog .ui-widget-header {
	background-color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
}
article.w2gm-listing-location-selected {
	background-color: <?php echo w2gm_hex2rgba(w2gm_get_dynamic_option('w2gm_primary_color'), 0.1); ?>;
}
.w2gm-field-checkbox-item-checked span {
	color: <?php echo w2gm_get_dynamic_option('w2gm_primary_color'); ?>;
}
<?php endif; ?>


<?php if (!w2gm_get_dynamic_option('w2gm_100_single_logo_width')): ?>
/* It works with devices width more than 768 pixels. */
@media screen and (min-width: 768px) {
	.w2gm-single-listing-logo-wrap {
		max-width: <?php echo w2gm_get_dynamic_option('w2gm_single_logo_width'); ?>px;
		float: left;
		margin: 0 20px 20px 0;
	}
	.rtl .w2gm-single-listing-logo-wrap {
		float: right;
		margin: 0 0 20px 20px;
	}
	/* temporarily */
	/*.w2gm-single-listing-text-content-wrap {
		margin-left: <?php echo w2gm_get_dynamic_option('w2gm_single_logo_width')+20; ?>px;
	}*/
}
<?php endif; ?>

<?php if (w2gm_get_dynamic_option('w2gm_hide_search_on_map_mobile')): ?>
/* It works with devices width less than 768 pixels. */
@media screen and (max-width: 768px) {
	.w2gm-map-search-form {
		display: none !important;
	}
	.w2gm-maps-canvas.w2gm-sidebar-open {
		margin-left: 0 !important;
		width: 100% !important;
	}
}
<?php endif; ?>

<?php if (w2gm_get_dynamic_option('w2gm_big_slide_bg_mode')): ?>
.w2gm-single-listing-logo-wrap .w2gm-big-slide {
	background-size: <?php echo w2gm_get_dynamic_option('w2gm_big_slide_bg_mode'); ?>;
}
<?php endif; ?>

<?php if (w2gm_get_dynamic_option('w2gm_listing_logo_bg_mode')): ?>
figure.w2gm-listing-logo .w2gm-listing-logo-img {
	background-size: <?php echo w2gm_get_dynamic_option('w2gm_listing_logo_bg_mode'); ?>;
}
<?php endif; ?>

<?php if (w2gm_get_dynamic_option('w2gm_map_marker_size')): ?>
.w2gm-map-marker,
.w2gm-map-marker-empty {
	height: <?php echo w2gm_get_dynamic_option('w2gm_map_marker_size'); ?>px;
	width: <?php echo w2gm_get_dynamic_option('w2gm_map_marker_size'); ?>px;
}
.w2gm-map-marker .w2gm-map-marker-icon {
	font-size: <?php echo round(0.55*w2gm_get_dynamic_option('w2gm_map_marker_size')); ?>px !important;
}
<?php endif; ?>