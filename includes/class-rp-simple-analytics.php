<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.refinedpractice.com
 * @since      1.0.0
 *
 * @package    RP_Simple_Analytics
 * @subpackage RP_Simple_Analytics/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    RP_Simple_Analytics
 * @subpackage RP_Simple_Analytics/includes
 * @author     Refined Practice <plugins@refinedpractice.com>
 */
class RP_Simple_Analytics {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      RP_Simple_Analytics_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $rp_simple_analytics    The string used to uniquely identify this plugin.
	 */
	protected $rp_simple_analytics;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;


	/**
	 * The capability at which to block the script from loading, e.g. publish_pages.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $block_at_capability    The capability at which to block the script from loading.
	 */
	protected $block_at_capability;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'RP_SIMPLE_ANALYTICS_VERSION' ) ) {
			$this->version = RP_SIMPLE_ANALYTICS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->rp_simple_analytics = 'rp-simple-analytics';
		if( ! get_option( 'rpsa_block_at_capability' ) ) {
			add_option( 'rpsa_block_at_capability', 'publish_pages' );
		}
		$this->block_at_capability = get_option( 'rpsa_block_at_capability' );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$this->register_cli_commands();
		}
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - RP_Simple_Analytics_Loader. Orchestrates the hooks of the plugin.
	 * - RP_Simple_Analytics_i18n. Defines internationalization functionality.
	 * - RP_Simple_Analytics_Admin. Defines all hooks for the admin area.
	 * - RP_Simple_Analytics_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rp-simple-analytics-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rp-simple-analytics-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-rp-simple-analytics-admin.php';

		/**
		 * The class responsible for defining WP-CLI commands
		 */
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-rp-simple-analytics-cli.php';
		}

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-rp-simple-analytics-public.php';

		$this->loader = new RP_Simple_Analytics_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the RP_Simple_Analytics_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new RP_Simple_Analytics_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new RP_Simple_Analytics_Admin( $this->get_rp_simple_analytics(), $this->get_version(), $this->get_block_at_capability() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_settings_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'define_settings' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'privacy_policy' );
		if ( get_option( 'rpsa_dashboard_widget' ) ) {
			$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'add_dashboard_widget' );
		}
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'display_activation_notice' );

		$main_plugin_file = strtok( plugin_basename(__FILE__), '/' ) . '/' . $this->rp_simple_analytics . '.php';
		$this->loader->add_filter( 'plugin_action_links_' . $main_plugin_file, $plugin_admin, 'add_action_links' );

	}

	/**
	 * Register WP-CLI commands
	 *
	 * @since    1.1.0
	 * @access   private
	 */
	private function register_cli_commands() {

		$plugin_cli = new RP_Simple_Analytics_Cli( $this->get_rp_simple_analytics(), $this->get_version(), $this->get_block_at_capability() );
		WP_CLI::add_command( 'rpsa', $plugin_cli );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new RP_Simple_Analytics_Public( $this->get_rp_simple_analytics(), $this->get_version(), $this->get_block_at_capability() );

		if ( get_option( 'rpsa_events' ) ) {
			$this->loader->add_action( 'wp_print_scripts', $plugin_public, 'add_event_js', 999 );
		}
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'script_loader_tag', $plugin_public, 'js_tag_additions', 10, 2 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_rp_simple_analytics() {
		return $this->rp_simple_analytics;
	}

	/**
	 * The capability at which to block the script from loading, e.g. publish_pages.
	 *
	 * @since     1.0.0
	 * @return    string    The capability at which to block the script from loading.
	 */
	public function get_block_at_capability() {
		return $this->block_at_capability;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    RP_Simple_Analytics_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
