<?php

namespace ACP\Admin\Page;

use AC\Admin\Page;
use AC\Registrable;
use ACP;
use ACP\Admin\Assets;
use ACP\Admin\Renderable;
use ACP\Asset;

class Tools extends Page
	implements Registrable {

	const NAME = 'import-export';

	/** @var Renderable[] */
	private $sections = [];

	/**
	 * @var Asset\Location\Absolute
	 */
	private $location;

	/**
	 * @since 1.4.6.5
	 */
	public function __construct( Asset\Location\Absolute $location ) {
		$this->location = $location;

		parent::__construct( self::NAME, __( 'Tools', 'codepress-admin-columns' ) );
	}

	/**
	 * @param Renderable $section
	 *
	 * @return $this
	 */
	public function add_section( Renderable $section ) {
		$this->sections[] = $section;

		return $this;
	}

	/**
	 * Register Hooks
	 */
	public function register() {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
	}

	/**
	 * @since 1.0
	 */
	public function admin_scripts() {
		$global_assets = [
			new Asset\Style( 'acp-style-tools', $this->location->with_suffix( 'assets/core/css/admin-tools.css' ) ),
			new Asset\Script( 'acp-script-tools', $this->location->with_suffix( 'assets/core/js/tools.js' ) ),
		];

		foreach ( $global_assets as $asset ) {
			$asset->enqueue();
		}

		foreach ( $this->sections as $section ) {
			if ( $section instanceof Assets ) {
				foreach ( $section->get_assets() as $asset ) {
					$asset->enqueue();
				}
			}
		}
	}

	/**
	 * @since 1.4.6.5
	 */
	public function render() {
		?>
		<div class="ac-section-group -tools">
			<?php
			foreach ( $this->sections as $section ) {
				$section->render();
			}
			?>
		</div>
		<?php
	}

}