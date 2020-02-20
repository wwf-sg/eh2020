<div class="ac-section -export">
	<div class="ac-section__header">
		<h2 class="ac-section__header__title"><?php _e( 'Export', 'codepress-admin-columns' ); ?></h2>
	</div>
	<div class="ac-section__body">
		<p>
			<?php _e( 'Select the column settings you would like to export and then select your export method.', 'codepress-admin-columns' ); ?>
			<?php _e( 'Use the download button to export to a .json file which you can then import to another Admin Columns Pro installation.', 'codepress-admin-columns' ); ?>
			<?php _e( 'Use the generate button to export to PHP code which you can place in your theme.', 'codepress-admin-columns' ); ?>
		</p>

		<form method="post">
			<?php wp_nonce_field( 'ac-ajax', '_ajax_nonce' ); ?>
			<?php wp_nonce_field( 'export', '_ac_nonce' ); ?>
			<input type="hidden" name="action" value="acp-export">
			<input type="hidden" name="encoder" value="<?= \ACP\Parser\FileEncodeFactory::FORMAT_JSON; ?>">
			<input type="hidden" name="response_type" value="<?= \ACP\Migrate\Controller\Export::RESPONSE_TYPE_FILE; ?>">
			<?php echo $this->table->render(); ?>
			<button class="button button-primary" data-export="json"><?php _e( 'Export File', 'codepress-admin-columns' ); ?></button>
			<button class="button" data-export="php" disabled><?php _e( 'Generate PHP', 'codepress-admin-columns' ); ?></button>
		</form>
	</div>
</div>


<div class="ac-modal -sactive" id="ac-modal-export-php">
	<div class="ac-modal__dialog">
		<div class="ac-modal__dialog__header">
			<?php _e( 'Export PHP', 'codepress-admin-columns' ); ?>
			<button class="ac-modal__dialog__close">
				<span class="dashicons dashicons-no"></span>
			</button>
		</div>
		<div class="ac-modal__dialog__content">
			<textarea rows="16"></textarea>
		</div>
	</div>
</div>