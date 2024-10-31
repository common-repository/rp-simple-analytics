<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.refinedpractice.com
 * @since      1.0.0
 *
 * @package    RP_Simple_Analytics
 * @subpackage RP_Simple_Analytics/admin
 */

/**
 * The admin-specific functionality of the plugin.
 * *
 * @package    RP_Simple_Analytics
 * @subpackage RP_Simple_Analytics/admin
 * @author     Refined Practice <plugins@refinedpractice.com>
 */
class RP_Simple_Analytics_Admin {

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
	* @param      string    $rp_simple_analytics       The name of this plugin.
	* @param      string    $version    The version of this plugin.
	*/
	public function __construct( $rp_simple_analytics, $version, $block_at_capability ) {

		$this->rp_simple_analytics = $rp_simple_analytics;
		$this->version = $version;
		$this->block_at_capability = $block_at_capability;

	}

	/**
	* Register the JavaScript for the admin area.
	*
	* @since    1.0.0
	*/
	public function enqueue_scripts( $hook ) {

		if ( 'index.php' === $hook ) {
			wp_enqueue_script( $this->rp_simple_analytics . "_embed", 'https://scripts.simpleanalyticscdn.com/embed.js', array( ), 'd2e8', true );
		}
		if ( 'settings_page_rp-simple-analytics' === $hook ) {
			wp_enqueue_code_editor( array( 'type' => 'text/javascript' ) );
			wp_add_inline_script( 'code-editor', 
				"
				jQuery( document ).ready( function( $ ) {
					wp.codeEditor.initialize( $( '#rpsa-events-extra-js' ) );
				} );
			"
			);
		}

	}


	/**
	* Add settings page
	*
	* @since    1.0.0
	*/
	public function add_settings_page( ) {
		add_options_page(
			__( 'Refined Practice - Simple Analytics Integration', 'rp-simple-analytics' ), 
			__( 'RP Simple Analytics', 'rp-simple-analytics' ), 
			'manage_options', 
			'rp-simple-analytics', 
			array(
				$this, 
				'settings_page_content'
			)
		);
	}


	/**
	* Privacy Policy content
	*
	* @since    1.0.0
	*/
	public function privacy_policy( ) {

		/* translators: %s: Link to Simple Analytics info on your privacy policy */
		$content = '<p><i>' . sprintf( __( 'As Simple Analytics does not place cookies or collect personally identifiable information there is no requirement to add anything to your privacy policy. However, you may want to include the following text to let your users know that you are using Simple Analytics. <a href="%s" target="_blank">Find out more about Simple Analytics privacy</a>.', 'rp-simple-analytics' ), 'https://docs.simpleanalytics.com/your-privacy-policy' ) . '</i></p>'
			. '<p><strong>' . __( 'Suggested Text:', 'rp-simple-analytics' ) . '</strong></p>'
			. sprintf(
					/* translators: 1: Link to Simple Analytics homepage 2: Link to Simple Analytics "What We Collect" page */
					__( 'To get critical information about the behavior of our visitors, we use <a href="%1$s" target="_blank">Simple Analytics</a>. This analytics software gives us insight about our visitors only in general, but not about individuals per say, as it does not track visitors and does not store any personal identifiable information. <a href="%2$s" target="_blank">Go to their documentation</a> to find out what Simple Analytics collects ( and most importantly what they do not ).', 'rp-simple-analytics' ), 
				 'https://simpleanalytics.com', 
				 'https://docs.simpleanalytics.com/what-we-collect'
				);
		wp_add_privacy_policy_content( __( 'Simple Analytics Integration by Refined Practice', 'rp-simple-analytics' ), wp_kses_post( wpautop( $content, false ) ) );
	}


	/**
	* Register Settings
	*
	* @since    1.0.0
	*/
	public function define_settings( ) {
		register_setting( 'rp-simple-analytics', 'rpsa_block_logged_in_users', array(
			'sanitize_callback' => array( $this, 'sanitize_boolean' ), 
			'default' => false, 
		) );
		register_setting( 'rp-simple-analytics', 'rpsa_block_at_capability', array(
			'sanitize_callback' => array( $this, 'sanitize_capability' ), 
			'default' => 'publish_pages', 
		) );
		register_setting( 'rp-simple-analytics', 'rpsa_dashboard_widget', array(
			'sanitize_callback' => array( $this, 'sanitize_boolean' ), 
			'default' => false, 
		) );
		register_setting( 'rp-simple-analytics', 'rpsa_events', array(
			'sanitize_callback' => array( $this, 'sanitize_boolean' ), 
			'default' => false, 
		) );
		register_setting( 'rp-simple-analytics', 'rpsa_events_jquery', array(
			'sanitize_callback' => array( $this, 'sanitize_boolean' ), 
			'default' => false, 
		) );
		register_setting( 'rp-simple-analytics', 'rpsa_events_extra_js' );
		add_settings_section(
			'rpsa-block-logged-in-users', 
			__( 'Track Logged-In Users', 'rp-simple-analytics' ), 
			array( $this, 'settings_section_logged_in_users' ), 
			'rp-simple-analytics'
		);
		add_settings_field(
			'rpsa-block-logged-in-users-toggle', 
			__( 'Block logged in users from tracking?', 'rp-simple-analytics' ), 
			array( $this, 'logged_in_users_toggle' ), 
			'rp-simple-analytics', 
			'rpsa-block-logged-in-users', 
			[
				'label_for' => 'rpsa-block-logged-in-users-toggle'
			]
		);		
		add_settings_field(
			'rpsa-block-at-capability-input', 
			__( 'Block users with this capability', 'rp-simple-analytics' ), 
			array( $this, 'block_at_capability_input' ), 
			'rp-simple-analytics', 
			'rpsa-block-logged-in-users', 
			[
				'label_for' => 'rpsa-block-at-capability-input'
			]
		);		
		add_settings_section(
			'rpsa-dashboard-widget', 
			__( 'Dashboard Widget', 'rp-simple-analytics' ), 
			array( $this, 'settings_section_dashboard_widget' ), 
			'rp-simple-analytics'
		);
		add_settings_field(
			'rpsa-dashboard-widget-toggle', 
			__( 'Add analytics widget to dashboard?', 'rp-simple-analytics' ), 
			array( $this, 'dashboard_widget_toggle' ), 
			'rp-simple-analytics', 
			'rpsa-dashboard-widget', 
			[
				'label_for' => 'rpsa-dashboard-widget-toggle'
			]
		);		
		add_settings_section(
			'rpsa-events', 
			__( 'Event Tracking', 'rp-simple-analytics' ), 
			array( $this, 'settings_section_events' ), 
			'rp-simple-analytics'
		);
		add_settings_field(
			'rpsa-events-toggle', 
			__( 'Include event tracking code?', 'rp-simple-analytics' ), 
			array( $this, 'settings_events_toggle' ), 
			'rp-simple-analytics', 
			'rpsa-events', 
			[
				'label_for' => 'rpsa-events-toggle'
			]
		);		
		add_settings_field(
			'rpsa-events-jquery-toggle', 
			__( 'Enqueue jQuery?', 'rp-simple-analytics' ), 
			array( $this, 'settings_events_jquery_toggle' ), 
			'rp-simple-analytics', 
			'rpsa-events', 
			[
				'label_for' => 'rpsa-events-jquery-toggle'
			]
		);		
		add_settings_field(
			'rpsa-events-extra-js', 
			__( 'Additional javascript for inclusion (rendered in your footer via <code>wp_add_inline_script</code>). Do not include <code>&lt;script&gt;</code> tags.', 'rp-simple-analytics' ), 
			array( $this, 'events_extra_js' ), 
			'rp-simple-analytics', 
			'rpsa-events', 
			[
				'label_for' => 'rpsa-events-extra-js'
			]
		);		
	}


	/**
	* Settings: Sanitize a settings "boolean"
	* The form can actually chuck anything it likes at us, let's turn it into false or 1
	*
	* @since    1.0.0
	*/
	public function sanitize_boolean( $input = NULL ) {
		if ( isset( $input ) ) {
			$input = 1;
		}
		return $input;
	}


	/**
	* Settings: Check input is a valid capability
	*
	* @since    1.0.0
	*/
	public function sanitize_capability( $input = NULL ) {
		$input = strtolower( trim( $input ) );
		$roles = wp_roles( );
		$capability_values = array( );
		foreach( $roles->roles as $role ) {
			$capability_values = array_merge( $capability_values, $role['capabilities'] );
		}
		$capabilities = array_keys( $capability_values );
		if ( ! in_array( $input, $capabilities ) ) {

			add_settings_error(
				'rpsa-block-logged-in-users-messages', 
				'unknown-capability', 
				'<code>' . $input . '</code> ' . __( 'is not a recognised capability. Please correct below and try again.', 'rp-simple-analytics' )
			);

			$input = $this->block_at_capability;

		}
		return $input;
	}


	/**
	* Settings: Block logged-in users section
	*
	* @since    1.0.0
	*/
	public function settings_section_logged_in_users( ) {
		_e( 'Use these settings to block logged in users with a particular capability from being included in the analytics. This works by not loading the analytics script for these users.', 'rp-simple-analytics' );
	}


	/**
	* Settings: Block logged in users toggle
	*
	* @since    1.0.0
	*/
	public function logged_in_users_toggle( ) {
		echo '<input type="checkbox" id="rpsa-block-logged-in-users-toggle" name="rpsa_block_logged_in_users" value="1"';
		if ( '1' === get_option( 'rpsa_block_logged_in_users' ) ) {
			echo ' checked';
		}
		echo '/>';
		echo '<br/>';
		_e( 'If this is checked then logged in users with the capability below will not be included in the analytics.', 'rp-simple-analytics' );
	}

	/**
	* Settings: Block logged in users capability
	*
	* @since    1.0.0
	*/
	public function block_at_capability_input( ) {
		echo '<input type="text" id="rpsa-block-at-capability-input" name="rpsa_block_at_capability" value="' . esc_attr( $this->block_at_capability ) . '" />';
		echo '<br/>';
		_e( 'On a default installation you can use <code>edit_theme_options</code> to block just Administrators,  <code>publish_pages</code> to block Editors and above, <code>publish_posts</code> to block Authors and above, <code>edit_posts</code> to block Contributors and above and <code>read</code> to block Subscribers and above. <a href="https://wordpress.org/support/article/roles-and-capabilities/" target="_blank">Read more about Roles and Capabilities</a>', 'rp-simple-analytics' );
	}


	/**
	* Settings: Dashboard widget section
	*
	* @since    1.0.0
	*/
	public function settings_section_dashboard_widget( ) {
		_e( 'Add a widget to your dashboard to show the latest analytics. <strong>CAUTION</strong> this will only work for sites with public analytics.', 'rp-simple-analytics' );
	}


	/**
	* Settings: Dashboard widget toggle
	*
	* @since    1.0.0
	*/
	public function dashboard_widget_toggle( ) {
		echo '<input type="checkbox" id="rpsa-dashboard-widget-toggle" name="rpsa_dashboard_widget" value="1"';
		if ( '1' === get_option( 'rpsa_dashboard_widget' ) ) {
			echo ' checked';
		}
		echo '/>';
	}


	/**
	* Settings: Events section
	*
	* @since    1.0.0
	*/
	public function settings_section_events( ) {
		_e( 'Event tracking is experimental and requires some additional javascript to trigger the events when needed. These settings let you add the basic event tracking code and add some custom javascript to the bottom of each page to trigger events. <a href="https://docs.simpleanalytics.com/events" target="_blank">Read about event tracking</a>', 'rp-simple-analytics' );
	}


	/**
	* Settings: Events toggle
	*
	* @since    1.0.0
	*/
	public function settings_events_toggle( ) {
		echo '<input type="checkbox" id="rpsa-events-toggle" name="rpsa_events" value="1"';
		if ( '1' === get_option( 'rpsa_events' ) ) {
			echo ' checked';
		}
		echo '/>';
	}

	/**
	* Settings: Events include jquery toggle
	*
	* @since    1.0.0
	*/
	public function settings_events_jquery_toggle( ) {
		echo '<input type="checkbox" id="rpsa-events-jquery-toggle" name="rpsa_events_jquery" value="1"';
		if ( '1' === get_option( 'rpsa_events_jquery' ) ) {
			echo ' checked';
		}
		echo '/>';
		echo '<br/>';
		_e( 'If you want to use jQuery in your custom javascript below then make sure it is enqueued by checking this option. Note that WordPress will load jQuery in noConflict mode ( see <a href="https://developer.wordpress.org/reference/functions/wp_enqueue_script/#comment-1473" target="_blank">this note</a> )', 'rp-simple-analytics' );
	
	}

	/**
	* Settings: Events extra javascript
	*
	* @since    1.0.0
	*/
	public function events_extra_js( ) {
		echo '<textarea id="rpsa-events-extra-js" rows="5" name="rpsa_events_extra_js" class="textarea">' . get_option( 'rpsa_events_extra_js' ) . '</textarea>';
	}


	/**
	* Display settings page
	*
	* @since    1.0.0
	*/
	public function settings_page_content( ) {

		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
		return;
		}

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title( ) ); ?></h1>
			<p>
				<?php
				_e( 'Simple Analytics is a privacy friendly analytics service which does not use cookies or collect any personal data.', 'rp-simple-analytics' );
				?>
				
			</p>
			<p>
				<?php
				_e( '<strong>To use Simple Analytics you first need to sign up for a subscription.</strong> We would love it if you could support development of this plugin by signing up via our referral link <a href="https://referral.simpleanalytics.com/refined-practice" target="_blank">https://referral.simpleanalytics.com/refined-practice</a> ( it does not cost you anything ) but if you would prefer not to then you can find out <a href="https://simpleanalytics.com/#signup" target="_blank">more about subscription prices here</a>.', 'rp-simple-analytics' );
				?>
		
			</p>
			<p>
				<?php
				_e( 'Once you have a subscription you should <a href="https://simpleanalytics.com/websites/add" target="_blank">add this website to your dashboard</a> to start collecting analytics.', 'rp-simple-analytics' );
				?>
			</p>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'rp-simple-analytics' );
				do_settings_sections( 'rp-simple-analytics' );
				submit_button( __( 'Save Settings', 'rp-simple-analytics' ) );
				?>
				</form>
		</div>
		<?php

	}


	/**
	* Dashboard widget registration
	*
	* @since    1.0.0
	*/
	public function add_dashboard_widget( ) {
		global $wp_meta_boxes;
		wp_add_dashboard_widget( $this->rp_simple_analytics, 'Simple Analytics', array( $this, 'dashboard_widget_content' ) );
	}


	/**
	* Dashboard widget content
	*
	* @since    1.0.0
	*/
	public function dashboard_widget_content( ) {

		$site_name = get_bloginfo( 'name' );
		$site_url = get_bloginfo( 'url' );
		$remove_http = '#^http(s)?://#';
		$remove_www  = '/^www\./';
		$replace     = '';
		$base_url    = preg_replace( $remove_http, $replace, $site_url );
		$base_url    = preg_replace( $remove_www, $replace, $base_url );
		$sa_stats_url = "https://simpleanalytics.com/" . $base_url;
		$sa_events_url = "https://simpleanalytics.com/events/" . $base_url;

		echo '<p>';
		printf(
			/* translators: %s: Site name of this website */
			__( '%s has received <span id="pageviews"></span> page views in the last month.', 'rp-simple-analytics' ), 
			$site_name
		);
		echo '</p>';

		echo '<div data-sa-graph-url="' . $sa_stats_url . '" data-sa-page-views-selector="#pageviews" >';

		echo '<p>';
		_e( 'Ad blockers do not like the Simple Analytics embed, disable yours to view this graph', 'rp-simple-analytics' );
		echo '</p>';

		echo '</div>';

		echo '<p><a href="' . $sa_stats_url . '" target="_blank">';
		_e( 'View the full page view analytics here', 'rp-simple-analytics' );
		echo '</a></p>';

		if ( get_option( 'rpsa_events' ) ) {
			echo '<p><a href="' . $sa_events_url . '" target="_blank">';
			_e( 'View the event analytics here', 'rp-simple-analytics' );
			echo '</a></p>';
		}

		echo '<p>';
		_e( "Can't see anything? Check that the site has been added to your Simple Analytics subscription and that the analytics are set to public", 'rp-simple-analytics' );
		echo '</p>';


	}


	/**
	* Plugin action links
	*
	* @since    1.1.0
	*/
	public function add_action_links( $links ) {
		$additional_links = array(
 			'<a href="' . admin_url( 'options-general.php?page=' . $this->rp_simple_analytics ) . '">Settings</a>',
 		);
		return array_merge( $links, $additional_links );
	}


	/**
	* Activation notice
	*
	* @since    1.1.0
	*/
	public function display_activation_notice( ) {
		// See https://stackoverflow.com/questions/38233751/show-message-after-activating-wordpress-plugin
	    if( get_transient( 'rpsa_activation_notice' ) ){
	    	/* translators: %s: Link to RP Simple Analytics settings page */
	    	$content = sprintf(
				/* translators: 1: Link to Simple Analytics home/dashboard page 2: Refined Practice Simple Analytics Referral link 3: Link to RP Simple Analytics settings page */
				__( 'RP Simple Analytics is now active. If you already have a Simple Analytics subscription then make sure that you <a href="%1$s" target="_blank">add this site to your Simple Analytics dashboard</a>. If you do not have a subscription yet please consider supporting this plugin by signing up through our <a href="%2$s" target="_blank">referral link</a>, you will also get one month for free! You can <a href="%3$s">adjust this plugin\'s settings here</a>.'  , 'rp-simple-analytics' ), 
					'https://simpleanalytics.com/',
					'https://referral.simpleanalytics.com/refined-practice',
					admin_url( 'options-general.php?page=' . $this->rp_simple_analytics )
				);
			?>
			<div class="updated notice is-dismissible">
				<p><?php echo $content;?></p>
			</div>
			<?php
			delete_transient( 'rpsa_activation_notice' );
		}
    }



}
