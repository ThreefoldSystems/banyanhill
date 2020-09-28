<?php
/*
	Plugin Name: TFS Portfolio Tracker
	Plugin URI: git@github.com:ThreefoldSystems/TFS-Portfolio-Tracker.git
	Description: Provides a connection layer for Portfolio Tracker API, and an admin interface for configuration
	Author: Threefold Systems
	Version: 1.3.1
	Author URI: http://threefoldsystems.com
 */

require('classes/class-portfolio-tracker-framework.php');

class Portfolio_Tracker_Core_Plugin {

    protected $core;

    public function __construct() {

        $this->core = Portfolio_Tracker_Framework::get_instance();
        $this->_wp_hooks();
    }

    /**
     * Shortcode to pull in the Portfolio Tracker dataTable
     *
     * @param $atts
     * @param null $content
     * @return string
     */
    public function portfolio_tracker_shortcode($atts, $content = null){
        update_option( 'tfs_pt_13171_return', 'a:1:{s:8:"NOV 2019";d:0.0166579538084743149217725743937990046106278896331787109375;}' );

        $this->core->view->set_template_path( dirname(__FILE__) . '/portfolio-tracker-theme/templates/' );
        $this->enqueue_portfolio_template_assets();

        if(isset($atts['template'])) {
            $template = $atts['template'];
        } else {
            $template = 'portfolio-tracker-default';
        }

        $content = array(
            'portfolios' => $this->core->ptapi->get_all_by_portfolio_id($atts['id']),
            'config' => $atts,
            'copy' => $content
        );

        if ( isset($atts['return_value']) ) {
            $content['return_values'] = true;
            $content['dynamic_graph_data'] = $this->get_monthly_return_value($atts['id'], $content['portfolios']);
        }

        return $this->core->view->load( $template, $content, true);

    }

    /**
     * Add options page
     */
    public function initialize_menu(){
        if (current_user_can('manage_options')) {

            add_menu_page(
                __('Settings Admin'),
                __('Portfolio Tracker'),
                'manage_options',
                $this->core->plugin_admin_page,
                array( $this, 'admin_page' ),
                plugin_dir_url( __FILE__ ) . 'img/tradestops-logo.ico'
            );
            add_submenu_page(
                $this->core->plugin_admin_page,
                __('Portfolio Admin'),
                __('Portfolio Settings'),
                'manage_options',
                $this->core->portfolio_admin_page,
                array( $this, 'portfolio_admin_page' )
            );

        }
    }

    /**
     * Start up the base admin page
     *
     */
    public function admin_page(){
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $this->enqueue_admin_base_assets();

        $content = array(
            'menuItems' => apply_filters('portfolio_tracker_admin_menu', array() ),
            'plugin_admin_page' => $this->core->plugin_admin_page,
            'config' => $this->core->config,
            'config_name' => $this->core->config_name
        );

        $this->core->view->load('admin-header', $content);
        $this->core->view->load('admin-base-form', $content);
        $this->core->view->load('admin-footer');
    }

    /**
     * Setup and return the portfolio admin page
     */
    public function portfolio_admin_page(){
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }
        $this->enqueue_admin_portfolio_assets();

        $content = array(
            'menuItems' => apply_filters('portfolio_tracker_admin_menu', array() ),
            'config' => $this->core->portfolio_config,
            'config_name' => $this->core->portfolio_config_name,
            'portfolios' => $this->core->ptapi->get_all_portfolios()
        );

        $this->core->view->load('admin-header', $content);
        $this->core->view->load('admin-portfolio-form', $content);
        $this->core->view->load('admin-footer');
    }

    /**
     * Enqueue the javascript needed for the base admin page
     */
    public function enqueue_admin_base_assets(){
        wp_enqueue_script('jquery');
        wp_enqueue_script( 'base-js', plugin_dir_url(__FILE__) . 'js/base-admin.js', false );
        wp_localize_script( 'base-js', 'admin', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
        wp_enqueue_style( 'base-css', plugin_dir_url(__FILE__) . 'css/base-admin.css', false );
    }

    /**
     * Enqueue the javascript and css needed for the portfolio admin page
     */
    public function enqueue_admin_portfolio_assets(){
        wp_enqueue_script('jquery');
        wp_enqueue_script( 'jquery-ui-js', plugin_dir_url(__FILE__) . 'js/jquery-ui.min.js', false );
        wp_enqueue_script( 'portfolio-js', plugin_dir_url(__FILE__) . 'js/portfolio-admin.js', false );
        wp_enqueue_style( 'jquery-ui-css', plugin_dir_url(__FILE__) . 'css/jquery-ui.min.css', false );
        wp_enqueue_style( 'portfolio-css', plugin_dir_url(__FILE__) . 'css/portfolio-admin.css', false );
        wp_enqueue_style( 'jquery-theme-css', plugin_dir_url(__FILE__) . 'css/jquery-ui.theme.min.css', false );
        wp_enqueue_style( 'jquery-structure-css', plugin_dir_url(__FILE__) . 'css/jquery-ui.structure.min.css', false );
    }

    /**
     * Enqueue the javascript and css needed for the portfolio frontend templates
     */
    public function enqueue_portfolio_template_assets(){

        $path = get_stylesheet_directory() . '/portfolio-tracker-theme/assets/';

        if(file_exists($path)){
            $path = get_stylesheet_directory_uri() . '/portfolio-tracker-theme/assets/';
        }else {
            $path = plugin_dir_url(__FILE__) . 'portfolio-tracker-theme/assets/';
        }

        wp_enqueue_script('jquery');
        wp_enqueue_script('data-tables', $path . 'js/jquery.dataTables.min.js', array('jquery'));
        wp_enqueue_script('custom-data-tables', $path . 'js/custom.dataTables.js', array('jquery'));
        wp_enqueue_script('chartjs', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js', array('jquery'));
        // Localize the enqueued JS script
        wp_localize_script( 'custom-data-tables', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'place' => '' ) );
        wp_enqueue_style( 'enqueue-font', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700', false );
        wp_enqueue_style( 'jquery-data-tables-css', $path . 'css/jquery.dataTables.min.css', false );
        wp_enqueue_style( 'custom-tracker-css', $path . 'css/jquery.custom.css', false );
    }

    /**
     * Make wordpress aware of the settings we're about to save from the base form
     *
     */
    public function admin_initialize() {
        register_setting( $this->core->config_name . '_group', $this->core->config_name, array($this->core, '_sanitize_option_input'));
    }


    /**
     * Fire off some wordpress stuff I guess...
     *
     */
    public function _wp_hooks(){
        /* Register settings pages */
        add_action('admin_init', array($this, 'admin_initialize'));

        /* Fire up the menu */
        add_action( 'admin_menu', array( $this, 'initialize_menu'));

        /* Register the Portfolio Tracker shortcode */
        add_shortcode('portfolio_tracker', array($this, 'portfolio_tracker_shortcode'));

        /* Allow the admin to clear the portfolio cache */
        add_action( 'wp_ajax_clear_portfolio_cache', array($this->core->cache, 'delete_portfolio_cache' ));

        add_action( 'wp_ajax_tfs_get_performance_data', array( $this->core->ptapi, 'get_performance_data' ) );
        add_action( 'wp_ajax_nopriv_tfs_get_performance_data', array( $this->core->ptapi, 'get_performance_data' ) );

    }

    /**
     * @param int $id
     * @param null $portfolios
     * @return array
     */
    private function get_monthly_return_value ($id, $portfolios = null) {
        $return_values = get_option( 'tfs_pt_' . $id . '_return', false );
        $return_values = unserialize($return_values);
        if ( empty($return_values) ) {
            $return_values = array();
            add_option( 'tfs_pt_' . $id . '_return', $return_values );
        }

        if ( !empty($portfolios) ) {
            foreach ($portfolios as $key => $value) {
                if ( $value->TradeGroup->{'$id'} !== null && $value->TradeGroup->Name === 'Active Positions' ) {
                    $group_id = $value->TradeGroup->{'$id'};
                }
            }
        }

        $return_value = 0;
        $portfolioMonth = '';
        if ( !empty($portfolios) ) {
            foreach($portfolios as $portfolio) {
                if ( $portfolio->TradeGroup->{'$id'} === $group_id || $portfolio->TradeGroup->{'$ref'} === $group_id ) {
                    if ( !empty($portfolio->PositionSetting->Published) && $portfolio->PositionSetting->Published === true &&
                        !empty($portfolio->TradeStatus) && $portfolio->TradeStatus === 1 ) {
                        if (!empty($portfolio->PositionSetting->Text2)) {
                            $text2array = explode('%', $portfolio->PositionSetting->Text2);
                            $text2percentage = $text2array[0];
                        }
                        $proportional_return =
                            $portfolio->OverallGain * ($text2percentage / 100);
                        $return_value = $return_value + $proportional_return;
                        if (!empty ($portfolio->PositionSetting->Text3)) {
                            $portfolioMonth = $portfolio->PositionSetting->Text3;
                        }
                    }
                }
            }
            $monthAsTimestamp = strtotime($portfolioMonth);
            $may19Timestamp = strtotime('MAY 2019');

            if ($monthAsTimestamp >= $may19Timestamp) {
                $return_values[$portfolioMonth] = $return_value;
            }
        }

        update_option( 'tfs_pt_' . $id . '_return', serialize($return_values) );
        return $return_values;
    }

}

$orphan = new Portfolio_Tracker_Core_Plugin();

if(!function_exists('ptracker')){

    /**
     * Shortcut to the framework
     *
     * @return static
     */
    function ptracker(){
        return Portfolio_Tracker_Framework::get_instance();
    }
}
