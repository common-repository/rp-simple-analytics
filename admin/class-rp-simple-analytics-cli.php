<?php

/**
 * The WP-CLI functionality of the plugin.
 *
 * @link	   https://www.refinedpractice.com
 * @since	  1.1.0
 *
 * @package	RP_Simple_Analytics
 * @subpackage RP_Simple_Analytics/admin
 */

/**
 * The WP-CLI functionality of the plugin.
 * *
 * @package	RP_Simple_Analytics
 * @subpackage RP_Simple_Analytics/admin
 * @author	 Refined Practice <plugins@refinedpractice.com>
 */
class RP_Simple_Analytics_Cli {

	/**
	* The ID of this plugin.
	*
	* @since	1.1.0
	* @access   private
	* @var	  string	$rp_simple_analytics	The ID of this plugin.
	*/
	private $rp_simple_analytics;

	/**
	* The version of this plugin.
	*
	* @since	1.1.0
	* @access   private
	* @var	  string	$version	The current version of this plugin.
	*/
	private $version;

	/**
	* The capability at which to block the script from loading, e.g. publish_pages.
	*
	* @since	1.1.0
	* @access   private
	* @var	  string	$version	The current version of this plugin.
	*/
	private $block_at_capability;

	/**
	* Initialize the class and set its properties.
	*
	* @since	1.1.0
	* @param	  string	$rp_simple_analytics	   The name of this plugin.
	* @param	  string	$version	The version of this plugin.
	*/
	public function __construct( $rp_simple_analytics, $version, $block_at_capability ) {

		$this->rp_simple_analytics = $rp_simple_analytics;
		$this->version = $version;
		$this->block_at_capability = $block_at_capability;

	}


	/**
	 * Prints Refined Practice Simple Analytics Integration plugin version number.
	 *
	 * ## EXAMPLES
	 *
	 *     # Display RP Simple Analytics version.
	 *     $ wp rpsa version
	 *     1.1.0
	 */
	public function version() {
		WP_CLI::log( $this->version );
	}

	/**
	 * Gets/sets whether or not logged-in users are blocked from Simple Analytics
	 *
	 *
     * ## OPTIONS
     *
     * [<on|off>]
     * : Turn on or off logged-in user blocking
	 * 
	 * ## EXAMPLES
	 *
	 *     # Display if logged-in users are blocked from Simple Analytics
	 *	   wp rpsa user-block
	 *
	 *     # Enable logged-in user block
	 *	   wp rpsa user-block on
	 *
	 *     # Disable logged-in user block
	 *	   wp rpsa user-block off
	 *
	 * @when after_wp_load
	 *
	  * @subcommand user-block
	 */
	public function user_block( $args ) {
		$type = 'line';
		list( $toggle ) = $args;
		if( 'on' === $toggle ) {
			update_option( 'rpsa_block_logged_in_users', '1' );	
			$type = 'success';
		}
		if( 'off' === $toggle ) {
			update_option( 'rpsa_block_logged_in_users', false );
			$type = 'success';
		}
		$user_block = get_option( 'rpsa_block_logged_in_users' );
		if( $user_block ) {
			WP_CLI::$type( 'Logged-in user block is enabled' );
		} else {
			WP_CLI::$type( 'Logged-in user block is disabled' );
		}
		
	}

	/**
	 * Gets/sets capability used to determine which users to block from analytics
	 *
	 *
     * ## OPTIONS
     *
     * [<capability>]
     * : Capability to check for when blocking logged-in users. Must be a valid WordPress capability
	 * 
	 * ## EXAMPLES
	 *
	 *     # Display check for when blocking logged-in users
	 *	   wp rpsa block-at-capability
	 *
	 *     # Set capability for blocking logged-in users to publish_posts
	 *	   wp rpsa block-at-capability publish_posts
	 *
	 * @when after_wp_load
	 *
	  * @subcommand block-at-capability
	 */
	public function block_at_capability( $args ) {
		list( $capability ) = $args;

		if( $capability ) {
			$roles = wp_roles( );
			$capability_values = array( );
			foreach( $roles->roles as $role ) {
				$capability_values = array_merge( $capability_values, $role['capabilities'] );
			}
			$capabilities = array_keys( $capability_values );
			if ( ! in_array( $capability, $capabilities ) ) {
				WP_CLI::error( 'No such capability: ' . $capability );
			} else 
			{
				update_option( 'rpsa_block_at_capability', $capability );
				$block_at_capability = get_option( 'rpsa_block_at_capability' );
				WP_CLI::success( 'Block-at-capability set to ' . $block_at_capability );
				return;
			}
		}
		
		$block_at_capability = get_option( 'rpsa_block_at_capability' );
		WP_CLI::log( 'Block-at-capability set to ' . $block_at_capability );
		
	}

	/**
	 * Gets/sets whether or not event tracking script is enabled
	 *
	 *
     * ## OPTIONS
     *
     * [<on|off>]
     * : Turn on or off event tracking script
	 * 
	 * ## EXAMPLES
	 *
	 *     # Display if event tracking script is enabled
	 *	   wp rpsa event-tracking
	 *
	 *     # Enable event tracking
	 *	   wp rpsa event-tracking on
	 *
	 *     # Disable event tracking
	 *	   wp rpsa event-tracking off
	 *
	 * @when after_wp_load
	 *
	  * @subcommand event-tracking
	 */
	public function event_tracking( $args ) {
		$type = 'line';
		list( $toggle ) = $args;
		if( 'on' === $toggle ) {
			update_option( 'rpsa_events', '1' );
			$type = 'success';	
		}
		if( 'off' === $toggle ) {
			update_option( 'rpsa_events', false );
			$type = 'success';	
		}
		$user_block = get_option( 'rpsa_events' );
		if( $user_block ) {
			WP_CLI::$type( 'Event tracking script is enabled' );
		} else {
			WP_CLI::$type( 'Event tracking script is disabled' );
		}
		
	}

	/**
	 * Gets/sets whether or not jQuery is enqueued
	 *
	 *
     * ## OPTIONS
     *
     * [<on|off>]
     * : Turn on or off jQuery
	 * 
	 * ## EXAMPLES
	 *
	 *     # Display if jQuery is enqueued
	 *	   wp rpsa jquery-enqueue
	 *
	 *     # Enqueue jQuery
	 *	   wp rpsa jquery-enqueue on
	 *
	 *     # Dequeue jQuery
	 *	   wp rpsa jquery-enqueue off
	 *
	 * @when after_wp_load
	 *
	  * @subcommand jquery-enqueue
	 */
	public function jquery_enqueue( $args ) {
		$type = 'line';
		list( $toggle ) = $args;
		if( 'on' === $toggle ) {
			update_option( 'rpsa_events_jquery', '1' );
			$type = 'success';
		}
		if( 'off' === $toggle ) {
			update_option( 'rpsa_events_jquery', false );
			$type = 'success';	
		}
		$user_block = get_option( 'rpsa_events_jquery' );
		if( $user_block ) {
			WP_CLI::$type( 'jQuery is enqueued' );
		} else {
			WP_CLI::$type( 'jQuery is dequeued' );
		}
		
	}


	/**
	 * Gets/sets additional event tracking javascipt
	 *
	 *
     * ## OPTIONS
     *
     * [--file=<file>]
     * : path to the javascript file you want to add
     *
     * [--js=<js>]
     * : javascript
	 * 
	 * ## EXAMPLES
	 *
	 *     # Display current javascript
	 *	   wp rpsa event-js
	 *
	 *     # include javascript from file (do not include <script> tags)
	 *	   wp rpsa event-js --file='/path/to/plugins/rp-simple-analytics/example-js/automated-events.js'
	 *
	 *     # include javascript from STDIN (do not include <script> tags)
	 *	   wp rpsa event-js --js='console.log("Hello!")'
	 *
	 * @when after_wp_load
	 *
	 * @subcommand event-js
	 */
	public function event_js( $args, $assoc_args ) {

		$file = trim( $assoc_args['file'] );
		$js = trim( $assoc_args['js'] );

		if( $file && $js ) {
			WP_CLI::warning("Both a file path and javascript on STDIN specified, using file path only");
		}

		if( $file ) {
			WP_CLI::log("Updating from file path: " . $file);
			if ( ! is_readable( $file ) ) {
				WP_CLI::error( sprintf( 'File missing or not readable: %s', $file ) );
			}
			$file_contents = file_get_contents( $file );
			if ( ! $file_contents ) {
				WP_CLI::error( sprintf( 'File is empty or not readable: %s', $file ) );
			}
			update_option( 'rpsa_events_extra_js', $file_contents );
			$event_js = get_option( 'rpsa_events_extra_js' );
			WP_CLI::success( "Additional event tracking javascript set to:\n" . $event_js );
			return;			
		} elseif( $js ) {
			update_option( 'rpsa_events_extra_js', $js );
			$event_js = get_option( 'rpsa_events_extra_js' );
			WP_CLI::success( "Additional event tracking javascript set to:\n" . $event_js );
			return;
		}
	
		$event_js = get_option( 'rpsa_events_extra_js' );
		WP_CLI::log( "Additional event tracking javascript:\n" . $event_js );
		
	}	

	/**
	 * Gets/sets whether or not the dashboard widget is enabled
	 *
	 *
     * ## OPTIONS
     *
     * [<on|off>]
     * : Turn on or off the dashboard widget
	 * 
	 * ## EXAMPLES
	 *
	 *     # Display if the dashboard widget is enabled
	 *	   wp rpsa dashboard-widget
	 *
	 *     # Enable event tracking
	 *	   wp rpsa dashboard-widget on
	 *
	 *     # Disable event tracking
	 *	   wp rpsa dashboard-widget off
	 *
	 * @when after_wp_load
	 *
	  * @subcommand dashboard-widget
	 */
	public function dashboard_widget( $args ) {
		$type = 'line';
		list( $toggle ) = $args;
		if( 'on' === $toggle ) {
			update_option( 'rpsa_dashboard_widget', '1' );
			$type = 'success';
		}
		if( 'off' === $toggle ) {
			update_option( 'rpsa_dashboard_widget', false );
			$type = 'success';
		}
		$user_block = get_option( 'rpsa_dashboard_widget' );
		if( $user_block ) {
			WP_CLI::$type( 'Dashboard widget is enabled' );
		} else {
			WP_CLI::$type( 'Dashboard widget is disabled' );
		}
		
	}



}

