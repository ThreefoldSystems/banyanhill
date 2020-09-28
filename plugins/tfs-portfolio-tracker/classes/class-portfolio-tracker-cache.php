<?php

/**
 * Cache Framework
 *
 * This class provides a small wrapper for caching API calls in transients
 *
 * Also of note is that Transients are inherently sped up by caching plugins, where normal Options are not.
 * A memcached plugin, for example, would make WordPress store transient values in fast memory instead of in the database.
 * For this reason, transients should be used to store any data that is expected to expire, or which can expire at any time.
 * Transients should also never be assumed to be in the database, since they may not be stored there at all.
 *
 * Please refer to codex for more documentation about transients: https://codex.wordpress.org/Transients_API
 */
class Portfolio_Tracker_Cache {

    /**
     * Here for future use
     */
    public function __construct(){

    }

    /**
     * Get the cached call that is currently being used
     *
     * @param $name
     * @return mixed
     */
    public function get_portfolio_cache( $name ){
        return get_transient( $name );
    }

    /**
     * Cache an api call
     *
     * @param $name
     * @param $data
     * @param int $expiration
     */
    public function set_portfolio_cache( $name, $data, $expiration = 0 ){
        set_transient( $name, $data, $expiration );
    }

    /**
     * Update the list of currently cached calls for the portfolio tracker API
     *
     * @param $name
     * @param $new_data
     * @param int $expiration
     */
    public function update_portfolio_cache_list( $name, $new_data, $expiration = 0 ){
        $data = get_transient( $name );

        if ( $data ){
            if(!in_array($new_data, $data)) {
                $data[] = $new_data;
                set_transient($name, $data, $expiration);
            }
        } else {
            set_transient( $name, array($new_data), $expiration );
        }
    }

    /**
     * Clear out any cached portfolio information
     *
     * @param $name
     */
    public function delete_portfolio_cache($name){
        $cached_calls = get_transient( $name );
        if(is_array($cached_calls)){
            foreach( $cached_calls as $key=>$value ){
                delete_transient($value);
                unset($cached_calls[$key]);
                $this->set_portfolio_cache($name, $cached_calls);
            }
        }
    }
}