<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://dwinteractive.se
 * @since      2.0.0
 *
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0.0
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/includes
 * @author     Robin Nilsson <robin.nilsson@dwinteractive.se>
 */
class Wpmynewsdesk_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wpmynewsdesk',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
