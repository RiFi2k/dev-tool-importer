<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.45press.com
 * @since      1.0.0
 *
 * @package    Dev_Tool_Importer
 * @subpackage Dev_Tool_Importer/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Dev_Tool_Importer
 * @subpackage Dev_Tool_Importer/includes
 * @author     Reilly Lowery <r.lowery@45press.com>
 */
class Dev_Tool_Importer_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'dev-tool-importer',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
