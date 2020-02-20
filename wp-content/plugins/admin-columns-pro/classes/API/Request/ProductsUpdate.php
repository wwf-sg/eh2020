<?php

namespace ACP\API\Request;

use ACP\API\Request;
use ACP\Type\License\Key;

/**
 * Used for the WordPress plugin updater
 */
class ProductsUpdate extends Request {

	/**
	 * @param Key|null $license_key
	 */
	public function __construct( Key $license_key = null ) {
		parent::__construct( [
			'command'          => 'products_update',
			'subscription_key' => $license_key ? $license_key->get_value() : null,
			'site_url'         => site_url(),
		] );
	}

}