<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.refinedpractice.com
 * @since      1.0.0
 *
 * @package    RP_Simple_Analytics
 * @subpackage RP_Simple_Analytics/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 *
 * @package    RP_Simple_Analytics
 * @subpackage RP_Simple_Analytics/public
 * @author     Refined Practice <plugins@refinedpractice.com>
 */
class RP_Simple_Analytics_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $rp_simple_analytics    The ID of this plugin.
	 */
	private $rp_simple_analytics;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * The capability at which to block the script from loading, e.g. publish_pages.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $block_at_capability;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $rp_simple_analytics       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $rp_simple_analytics, $version, $block_at_capability ) {

		$this->rp_simple_analytics = $rp_simple_analytics;
		$this->version = $version;
		$this->block_at_capability = $block_at_capability;

	}


	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if ( get_option( 'rpsa_events' ) && get_option( 'rpsa_events_jquery' ) ) {
			$dependencies = array( 'jquery' );
		} else {
			$dependencies = array();
		}

		if ( ! ( get_option( 'rpsa_block_logged_in_users' ) && current_user_can( $this->block_at_capability ) ) ) {
			wp_enqueue_script( $this->rp_simple_analytics, 'https://scripts.simpleanalyticscdn.com/latest.js', $dependencies, '86df', true );

		}

		if ( ! ( get_option( 'rpsa_block_logged_in_users' ) && current_user_can( $this->block_at_capability ) ) && trim(get_option( 'rpsa_events_extra_js' ) ) && get_option( 'rpsa_events' ) ) {
			wp_add_inline_script( $this->rp_simple_analytics, get_option('rpsa_events_extra_js') );
		}


	}

	/**
	 * Output event inline js in head.
	 *
	 * @since    1.0.0
	 */
	public function add_event_js() {
		if ( ! ( get_option( 'rpsa_block_logged_in_users' ) && current_user_can( $this->block_at_capability ) ) ) {
			echo "<script>window.sa_event=window.sa_event||function(event){sa_event.q?sa_event.q.push(event):sa_event.q=[event]}</script>";
		}
	}

	/**
	 * Make required alterations to script tag output.
	 *
	 * @since    1.0.0
	 */
	public function js_tag_additions( $tag, $handle ) {

		/**
		 * Adds async and defer attributes to chosen scripts plus noscript alternatives etc.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in RP_Simple_Analytics_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The RP_Simple_Analytics_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$scripts_to_async = array( 'rp-simple-analytics' );
		$noscript_script = 'rp-simple-analytics';

		if( in_array( $handle, $scripts_to_async ) ) {
			$tag = str_replace( ' src', ' async defer src', $tag );
		}

		if( $handle === $noscript_script ) {
			$tag .= '<noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif" alt="" referrerpolicy="no-referrer-when-downgrade" /></noscript>' . "\n";
		}


		return $tag;
	}

}
