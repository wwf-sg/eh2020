<?php

namespace ACP\Entity;

use ACP\Type\License\ExpiryDate;
use ACP\Type\License\Key;
use ACP\Type\License\RenewalMethod;
use ACP\Type\License\Status;

final class License {

	/**
	 * @var Key
	 */
	private $key;

	/**
	 * @var Status
	 */
	private $status;

	/**
	 * @var int
	 */
	private $renewal_discount;

	/**
	 * @var RenewalMethod
	 */
	private $renewal_method;

	/**
	 * @var ExpiryDate
	 */
	private $expiry_date;

	public function __construct(
		Key $key,
		Status $status,
		$renewal_discount,
		RenewalMethod $renewal_method,
		ExpiryDate $expiry_date
	) {
		$this->set_key( $key );
		$this->set_status( $status );
		$this->set_renewal_discount( $renewal_discount );
		$this->set_renewal_method( $renewal_method );
		$this->set_expiry_date( $expiry_date );
	}

	/**
	 * @return Key
	 */
	public function get_key() {
		return $this->key;
	}

	private function set_key( Key $key ) {
		$this->key = $key;
	}

	/**
	 * @return ExpiryDate
	 */
	public function get_expiry_date() {
		return $this->expiry_date;
	}

	private function set_expiry_date( ExpiryDate $expiry_date ) {
		$this->expiry_date = $expiry_date;
	}

	/**
	 * @return bool
	 */
	public function is_lifetime() {
		return $this->expiry_date->is_lifetime();
	}

	/**
	 * @return bool
	 */
	public function is_expired() {
		return $this->expiry_date->is_expired();
	}

	/**
	 * @return int
	 */
	public function get_renewal_discount() {
		return $this->renewal_discount;
	}

	/**
	 * @param int $discount
	 */
	private function set_renewal_discount( $discount ) {
		$discount = (int) $discount;

		if ( $discount < 0 || $discount > 100 ) {
			$discount = 0;
		}

		$this->renewal_discount = $discount;
	}

	/**
	 * @return RenewalMethod
	 */
	public function get_renewal_method() {
		return $this->renewal_method;
	}

	private function set_renewal_method( RenewalMethod $renewal_method ) {
		$this->renewal_method = $renewal_method;
	}

	/**
	 * @return bool
	 */
	public function is_auto_renewal() {
		return $this->renewal_method->is_auto_renewal();
	}

	/**
	 * @return Status
	 */
	public function get_status() {
		return $this->status;
	}

	private function set_status( Status $status ) {
		$this->status = $status;
	}

	/**
	 * @return bool
	 */
	public function is_active() {
		return $this->status->is_active();
	}

	/**
	 * @return bool
	 */
	public function is_cancelled() {
		return $this->status->is_cancelled();
	}

}