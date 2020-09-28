<?php


class Portfolio_Tracker_Log {

    /**
     * Boolean to indicate if logging is enabled or disabled
     * @var boolean
     */
    public $enabled = false;
    /**
     *	Constructor
     *
     **/
    function __construct(){
        if ( defined( 'PORTFOLIO_TRACKER_LOG_ENABLED' ) && PORTFOLIO_TRACKER_LOG_ENABLED == 1 ) {
            $this->enabled = true;
        }
    }
    /**
     *	__call() Magic method to call log methods depending on which named function was called.
     *
     *	@param string $method The name of the nonexistent method called
     *	@param array $args [0 => 'A text message describing the event', 1 => $object An object or array of data to output to the log] An array of the arguments passed to the method
     *	@return void
     **/
    public function __call($method, $args = array()){
        if(!in_array(strtolower($method), array('debug' , 'info', 'notice', 'warn', 'error', 'crit', 'emerg') ) )
            return;
        $method = ucwords($method);
        if($this->enabled == true){
            error_log('*** MW Log: ' . $method . ': ' . $args[0]);
            if(isset($args[1]))
                error_log(print_r($args[1], true));
        }
    }

}