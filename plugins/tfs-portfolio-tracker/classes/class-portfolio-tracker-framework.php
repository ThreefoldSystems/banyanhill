<?php

/**
 * Created by PhpStorm.
 * User: adamwilson
 * Date: 6/8/16
 * Time: 3:35 PM
 */
class Portfolio_Tracker_Framework
{

    /**
     * Give me a singleton
     *
     * @return static
     */
    public static function get_instance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }
        return $instance;
    }

    /**
     * This is gross.  Should be an autoloader..
     */
    protected function __construct()
    {

        include_once('class-portfolio-tracker-db-wrapper.php');
        include_once('class-portfolio-tracker-view.php');
        include_once('class-portfolio-tracker-wrapper.php');
        include_once('class-portfolio-tracker-log.php');

        $this->wp = new Portfolio_DB_Wrapper();

        $this->view = new Portfolio_Tracker_View();

        $this->plugin_admin_page = 'portfolio-tracker';

        $this->domain = 'portfolio_tracker_framework';

        $this->config_name = $this->domain . '_config';

        $base_config = parse_ini_file(dirname(__FILE__) . '/../default-config.ini');

        $this->config = $this->wp->get_option($this->config_name, $base_config);

        $this->portfolio_config_name = 'portfolio_tracker_config';

        $this->portfolio_config = parse_ini_file(dirname(__FILE__) . '/../portfolio-config.ini');

        $this->portfolio_admin_page = 'portfolio-admin';

        // Add a log object to the
        if($this->config['logging'] == 1 && !defined('PORTFOLIO_TRACKER_LOG_ENABLED')){
            define('PORTFOLIO_TRACKER_LOG_ENABLED', $this->config['logging']);
        }

        $this->ptapi = new Portfolio_Tracker_Wrapper($this->config);

        $this->log = new Portfolio_Tracker_Log();

        $this->cache = new Portfolio_Tracker_Cache();

        add_filter('portfolio_tracker_admin_menu', array($this, 'add_tab_items'), 1, 1);
    }

    /**
     * Add in all the admin tabs to be displayed
     *
     * @param $menu
     * @return array
     */
    public function add_tab_items($menu)
    {
        $menu[] = array('title' => __('Base Settings'), 'page' => $this->plugin_admin_page);
        $menu[] = array('title' => __('Portfolio Settings'), 'page' => $this->portfolio_admin_page);
        return $menu;
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
                $input[$k] = str_replace('http://', '', $input[$k]);
                $input[$k] = str_replace('https://', '', $input[$k]);
                $output[$k] = strip_tags( stripslashes( $input[ $k ] ) );
            }
        }
        return apply_filters( 'portfolio_tracker_sanitize_option_input', $output, $input );
    }

}