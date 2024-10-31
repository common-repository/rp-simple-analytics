<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.refinedpractice.com
 * @since             1.0.0
 * @package           RP_Simple_Analytics
 *
 * @wordpress-plugin
 * Plugin Name:       Refined Practice - Simple Analytics Integration
 * Plugin URI:        https://www.refinedpractice.com/wordpress-plugins/
 * Description:       Embeds the Simple Analytics privacy friendly analytics service (see https://simpleanalytics.com/)
 * Version:           1.2.0
 * Author:            Refined Practice
 * Author URI:        https://www.refinedpractice.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rp-simple-analytics
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RP_SIMPLE_ANALYTICS_VERSION', '1.2.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rp-simple-analytics-activator.php
 */
function activate_rp_simple_analytics() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rp-simple-analytics-activator.php';
	RP_Simple_Analytics_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rp-simple-analytics-deactivator.php
 */
function deactivate_rp_simple_analytics() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rp-simple-analytics-deactivator.php';
	RP_Simple_Analytics_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rp_simple_analytics' );
register_deactivation_hook( __FILE__, 'deactivate_rp_simple_analytics' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rp-simple-analytics.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rp_simple_analytics() {

	$plugin = new RP_Simple_Analytics();
	$plugin->run();

}
run_rp_simple_analytics();
