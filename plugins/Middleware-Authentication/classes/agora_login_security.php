<?php
/**
 * Class: agora_login_security constructor.
 * @author: Threefold Systems
 */

use ReallySimpleJWT\TokenBuilder;
use ReallySimpleJWT\TokenValidator;


class agora_login_security{

	/**
	 * @var string
	 */
	public $key_prefix = 'AGORA_LOGIN_SEC_IP_';


	/**
	 * @var string
	 */
	public $lockdown_key = 'AGORA_LOCKDOWN_';


    /**
     * Description: Secret Key for JWT
     * @var string
     */

    public $secret;


	/**
	 * Description: Constructor
	 * @param agora_api_wrapper $mw
	 * @param int
	 */
	function __construct(agora_api_wrapper $mw, $enabled = 1) {
		$this->mw = $mw;
		$this->enabled = ($enabled == 1) ? true : false;
        $this->secret = "Thr33f0ldSystems?CSsD!@%2^";

    }

	/**
	 * Description: Generate a VID for tokenized logins
	 * @method generate_vid
	 * @param $contactID
	 * @param $orgID
	 * @param $mailingID
	 * @return mixed|string
	 */
	public function generate_vid($contactID, $orgID, $mailingID){
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
	 * Description: Generate a URL to allow single-sign-on across Middleware enabled sites
	 * @method get_single_sign_on_url
	 * @param  string         $target_url
	 * @param string|null     $u = customer number from adv
	 * @param string          $a = passthrough some other additonal info to site
	 * @param bool|null       $sso_flag
	 * @return string
     * @throws \ReallySimpleJWT\Exception\TokenBuilderException
	 */
    public function get_single_sign_on_url($target_url, $u = null, $a = '', $sso_flag = false){

        if(!$u) return $target_url;

        $jwt_payload = array(
              'csid'  => $u,
              'a'   => $a,
              'returnurl' => $target_url
          );

        $params['sk'] = $this->make_signed_jwt($jwt_payload);

        return $target_url . '?' . http_build_query($params);
    }

	/**
	 * Description: Runs when a login action is triggered. Hits the die() function if the user has gone over their rate limit
	 * @method handle_login
	 */
	public function handle_login()
	{
		if(!$this->enabled) return;

		if(!($ip = $this->mw->get_user_ip())) return;

		$die = false;

		if(($count = $this->get_count($ip)) && $count > 50){

			$this->delete_count($ip);
			$this->set_lockdown($ip);
			$die = true;

		}elseif($this->is_locked_down($ip)){

			$die = true;
		}
		if(apply_filters('agora_login_security_die', $die, $ip))
		{
			wp_die(
				__('Too many login attemps from one IP address! Please take a break and try again later'),
				__('Too many login attempts'),
				array('response' => 403)
			);
		}
	}

	/**
	 * Description: Increment a counter when a failed login occurs
	 * @method failed_login
	 */
	public function failed_login(){
		if(!$this->enabled) return;

		if(!($ip = $this->mw->get_user_ip()))
			return;

		$this->increment_count($ip);
	}

	/**
	 * @method increment_count
	 * @param $ip
	 * @return int
	 */
	public function increment_count($ip){
		$c = self::get_count($ip) + 1;

		set_transient($this->get_key($ip), $c, 60*60);

		return $c;
	}

	/**
	 * @method get_key
	 * @param $key
	 * @return string
	 */
	public function get_key($key){
		return $this->key_prefix . $key;
	}

	/**
	 * @method get_count
	 * @param $ip
	 * @return int
	 */
	public function get_count($ip){
		if($x = get_transient($this->get_key($ip)))
			return absint($x);
		return 0;
	}

	/**
	 * @method delete_count
	 * @param $ip
	 */
	public function delete_count($ip){
		delete_transient($this->get_key($ip));
	}

	/**
	 * @method set_lockdown
	 * @param $ip
	 */
	public function set_lockdown($ip){
		set_transient($this->get_lockdown_key($ip), true, 60 * 60);
	}

	/**
	 * @method get_lockdown_key
	 * @param $key
	 * @return string
	 */
	public function get_lockdown_key($key){
		return $this->lockdown_key . $key;
	}

	/**
	 * @method is_locked_down
	 * @param $ip
	 * @return bool
	 */
	public function is_locked_down($ip){
		return (bool) get_transient($this->get_lockdown_key($ip));
	}

	/**
	 * @method clear_lockdown
	 * @param $ip
	 */
	public function clear_lockdown($ip){
		delete_transient($this->get_lockdown_key($ip));
	}

	/**
	 * Description: Run when a login was successful
	 * @method successful_login
	 */
	public function successful_login()
	{
		if(!($ip = $this->mw->get_user_ip()))
			return;

		$this->delete_count($ip);
		$this->clear_lockdown($ip);
	}

	/**
	 * Description: Required to properly generate an MD5 hash matching the corresponding perl hash code.
	 * @method md5_base64
	 * @param $data
	 * @return string
	 */
	function md5_base64 ( $data ){
		$hash = pack("H*", md5($data));
		$encode = base64_encode($hash);
		return $encode;
	}


    /**
     *
     * makeSignedJwt
     * Description: Creates a signed JWT Token
     * @param $payload
     * @return string
     * @throws \ReallySimpleJWT\Exception\TokenBuilderException
     */
    public function make_signed_jwt($payload)
    {

        $builder = new TokenBuilder();
        $current_time = date('Y-m-d H:i:s');
        $expiration = strtotime($current_time . "+3hours");
        $payload=  serialize($payload);

        $token = $builder->addPayload(['key' => 'payload', 'value' => $payload ])
            ->setSecret($this->secret)
            ->setExpiration($expiration)
            ->setIssuer("AUTH")
            ->build();

        return $token;
    }


    /**
     * decryptSignedJWT
     * @param $encrpytedJWT
     * @return mixed
     * @throws Exception
     */
    public function decryptSignedJWT($encrpytedJWT)
    {
        $validator = new TokenValidator();

        try {
            $validator->splitToken($encrpytedJWT)
                ->validateExpiration()
                ->validateSignature($this->secret);
            $payload = $validator->getPayload();
            $arr_payload=  json_decode($payload, true);
            //TODO: Check expiry

            if(is_serialized($arr_payload["payload"])) {

                return unserialize($arr_payload["payload"]);
            } else {

                return $arr_payload("payload");
            }

        } catch(Exception $e){

            return false;

        }





    }


}