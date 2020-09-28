<?php

class Portfolio_Tracker_API_Wrapper {
    /**
     * The URL to use for the API calls
     * @var string
     */
    public $url;
    /**
     * Function to retrieve the *users* IP address
     */
    public function get_user_ip(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return filter_var($ip, FILTER_VALIDATE_IP);
    }
}