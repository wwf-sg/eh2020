<?php w2gm_renderTemplate('admin_header.tpl.php'); ?>

<h2>
	<?php _e('Maps Reset', 'W2GM'); ?>
</h2>

<h3>Are you sure you want to reset locator?</h3>
<a href="<?php echo admin_url('admin.php?page=w2gm_reset&reset=settings'); ?>">Reset settings</a>
<br />
<a href="<?php echo admin_url('admin.php?page=w2gm_reset&reset=settings_tables'); ?>">Reset settings and database tables</a>

<?php w2gm_renderTemplate('admin_footer.tpl.php'); ?>