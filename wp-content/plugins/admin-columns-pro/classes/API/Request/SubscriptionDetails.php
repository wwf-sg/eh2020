<?php

namespace ACP\API\Request;

use ACP\API\Request;
use ACP\Type\License\Key;

/**
 * Used for updating subscription information, such as expiration date.
 */
class SubscriptionDetails extends Request {

	/**
	 * @param Key $license_key
	 */
	public function __construct( Key $license_key ) {
		parent::__construct( [
			'command'          => 'subscription_details',
			'subscription_key' => $license_key->get_value(),
			'site_url'         => site_url(),
		] );
	}

}