<?php

namespace ACP;

use AC;
use AC\Capabilities;
use AC\Integrations;
use AC\Registrable;
use ACP\API\Cached;
use ACP\Type\SiteUrl;

class Updates implements Registrable {

	/**
	 * @var API
	 */
	private $api;

	/**
	 * @var Integrations
	 */
	private $integrations;

	/**
	 * @var LicenseKeyRepository
	 */
	private $license_key_repository;

	/**
	 * @var SiteUrl
	 */
	private $site_url;

	public function __construct( API $api, LicenseKeyRepository $license_key_repository, SiteUrl $site_url ) {
		$this->api = $api;
		$this->license_key_repository = $license_key_repository;
		$this->site_url = $site_url;
		$this->integrations = new Integrations();
	}

	public function register() {
		// Register plugin and add-on to the WordPress updater.
		add_action( 'init', [ $this, 'register_updater' ], 9 );

		// Forces update check when user clicks "Check again" on dashboard page.
		add_action( 'init', [ $this, 'force_plugin_update_check_on_request' ] );
	}

	/**
	 * @return bool
	 */
	private function is_doing_ajax_update_process() {
		return wp_doing_ajax() && 'update-plugin' === filter_input( INPUT_POST, 'action' );
	}

	public function register_updater() {

		// Skip updater during the ajax update process
		if ( $this->is_doing_ajax_update_process() ) {
			return;
		}

		foreach ( $this->get_installed_plugins() as $basename => $version ) {
			// Add plugins to update process
			$updater = new Updates\Updater( $basename, $version, new API\Cached( $this->api ), $this->site_url, $this->license_key_repository->find() );
			$updater->register();

			// Click "view details" on plugin page
			$view_details = new Updates\ViewPluginDetails( dirname( $basename ), $this->api );
			$view_details->register();
		}
	}

	public function force_plugin_update_check_on_request() {
		global $pagenow;

		$force_check = '1' === filter_input( INPUT_GET, 'force-check' );

		if ( $force_check && $pagenow === 'update-core.php' && current_user_can( Capabilities::MANAGE ) ) {
			$api = new API\Cached( $this->api );
			$api->dispatch(
				new API\Request\ProductsUpdate( $this->site_url, $this->license_key_repository->find() ), [
					Cached::FORCE_UPDATE => true,
				]
			);
		}
	}

	/**
	 * @return array [ $basename => $version ]
	 */
	private function get_installed_plugins() {
		$plugins = [
			ACP()->get_basename() => ACP()->get_version(),
		];

		foreach ( $this->integrations as $integration ) {
			$plugin_info = new AC\PluginInformation( $integration->get_basename() );

			if ( $plugin_info->is_installed() ) {
				$plugins[ $plugin_info->get_basename() ] = $plugin_info->get_version();
			}
		}

		return $plugins;
	}

}