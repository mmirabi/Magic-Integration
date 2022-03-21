<?php
/**
 * Plugin Name: Magic
 * Plugin URI: https://magicrugs.com
 * Description: A Custom integration dedicate plugin
 * Version: 1.3.0
 * Author: Mehdi Mirabi
 * Author URI: https://mehdimirabi.com
 * Text Domain: Mehdi Mirabi
 * Domain Path: /languages
 */

// Define LISTIRS_CSI_PLUGIN_FILE.
if ( ! defined( 'LISTIRS_CSI_PLUGIN_FILE' ) ) {
	define( 'LISTIRS_CSI_PLUGIN_FILE', __FILE__ );
}

/**
 * Check get_plugin_data function exist
 */
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}


// Get plugin Data.
$plugin_data = get_plugin_data( LISTIRS_CSI_PLUGIN_FILE );

// Set another useful Plugin defines.
define( 'LISTIRS_CSI_PLUGIN_VERSION', $plugin_data['Version'] );

/**
 * Load main class.
 */
require 'includes/class-listirs-csi.php';

/**
 * Main instance of plugin.
 */
new Listirs_CSI();