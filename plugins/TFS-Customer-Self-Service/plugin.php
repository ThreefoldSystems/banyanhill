<?php
/*
	Plugin Name: TFS Customer Self Service
	Plugin URI: https://github.com/TFS-Customer-Self-Service
	Description: Adds customer self service functionality that leverages the Middleware 2.x service layer.
	Author: Threefold Systems
	Version: 1.5.2
*/

require 'config.php';

class CSS_Customer_Self_Service
{
	/**
	 * @var CSS_Core
	 */
	private $css_core;

	/**
	 * TFS_customer_self_service constructor.
	 */
	public function __construct()
	{
		// Initialize plugin if dependency check passes
		if ( $this->dependency_check() ) {
			include_once('classes/class-css-core.php');

			$this->css_core = CSS_Core::get_instance();

			/**	 Add the shortcode */
			add_shortcode( $this->css_core->shortcode_name, array( $this, 'display_css' ) );

			/**	 Activation and deactivation hooks */
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

			/**	 Plugin setup */
			add_action( 'admin_menu', array( $this, 'initialize_menu' ) );
			add_filter( 'agora_middleware_admin_menu', array( $this, 'add_tab_item' ), 6, 1 );
			add_action( 'css_plugin_admin_page', array( $this, 'tfs_css_admin' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			/**  Scripts and styles */
			add_action( 'wp_enqueue_scripts', array( $this,'enqueue_dependent_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this,'enqueue_dependent_scripts_backend' ) );

			/** Opium pre-pop link shortcode */
			add_shortcode('prepop_link', array($this, 'prepop_link_shortcode'));
		}
	}

	/**
	 * Display css admin page
	 */
	public function tfs_css_admin()
	{
		$content = array(
			'config_name' => $this->css_core->config_name,
			'config'      => $this->css_core->config,
			'plugin_mode' => array( 'Display Shortcode', 'Display text' ),
			'is_secure' => $this->is_secure()
		);

		$this->css_core->template_manager->process_template(
			'css-admin',
			$content
		);
	}

	/**
	 * Display customer self service page
	 *
	 *  @param $atts
	 *  @param string $content
	 *  @return string
	 */
	function display_css( $atts = null, $content = null )
	{
		// If user is logged in, display customer self service page
		if ( is_user_logged_in() ) {
			$this->css_core->template_manager->process_template(
				'css-customer-self-service',
				$content
			);
		} else {
			if ( ! $_SESSION['agora_session_var']['login']['message'] == "Invalid username or password" ) {
                $data = array();
                $auth_config = $this->css_core->core->wp->get_option('agora-middleware-auth-config');
                if ( !empty($auth_config) && !is_wp_error($auth_config) ) {
                    $data['brand_color'] = $auth_config['brand_color'];
                }
                $data['title'] = $this->css_core->core->get_language_variable('txt_login_title');
                $data['forgot_link'] = $this->css_core->core->get_language_variable('txt_forgot_link');
                $data['no_login'] = $this->css_core->core->get_language_variable('txt_failed_login');
                $data['form_parameters'] = array(
                    'label_username' => $this->css_core->core->get_language_variable('txt_default_login_message'),
                    'label_log_in' => $this->css_core->core->get_language_variable('txt_login_button'),
                    'label_password' => $this->css_core->core->get_language_variable('txt_login_pwd'),
                    'label_remember' => $this->css_core->core->get_language_variable('txt_login_remember')
                );
				return $this->css_core->core->view->load( 'mw-login-block', $data, true );
			}
		}
	}

	/**
	 *  Checks if the connection is under https or not
	 *
	 *  @return bool
	 */
	public function is_secure()
	{
		return ( ! empty($_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
	}

	/**
	 * Function for admin init to register settings
	 *
	 * @return void
	 */
	public function register_settings()
	{
		register_setting( $this->css_core->config_name . '_group', $this->css_core->config_name, array( $this, '_sanitize_option_input' ) );
	}

	/**
	 *  Plugin activation hook, picks the default language vars from the ini file and set them up and sends
	 *  the reporting ping to TFS ( sendToReportingService ).
	 **/
	public function activation()
	{
		// Remove relevanssi's wp_insert_post action to avoid errors when plugin is activated
		remove_all_actions( 'wp_insert_post', 99 );

		// Create Customer Self Service page.
		$this->css_core->core->_create_page( 'customer-self-service', 'Customer Self Service', '[tfs_customer_self_service]' );

		// Pick out the default language vars from the ini file and set them up
		$default_variables = parse_ini_file( dirname( __FILE__ ) . '/default_variables.ini' );
		$this->css_core->core->register_language_variables( $default_variables );

		// Home ping back
		$this->home_ping_back( 'Plugin Activated' );
	}

	/**
	 *  WordPress plugin deactivation function for the css plugin,
	 **/
	public function deactivation()
	{
		// Home ping back
		$this->home_ping_back( 'Plugin De-Activated' );
	}

	/**
	 *  Home ping back
	 **/
	public function home_ping_back( $action )
	{
		if ( method_exists('agora_core_framework', 'sendToReportingService' ) ) {
			$plugin_details = get_plugin_data(__FILE__, false);

			$arg = array(
				'action'         => $action,
				'plugin_name'    => $plugin_details['Name'],
				'plugin_version' => $plugin_details['Version'],
			);

			$this->css_core->core->sendToReportingService( $arg );
		}
	}

	/**
	 *  Function to add menu item(s) to the Admin menu
	 */
	public function initialize_menu()
	{
		add_submenu_page(
			$this->css_core->core->plugin_admin_page,
			__('Customer Self Service'),
			__('Customer Self Service'),
			'manage_options',
			$this->css_core->plugin_admin_page,
			array( $this, 'admin_page' )
		);
	}

	/**
	 *  Adds menu items and defines page for the configuration backend plugin page
	 */
	public function admin_page()
	{
		$content = array(
			'menuItems'         => apply_filters( 'agora_middleware_admin_menu', array() ),
			'plugin_admin_page' => $this->css_core->plugin_admin_page,
		);

		$this->css_core->core->view->load( 'admin_header', $content );

		do_action( 'css_plugin_admin_page' );

		$this->css_core->core->view->load( 'admin_footer' );
	}

	/**
	 * Adds a tab for this plugin to the admin menu
	 *
	 * @return array
	 */
	public function add_tab_item( $menu )
	{
		$menu[] = array(
			'title' => __('Customer Self Service'), 'page' => $this->css_core->plugin_admin_page
		);

		return $menu;
	}

	/**
	 *  Checks for the Middleware Base plugin dependencies
	 */
	public function dependency_check()
	{
		if ( defined( 'TFS_CSD_BYPASS' ) || ( class_exists('agora_core_framework') && class_exists( 'agora_authentication_plugin' ) ) ) {
			return true;
		} else {
			// For some reason this isn't automatically included at runtime of this particular function.
			include_once( ABSPATH . '/wp-admin/includes/plugin.php' );

			add_action('all_admin_notices', array( $this, 'dependent_plugin_admin_error' ) );

            deactivate_plugins( plugin_basename( __FILE__ ) );

            if(isset($_GET['activate'])) unset($_GET['activate']);

            return false;
		}
	}

	/**
	 *  Echoes an html message that comes from 4.7 dependency_check()
	 *
	 */
	public function dependent_plugin_admin_error()
	{
		echo '<div id="message" class="error"><p>';
		echo __('The Customer Self Service Plugin requires the Authentication Middleware plugin version 1.9.5 to work. 
		Customer Self Service will not function without it.');
		echo '</p></div>';
	}

	/**
	 * Create the pre-pop link when the shortcode is called.
	 *
	 * @param $atts
	 * @param $content
	 * @return mixed
	 */
	function prepop_link_shortcode($atts, $content) {
		
		if(isset($atts['urlnick'])){

			$link = $this->css_core->opium->create_prepop_link($atts['urlnick'], $atts['promocode'], $atts['domain']);
			return '<a target="_blank"' .( isset($atts['class']) ? 'class="' . $atts['class'] . '"' : '' ) . 'href="' . $link . '">' . $content . '</a>';
		} else {
			return 'You must set the "urlnick" parameter for this link to populate correctly';
		}

	}

	/**
	 *   Enqueue the CSS and JS CSS plugin files
	 */
	public function enqueue_dependent_scripts()
	{
		/* Sitewide enqueue */
		// Generic wp jquery
		wp_enqueue_script( 'jquery' );

		// Cookie js
		wp_enqueue_script( 'cookie-js', plugins_url( '/assets/vendor/js/js.cookie.min.js', __FILE__ ), array( 'jquery' ), null, false );

		// Lightbox featherlight files
		wp_enqueue_script( 'featherlight-js', plugins_url( '/assets/vendor/js/featherlight.min.js', __FILE__ ), array( 'jquery' ), null, false );

		// Localize and enqueue sitewide js
		wp_register_script( 'tfs-css-js-sitewide-localized', plugins_url( '/assets/js/localized/tfs-css-plugin-sitewide-localized.js', __FILE__ ) );
		wp_localize_script( 'tfs-css-js-sitewide-localized', 'tfs_css_localized_sitewide_data', $this->localize_sitewide_data());
		wp_enqueue_script( 'tfs-css-js-sitewide-localized');

		wp_enqueue_script( 'tfs-css-js-sitewide', plugins_url( '/assets/js/min/tfs-css-plugin-sitewide.min.js', __FILE__ ), array( 'jquery' ), null, false  );

		wp_enqueue_style( 'featherlight-css', plugins_url( '/assets/vendor/css/featherlight.min.css', __FILE__ ) );
		wp_enqueue_style( 'tfs-css-css-sitewide', plugins_url( '/assets/css/tfs-css-plugin-sitewide.css', __FILE__ ) );

		/* Frontend enqueue */
		// Enqueue if the shortcode is present
		$content = get_queried_object()->post_content;

		if ( strpos( $content, $this->css_core->shortcode_name ) !== false || strpos ($_SERVER['REQUEST_URI'], 'premium-content' ) !== false ) {
			// Customer self service js and css files

			// Localize and enqueue main js for css page
			wp_register_script( 'tfs-css-js-frontend-localized', plugins_url( '/assets/js/localized/tfs-css-plugin-frontend-localized.js', __FILE__ ) );
			wp_localize_script( 'tfs-css-js-frontend-localized', 'tfs_css_localized_frontend_data', $this->localize_frontend_data());
			wp_enqueue_script( 'tfs-css-js-frontend-localized');

			wp_enqueue_script( 'tfs-css-validate', plugins_url( '/assets/vendor/js/jquery.validate.min.js', __FILE__ ), array( 'jquery' ), null, false );
			wp_enqueue_script( 'tfs-css-js-frontend', plugins_url( '/assets/js/min/tfs-css-plugin-frontend.min.js', __FILE__ ), array( 'jquery' ), null, false  );

			wp_enqueue_style('font-awesome', plugins_url( '/assets/vendor/css/font-awesome.min.css', __FILE__ ) );
		}
	}

	/**
	 *   Enqueue the CSS and JS CSS plugin files for backend
	 */
	public function enqueue_dependent_scripts_backend()
	{
		// Localize and enqueue backend js
		// This will just be an empty script that we append variables to
		wp_register_script( 'tfs-css-js-backend-localized', plugins_url( '/assets/js/localized/tfs-css-plugin-backend-localized.js', __FILE__ ) );
		wp_localize_script( 'tfs-css-js-backend-localized', 'tfs_css_localized_backend_data', $this->localize_backend_data() );
		wp_enqueue_script( 'tfs-css-js-backend-localized');

		wp_enqueue_script( 'tfs-css-js-backend', plugins_url( '/assets/js/min/tfs-css-plugin-backend.min.js', __FILE__ ) );
		wp_enqueue_style( 'tfs-css-css-backend', plugins_url( '/assets/css/tfs-css-plugin-backend.css', __FILE__ ) );
	}

	/**
	 *   Localize sitewide data
	 */
	private function localize_sitewide_data() {
		return array(
			'subscription_renewals_save_for' => $this->css_core->config['subscription_renewals_save_for']
		);
	}

	/**
	 *   Localize frontend data
	 */
	private function localize_frontend_data() {
		return array(
			'css_ajax_url' => admin_url( 'admin-ajax.php' ),
			'security_css_change_address' => wp_create_nonce( "css_change_address" ),
			'security_css_change_password' => wp_create_nonce( "css_change_password" ),
			'security_css_change_email' => wp_create_nonce( "css_change_email" ),
			'security_css_open_url' => wp_create_nonce( "css_open_url" ),
			'security_css_add_remove_customer_list' => wp_create_nonce( "css_add_remove_customer_list" ),
			'security_css_get_state' => wp_create_nonce( "css_get_state" ),
			'security_css_change_subs_email' => wp_create_nonce( "css_change_subs_email" ),
			'security_css_cancel_auto_renew' => wp_create_nonce( "css_cancel_auto_renew" ),
			'security_css_change_username' => wp_create_nonce( "css_change_username" ),
			'security_css_change_listings_email' => wp_create_nonce( "css_change_listings_email" ),
			'security_css_request_change_updates' => wp_create_nonce( "css_request_change_updates" ),
			'security_css_request_disable_auto_renew' => wp_create_nonce( "css_request_disable_auto_renew" ),
			'security_css_change_social' => wp_create_nonce( "css_change_social" ),
			'security_css_remove_social' => wp_create_nonce( "css_remove_social" ),
			'security_css_toggle_avatar' => wp_create_nonce( "css_toggle_avatar" ),			
			'txt_css_enter_firstname' => tfs_css()->core->get_language_variable('txt_css_enter_firstname'),
			'txt_css_enter_lastname' => tfs_css()->core->get_language_variable('txt_css_enter_lastname'),
			'txt_css_phonenumber' => tfs_css()->core->get_language_variable('txt_css_phonenumber'),
			'txt_css_list_subscribed' => tfs_css()->core->get_language_variable('txt_css_list_subscribed'),
			'txt_css_list_unsubscribed' => tfs_css()->core->get_language_variable('txt_css_list_unsubscribed'),
			'txt_css_username_email_address' => tfs_css()->core->get_language_variable('txt_css_username_email_address'),
			'txt_css_username_insert_new' => tfs_css()->core->get_language_variable('txt_css_username_insert_new'),
			'txt_success_css_change_pwd' => tfs_css()->core->get_language_variable('txt_css_pwd_success_alert'),
			'txt_success_css_change_address' => tfs_css()->core->get_language_variable('txt_css_addr_success'),
			'txt_css_email_sent_success' => tfs_css()->core->get_language_variable('txt_css_email_sent_success'),
			'txt_css_email_success' => tfs_css()->core->get_language_variable('txt_css_email_success'),
			'txt_css_attached_email_success' => tfs_css()->core->get_language_variable('txt_css_attached_email_success'),
			'txt_css_username_success' => tfs_css()->core->get_language_variable('txt_css_username_success'),
			'txt_css_email_error_insert_email' => tfs_css()->core->get_language_variable('txt_css_email_error_insert_email'),
			'txt_css_email_error_match_emails' => tfs_css()->core->get_language_variable('txt_css_email_error_match_emails'),
			'txt_css_pwd_match' => tfs_css()->core->get_language_variable('txt_css_pwd_match'),
			'txt_css_pwd_existing_pwd_placeholder' => tfs_css()->core->get_language_variable('txt_css_pwd_existing_pwd_placeholder'),
			'txt_css_ensure_password_correct' => tfs_css()->core->get_language_variable('txt_css_ensure_password_correct'),
			'txt_css_pwd_new_pwd_placeholder' => tfs_css()->core->get_language_variable('txt_css_pwd_new_pwd_placeholder'),
			'txt_css_loading' => "<div class='tfs_css_preloader'><img src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDBweCIgIGhlaWdodD0iNDBweCIgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDEwMCAxMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIiBjbGFzcz0ibGRzLWVjbGlwc2UiIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTogcnVubmluZzsgYW5pbWF0aW9uLWRlbGF5OiAwczsgYmFja2dyb3VuZDogbm9uZTsiPjxwYXRoIG5nLWF0dHItZD0ie3tjb25maWcucGF0aENtZH19IiBuZy1hdHRyLWZpbGw9Int7Y29uZmlnLmNvbG9yfX0iIHN0cm9rZT0ibm9uZSIgZD0iTTEwIDUwQTQwIDQwIDAgMCAwIDkwIDUwQTQwIDQzIDAgMCAxIDEwIDUwIiBmaWxsPSJyZ2JhKDAlLDAlLDAlLDAuNikiIHRyYW5zZm9ybT0icm90YXRlKDM2MCAtOC4xMDg3OGUtOCAtOC4xMDg3OGUtOCkiIGNsYXNzPSIiIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTogcnVubmluZzsgYW5pbWF0aW9uLWRlbGF5OiAwczsiPjxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSIgdHlwZT0icm90YXRlIiBjYWxjTW9kZT0ibGluZWFyIiB2YWx1ZXM9IjAgNTAgNTEuNTszNjAgNTAgNTEuNSIga2V5VGltZXM9IjA7MSIgZHVyPSIwLjVzIiBiZWdpbj0iMHMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiBjbGFzcz0iIiBzdHlsZT0iYW5pbWF0aW9uLXBsYXktc3RhdGU6IHJ1bm5pbmc7IGFuaW1hdGlvbi1kZWxheTogMHM7Ij48L2FuaW1hdGVUcmFuc2Zvcm0+PC9wYXRoPjwvc3ZnPg==' alt='Loading' class='loading'></div>",
			'min_pwd_length' => $this->css_core->config['min_length_pwd'],
			'txt_css_no_change' => tfs_css()->core->get_language_variable('txt_css_no_change'),
			'txt_css_general_error' => tfs_css()->core->get_language_variable('txt_css_general_error'),
			'request_pwd_on_email_update' => $this->css_core->config['request_pwd_on_addr_update']
		);

	}

	/**
	 *   Localize backend data
	 */
	private function localize_backend_data() {
		return array(
			'subscription_renewals' => $this->css_core->config_name . '[subscription_renewals]',
			'custom_templates' => $this->css_core->config['custom_templates'],
			'custom_templates_name' => $this->css_core->config_name . '_custom_templates',
			'css_contact_mode' => $this->css_core->config['css_contact_mode'],
			'allowed_listings_checkbox' => $this->css_core->config['allowed_listings_checkbox'],
			'allowed_subscriptions_checkbox' => $this->css_core->config['allowed_subscriptions_checkbox'],
			'allowed_listings_checkbox_name' => $this->css_core->config_name . '_allowed_listings_checkbox',
			'allowed_subscriptions_checkbox_name' => $this->css_core->config_name . '_allowed_subscriptions_checkbox',
		);
	}


	/**
	 * Function to sanitize and validate options input
	 *	@param array $input 	The options array submitted by the form
	 * 	@return array
	 **/
	public function _sanitize_option_input($input){

		$output = array();

		foreach($input as $k => $v){
			if(isset($input[$k])){
				if ( $k == 'css_account_landing' || $k == 'css_phone_data' ) {
					$output[$k] = htmlentities ( $input[$k] );
				} else {
					$output[$k] = strip_tags( stripslashes( $input[$k] ) );
				}
			}
		}

		return apply_filters( 'tfs_css_sanitize_option_input', $output, $input );
	}
}

$tfs_orphan = new CSS_Customer_Self_Service();

if ( ! function_exists('tfs_css') ) {
    /**
     * Return a singleton of TFS CSS
     */
    function tfs_css()
    {
        return CSS_Core::get_instance();
    }
}


