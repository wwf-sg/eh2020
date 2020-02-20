<?php
namespace ACP\Admin;

use ACP\Asset\Enqueueable;

interface Assets {

	/**
	 * @return Enqueueable[]
	 */
	public function get_assets();

}