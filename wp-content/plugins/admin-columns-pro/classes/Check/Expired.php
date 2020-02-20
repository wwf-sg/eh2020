<?php

namespace ACP\Check;

use AC\Ajax;
use AC\Capabilities;
use AC\Message;
use AC\Registrable;
use AC\Screen;
use AC\Storage;
use ACP\LicenseKeyRepository;
use ACP\LicenseRepository;
use DateTime;
use Exception;

class Expired implements Registrable {

	/**
	 * @var LicenseRepository
	 */
	private $license_repository;

	/**
	 * @var LicenseKeyRepository
	 */
	private $license_key_repository;

	public function __construct( LicenseRepository $license_repository, LicenseKeyRepository $license_key_repository ) {
		$this->license_repository = $license_repository;
		$this->license_key_repository = $license_key_repository;
	}

	public function register() {
		add_action( 'ac/screen', array( $this, 'display' ) );

		$this->get_ajax_handler()->register();
	}

	/**
	 * @param Screen $screen
	 *
	 * @throws Exception
	 */
	public function display( Screen $screen ) {
		if ( ! $screen->has_screen() || ! current_user_can( Capabilities::MANAGE ) ) {
			return;
		}

		$license_key = $this->license_key_repository->find();

		if ( ! $license_key ) {
			return;
		}

		$license = $this->license_repository->find( $license_key );

		if ( ! $license || ! $license->is_expired() || ! $license->get_expiry_date()->exists() ) {
			return;
		}

		$expiry_date = $license->get_expiry_date()->get_value();

		if ( $screen->is_plugin_screen() ) {
			// Inline message on plugin page
			$notice = new Message\Plugin( $this->get_message( $expiry_date ), ACP()->get_basename() );
		} else if ( $screen->is_admin_screen( 'settings' ) ) {
			// Permanent displayed on settings page
			$notice = new Message\Notice( $this->get_message( $expiry_date ) );
		} else if ( $screen->is_admin_screen( 'columns' ) && $this->get_dismiss_option()->is_expired() ) {
			// Dismissible on columns page
			$notice = new Message\Notice\Dismissible( $this->get_message( $expiry_date ), $this->get_ajax_handler() );
		} else if ( $screen->is_list_screen() ) {
			// Dismissible on list table
			$notice = new Message\Notice\Dismissible( $this->get_message( $expiry_date ), $this->get_ajax_handler() );
		} else {
			$notice = false;
		}

		if ( $notice instanceof Message ) {
			$notice
				->set_type( Message::WARNING )
				->register();
		}
	}

	/**
	 * @param DateTime $expiration_date
	 *
	 * @return string
	 */
	private function get_message( DateTime $expiration_date ) {
		$expired_on = ac_format_date( get_option( 'date_format' ), $expiration_date->getTimestamp() );
		$my_account_link = ac_helper()->html->link( ac_get_site_utm_url( 'my-account/license', 'renewal' ), __( 'My Account Page', 'codepress-admin-columns' ) );

		return sprintf(
			__( 'Your Admin Columns Pro license has expired on %s. To receive updates, renew your license on the %s.', 'codepress-admin-columns' ),
			'<strong>' . $expired_on . '</strong>',
			$my_account_link
		);
	}

	/**
	 * @return Ajax\Handler
	 */
	protected function get_ajax_handler() {
		$handler = new Ajax\Handler();
		$handler
			->set_action( 'ac_notice_dismiss_expired' )
			->set_callback( array( $this, 'ajax_dismiss_notice' ) );

		return $handler;
	}

	/**
	 * @return Storage\Timestamp
	 */
	protected function get_dismiss_option() {
		return new Storage\Timestamp(
			new Storage\UserMeta( 'ac_notice_dismiss_expired' )
		);
	}

	public function ajax_dismiss_notice() {
		$this->get_ajax_handler()->verify_request();
		$this->get_dismiss_option()->save( time() + MONTH_IN_SECONDS );
	}

}