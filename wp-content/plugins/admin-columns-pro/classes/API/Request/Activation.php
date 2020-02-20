<?php

namespace ACP\API\Request;

use ACP\API\Request;
use ACP\Type\License\Key;

class Activation extends Request {

	public function __construct( Key $license_key, $is_network_active ) {
		parent::__construct( [
			'command'          => 'activation',
			'subscription_key' => $license_key->get_value(),
			'site_url'         => site_url(),
			'network_active'   => (bool) $is_network_active,
		] );
	}

}