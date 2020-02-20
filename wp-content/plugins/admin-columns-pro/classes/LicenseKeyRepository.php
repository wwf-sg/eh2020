<?php

namespace ACP;

use ACP\Type\License\Key;

class LicenseKeyRepository {

	const OPTION_KEY = 'acp_subscription_key';

	/** @var bool */
	private $network_actived;

	public function __construct( $network_actived = false ) {
		$this->network_actived = (bool) $network_actived;
	}

	public function find() {
		$key = defined( 'ACP_LICENCE' ) && ACP_LICENCE
			? ACP_LICENCE
			: $this->get();

		if ( ! Key::is_valid( $key ) ) {
			return null;
		}

		return new Key( $key );
	}

	private function get() {
		return $this->network_actived
			? get_site_option( self::OPTION_KEY )
			: get_option( self::OPTION_KEY );
	}

	public function save( Key $license_key ) {
		$this->network_actived
			? update_site_option( self::OPTION_KEY, $license_key->get_value() )
			: update_option( self::OPTION_KEY, $license_key->get_value(), false );
	}

	public function delete() {
		$this->network_actived
			? delete_site_option( self::OPTION_KEY )
			: delete_option( self::OPTION_KEY );
	}

}