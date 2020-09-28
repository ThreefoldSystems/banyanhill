<?php
/**
 *	A class to wrap tradestops api calls
 *
 *	@package agora_tradestops_api_wrapper
 *
 *	@author Adam Wilson
 *
 **/

require('class-portfolio-tracker-api-wrapper.php');
require('class-portfolio-tracker-cache.php');

class Portfolio_Tracker_Wrapper extends Portfolio_Tracker_API_Wrapper {

    /**
     * Object container for the logging system.
     * @var object
     */
    private $log;

    /**
     *  PHP Constructor
     */
    public function __construct($config){
		//Hotfix for multi API consumption
		$use_dent_API = false;
		$dent_API_urls = array(
			'/boom-and-bust/',
			'/boom-and-bust-elite/',
			'/john-del-vecchios-hidden-fortunes/',
			'/peak-income/',
			'/fortune-hunter/',
			'/cannabis-paydays/',
			'/peak-profits/',
			'/john-del-vecchios-small-cap-all-stars/',
			'/profits-accelerator/',
			'/instant-income-alert/',
			'/tech-profits-accelerator/',
			'/delta-profit-trader/'
		);
		
		if ( in_array( $_SERVER['REQUEST_URI'], $dent_API_urls ) ) {
			$use_dent_API = true;
		}
		
        $this->token = '?ApiKey=' . ($use_dent_API === true ? 'TUjqmelwEUiUxlntV_yI4g' : $config['token']);
        $this->url = $config['url'];

        $this->http_args = array('headers' => array('token' => $this->token), 'sslverify' => false);
        $this->portfolio_url = 'https://' . $this->url . '/api/ApiPortfolio/';
        $this->position_url = 'https://' . $this->url . '/api/ApiPosition/';

        if($config['cache'] == 1) {
            $this->cache = new Portfolio_Tracker_Cache();
            $this->cache_it = true;
            $this->cache_expiration = $config['hours'] * 3600 + $config['minutes'] * 60;
        } else {
            $this->cache_it = false;
        }
        $this->log = new Portfolio_Tracker_Log();
    }

    /**
     * getAllPortfolios
     * Get all of the portfolio IDs and Names
     *
     * @return mixed
     */
    function get_all_portfolios(){
        $url = $this->portfolio_url . 'GetAllPortfolios/' . $this->token;
        return $this->_get($url);
    }

    /**
     * Get
     * pulls all of the information in from the general tab
     * @param id
     *
     * @return mixed
     */
    function get_general($id){
        $url = $this->portfolio_url . 'Get/' . $this->token . '&id=' . $id;
        return $this->_get($url);
    }

    /**
     * getAllByCustomerId
     * pull all of the data inside the general tabs for all of the portfolios inside your portfolio
     *
     * @return mixed
     */
    function get_all_by_customer_id(){
        $url = $this->portfolio_url . 'GetAllByCustomerId/' . $this->token;
        return $this->_get($url);
    }

    /**
     * getFirstByCustomerId
     * get the first portfolio in the account
     *
     * @return mixed
     */
    function get_first_portfolio_by_customer_id(){
        $url = $this->portfolio_url . 'GetFirstPortfolioByCustomerId/' . $this->token;
        return $this->_get($url);
    }

    /**
     * getListPositionsByPortfolioId
     * pulls the Names, symbols and Ids for each portfolio
     * @param portfolio_id
     *
     * @return mixed
     */
    function get_list_positions_by_portfolio_id($portfolio_id){
        //this url has a typo on purpose.  Tradestops needs to fix this...
        $url = $this->position_url . 'GetListPositinsByPortfolioId/' . $this->token . '&portfolioid=' . $portfolio_id;
        return $this->_get($url);
    }

    /**
     * getAllByPortfolioId
     * Get the customer number from their Message Central Contact ID and Org ID
     * @param $portfolio_id
     *
     * @return mixed
     */
    function get_all_by_portfolio_id($portfolio_id){
        $url = $this->position_url . 'GetAllByPortfolioId/' . $this->token . '&portfolioId=' . $portfolio_id;
        return $this->_get($url);
    }

    /**
     * Get
     * Gets all of the data available in "Trade Management" on a particular position
     * @param $id
     *
     * @return mixed
     */
    function get_trade_management($id){
        $url = $this->position_url . 'Get/' . $this->token . '&Id=' . $id;
        return $this->_get($url);
    }

    /**
     * Get the 5yr performance data using Ajax
     * @return string
     */
    public function get_performance_data() {

        $symbol              = isset( $_POST['symbol'] ) ? strip_tags( $_POST['symbol'] ) : 'ADSK';
        $portfolio_id        = isset( $_POST['portfolio_id'] ) ? sanitize_text_field( $_POST['portfolio_id'] ) : false;
        $alternative_source  = get_transient( 'alternative_source_' . $portfolio_id );
        if ( !empty($symbol) && is_array( $alternative_source ) && array_key_exists( $symbol, $alternative_source ) ) {
            $alternative_source = trim( $alternative_source[$symbol] );
        } else {
            $alternative_source = false;
        }
        $period              = isset( $_POST['period'] ) ? sanitize_text_field( $_POST['period'] ) : '5y';
        $width               = isset( $_POST['width'] ) ? sanitize_text_field( $_POST['width'] ) : '253';
        $all_periods   = array( '1y', '3y', '5y' );

        // get portfolio backup data
        if ( ! empty( $alternative_source ) || $alternative_source != null ) {

            $alternative_source_text = get_option( 'alternative_source_text' ) ?:
                'Please note: full five year performance figures are not available. Please click the link below to view an alternative source:';
            if ( preg_match( '/^http(s)?:\/\//', $alternative_source ) ) {
                printf( '<div class="alternative-source">%s<br><br><a href="%s" target="_blank">%s</a></div>',
                    $alternative_source_text, $alternative_source, $alternative_source );
            } else {
                printf( '<div class="no-data">%s</div>', $alternative_source );
            }

        } else if ( !empty($symbol) ) {
            $uid = md5( $symbol );


            $symbol_data_url = "http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=1y&symbol=" . $symbol;

            $symbol_data = $this->_get($symbol_data_url, true);

            if ( !is_wp_error($symbol_data) && !empty($symbol_data) ) {
                $symbol_data = new SimpleXMLElement( $symbol_data );

                $charts = array();
                foreach ( $all_periods as $this_period ) {

                    if ( ! is_dir( $this->base_dir . $uid . '-' . $period ) ) {
                        mkdir( $this->base_dir . $uid  . '-' . $period, 0755, true );
                    }

                    $chart_url = "http://charts.rightwaytrader.com/v.0.1.1/mscharts/marketdatainfochart.asmx/GetDailyChart?symbol=" .
                        $symbol . "&periodType=" . $this_period . "&caption=&width=" . $width . "&height=" . round($width * 25 / 47);
                    $chart = $this->_get($chart_url, true);

                    $chart = new SimpleXMLElement( $chart );

                    if ( !$chart->__toString() ) {
                        $chart_img = '<span>There is no image to return<span/>';
                    } else {
                        $chart_img = '<img class="performance-image" alt="'. $this_period .'" src="data:image/png;base64,'. $chart .'"/>';
                    }

                    $charts[$this_period] = $chart_img;
                }

                include_once(dirname(__FILE__) . '/../views/performance-template.php');

            }

        } else {
            echo $this->no_data;
        }
        wp_die();
    }


    /**
     *	A helper method to reduce repetition
     *
     *	@param string $url
     *	@return array Associative array of returned data. Returns WP_Error object on error
     **/
    private function _get( $url, $xml = null ){
        $this->log->info('Tradestops GET Request to: ' . $url);
        $url = esc_url_raw($url);
        $this->http_args['timeout'] = 50;
        //generate URL of MD5
        $cached_call = md5($url);

        if( $this->cache_it ) {
            $cache_exists = $this->cache->get_portfolio_cache( $cached_call );
            if($cache_exists){
                $this->log->info('Returned: Cached Version');
                return $cache_exists;
            }
        }

        $result = wp_remote_get($url, $this->http_args);
        if ( is_wp_error($result) ) {
            /**
             * WP error can happen if we can't connect or get internal server errors etc.
             */
            $this->log->error('WP Error', $result);
            return $result;
        } elseif ( $result['response']['code'] == 200 ) {
            if ( empty($xml)) {
                /**
                 * This is a successful call and will return a php object
                 */
                $response_content = json_decode(wp_remote_retrieve_body($result));
            } else {
                $response_content = wp_remote_retrieve_body($result);
            }
            $this->log->info('Response', $response_content);
            if( $this->cache_it ) {
                $this->cache->update_portfolio_cache_list('portfolio_tracker_cache_list', $cached_call);
                $this->cache->set_portfolio_cache($cached_call, $response_content, $this->cache_expiration);
            }
            return $response_content;
        }else{
            /**
             * Some other thing happened, log an error
             */
            $this->log->error($url, $result);
        }
    }
}
