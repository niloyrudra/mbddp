<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://niloyrudra.com/
 * @since             1.0.0
 * @package           Mbddp
 *
 * @wordpress-plugin
 * Plugin Name:       Muluma Bed Delivery Date Plugin
 * Plugin URI:        https://muluma-bed.ch
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Niloy Rudra
 * Author URI:        https://niloyrudra.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mbddp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    add_action( "admin_notices", "showing_admin_notice" );
    function showing_admin_notice() {
        echo "<div class='error'>";
        echo "<p>";
        echo "WC Delivery date Provider plugin neeeds WooCommerce to get activated.";
        echo "</p>";
        echo "</div>";
    }
    // exit;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MBDDP_VERSION', '1.0.0' );

/**
 * Utility Constances
 */
define( "MBDDP_PLUGIN_SLUG", "mbddp" );
define( "MBDDP_PLUGIN_SETTINGS_API_GROUP_ID", "mbddp_group_id" );
define( "MBDDP_PLUGIN_PATH", plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mbddp-activator.php
 */
function activate_mbddp() {
	require_once MBDDP_PLUGIN_PATH . 'includes/class-mbddp-activator.php';
	Mbddp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mbddp-deactivator.php
 */
function deactivate_mbddp() {
	require_once MBDDP_PLUGIN_PATH . 'includes/class-mbddp-deactivator.php';
	Mbddp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mbddp' );
register_deactivation_hook( __FILE__, 'deactivate_mbddp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require MBDDP_PLUGIN_PATH . 'includes/class-mbddp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mbddp() {

	$plugin = new Mbddp();
	$plugin->run();

}
run_mbddp();
// if( class_exists( "WC" ) || class_exists( "WC_Product" ) ) run_mbddp();
