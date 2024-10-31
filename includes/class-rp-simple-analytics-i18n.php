<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.refinedpractice.com
 * @since      1.0.0
 *
 * @package    RP_Simple_Analytics
 * @subpackage RP_Simple_Analytics/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    RP_Simple_Analytics
 * @subpackage RP_Simple_Analytics/includes
 * @author     Refined Practice <plugins@refinedpractice.com>
 */
class RP_Simple_Analytics_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'rp-simple-analytics',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
