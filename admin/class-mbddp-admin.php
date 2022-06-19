<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://niloyrudra.com/
 * @since      1.0.0
 *
 * @package    Mbddp
 * @subpackage Mbddp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mbddp
 * @subpackage Mbddp/admin
 * @author     Niloy Rudra <niloyrudra4249@gmail.com>
 */
class Mbddp_Admin {

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
		$this->version = $version;

		// Initiating Admin Page
		$this->admin_pages();

	}

	/**
	 * Setup menus
	 *
	 * Source: http://stackoverflow.com/a/23002306
	 */
	public function admin_pages() {
		add_action( 'admin_menu', array( $this, 'register_admin_menu_pages' ) );
	}

	/**
	 * Refistering Admin Menu Page Callbacks
	 */
	public function register_admin_menu_pages() {
		// Dashboard
		add_menu_page(
			__( "Muluma Bed Delivery Date Settings", "mbddp" ),
			__( "MBDD Settings", "mbddp" ),
			'manage_options',
			MBDDP_PLUGIN_SLUG,
			array( $this, "admin_dashboard" ),
			'dashicons-calendar',
			6
		);

		add_submenu_page(
			MBDDP_PLUGIN_SLUG,
			__( "MBDD Settings", "mbddp" ),
			__( "Settings", "mbddp" ),
			'manage_options',
			MBDDP_PLUGIN_SLUG,
			array( $this, "admin_dashboard" ),
		);
	}

	/**
	 * Page Generation Callbacks
	 */
	public function admin_dashboard() {
		require_once MBDDP_PLUGIN_PATH . "admin/partials/mbddp-admin-display.php";
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mbddp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mbddp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mbddp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mbddp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mbddp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mbddp-admin.js', array( 'jquery' ), $this->version, false );

	}

}
