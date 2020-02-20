<?php

namespace ACP\Table;

use AC\Form\Element\Select;
use AC\ListScreen;
use AC\ListScreenRepository\FilterStrategy;
use AC\ListScreenRepository\ListScreenRepository;
use AC\ListScreenRepository\SortStrategy;
use AC\PermissionChecker;
use AC\Registrable;
use ACP\Asset;

class Switcher implements Registrable {

	/** @var ListScreenRepository */
	private $list_screen_repository;

	/** @var Asset\Location\Absolute */
	private $location;

	public function __construct( ListScreenRepository $list_screen_repository, Asset\Location\Absolute $location ) {
		$this->list_screen_repository = $list_screen_repository;
		$this->location = $location;
	}

	public function register() {
		add_action( 'ac/table_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'ac/admin_footer', array( $this, 'switcher' ) );
	}

	private function add_filter_args_to_url( $link ) {
		if ( $post_status = filter_input( INPUT_GET, 'post_status', FILTER_SANITIZE_STRING ) ) {
			$link = add_query_arg( array( 'post_status' => $post_status ), $link );
		}

		if ( $author = filter_input( INPUT_GET, 'author', FILTER_SANITIZE_STRING ) ) {
			$link = add_query_arg( array( 'author' => $author ), $link );
		}

		return $link;
	}

	/**
	 * @param ListScreen $list_screen
	 */
	public function switcher( $list_screen ) {
		if ( ! $list_screen ) {
			return;
		}

		$list_screens = $this->list_screen_repository->find_all( [
			'key'    => $list_screen->get_key(),
			'sort'   => new SortStrategy\ManualOrder(),
			'filter' => new FilterStrategy\ByPermission( new PermissionChecker( wp_get_current_user() ) ),
		] );

		if ( $list_screens->count() > 1 ) : ?>
			<form class="layout-switcher">
				<label for="column-view-selector" class="label screen-reader-text">
					<?php _e( 'Switch View', 'codepress-admin-columns' ); ?>
				</label>
				<span class="spinner"></span>

				<?php

				$options = [];

				/** @var ListScreen $_list_screen */
				foreach ( $list_screens as $_list_screen ) {
					$options[ $this->add_filter_args_to_url( $_list_screen->get_screen_link() ) ] = $_list_screen->get_title() ? $_list_screen->get_title() : __( 'Original', 'codepress-admin-columns' );
				}

				$select = new Select( 'layout', $options );
				$select->set_attribute( 'id', 'column-view-selector' )
				       ->set_attribute( 'data-ac-tip', __( 'Switch View', 'codepress-admin-columns' ) )
				       ->set_value( $this->add_filter_args_to_url( $list_screen->get_screen_link() ) );

				echo $select->render();

				?>
				<script type="text/javascript">
					jQuery( document ).ready( function( $ ) {
						$( '.layout-switcher' ).change( function() {
							var _select = $( this ).addClass( 'loading' ).find( 'select' ).attr( 'disabled', 1 );
							window.location = _select.val();
						} );
					} );
				</script>
			</form>
		<?php
		endif;
	}

	/**
	 * Loads scripts on the list screen
	 */
	public function enqueue_scripts() {
		$style = new Asset\Style( 'acp-layouts', $this->location->with_suffix( 'assets/core/css/layouts-listings-screen.css' ) );
		$style->enqueue();

		$script = new Asset\Script( 'acp-layouts', $this->location->with_suffix( 'assets/core/js/layouts-listings-screen.js' ) );
		$script->enqueue();
	}

}