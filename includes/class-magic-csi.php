<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Magic_CSI {
	/**
	 * Magic_CSI constructor.
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
		define( 'MAGIC_CSI_ABSPATH', plugin_dir_path( MAGIC_CSI_PLUGIN_FILE ) );
		define( 'MAGIC_CSI_URL', plugin_dir_url( dirname( __FILE__ ) ) );
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
		load_plugin_textdomain( 'magic-csi', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Includes classes and functions.
	 */
	public function includes() {
//		require_once MAGIC_CSI_ABSPATH . 'includes/class-magic-csi-install.php';

		/*		if ( is_admin() ) {
					require_once MAGIC_CSI_ABSPATH . 'includes/admin/class-magic-csi-admin.php';
					require_once MAGIC_CSI_ABSPATH . 'includes/admin/class-magic-csi-settings.php';
				} else {
					require_once MAGIC_CSI_ABSPATH . 'includes/class-magic-csi-public.php';
				}*/

		// Utility classes.
		require_once MAGIC_CSI_ABSPATH . 'includes/acf/class-magic-csi-acf.php';
		require_once MAGIC_CSI_ABSPATH . 'includes/class-magic-csi-public.php';
		require_once MAGIC_CSI_ABSPATH . 'includes/woocommerce/class-magic-csi-woocommerce.php';

		// Load magic class.
		require_once MAGIC_CSI_ABSPATH . 'includes/magic/class-magic-magic.php';
		require_once MAGIC_CSI_ABSPATH . 'includes/magic/class-magic-magic-ajax.php';

		/*// API classes.
		require_once MAGIC_CSI_ABSPATH . 'includes/class-magic-csi-rest-api.php';
		require_once MAGIC_CSI_ABSPATH . 'includes/api/v1/class-magic-csi-api-controller.php';*/

		// Template functions.
		require_once MAGIC_CSI_ABSPATH . 'includes/template-functions.php';
	}
}