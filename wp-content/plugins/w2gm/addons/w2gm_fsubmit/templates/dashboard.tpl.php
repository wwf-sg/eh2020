<div class="w2gm-content">
	<?php w2gm_renderMessages(); ?>

	<?php $frontpanel_buttons = new w2gm_frontpanel_buttons(array('buttons' => 'submit,logout')); ?>
	<?php $frontpanel_buttons->display(); ?>

	<div class="w2gm-dashboard-tabs-content">
		<ul class="w2gm-dashboard-tabs w2gm-nav w2gm-nav-tabs w2gm-clearfix">
			<li <?php if ($frontend_controller->active_tab == 'listings') echo 'class="w2gm-active"'; ?>><a href="<?php echo w2gm_dashboardUrl(); ?>"><?php _e('Listings', 'W2GM'); ?> (<?php echo $frontend_controller->listings_count; ?>)</a></li>
			<?php if (get_option('w2gm_allow_edit_profile')): ?>
			<li <?php if ($frontend_controller->active_tab == 'profile') echo 'class="w2gm-active"'; ?>><a href="<?php echo w2gm_dashboardUrl(array('w2gm_action' => 'profile')); ?>"><?php _e('My profile', 'W2GM'); ?></a></li>
			<?php endif; ?>
			<?php do_action('w2gm_dashboard_links', $frontend_controller); ?>
		</ul>
	
		<div class="w2gm-tab-content w2gm-dashboard">
			<div class="w2gm-tab-pane w2gm-active">
				<?php w2gm_renderTemplate($frontend_controller->subtemplate, $frontend_controller->template_args); ?>
			</div>
		</div>
	</div>
</div>