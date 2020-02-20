<?php

namespace ACP;

use AC;
use AC\Admin\Section\ListScreenMenu;
use AC\Capabilities;
use AC\Controller\ListScreenRequest;
use AC\DefaultColumns;
use AC\ListScreenApiData;
use AC\Request;
use AC\UnitializedListScreens;
use ACP\Admin;
use ACP\Asset;
use ACP\Editing\Admin\CustomFieldEditing;
use ACP\Migrate;
use ACP\Migrate\Admin\ExportSection;
use ACP\Migrate\Admin\ImportSection;
use ACP\Parser;
use ACP\Plugin\NetworkUpdate;
use ACP\Plugin\Updater;
use ACP\Settings;
use ACP\Sorting\Admin\Section\ResetSorting;
use ACP\Sorting\Admin\ShowAllResults;
use ACP\ThirdParty;
use ACP\Updates\AddonInstaller;

/**
 * The Admin Columns Pro plugin class
 * @since 1.0
 */
final class AdminColumnsPro extends AC\Plugin {

	/**
	 * @var AC\Admin
	 */
	private $network_admin;

	/**
	 * @var API
	 */
	private $api;

	/**
	 * @since 3.8
	 */
	private static $instance = null;

	/**
	 * @return AdminColumnsPro
	 * @since 3.8
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->api = new API();
		$this->api
			->set_url( ac_get_site_url() )
			->set_proxy( 'https://api.admincolumns.com' )
			->set_request_meta( array(
				'php_version' => PHP_VERSION,
				'acp_version' => $this->get_version(),
			) );

		$this->localize();

		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 1, 2 );
		add_filter( 'network_admin_plugin_action_links', array( $this, 'add_network_settings_link' ), 1, 2 );

		add_filter( 'ac/show_banner', '__return_false' );

		add_action( 'ac/table_scripts', array( $this, 'table_scripts' ) );

		add_action( 'init', array( $this, 'install' ), 1000 );
		add_action( 'init', array( $this, 'install_network' ), 1000 );

		add_filter( 'ac/view/templates', array( $this, 'templates' ) );

		$list_screen_repository = AC()->get_listscreen_repository();
		$list_screen_repository->register_repository( new ListScreenRepository\ListScreenData( new Parser\DecodeFactory(), new ListScreenApiData() ) );

		$license_key_repository = new LicenseKeyRepository( $this->is_network_active() );
		$license_repository = new LicenseRepository( $this->is_network_active() );

		$location = new Asset\Location\Absolute(
			$this->get_url(),
			$this->get_dir()
		);

		$modules = [
			new Admin\Settings( $list_screen_repository, $location ),
			new Editing\Addon(),
			new Sorting\Addon(),
			new Filtering\Addon(),
			new Export\Addon(),
			new Search\Addon(),
			new ThirdParty\ACF\Addon(),
			new ThirdParty\bbPress\Addon(),
			new ThirdParty\WooCommerce\Addon(),
			new ThirdParty\YoastSeo\Addon(),
			new Table\Switcher( $list_screen_repository, $location ),
			new Table\HorizontalScrolling( $list_screen_repository, $location ),
			new Table\HideSearch(),
			new Table\HideBulkActions(),
			new Table\HideFilters(),
			new ListScreens(),
			new NativeTaxonomies(),
			new IconPicker(),
			new TermQueryInformation(),
			new Migrate\Controller\Export( $list_screen_repository, new Parser\FileEncodeFactory(), new Request() ),
			new Migrate\Controller\Import( $list_screen_repository, new Parser\FileDecodeFactory() ),
			new Controller\AjaxRequestListScreenUsers(),
			new Controller\AjaxRequestListScreenOrder(),
			new Controller\License( $this->api, $license_repository, $license_key_repository, $this->is_network_active() ),
			new Updates( $this->api, $license_key_repository ),
			new AddonInstaller( $this->api, $license_repository, $license_key_repository ),
			new Check\Activation( $this->get_basename(), $license_repository, $license_key_repository ),
			new Check\Expired( $license_repository, $license_key_repository ),
			new Check\Renewal( $license_repository, $license_key_repository ),
		];

		if ( $this->is_beta() ) {
			$modules[] = new Check\Beta( new Admin\Feedback() );
		}

		foreach ( $modules as $module ) {
			if ( $module instanceof AC\Registrable ) {
				$module->register();
			}
		}

		/**
		 * Hook is used for hiding the license form from the settings page
		 *
		 * @param bool $show_license
		 */
		$show_license = (bool) apply_filters( 'acp/display_licence', true );

		// Add admin components
		$admin = AC()->admin();

		// General Settings
		$general = AC\Admin\GeneralSectionFactory::create();

		$general->register_setting( new CustomFieldEditing() )
		        ->register_setting( new ShowAllResults() );

		/** @var AC\Admin\Page\Settings $settings */
		$settings = $admin->get_page( AC\Admin\Page\Settings::NAME );

		if ( $show_license ) {
			$settings->register_section( new Admin\Section\License( $location, $license_repository, $license_key_repository, $this->is_network_active() ) );
		}

		$settings->register_section( new ResetSorting() );

		$tools = new Admin\Page\Tools( $location );

		$tools->add_section( new ExportSection( $list_screen_repository ) )
		      ->add_section( new ImportSection() );

		$admin->register_page( $tools );

		// Network Admin
		$network_admin = new AC\Admin( 'settings.php', 'network_admin_menu', network_admin_url() );

		$page_settings = new AC\Admin\Page\Settings();

		if ( $show_license ) {
			$page_settings->register_section( new Admin\Section\License( $location, $license_repository, $license_key_repository, $this->is_network_active() ) );
		}

		$controller = new ListScreenRequest( new Request(), $list_screen_repository, new AC\Preferences\Network( 'settings' ), true );

		$page_columns = new AC\Admin\Page\Columns(
			$controller,
			new ListScreenMenu( $controller, true ),
			new UnitializedListScreens( new DefaultColumns(), true )
		);

		$network_admin->register_page( $page_columns )
		              ->register_page( $page_settings )
		              ->register();

		$this->network_admin = $network_admin;
	}

	/**
	 * Localize
	 */
	public function localize() {
		load_plugin_textdomain( 'codepress-admin-columns', false, dirname( $this->get_basename() ) . '/languages/' );
		load_plugin_textdomain( 'codepress-admin-columns', false, dirname( $this->get_basename() ) . '/admin-columns/languages/' );
	}

	public function install_network() {
		if ( ! current_user_can( Capabilities::MANAGE ) || ! is_network_admin() ) {
			return;
		}

		$updater = new Updater\Network( $this->get_version() );

		$updater->add_update( new NetworkUpdate\V5000( $updater->get_stored_version() ) )
		        ->parse_updates();
	}

	/**
	 * @return API
	 */
	public function get_api() {
		return $this->api;
	}

	/**
	 * @return string
	 */
	protected function get_file() {
		return ACP_FILE;
	}

	/**
	 * @return string
	 */
	protected function get_version_key() {
		return 'acp_version';
	}

	/**
	 * @since 4.0
	 */
	public function network_admin() {
		return $this->network_admin;
	}

	/**
	 * @param array  $links
	 * @param string $file
	 *
	 * @return array
	 * @see   filter:plugin_action_links
	 * @since 1.0
	 */
	public function add_settings_link( $links, $file ) {
		if ( $file === $this->get_basename() ) {
			array_unshift( $links, sprintf( '<a href="%s">%s</a>', AC()->admin()->get_url( AC\Admin\Page\Columns::NAME ), __( 'Settings' ) ) );
		}

		return $links;
	}

	/**
	 * @param array  $links
	 * @param string $file
	 *
	 * @return array
	 */
	public function add_network_settings_link( $links, $file ) {
		if ( $file === $this->get_basename() ) {
			array_unshift( $links, sprintf( '<a href="%s">%s</a>', $this->network_admin()->get_url( 'settings' ), __( 'Settings' ) ) );
		}

		return $links;
	}

	/**
	 * @return void
	 */
	public function table_scripts() {
		wp_enqueue_style( 'acp-table', $this->get_url() . "assets/core/css/table.css", array(), $this->get_version() );
		wp_enqueue_script( 'acp-table', $this->get_url() . "assets/core/js/table.js", array(), $this->get_version() );
	}

	/**
	 * @return void
	 */
	public function register_global_scripts() {
		wp_register_style( 'ac-jquery-ui', $this->get_url() . 'assets/core/css/ac-jquery-ui.css', array(), $this->get_version() );
	}

	/**
	 * @since 4.0
	 */
	public function editing() {
		_deprecated_function( __METHOD__, '4.5', 'acp_editing()' );

		return acp_editing();
	}

	/**
	 * @since 4.0
	 */
	public function filtering() {
		_deprecated_function( __METHOD__, '4.5', 'acp_filtering()' );

		return acp_filtering();
	}

	/**
	 * @since 4.0
	 */
	public function sorting() {
		_deprecated_function( __METHOD__, '4.5', 'acp_sorting()' );

		return acp_sorting();
	}

	/**
	 * @param AC\ListScreen $list_screen
	 *
	 * @since      4.0
	 * @deprecated 5.0.0
	 */
	public function layouts( AC\ListScreen $list_screen ) {
		_deprecated_function( __METHOD__, '5.0.0' );
	}

	/**
	 * @param array $templates
	 *
	 * @return array
	 */
	public function templates( $templates ) {
		$templates[] = $this->get_dir() . 'templates';

		return $templates;
	}

}