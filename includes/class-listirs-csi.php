<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Listirs_CSI {
	/**
	 * Listirs_CSI constructor.
	 */
	public function __construct() {
		$this->set_constants();
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Define Constants.
	 */
	private function set_constants() {
		define( 'LISTIRS_CSI_ABSPATH', plugin_dir_path( LISTIRS_CSI_PLUGIN_FILE ) );
		define( 'LISTIRS_CSI_URL', plugin_dir_url( dirname( __FILE__ ) ) );
	}

	/**
	 * Initial plugin setup.
	 */
	private function init_hooks() {

		// Load text domain
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load plugin textdomain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'listirs-csi', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Includes classes and functions.
	 */
	public function includes() {
//		require_once LISTIRS_CSI_ABSPATH . 'includes/class-listirs-csi-install.php';

		/*		if ( is_admin() ) {
					require_once LISTIRS_CSI_ABSPATH . 'includes/admin/class-listirs-csi-admin.php';
					require_once LISTIRS_CSI_ABSPATH . 'includes/admin/class-listirs-csi-settings.php';
				} else {
					require_once LISTIRS_CSI_ABSPATH . 'includes/class-listirs-csi-public.php';
				}*/

		// Utility classes.
		require_once LISTIRS_CSI_ABSPATH . 'includes/acf/class-listirs-csi-acf.php';
		require_once LISTIRS_CSI_ABSPATH . 'includes/class-listirs-csi-public.php';
		require_once LISTIRS_CSI_ABSPATH . 'includes/woocommerce/class-listirs-csi-woocommerce.php';

		// Load magic class.
		require_once LISTIRS_CSI_ABSPATH . 'includes/magic/class-listirs-magic.php';
		require_once LISTIRS_CSI_ABSPATH . 'includes/magic/class-listirs-magic-ajax.php';

		/*// API classes.
		require_once LISTIRS_CSI_ABSPATH . 'includes/class-listirs-csi-rest-api.php';
		require_once LISTIRS_CSI_ABSPATH . 'includes/api/v1/class-listirs-csi-api-controller.php';*/

		// Template functions.
		require_once LISTIRS_CSI_ABSPATH . 'includes/template-functions.php';
	}
}