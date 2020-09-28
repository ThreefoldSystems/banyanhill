<?php
/**
 * Core class of the plugin, loads everything in one place.
 *
 * Make calls: tfs_css()->xxx;
 * i.e. tfs_css()->subscriptions->get_local_subscriptions_dimensional();
 * i.e. tfs_css()->core->get_language_variable('txt_css_modal_pwd_submit');
 */
class CSS_Core
{
    /**
     * @var agora_core_framework
     */
    public $core;

    /**
     * @var
     */
    public $plugin_basename;

    /**
     * @var
     */
    public $plugin_admin_page;

    /**
     * @var
     */
    public $config_name;

    /**
     * @var
     */
    public $config;

    /**
     * @var
     */
    public $shortcode_name;

    /**
     * @var CSS_Template_Manager
     */
    public $template_manager;

    /**
     * @var CSS_Subscriptions
     */
    public $subscriptions;

    /**
     * @var CSS_Eletters
     */
    public $eletters;

    /**
     * @var CSS_Update_Api
     */
    public $css_update_api;

    /**
     * @var CSS_Opium
     */
    public $opium;


    /**
     * Construct
     */
    public function __construct()
    {
        include_once('class-css-helpers.php');
        include_once('class-css-subscriptions.php');
        include_once('class-css-eletters.php');
        include_once('class-css-update-api.php');
        include_once('class-css-template-manager.php');
        include_once('class-css-opium.php');

        // Middleware Classes
        $this->core = agora_core_framework::get_instance();

        // Plugin settings
        $this->plugin_basename = plugin_basename( __FILE__ );
        $this->plugin_admin_page = 'tfs-customer-self-service';

        if ( defined('CSD_CONFIG_NAME') ) {
            $this->config_name = 'tfs-csd-config';
        } else {
            $this->config_name = 'tfs-customer-self-service-config';
        }

        // Config
        $base_css_config = parse_ini_file( dirname( __FILE__ ) . '/../default_css_config.ini' );

        $this->config = $this->core->wp->get_option( $this->config_name, $base_css_config );

        $this->shortcode_name = 'tfs_customer_self_service';

        // Add custom template path to view paths if enabled in the backend and folder name specified
        if ( $this->config['custom_templates'] && $this->config['templates_directory'] ) {
            $this->core->view->set_template_path( get_stylesheet_directory() . '/' . $this->config['templates_directory'] );
        }

        // Add alt template path to view paths if enabled in the backend
        if ( !empty($this->config['alt_templates']) && $this->config['alt_templates'] === '1' ) {
            $this->core->view->set_template_path( dirname( __FILE__ ) . '/../alt-theme' );
        }

        if( class_exists('\csd_ext\tfs_customer_service_extension') ) {
            $this->core->view->set_template_path( dirname( __FILE__ ) . '/../../Customer-Service-Extension/theme' );
        }

        $this->core->view->set_template_path( dirname( __FILE__ ) . '/../theme' );
        $this->core->view->set_template_path( dirname( __FILE__ ) . '/../views/backend' );

        $this->helpers = new CSS_Helpers();
        $this->opium = new CSS_Opium($this->core, $this->config);
        $this->subscriptions = new CSS_Subscriptions( $this->core, $this->opium, $this->plugin_admin_page, $this->config );
        $this->eletters = new CSS_Eletters();

        $this->template_manager = new CSS_Template_Manager( $this->core, $this->subscriptions, $this->eletters, $this->helpers, $this->config );

        $this->css_update_api = new CSS_Update_Api( $this->core, $this->subscriptions, $this->eletters, $this->template_manager, $this->config );
        

    }

    /**
     * This class follows a singleton pattern.
     *
     * @return static
     */
    public static function get_instance()
    {
        static $instance = null;

        if ( null === $instance ) {
            $instance = new static();
        }

        return $instance;
    }
}