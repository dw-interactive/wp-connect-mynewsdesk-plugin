<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://dwinteractive.se
 * @since             2.0.0
 * @package           Wpmynewsdesk
 *
 * @wordpress-plugin
 * Plugin Name:       Mynewsdesk
 * Plugin URI:        https://dwinteractive.se
 * Description:       This fetches all types of media that Mynewsdesk has to offer into various formats.
 * Version:           2.0.0
 * Author:            Robin Nilsson
 * Author URI:        https://dwinteractive.se
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpmynewsdesk
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpmynewsdesk-activator.php
 */
function activate_wpmynewsdesk() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpmynewsdesk-activator.php';
	Wpmynewsdesk_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpmynewsdesk-deactivator.php
 */
function deactivate_wpmynewsdesk() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpmynewsdesk-deactivator.php';
	Wpmynewsdesk_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpmynewsdesk' );
register_deactivation_hook( __FILE__, 'deactivate_wpmynewsdesk' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpmynewsdesk.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_wpmynewsdesk() {

	$plugin = new Wpmynewsdesk();
	$plugin->run();

}
run_wpmynewsdesk();
