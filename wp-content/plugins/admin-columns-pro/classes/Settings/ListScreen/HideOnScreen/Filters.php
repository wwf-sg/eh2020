<?php
namespace ACP\Settings\ListScreen\HideOnScreen;

use ACP\Settings\ListScreen\HideOnScreen;

class Filters extends HideOnScreen {

	public function __construct() {
		parent::__construct( 'hide_filters', __( 'Filters', 'codepress-admin-columns' ) );
	}

}