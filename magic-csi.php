<? php
/**
 * Plugin Name: MagicRugs Pro (Size)
 * Plugin URI: https://magicrugs.com
 * Description: A Custom integration dedicate plugin
 * Version: 1.2.0
 * Author: Mehdi Mirabi
 * Author URI: https://mehdimirabi.com
 * Text Domain: Mehdi Mirabi
 * Domain Path: /languages
 */

 // Define MAGIC_CSI_PLUGIN_FILE.
 if ( ! defined('MAGIC_CSI_PLUGIN_FILE')) {
        define('MAGIC_CSI_PLUGIN_FILE', __FILE__ );
 }

 // Get help data.
 // $plugin_data = get_plugin_data( MAGIC_CSI_PLUGIN_FILE )

 // set another useful plugin defines.
 define('MAGIC_CSI_PLUGIN_VERSION', $plugin_data['Version']);

 // Load main class.
 require 'indludes/class-magic-csi.php';

 // Main instance og plugin.
 new Magic_CSI();