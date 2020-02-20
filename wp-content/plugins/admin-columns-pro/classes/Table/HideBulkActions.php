<?php

namespace ACP\Table;

use AC\ListScreen;
use AC\Registrable;
use ACP\Settings\ListScreen\HideOnScreen;

final class HideBulkActions implements Registrable {

	/** @var HideOnScreen\BulkActions */
	private $hide_on_screen;

	public function __construct() {
		$this->hide_on_screen = new HideOnScreen\BulkActions();
	}

	public function register() {
		add_action( 'ac/admin_head', [ $this, 'admin_head' ] );
	}

	public function admin_head( ListScreen $list_screen ) {
		if ( ! $this->hide_on_screen->is_hidden( $list_screen ) ) {
			return;
		}

		$selector = $this->get_bulk_actions_selector();

		if ( ! $selector ) {
			return;
		}
		?>
		<style>
			<?php echo $selector; ?> { display: none; }
		</style>
		<?php
	}

	private function get_bulk_actions_selector() {
		return '.tablenav div.actions.bulkactions';
	}

}