<?php
/**
 * Plugin Name: BanyanHill SMS Marketing Opt-in
 * Description: UX for SMS Marketing Opt-in.
 * Version: 2.2.1
 * Author: Banyan Hill Web Team
 */

class BhSmsMarketing
{
    private $callfire;
    private $db;

    public function __construct($db) {
      require(plugin_dir_path(__FILE__) . 'classes/call-fire.php');
      $this->callfire = new BhCallFire();

      if (!$db) {
        error_log("Database connection not established db_extras.", 0);
        return;
      } else if ($db->connect_error) {
        error_log("Connection failed: " . $db->connect_error, 0);
        return;
      } else {
        $this->db = $db;
      }

      register_activation_hook(__FILE__, array($this, 'bh_activate'));

      add_shortcode('bh-sms-marketing', array($this, 'bh_sms_marketing'));

      add_action( 'wp_enqueue_scripts', array($this, 'bh_register_plugin_scripts'));

      add_action( 'wp_ajax_bh_procedure_sms_sign_up', array($this, 'bh_procedure_sms_sign_up'));
      add_action( 'wp_ajax_nopriv_bh_procedure_sms_sign_up', array($this, 'bh_procedure_sms_sign_up'));
    }

    public function bh_procedure_sms_sign_up() {
      if(!isset($_POST['SMSList']) || empty($_POST['SMSList'])){
        $response = array();
        $response[ 'status' ] = 'Error: List not found';
        $response[ 'data' ] = array(
          'message' => $output
        );

        echo json_encode($response);
        die();
      }

      if(!isset($_POST['PhoneNumber']) || empty($_POST['PhoneNumber'])){
        $response = array();
        $response[ 'status' ] = 'Error: Phone Number not found';
        $response[ 'data' ] = array(
          'message' => $output
        );

        echo json_encode($response);
        die();
      }

      if(!isset($_POST['FirstName']) || empty($_POST['FirstName'])){
        $response = array();
        $response[ 'status' ] = 'Error: First name not found';
        $response[ 'data' ] = array(
          'message' => $output
        );

        echo json_encode($response);
        die();
      }

      $list_id = 0;

      $list = $this->callfire->getList($_POST['SMSList']);
      if ( $list->totalCount === 0 ) {
        $list = $this->callfire->insertList($_POST['SMSList']);
        $list_id = $list->id;
      } else if ( $list->totalCount > 1 ) {
        $response = array();
        $response[ 'status' ] = 'Error: multiple lists found';
        $response[ 'data' ] = array(
          'message' => $list
        );

        echo json_encode($response);
        die();
      } else {
        $list_id = $list->items[0]->id;
      }

      if ( $list_id ) {
        $cell = (string)$_POST['PhoneNumber'];
        $cell = "(".$cell[0].$cell[1].$cell[2].") ".$cell[3].$cell[4].$cell[5]." - ".$cell[6].$cell[7].$cell[8].$cell[9];

        $this->db->query("INSERT INTO sms_marketing
          (
            `sms_list`,`cell`,`first_name`,
            `created`,`disclaimer`,`url`,
            `user_agent`,`ip`
          )
            VALUES
          (
            '".$this->db->real_escape_string($_POST['SMSList'])."','".$this->db->real_escape_string($cell)."','".$this->db->real_escape_string($_POST['FirstName'])."',
            '".gmdate('Y-m-d H:i:s')."','".$this->db->real_escape_string($_POST['Disclaimer'])."','".$this->db->real_escape_string($_POST['ReqURL'])."',
            '".$_SERVER['HTTP_USER_AGENT']."','".$this->get_client_ip()."'
          )");

        $rs = $this->db->query("SELECT * FROM `sms_marketing_keyword` WHERE `sms_list`='".$this->db->real_escape_string($_POST['SMSList'])."'");
        if($r = $rs->fetch_assoc()) {
          $text = $this->callfire->sendText($_POST['PhoneNumber'], $r['initial_prompt_message']);

          $this->db->query("INSERT INTO sms_marketing
            (
              `cell`,`first_name`,
              `created`,`message`,`url`,
              `user_agent`,`ip`
            )
              VALUES
            (
              '".$this->db->real_escape_string($cell)."','".$this->db->real_escape_string($_POST['FirstName'])."',
              '".gmdate('Y-m-d H:i:s')."','".$this->db->real_escape_string($r['initial_prompt_message'])."','".$this->db->real_escape_string($_POST['ReqURL'])."',
              '".$_SERVER['HTTP_USER_AGENT']."','".$this->get_client_ip()."'
            )");
        }

        $response = array();
        $response[ 'status' ] = 'success';

        echo json_encode($response);
        die();
      } else {
        $response = array();
        $response[ 'status' ] = 'Error: Unable to create/retrieve list';
        $response[ 'data' ] = array(
          'message' => $list
        );

        echo json_encode($response);
        die();
      }
    }

    public function bh_sms_marketing($atts) {
      if(isset($atts['targeting']) && !empty($atts['targeting'])){
        $targeting = $atts['targeting'];
      } else {
        $targeting = '';
      }

      if(isset($atts['targeting-value']) && !empty($atts['targeting-value'])){
        $targeting_value = $atts['targeting-value'];
      } else {
        $targeting_value = '';
      }

      $this->bh_enqueue_plugin_scripts();

      if(isset($atts['list']) && !empty($atts['list'])){
        $list = $atts['list'];
      } else {
        $list = 'bh-sms-marketing-plugin-general';
      }

      if(isset($atts['timer']) && !empty($atts['timer'])){
        $timer = $atts['timer'];
      } else {
        $timer = '15';
      }

      if(isset($atts['thank-you-redirect']) && !empty($atts['thank-you-redirect'])){
        $thank_you_redirect = $atts['thank-you-redirect'];
      } else {
        $thank_you_redirect = '';
      }

      if(isset($atts['company']) && !empty($atts['company'])){
        $company = $atts['company'];
      } else {
        $company = 'Banyan Hill';
      }

      if(isset($atts['messages']) && !empty($atts['messages'])){
        $messages = $atts['messages'];
      } else {
        $messages = 'Messages';
      }

      $sms_thank_you = $company . ': Thank you for signing up. Msg&data rates may apply. Approx. '.$messages.' Msgs/Month. Txt STOP 2 stop.';

      $content = '';

      $content .= $this->bh_template_goals_modal_sms_opt_in($atts, $list);
      $content .= '<script>
        jQuery(document).ready(function($){
          if (getParameterByName(\''.$targeting.'\') !== null && getParameterByName(\''.$targeting.'\') === \''.$targeting_value.'\') {
        bhSmsMarketingInit("'.$list.'", '.$timer.', "'.$thank_you_redirect.'", "'.$sms_thank_you.'");
      }
        });
      </script>';

      return $content;
    }

    public function bh_register_plugin_scripts() {
      wp_register_style('modal-styles', (get_stylesheet_directory_uri() . '/css/modal-styles.css'));
      wp_register_style('bh-sms-marketing', plugins_url('/css/bh-sms-marketing.css?date=2020-01-16-000', __FILE__));

      wp_register_script('jquery-modal', (get_stylesheet_directory_uri() . '/js/jquery.modal.min.js'));
      wp_register_script('bh-sms-marketing', plugins_url('/js/bh-sms-marketing.js?date=2020-02-12-0', __FILE__));
    }

    public function bh_enqueue_plugin_scripts() {
      wp_enqueue_style('modal-styles');
      wp_enqueue_style('bh-sms-marketing');

      wp_enqueue_script('jquery');
      wp_enqueue_script('jquery-modal');

      $args = array(
        'url'           => admin_url( 'admin-ajax.php' ),
      );

      wp_enqueue_script('bh-sms-marketing');
      wp_localize_script('bh-sms-marketing', 'bhSmSMarketing', $args);
    }

    public function bh_activate() {
      if( !file_exists(get_stylesheet_directory() . '/js/jquery.modal.min.js') ) {
          deactivate_plugins( plugin_basename( __FILE__ ) );
          wp_die( __( 'Please upload jquery.modal.min.js to the current theme under js directory. Could not find within: ' . get_stylesheet_directory() . '/js/jquery.modal.min.js'), 'Plugin dependency check', array( 'back_link' => true ) );
      }

      if( !file_exists(get_stylesheet_directory() . '/css/modal-styles.css') ) {
          deactivate_plugins( plugin_basename( __FILE__ ) );
          wp_die( __( 'Please upload modal-styles.css to the current theme under css directory. Could not find within: ' . get_stylesheet_directory() . '/css/modal-styles.css'), 'Plugin dependency check', array( 'back_link' => true ) );
      }
    }

    public function bh_template_goals_modal_sms_opt_in($atts, $list) {
      $css_wrapper_height = '';
      $html_featured_image = '';
      if(isset($atts['featured-image']) && !empty($atts['featured-image'])){
        $html_featured_image = '<div id="bh-sms-marketing-featured-image" style="background-image: url(\''.$atts['featured-image'].'\');"></div>';
        $css_wrapper_height = 'min-height:170px;';
      }

      if(isset($atts['cta-sms']) && !empty($atts['cta-sms'])){
        $cta_sms = $atts['cta-sms'];
      } else {
        $cta_sms = '';
      }

      if(isset($atts['include-boilerplate-welcome']) && !empty($atts['include-boilerplate-welcome'])){
        $include_boilerplate_welcome = $atts['include-boilerplate-welcome'];
      } else {
        $include_boilerplate_welcome = 'true';
      }

      $builder = '<div id="bh-sms-marketing" class="modal">
        <div id="bh-sms-marketing-logo" style="background-image: url(\''.$atts['header-image'].'\')"></div>
        <div id="bh-sms-marketing-signup">
          <div style="'.$css_wrapper_height.'">
            '.$html_featured_image.'
            <div id="bh-sms-marketing-header">'.$atts['header'].'</div>
          </div>
          <div class="bh-sms-marketing-input-wrapper">
            <input type="text" name="name" id="bh-sms-marketing-name" class="bh-sms-marketing-input" placeholder="First Name" />
            <i class="fa fa-user bh-sms-marketing-input-icon"></i>
          </div>
          <div class="bh-sms-marketing-input-wrapper">
            <input type="text" name="phone" id="bh-sms-marketing-phone" class="bh-sms-marketing-input" placeholder="Phone Number" />
            <i class="fa fa-mobile bh-sms-marketing-input-icon" id="bh-sms-marketing-input-icon-cell"></i>
          </div>
          <button id="bh-sms-marketing-submit"
            data-include-boilerplate-welcome="'.$include_boilerplate_welcome.'"
            data-cta-sms="'.$cta_sms.'" data-list="'.$list.'">Sign up now!</button>
          <div id="bh-sms-marketing-error" style="display:none;"></div>
          <div id="bh-sms-marketing-disclaimer-wrapper">
            <div>
              <label id="bh-sms-marketing-optin-container">
                <input type="checkbox" id="bh-sms-marketing-optin"/>
                <span id="bh-sms-marketing-optin-checkmark"></span>
              </label>
            </div>
            <div id="bh-sms-marketing-disclaimer-text">
              I agree to receive autodialed and prerecorded marketing messages and calls
              from <span style="font-style: italic;">'.$atts['company'].'</span> about <strong>'.$atts['service'].'</strong> at the phone number I provide, even if my
              number is on a national, state or corporate do not call list, and agree to the
              <a href="/disclaimer/">Terms & Conditions</a> (including class action waiver and arbitration provision)
              and <a href="/privacy-policy/">Privacy Policy</a>. Consent is not a condition of purchase.
              Msg&data rates may apply. '.$atts['messages'].' Msgs/Month.
            </div>
          </div>
        </div>
        <div id="bh-sms-marketing-thank-you" style="display:none;">
          <h3 id="bh-sms-marketing-thank-you-header">'.$atts['thank-you-header'].'</h3>
          <div id="bh-sms-marketing-thank-you-sub-header">
            '.$atts['thank-you-sub-header'].'
          </div>
          <div id="bh-sms-marketing-thank-you-redirect" style="display:none;">
            Page will redirect in 5 seconds
          </div>
        </div>
      </div>';

      return $builder;
    }

    private function get_client_ip() {
      $ipaddress = '';
      if (isset($_SERVER['HTTP_CLIENT_IP']))
          $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
      else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
      else if(isset($_SERVER['HTTP_X_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
      else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
      else if(isset($_SERVER['HTTP_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_FORWARDED'];
      else if(isset($_SERVER['REMOTE_ADDR']))
          $ipaddress = $_SERVER['REMOTE_ADDR'];
      else
          $ipaddress = 'UNKNOWN';
      return $ipaddress;
    }

    private function bh_debug_var($obj) {
      print('<pre>');
      var_dump($obj);
      print('</pre>');
    }
}

global $db_extra;
$bh_sms_marketing = new BhSmsMarketing($db_extra);
