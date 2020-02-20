<?php

namespace ACP\Admin\Section;

use AC;
use ACP;
use ACP\Asset;
use ACP\Controller;
use ACP\LicenseKeyRepository;
use ACP\LicenseRepository;
use ACP\Type\License\Key;
use DateTime;

class License extends AC\Admin\Section\Custom {

	/** @var ACP\Asset\Location */
	private $location;

	/** @var LicenseRepository */
	private $license_repository;

	/**
	 * @var Key
	 */
	private $license_key_repository;

	/**
	 * @var bool
	 */
	private $is_network_activated;

	public function __construct( Asset\Location $location, LicenseRepository $license_repository, LicenseKeyRepository $license_key_repository, $is_network_activated = false ) {
		$this->location = $location;
		$this->license_repository = $license_repository;
		$this->license_key_repository = $license_key_repository;
		$this->is_network_activated = (bool) $is_network_activated;

		parent::__construct( 'updates', __( 'Updates', 'codepress-admin-columns' ), __( 'Enter your license code to receive automatic updates.', 'codepress-admin-columns' ) );
	}

	public function register() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function enqueue_scripts() {
		$style = new Asset\Style( 'acp-license-manager', $this->location->with_suffix( 'assets/core/css/license-manager.css' ) );
		$style->enqueue();

		$script = new Asset\Script( 'acp-license-manager', $this->location->with_suffix( 'assets/core/js/license-manager.js' ) );
		$script->enqueue();
	}

	/**
	 * @return string|null
	 */
	private function get_defined_key() {
		return defined( 'ACP_LICENCE' ) && ACP_LICENCE ? ACP_LICENCE : null;
	}

	private function submit_buttons() { ?>
		<span class="buttons">
			<?php if ( ! $this->get_defined_key() ): ?>
				<button type="submit" class="button" name="action" value="<?= Controller\License::DEACTIVATE_ACTION; ?>"><?php _e( 'Deactivate license', 'codepress-admin-columns' ); ?></button>
			<?php endif; ?>
			<button type="submit" class="button" name="action" value="<?= Controller\License::UPDATE_ACTION; ?>"><?php _e( 'Check license', 'codepress-admin-columns' ); ?></button>
		</span>
		<?php
	}

	private function attr_title_license_key( Key $key ) {
		printf( ' title="%s"', esc_attr( substr( $key->get_value(), 0, 7 ) ) );
	}

	public function display_fields() {
		$license = null;

		$license_key = $this->license_key_repository->find();

		if ( $license_key ) {
			$license = $this->license_repository->find( $license_key );
		}

		// When the plugin is network activated, the license is managed globally
		if ( $this->is_network_activated && ! is_network_admin() ) {
			?>
			<p class="description">
				<?php
				$page = __( 'network settings page', 'codepress-admin-columns' );

				if ( current_user_can( 'manage_network_options' ) ) {
					$page = ac_helper()->html->link( network_admin_url( 'settings.php?page=codepress-admin-columns&tab=settings' ), $page );
				}

				printf( __( 'The license can be managed on the %s.', 'codepress-admin-columns' ), $page );
				?>
			</p>
			<?php
		} else { ?>

			<form id="licence_activation" action="" method="post">
				<?php wp_nonce_field( 'acp-license', '_acnonce' ); ?>

				<?php if ( $license ) : ?>

					<?php if ( $license->is_expired() ) : ?>

						<p<?php $this->attr_title_license_key( $license->get_key() ); ?>>
							<span class="dashicons dashicons-no-alt"></span>
							<?php _e( 'Automatic updates are disabled.', 'codepress-admin-columns' ); ?>
							<?php $this->submit_buttons(); ?>
						</p>
						<p class="description">
							<?php printf( __( 'License has expired on %s', 'codepress-admin-columns' ), '<strong>' . $this->localize_date( $license->get_expiry_date()->get_value() ) . '</strong>' ); ?>
						</p>

					<?php elseif ( $license->is_cancelled() ) : ?>

						<p<?php $this->attr_title_license_key( $license->get_key() ); ?>>
							<span class="dashicons dashicons-no-alt"></span>
							<?php _e( 'Automatic updates are disabled.', 'codepress-admin-columns' ); ?>
							<?php $this->submit_buttons(); ?>
						</p>

						<p class="description">
							<?php _e( 'Your subscription is cancelled.', 'codepress-admin-columns' ); ?>
						</p>

					<?php else : ?>

						<p<?php $this->attr_title_license_key( $license->get_key() ); ?>>
							<span class="dashicons dashicons-yes"></span>
							<?php _e( 'Automatic updates are enabled.', 'codepress-admin-columns' ); ?>
							<?php $this->submit_buttons(); ?>
						</p>
						<p class="description">
							<?php if ( ! $license->is_lifetime() && ! $license->is_auto_renewal() && $license->get_expiry_date()->exists() ) : ?>
								<?php printf( __( 'License is valid until %s', 'codepress-admin-columns' ), '<strong>' . $this->localize_date( $license->get_expiry_date()->get_value() ) . '</strong>' ); ?>
							<?php endif; ?>
						</p>

					<?php endif; ?>

					<?php if ( $this->get_defined_key() ): ?>
						<p class="description"<?php $this->attr_title_license_key( $license->get_key() ); ?>>
							<?php _e( 'License key is defined in code.', 'codepress-admin-columns' ); ?>
						</p>
					<?php endif; ?>

				<?php elseif ( $this->get_defined_key() ) : ?>

					<input type="hidden" name="license" value="<?= $this->get_defined_key(); ?>">
					<button type="submit" class="button" name="action" value="<?= Controller\License::ACTIVATE_ACTION; ?>"><?php _e( 'Activate license', 'codepress-admin-columns' ); ?></button>
					<p class="description">
						<span class="dashicons dashicons-info"></span>
						<?php _e( 'License key is defined in code but not yet activated.', 'codepress-admin-columns' ); ?>
					</p>

				<?php else : ?>

					<?php $key = $this->license_key_repository->find(); ?>

					<input type="text" value="<?= $key ? $key->get_value() : null; ?>" name="license" size="40" placeholder="<?php echo esc_attr( __( 'Enter your license code', 'codepress-admin-columns' ) ); ?>">
					<button type="submit" class="button" name="action" value="<?= Controller\License::ACTIVATE_ACTION; ?>"><?php _e( 'Update license', 'codepress-admin-columns' ); ?></button>
					<p class="description">
						<?php printf( __( 'You can find your license key on your %s.', 'codepress-admin-columns' ), '<a href="' . ac_get_site_utm_url( 'my-account/license', 'license-activation' ) . '" target="_blank">' . __( 'account page', 'codepress-admin-columns' ) . '</a>' ); ?>
					</p>
				<?php endif; ?>

			</form>
			<?php
		}
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

		return ac_format_date( $format, $date->getTimestamp(), $date->getTimezone() );
	}

}