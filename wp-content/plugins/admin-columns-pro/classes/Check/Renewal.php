<?php

namespace ACP\Check;

use AC\Ajax;
use AC\Capabilities;
use AC\Message;
use AC\Registrable;
use AC\Screen;
use AC\Storage;
use ACP\Entity\License;
use ACP\LicenseKeyRepository;
use ACP\LicenseRepository;
use DateTime;
use Exception;

class Renewal
	implements Registrable {

	/**
	 * @var LicenseRepository
	 */
	private $license_repository;

	/**
	 * @var LicenseKeyRepository
	 */
	private $license_key_repository;

	/**
	 * @var int[] Intervals to check in ascending order with a max of 90 days
	 */
	protected $intervals;

	public function __construct( LicenseRepository $license_repository, LicenseKeyRepository $license_key_repository ) {
		$this->license_repository = $license_repository;
		$this->license_key_repository = $license_key_repository;
		$this->intervals = [ 1, 7, 21 ];
	}

	public function register() {
		add_action( 'ac/screen', array( $this, 'display' ) );

		$this->get_ajax_handler()->register();
	}

	/**
	 * @throws Exception
	 */
	public function ajax_dismiss_notice() {
		$this->get_ajax_handler()->verify_request();

		$interval = (int) filter_input( INPUT_POST, 'interval', FILTER_SANITIZE_NUMBER_INT );

		if ( ! array_key_exists( $interval, $this->intervals ) ) {
			wp_die();
		}

		// 90 days
		$result = $this->get_dismiss_option( $interval )->save( time() + ( MONTH_IN_SECONDS * 3 ) );

		wp_die( $result );
	}

	/**
	 * @return Ajax\Handler
	 */
	protected function get_ajax_handler() {
		$handler = new Ajax\Handler();
		$handler->set_action( 'ac_notice_dismiss_renewal' )
		        ->set_callback( array( $this, 'ajax_dismiss_notice' ) );

		return $handler;
	}

	/**
	 * @param int $interval
	 *
	 * @return Storage\Timestamp
	 * @throws Exception
	 */
	protected function get_dismiss_option( $interval ) {
		return new Storage\Timestamp(
			new Storage\UserMeta( 'ac_notice_dismiss_renewal_' . $interval )
		);
	}

	private function is_valid_screen( Screen $screen ) {
		return $screen->has_screen() && ( $screen->is_admin_screen() || $screen->is_list_screen() || $screen->is_plugin_screen() );
	}

	/**
	 * @param Screen $screen
	 *
	 * @throws Exception
	 */
	public function display( Screen $screen ) {
		if ( ! $this->is_valid_screen( $screen ) ) {
			return;
		}

		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return;
		}

		if ( apply_filters( 'acp/hide_renewal_notice', false ) ) {
			return;
		}

		$license_key = $this->license_key_repository->find();

		if ( ! $license_key ) {
			return;
		}

		$license = $this->license_repository->find( $license_key );

		if ( ! $license || $license->is_auto_renewal() || $license->is_expired() || $license->is_cancelled() ) {
			return;
		}

		$days_remaining = $this->get_days_remaining( $license );

		$interval = $this->get_current_interval( $days_remaining );

		if ( false === $interval ) {
			return;
		}

		if ( ! $this->get_dismiss_option( $interval )->is_expired() ) {
			return;
		}

		$ajax_handler = $this->get_ajax_handler();
		$ajax_handler->set_param( 'interval', $interval );

		$notice = new Message\Notice\Dismissible(
			$this->get_message( $license ),
			$ajax_handler
		);

		$notice
			->set_type( $notice::WARNING )
			->register();
	}

	/**
	 * Get the current interval compared to the license state. Returns false when no interval matches
	 *
	 * @param int $remaining_days
	 *
	 * @return false|int
	 */
	protected function get_current_interval( $remaining_days ) {
		foreach ( $this->intervals as $k => $interval ) {
			if ( $interval >= $remaining_days ) {
				return $k;
			}
		}

		return false;
	}

	/**
	 * @param DateTime $date
	 *
	 * @return string
	 */
	private function localize_date( DateTime $date, $format = null ) {
		if ( null === $format ) {
			$format = get_option( 'date_format' );
		}

		return ac_format_date( $format, $date->getTimestamp() );
	}

	/**
	 * @param License $license
	 *
	 * @return string
	 */
	protected function get_message( License $license ) {
		$discount = $license->get_renewal_discount();

		$expiry_date = '<strong>' . $this->localize_date( $license->get_expiry_date()->get_value() ) . '</strong>';

		$remaining = sprintf( '<strong>%s</strong>', $license->get_expiry_date()->get_human_time_diff() );
		$renewal_link = ac_helper()->html->link( ac_get_site_utm_url( 'my-account/license', 'renewal' ), __( 'Renew your license', 'codepress-admin-columns' ) );

		if ( $discount ) {
			return sprintf(
				__( "Your Admin Columns Pro license will expire in %s. %s before %s to get a %d%% discount!", 'codepress-admin-columns' ),
				$remaining,
				$renewal_link,
				$expiry_date,
				$discount
			);
		}

		return sprintf(
			__( "Your Admin Columns Pro license will expire in %s. In order get access to new features and receive security updates, please %s before %s.", 'codepress-admin-columns' ),
			$remaining,
			strtolower( $renewal_link ),
			$expiry_date
		);
	}

	private function get_days_remaining( License $license ) {
		if ( ! $license->get_expiry_date()->exists() ) {
			return 0;
		}

		if ( $license->is_expired() ) {
			return 0;
		}

		$days = floor( $license->get_expiry_date()->get_remaining_seconds() / DAY_IN_SECONDS );

		return intval( $days );
	}

}