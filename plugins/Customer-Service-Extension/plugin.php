<?php
/*
	Plugin Name: TFS Customer Service Extension Plugin
	Plugin URI: https://github.com/ThreefoldSystems/Customer-Service-Extension
	Description: A custom extension plugin for the CSD
	Author: Threefold Systems
	Version: 1.2.3
	Author URI: http://threefoldsystems.com
 */
namespace csd_ext;

include_once('classes/class_dependency_check.php');
include_once('classes/class_display.php');
include_once('classes/class_change_status_controller.php');
include_once('classes/class_lifetime_controller.php');
include_once('classes/class_change_email_controller.php');
include_once('classes/class_change_text_alert_controller.php');
include_once('classes/class_change_auto_renewal_controller.php');
include_once('classes/class_change_renewal_price_controller.php');
include_once('classes/class_change_renewal_date_controller.php');
include_once('classes/class_reminder_cron.php');
require 'config.php';

/**
 * TFS Customer Service Extension Plugin
 *
 * A custom extension plugin for the CSD
 *
 * @package tfs_customer_service_extension
 *
 */
class tfs_customer_service_extension {

    /**
     * @var class_dependency_check
     */
    private $dependency_check;

    /**
     * @var class_display
     */
    private $display_class;

    /**
     * @var agora_core_framework
     */
    public $core;

    /**
     * @var wpdb
     */
    private $wpdb;

    /**
     * Constructor Method.
     *
     * @method __construct
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        $dependencies = array("agora_core_framework");
        $this->dependency_check = new class_dependency_check( $dependencies );
        $this->display_class = new class_display( );
        $this->reminder_class = new class_reminder_cron();

        $check_dependency = $this->dependency_check->check_dependencies();
        if ( !empty( $check_dependency ) ) {
            include_once('classes/class_middleware.php');
            $this->middleware = class_middleware::get_instance();
            // Wordpress API Hooks
            $this->_wordpress_hooks();
        } else {
            deactivate_plugins( plugin_basename( __FILE__ ) );
        }

    }

    /**
     *	Activation Method
     *
     *  @method activation
     *
     **/
    public function activation() {
        $default_variables = parse_ini_file( dirname( __FILE__ ) . '/default_variables.ini' );
        $this->middleware->core->register_language_variables( $default_variables );

        $charset_collate = $this->wpdb->get_charset_collate();

        $tableName = $this->wpdb->prefix . 'csd_ext_auto_renewals';

        $sql = "CREATE TABLE IF NOT EXISTS $tableName (
          id int(11) unsigned NOT NULL AUTO_INCREMENT,
          customerNumber varchar(20) DEFAULT NULL,
          subName varchar(120) DEFAULT NULL,
          subRef varchar(20) DEFAULT NULL,
          expireDate int(20) DEFAULT NULL,
          reminderSent enum('true', 'false') NOT NULL DEFAULT 'false',
          PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $result = dbDelta($sql);

        if (! wp_next_scheduled ( 'csd_ext_trigger_reminder_email' )) {
            wp_schedule_event(time(), 'daily', 'csd_ext_trigger_reminder_email');
        }
    }

    /**
     *	Deactivation Method
     *
     * @method deactivation
     *
     *	@param void
     *	@return void
     **/
    public function deactivation() {
        wp_clear_scheduled_hook('csd_ext_trigger_reminder_email');
    }

    /**
     * Description:
     * Version: 0.1
     * @author: Threefold Systems
     * @method _wordpress_hooks
     * @param void
     * @return void
     */
    private function _wordpress_hooks() {

        register_activation_hook( __FILE__, array($this, 'activation' ) );
        register_deactivation_hook( __FILE__, array($this, 'deactivation' ) );

        if(!is_admin()) {
            //localize frontend vars
            wp_register_script( 'csd-ext-js-localize-frontend',
                plugins_url( '/assets/javascript/localized/csd-ext-localized-frontend.js', __FILE__ ) );
            wp_localize_script( 'csd-ext-js-localize-frontend', 'csd_ext_js_localize_frontend',
                $this->localize_frontend_data() );
            wp_enqueue_script( 'csd-ext-js-localize-frontend' );

            //enqueue styles
            wp_enqueue_style( 'plugin-css', plugins_url( '/assets/css/styles.css', __FILE__ ) );
            wp_enqueue_style( 'font-awesome', plugins_url( '/assets/vendor/css/font-awesome.min.css', __FILE__ ) );

            //enqueue scripts
            wp_enqueue_script( 'plugin-js', plugins_url( '/assets/javascript/scripts.js', __FILE__ ), array( 'jquery' ),
                null, false );
        }

        //add shortcodes
        add_shortcode( 'csd_extension_view', array( $this->display_class, 'display_frontend' ) );
        add_action( 'csd_ext_trigger_reminder_email', array( $this->reminder_class, 'csd_ext_reminder_email') );

        // Pick out the default language vars from the ini file and set them up
        $default_variables = parse_ini_file( dirname( __FILE__ ) . '/default_variables.ini' );
        $this->middleware->core->register_language_variables( $default_variables );


    }

    /**
     * Localize frontend data
     * @method localize_frontent_data
     */
    private function localize_frontend_data() {
        return array(
            'csd_ext_ajax_url' => admin_url( 'admin-ajax.php' )
        );
    }

}

$tfs_customer_service_extension = new tfs_customer_service_extension();
