<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.45press.com
 * @since      1.0.0
 *
 * @package    Dev_Tool_Importer
 * @subpackage Dev_Tool_Importer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dev_Tool_Importer
 * @subpackage Dev_Tool_Importer/admin
 * @author     Reilly Lowery <r.lowery@45press.com>
 */
class Dev_Tool_Importer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, DTK_IMPORTER_URL . 'admin/css/dtk-importer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script( $this->plugin_name, DTK_IMPORTER_URL . 'admin/js/dtk-importer-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the plugins settings.
	 *
	 * @since    1.0.0
	 */
	public function settings_init() {

		add_settings_section(
			'dtk_importer_setup',
			'Set CSV Location',
			array( $this, 'importer_setup_content' ),
			'dtk-importer-admin'
		);

		register_setting( 'dtk-importer-admin', 'dtk_importer_csv' );

		add_settings_field(
			'dtk_importer_csv',
			__( 'Pill', 'wporg' ),
			array( $this, 'importer_csv_callback' ),
			'dtk-importer-admin',
			'dtk_importer_setup',
			[
				'label_for' => 'wporg_field_pill',
				'class' => 'wporg_row',
				'wporg_custom_data' => 'custom',
			]
		);

	}

	/**
	 * Callback for importer csv.
	 *
	 * @since    1.0.0
	 */
	public function importer_csv_callback() {

	}

	/**
	 * Register settings section callback.
	 *
	 * @since    1.0.0
	 */
	public function importer_setup_content() {

		$setting = get_option( 'dtk_importer_csv' );
		?>
		<input type="text" name="dtk_importer_csv" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
		<?php

	}

	/**
	 * Add our plugin page to the Tools menu.
	 *
	 * @since    1.0.0
	 */
	public function importer_page() {

		add_management_page( 'Developers Toolkit Importer', 'DTK Importer', 'manage_options', 'dtk-importer-admin', array( $this, 'importer_content' ) );

	}

	/**
	 * Add content to our plugin page.
	 *
	 * @since    1.0.0
	 */
	public function importer_content() {
settings_fields( 'dtk_importer_csv' );
do_settings_sections( 'dtk-importer-admin' );
submit_button( 'Save Settings' );
		//include DTK_IMPORTER_PATH . 'admin/partials/dtk-importer-page.php';

	}

	/**
	 * Main AJAX function for handling importing data chunks
	 *
	 * @since    1.0.0
	 */
	public function import_chunk() {

		$data = sanitize_text_field( $_POST['data'] );
		parse_str( $data, $data_array );
		$security = $data_array['dtk_importer_security'];



		$option_name  = 'dtk_importer_csv';
		$option_value = get_option( $option_name );

		$file = new SplFileObject( $option_value );
		$file->setFlags( SplFileObject::READ_CSV );
		$file->setFlags( SplFileObject::READ_AHEAD );
		$file->setFlags( SplFileObject::SKIP_EMPTY );
		$file->setFlags( SplFileObject::DROP_NEW_LINE );

		$whole_shabang = array();

		$i = -1;
		while ( ! $file->eof() ) {
			$array = $file->fgetcsv();
			if ( ! empty( $array[1] ) ) {
				$i++;
				if ( 0 !== $i ) {
					$whole_shabang[ $i ] = $array;
				}
			}
		}

		$total = $_POST['total'];
		$size  = $_POST['size'];

		echo '<h2 style="text-align:center">Proccessing chunk <span style="color:green">' . esc_html( $size ) . '</span> of ' . esc_html( $total ) . '</h2>';

		die();

	}

	/**
	 * Listen for our POST data
	 *
	 * @since    1.0.0
	 */
	public function post_listener() {

		if ( isset( $_POST['dtk_importer_csv'] ) ) {

			$url         = $_POST['dtk_importer_csv'];
			$option_name = 'dtk_importer_csv';


			update_option( $option_name, $url );
		}

	}

}
