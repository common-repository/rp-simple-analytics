<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.refinedpractice.com
 * @since      1.0.0
 *
 * @package    RP_Simple_Analytics
 * @subpackage RP_Simple_Analytics/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    RP_Simple_Analytics
 * @subpackage RP_Simple_Analytics/includes
 * @author     Refined Practice <plugins@refinedpractice.com>
 */
class RP_Simple_Analytics_Activator {

	/**
	 * Adds some default option values on plugin activation.
	 *
	 *
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( ! get_option( 'rpsa_events' ) ) {
			add_option( 'rpsa_events', false );
		}
		if ( ! get_option( 'rpsa_events_jquery' ) ) {
			add_option( 'rpsa_events_jquery', false );
		}
		if ( ! get_option( 'rpsa_dashboard_widget' ) ) {
			add_option( 'rpsa_dashboard_widget', '1' );
		}
		if ( ! get_option( 'rpsa_events_extra_js' ) ) {
			add_option( 'rpsa_events_extra_js', false );
		}
		if ( ! get_option( 'rpsa_block_logged_in_users' ) ) {
			add_option( 'rpsa_block_logged_in_users', '1' );
		}
		if ( ! get_option( 'rpsa_block_at_capability' ) ) {
			add_option( 'rpsa_block_at_capability', 'publish_pages' );
		}

		// See https://stackoverflow.com/questions/38233751/show-message-after-activating-wordpress-plugin
		set_transient( 'rpsa_activation_notice', true, 360 );

	}

}
