		<input type="button" id="reset_icon" class="w2gm-btn w2gm-btn-primary" value="<?php esc_attr_e('Reset icon image', 'W2GM'); ?>" />

		<div class="w2gm-icons-theme-block">
		<?php foreach ($categories_icons AS $icon): ?>
			<div class="w2gm-icon" icon_file="<?php echo $icon; ?>"><img src="<?php echo W2GM_CATEGORIES_ICONS_URL . $icon; ?>" title="<?php echo $icon; ?>" /></div>
		<?php endforeach;?>
		</div>
		<div class="w2gm-clearfix"></div>