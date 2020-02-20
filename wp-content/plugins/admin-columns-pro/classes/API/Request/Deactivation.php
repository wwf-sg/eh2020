<?php

namespace ACP\API\Request;

use ACP\API\Request;
use ACP\Type\License\Key;

class Deactivation extends Request {

	public function __construct( Key $license_key ) {
		parent::__construct( [
			'command'          => 'deactivation',
			'subscription_key' => $license_key->get_value(),
			'site_url'         => site_url(),
		] );
	}

}