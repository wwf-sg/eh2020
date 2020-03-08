<div class="w2gm-content">
<script>
	w2gm_map_markers_attrs_array.push(new w2gm_map_markers_attrs('<?php echo $map_id; ?>', eval(<?php echo $locations_options; ?>), <?php echo $map_args; ?>));
</script>

<?php
if ($search_form) {
	$search_form = new w2gm_search_map_form($map_id, $controller, $args, $listings_content, $locations_array);
}
?>
<div id="w2gm-maps-canvas-wrapper-<?php echo $map_id; ?>" class="w2gm-maps-canvas-wrapper <?php if ($directions_sidebar_open) echo 'w2gm-directions-sidebar-open'; ?> <?php if ($search_form && $search_form->isCategoriesOrKeywords()) echo 'w2gm-map-search-input-enabled'; ?> <?php if ($sticky_scroll):?>w2gm-sticky-scroll<?php endif; ?>" data-id="<?php echo $map_id; ?>" <?php if ($sticky_scroll_toppadding):?>data-toppadding="<?php echo $sticky_scroll_toppadding; ?>"<?php endif; ?> data-height="<?php echo $height; ?>">
	<?php if ($directions_sidebar): ?>
	<?php w2gm_renderTemplate('maps/directions_sidebar.tpl.php', array('map_id' => $map_id))?>
	<?php endif; ?>
	<?php
	if ($search_form) {
		echo $search_form->display();
	} ?>
	<div id="w2gm-maps-canvas-<?php echo $map_id; ?>" class="w2gm-maps-canvas <?php if ($directions_sidebar_open) echo 'w2gm-directions-sidebar-open'; ?> <?php if ($search_form && !empty($args['search_on_map_open'])) echo 'w2gm-sidebar-open'; ?>" data-shortcode-hash="<?php echo $map_id; ?>" style="<?php if ($width) echo 'max-width:' . $width . 'px'; ?> height: <?php if ($height) { if ($height == '100%') echo '100%'; else echo $height.'px'; } else echo '300px'; ?>"></div>
</div>

<?php if ($directions_panel && w2gm_getMapEngine() == 'google'): ?>
	<?php w2gm_renderTemplate('maps/google_directions.tpl.php', array('map_id' => $map_id, 'locations_array' => $locations_array))?>
<?php endif; ?>
</div>