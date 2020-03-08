<?php w2gm_renderTemplate('admin_header.tpl.php'); ?>

<h2><?php _e('Demo Data Import'); ?></h2>

<?php if (!w2gm_getValue($_POST, 'submit')): ?>
<div class="error">
	<p><?php _e("1. This is Demo Data Import tool. This tool will help you to install some demo content: listings and demo pages.", "W2GM"); ?></p>
	<p><?php _e("2. Each time you click import button - it creates new set of listings and pages. Avoid duplicates.", "W2GM"); ?></p>
	<p><?php _e("3. Import will not add pages in your navigation menus.", "W2GM"); ?></p>
	<p><?php _e("4. This is not 100% copy of the demo site. Just gives some examples of maps usage. Final view and layout depends on your theme options.", "W2GM"); ?></p>
</div>

<form method="POST" action="" id="demo_data_import_form">
	<?php wp_nonce_field(W2GM_PATH, 'w2gm_csv_import_nonce');?>
	
	<?php submit_button(__('Start import', 'W2GM'), 'primary', 'submit', true, array('id' => 'import_button')); ?>
</form>
<?php endif; ?>

<?php w2gm_renderTemplate('admin_footer.tpl.php'); ?>