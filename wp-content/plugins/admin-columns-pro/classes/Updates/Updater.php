<?php

namespace ACP\Updates;

use AC\Registrable;
use ACP\API;
use ACP\RequestDispatcher;
use ACP\Type\License\Key;

/**
 * Hooks into the WordPress update process for plugins
 */
class Updater implements Registrable {

	/** @var string */
	private $basename;

	/** @var string */
	private $slug;

	/**
	 * @var string
	 */
	private $version;

	/** @var RequestDispatcher */
	private $api;

	/**
	 * @var Key|null
	 */
	private $license_key;

	public function __construct( $basename, $version, RequestDispatcher $api, Key $license_key = null ) {
		$this->basename = $basename;
		$this->slug = dirname( $basename );
		$this->version = $version;
		$this->api = $api;
		$this->license_key = $license_key;
	}

	public function register() {
		add_action( 'pre_set_site_transient_update_plugins', [ $this, 'check_update' ] );
	}

	public function check_update( $transient ) {
		$response = $this->api->dispatch( new API\Request\ProductsUpdate( $this->license_key ) );

		if ( ! $response || $response->has_error() ) {
			return $transient;
		}

		$plugin_data = $response->get( $this->slug );

		if ( empty( $plugin_data ) ) {
			return $transient;
		}

		$plugin_data = (object) $plugin_data;

		if ( version_compare( $this->version, $plugin_data->new_version, '<' ) ) {
			$transient->response[ $this->basename ] = $plugin_data;
		}

		return $transient;
	}

}