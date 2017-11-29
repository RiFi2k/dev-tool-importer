<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.45press.com
 * @since             1.0.0
 * @package           Dev_Tool_Importer
 *
 * @wordpress-plugin
 * Plugin Name:       Developer Toolkit Importer
 * Plugin URI:        https://developer-toolkit-wp.io
 * Description:       Import your custom data exactly how you want
 * Version:           1.0.0
 * Author:            Reilly Lowery
 * Author URI:        https://www.45press.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dtk-importer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version
 */
define( 'DTK_IMPORTER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dev-tool-importer-activator.php
 */
function activate_dev_tool_importer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dev-tool-importer-activator.php';
	Dev_Tool_Importer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dev-tool-importer-deactivator.php
 */
function deactivate_dev_tool_importer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dev-tool-importer-deactivator.php';
	Dev_Tool_Importer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dev_tool_importer' );
register_deactivation_hook( __FILE__, 'deactivate_dev_tool_importer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dev-tool-importer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dev_tool_importer() {

	$plugin = new Dev_Tool_Importer();
	$plugin->run();

}
run_dev_tool_importer();
