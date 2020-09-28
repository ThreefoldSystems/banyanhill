<?php
/**
 * Provides Opium integration for gathering necessary information to create pre-pop links
 */

class CSS_Opium {

    /**
     * An instance of the Agora MW Core Plugin
     * @var $core
     */
    private $core;

    public $config;

    /**
     * CSS_Opium constructor.
     * @param $core
     */
    public function __construct($core, $config)
    {
        $this->core = $core;
        $this->config = $config;
        $this->default_domain = 'https://' . $this->config['opium_url'];
        $this->default_promo = $this->config['opium_promo'];
    }

    /**
     * Build the opium pre-pop link.
     * 
     * @param $urlNick
     * @param $baseUrl
     * @param $promoCode
     * @return string
     */
    public function create_prepop_link($urlNick = "", $promoCode = null, $baseUrl = null, $additional_variables = array()) {

        if (!empty($baseUrl)) {
            $baseUrl = $this->default_domain;
        } else {
            $baseUrl = 'https://' . $baseUrl;
        }
        $vars_to_append = "";

        if (!empty($additional_variables)) {

            foreach($additional_variables as $key => $additional_variable){
                $vars_to_append.= $key . "=" . $additional_variable;
            }
            $vars_to_append = "&".$vars_to_append;
        }

        if (empty($promoCode))
            $promoCode = $this->default_promo;

        if(!empty($urlNick)) {

            if (is_user_logged_in() AND !current_user_can('administrator')) {
                $link = $baseUrl . '/' . $urlNick . '/' . $promoCode . '/index.htm?pageNumber=2&' . $this->get_link_variables() .$vars_to_append;
                return $link;
            }
        }
        return $baseUrl . '/' . $urlNick . '/' . $promoCode;
    }

    /**
     * Function to gather all of the variables required in a opium pre-pop link.
     *
     * @return bool|string
     */
    public function get_link_variables() {
        $customer_id = $this->core->user->get_customer_number();
        $privateKey = OPIUM_PREPOP_KEY;
        $bytes = random_bytes(8);
        $iv = bin2hex($bytes);
        $encrypted = bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $privateKey, $customer_id, MCRYPT_MODE_CBC,
            $iv));
        $urlParam = "ecn=" . $encrypted . "&iv=" .
            $iv . "&r=WEB";
        return ($urlParam);
    }

    /**
     * Function to perform security check on "vid" URL variable sent from the affiliates' website.
     * Note: vid key is always exactly 6 characters long
     *
     * @param $mailingID
     * @param $contactID
     * @param $orgID
     *
     * @return integer
     */
    private function generate_vid($mailingID, $contactID, $orgID){
        $shsec = '5018bbccde7b4d3ecf0b800b39e7200f';
        $valueToHash = $mailingID.'|'.$contactID.'|'.$orgID.'|'.$shsec;
        $hash = $this->md5_base64($valueToHash);
        $hashArray = str_split($hash);
        $vid = $hashArray[1].$hashArray[8].$hashArray[4].$hashArray[15].$hashArray[2].$hashArray[11];
        $vid = str_replace("/", "-", $vid);
        $vid = str_replace("+", "_", $vid);

        return $vid;
    }

    /**
     * Required to properly generate an MD5 hash matching the corresponding perl hash code.
     *
     * @param $data
     * @return string
     */
    function md5_base64 ( $data )
    {
        $hash = pack("H*", md5($data));
        $encode = base64_encode($hash);
        return $encode;
    }

    /**
     * The get mailing history call is really lengthy, sometimes we need to extend the timeout to get it all back.
     *
     * @param $time
     * @return int
     */
    function extend_timeout($time) {
        return 400;
    }
}