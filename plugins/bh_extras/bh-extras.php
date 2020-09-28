<?php
/**
 * Plugin Name: BanyanHill Extras
 * Description: Function for SUA integration, post delete tracking, API bh, watchlist, and register account
 * Version: 2.4.0
 * Author: BanyanHill Web Team
 */

class BhExtras
{
  private $db;
  private $agora;
  private $bh_lead_controller;
  private $bh_carl_controller;
  private $origin;
  private $deployment;

  public function __construct($agora, $db) {
    register_activation_hook(__FILE__, array($this, 'bh_activate'));

    if (!$db) {
      error_log("Database connection not established db_extras.", 0);
      return;
    } else if ($db->connect_error) {
      error_log("Connection failed: " . $db->connect_error, 0);
      return;
    } else {
      $this->db = $db;
    }

    if(strpos($_SERVER['SERVER_NAME'], "banyanhill") !== false) {
      $this->origin = 'banyanhill';
    } else if(strpos($_SERVER['SERVER_NAME'], "moneyandmarkets") !== false) {
      $this->origin = 'mam';
    } else {
      $this->origin = '';
    }

    if(strpos($_SERVER['SERVER_NAME'], "dev") !== false) {
      $this->deployment = 'dev';
    } else {
      $this->deployment = 'prod';
    }

    if ($agora) {
      $this->agora = $agora;

      add_action('init', array($this, 'bh_register_verify_user_code'));

      add_action('wp_ajax_bh_register_new_account', array($this, 'bh_register_new_account'));
      add_action('wp_ajax_nopriv_bh_register_new_account', array($this, 'bh_register_new_account'));

      add_shortcode('bh-register', array($this, 'bh_register_user'));

      add_filter('wp_authenticate_user', array($this, 'bh_register_user_active'));
    }

    wp_enqueue_style('bh-extras-shared', plugins_url('/css/bh-extras-shared.css?date=2020-02-28-000', __FILE__));
    wp_enqueue_script('bh-extras-shared', plugins_url('/js/bh-extras-shared.js?date=2020-02-28-000', __FILE__));

    add_shortcode('bh-sua', array($this, 'bh_sua'));

    add_shortcode('bh-watchlist', array($this, 'bh_watchlist'));

    add_action('admin_menu', array($this, 'bh_register_settings'));

    add_action('wp_enqueue_scripts', array($this, 'bh_register_plugin_scripts'));
    add_action('transition_post_status', array($this, 'bh_transition_post_status'), 10, 3);

    add_action('wp_ajax_bh_watchlist_ajax', array($this, 'bh_watchlist_ajax'));

    require(plugin_dir_path(__FILE__) . 'classes/lead.php');
    require(plugin_dir_path(__FILE__) . 'classes/carl.php');

    $this->bh_carl_controller = new BhCarlController();
    $this->bh_lead_controller = new BhLeadController($this->db, $this->bh_carl_controller);
  }

  public function bh_register_settings() {
    add_options_page( 'BH Page History', 'BH Page History', 'manage_options', 'bh-page-history',  array($this, 'bh_page_history_settings_page' ));
  }

  public function bh_page_history_settings_page() {
    $html = '<h1>Page History</h1>';

    $start = gmdate('Y-m-d', strtotime('-2 weeks'));
    $end = gmdate('Y-m-d', strtotime('+1 day'));
    if(isset($_GET['start'])) {
      $start = $this->db->real_escape_string($_GET['start']);
    }

    if(isset($_GET['end'])) {
      $end = $this->db->real_escape_string($_GET['end']);
    }

    $rs = $this->db->query("SELECT * FROM `info` WHERE `name`='event-trash-".$this->deployment."-".$this->origin."' AND `created` > '$start' AND `created` < '$end';");

    $html .= '
      <form action="/wp-admin/options-general.php" method="get">
        <input type="hidden" name="page" value="bh-page-history"/>
        <div style="display: flex;">
          <div>
            <div style="width:50pt;">Start</div>
            <input type="text" name="start" value="'.$start.'" />
          </div>
          <div>
            <div style="width:50pt;">End</div>
            <input type="text" name="end" value="'.$end.'" />
          </div>
          <div>
            <div style="width:50pt;">&nbsp;</div>
            <button class="button button-primary" type="submit">Search</button>
          </div>
        </div>
      </form>';

    $html .= '<div>';
    $html .= '
      <div style="display: flex;">
        <div style="width:150pt;padding:5px;"><strong>Post</strong></div>
        <div style="width:150pt;padding:5px;"><strong>UID</strong></div>
        <div style="width:150pt;padding:5px;"><strong>Date</strong></div>
      </div>
    ';
    while($r = $rs->fetch_assoc()){
      $data = json_decode($r['val'], true);

      $html .= '
        <div style="display: flex;">
          <div style="width:150pt;padding:5px;"><a target="_blank" href="/wp-admin/post.php?post='.$data['post'].'&action=edit">'.$data['post'].'</a></div>
          <div style="width:150pt;padding:5px;"><a target="_blank" href="/wp-admin/user-edit.php?user_id='.$data['uid'].'">'.$data['uid'].'</a></div>
          <div style="width:150pt;padding:5px;">'.$r['created'].'</div>
        </div>';
    }
    $html .= '</div>';

    echo $html;
  }

  public function bh_register_plugin_scripts() {
    wp_register_style('modal-styles', (get_stylesheet_directory_uri() . '/css/modal-styles.css'));
    wp_register_style('bh-extras', plugins_url('/css/bh-extras.css?date=2020-01-31-000', __FILE__));

    wp_register_script('bh-extras', plugins_url('/js/bh-extras.js?date=2020-01-29-030', __FILE__), array(), '', true);
    wp_register_script('jquery-modal', (get_stylesheet_directory_uri() . '/js/jquery.modal.min.js'));
  }

  public function bh_watchlist_ajax() {
    $check = check_ajax_referer( 'bh-watchlist', 'nonce', false );
    if(!$check) {
      wp_send_json_success( array(
        'success' => false,
        'msg' => 'Current session has timed out.',
        'op' => 'reload'
      ) );
    }

    $rsp = true;
    $msg = '';
    $rt = [];

    if(isset($_POST['op'])) {
      if($_POST['op'] == 'upsert') {
        $rs = $this->db->query("INSERT INTO `watchlist`
          (
            `id`,
            `origin`,
            `userid`,
            `created`,
            `name`,
            `symbol`,
            `enabled`,
            `position`
          )
          VALUES
          (
            ".$this->db->real_escape_string($_POST['id']).",
            '".$this->origin."',
            ".get_current_user_id().",
            '".gmdate("Y-m-d H:i:s")."',
            '".$this->db->real_escape_string($_POST['name'])."',
            '".$this->db->real_escape_string($_POST['symbol'])."',
            1,
            9999
          )
          ON DUPLICATE KEY UPDATE
            `symbol` = '".$this->db->real_escape_string($_POST['symbol'])."'"
        );

        if(!$rs || $rs->error) {
          $rsp = false;
          $msg = $rs->error;
        }
      } else if ($_POST['op'] == 'delete') {
        $this->db->query("UPDATE `watchlist` SET `enabled` = 0 WHERE `origin`='".$this->origin."' AND `id`=".$this->db->real_escape_string($_POST['id']));
      } else if ($_POST['op'] == 'position') {
          foreach($_POST['data'] as &$row) {
              $this->db->query("UPDATE `watchlist` SET `position` = ".$this->db->real_escape_string($row['position'])." WHERE `origin`='".$this->origin."' AND `id`=".$this->db->real_escape_string($row['id']));
          }
      }
    } else if (isset($_GET['op'])) {
      if($_GET['op'] == 'rt') {
        $rs = $this->db->query("SELECT * FROM `watchlist` WHERE `origin`='".$this->origin."' AND `enabled` = 1 AND `userid`= ".get_current_user_id()." ORDER BY `position` ASC");
        while($r = $rs->fetch_assoc()) {
          $rt[]= $r;
        }
      }
    }

    wp_send_json_success( array(
      'success' => $rsp,
      'msg' => $msg,
      'rt' => $rt
    ) );
  }

  public function bh_watchlist($atts) {
    $this->bh_enqueue_plugin_scripts();

    $content = $this->bh_watchlist_html($atts);

    return $content;
  }

  private function bh_watchlist_html($atts) {
    wp_enqueue_script('jquery-ui-sortable');
    if(isset($atts['class']) && !empty($atts['class'])) {
        $class = $atts['class'];
    } else {
        $class = '';
    }

    if ( is_user_logged_in() ) {
      $content = '
        <div class="bh-watchlist-add-wrapper">
            <button class="bh-watchlist-add" class="btn">Add</button>
        </div>

        <div class="bh-watchlist-wrapper">
        </div>
        ';
    } else {
      if ($this->origin == 'mam') {
        $header = 'Money & Markets';
        $action_login = 'jQuery(\'[name=redirect_to]\').val(\''. urlencode(preg_replace('/[^A-Za-z0-9\-\/?]/', '',$_SERVER['REQUEST_URI'])) .'\'); jQuery(\'#modal-login\').modal({fadeDuration: 250, fadeDelay: 0.80})';
      } else {
        $header = 'Banyan Hill';
        $action_login = 'location.href = \'/customer-login?redirect_to=' . urlencode(preg_replace('/[^A-Za-z0-9\-\/?]/', '',$_SERVER['REQUEST_URI'])) . "'";
      }

      $content = '
        <div class="bh-watchlist-container">
          <h3 class="bh-watchlist-header">Customize '.$header.'</h3>
          <div class="bh-watchlist-sub-header">Have Watchlists? Login to see them here or create an account to get started.</div>
          <div class="bh-watchlist-action">
            <button class="btn btn-primary" onclick="location.href = \'/register/\'">CREATE ACCOUNT</button> ... or <span onclick="'.$action_login.'">Login</span>
          </div>
        </div>';
    }


    return '<div class="'.$class.'">' . $content . '</div>';
  }

  public function bh_register_verify_user_code(){
      if(isset($_GET['confirm_act'])){
          $data = unserialize(base64_decode($_GET['confirm_act']));
          $code = get_user_meta($data['id'], 'activation_code', true);

          if($code == $data['code']){
            update_user_meta($data['id'], 'account_activated', 'active');

            $meta = get_user_meta($data['id']);

            $customer = $this->agora->mw->put_create_customer_by_email($meta['email'][0]);

            if(isset($customer->error_data['post_request_failed']['body']) && !empty($customer->error_data['post_request_failed']['body'])){
              wp_redirect("/login/?notification=error_agora");
              exit;
            } else {
              $this->agora->mw->put_add_account_by_id_username_pass($customer->customerNumber, $meta['email'][0], $meta['password'][0]);
              $this->agora->mw->put_update_postal_address(
                array(
                  'customerNumber' => $customer->customerNumber,
                  'firstName' => $meta['first_name'][0],
                  'lastName' => $meta['last_name'][0]
                )
              );

              $this->bh_log_event('mw-create-customer',
                array(
                  'email' => $meta['email'][0],
                  'password' => $meta['password'][0],
                  'customerNumber' => $customer->customerNumber
                )
              );

              $this->bh_register_update_mw_meta($data['id'], $customer->customerNumber, $meta['email'][0], $meta['password'][0], $meta['first_name'][0], $meta['last_name'][0]);

              wp_redirect("/login/?notification=account_confirmed");
              exit;
            }

          }
      } elseif(isset($_GET['resend_confirm_act'])) {
        $data = unserialize(base64_decode($_GET['resend_confirm_act']));
        $email = get_user_meta($data['id'], 'email', true);

        $this->bh_set_confirmation_account($data['id'], $email);

        if (strpos($email, 'gmail.com') !== false) {
          wp_redirect("/login/?notification=confirm_gmail");
        } else {
          wp_redirect("/login/?notification=confirm_email");
        }

        exit;
      }
  }

  public function bh_register_user_active($user){
    $user_status = get_user_meta($user->ID, 'account_activated', true);

    if(!empty($user_status) && $user_status == 'inactive'){
      $hash = base64_encode(serialize(array('id' => $user->ID)));

      wp_redirect("/login/?notification=account_needs_confirmation&hash=".$hash);
      exit;
    }

    return $user;
  }

  private function bh_register_update_mw_meta($user_id, $customerNumber, $email, $password, $first_name, $last_name) {
    $portalCode = (object) array('authGroup' => 'SOC');

    $id = (object) array(
      'userName'    => $email,
      'portalCode'  => $portalCode,
      'authType'    => 'E'
    );

    // $subscriptions = $this->agora->mw->findSubscriptionByEmailAddress('BANYANHILLTESTUSER3@GMAIL.COM');
    // foreach($subscriptions->subscriptions as $key => $subscription) {
    //   if(isset($subscription->id->customerNumber)){
    //     $subscriptions->subscriptions[$key]->id->customerNumber = $customerNumber;
    //   }
    // }
    // if using below $subscriptions->subscriptions

    $subscriptionsAndOrders = (object) array(
      'subscriptions'           => array(),
      'productOrders'           => array(),
      'accessMaintenanceOrders' => array()
    );

    $meta = (object) array(
      'accounts'                => array(),
      'emailAddresses'          => array(),
      'subscriptionsAndOrders'  => $subscriptionsAndOrders,
      'postalAddresses'         => array()
    );

    $meta->accounts[]= (object) array(
      'customerNumber'              => $customerNumber,
      'role'                        => '',
      'temp'                        => false,
      'password'                    => $password,
      'id'                          => $id,
      'denyAccess'                  => 'N',
      'authStatus'                  => 'A'
    );

    $id = (object) array(
      'customerNumber'  => $customerNumber,
      'addressCode'     => 'ADDR-01',
      'addressFlag'     => '0'
    );

    $email = (object) array(
      'emailAddress'  => $email,
      'temp'          => false
    );

    $meta->postalAddresses[]= (object) array(
      'countryCode'           => '',
      'postalCode'            => '',
      'street'                => '',
      'street2'               => '',
      'street3'               => '',
      'city'                  => '',
      'state'                 => '',
      'county'                => '',
      'firstName'             => $first_name,
      'middleInitial'         => '',
      'lastName'              => $last_name,
      'companyName'           => '',
      'departmentName'        => '',
      'phoneNumber'           => '',
      'phoneNumber2'          => '',
      'phoneNumber3'          => '',
      'faxNumber'             => '',
      'suffix'                => '',
      'title'                 => '',
      'emailAddress'          => $email,
      'birthDate'             => '',
      'temp'                  => false,
      'id'                    => $id
    );

    $meta->emailAddresses[]= $email;

    update_user_meta($user_id, 'agora_middleware_aggregate_data', $meta);
  }

  public function bh_register_new_account() {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      wp_send_json(
        array(
          'success' => false
        )
      );
    }

    $check_email = $this->agora->mw->get_account_by_email(trim($_POST['email']));

    if(is_array($check_email) && isset($check_email[0]->customerNumber)){
      wp_send_json(
        array(
          'success' => false,
          'message' => 'Email address already appears to exist'
        )
      );
    } else {
      $user = get_user_by( 'email', $_POST['email'] );
      if($user) {
        wp_send_json(
          array(
            'success' => false,
            'message' => 'Email address already appears to exist'
          )
        );
      }

      $user_id = wp_create_user($_POST['email'], $_POST['password'], $_POST['email']);

      if(!$user_id) {
        wp_send_json(
          array(
            'success' => false,
            'message' => 'Sorry, unknown error please try again.'
          )
        );
      } else {
        $first_name = '';
        if(isset($_POST['first_name']) && !empty($_POST['first_name'])) {
          $first_name = $_POST['first_name'];
        }

        $last_name = '';
        if(isset($_POST['last_name']) && !empty($_POST['last_name'])) {
          $last_name = $_POST['last_name'];
        }

        update_user_meta($user_id, "email", $_POST['email']);
        update_user_meta($user_id, "password", $_POST['password']);

        update_user_meta($user_id, "first_name", $first_name);
        update_user_meta($user_id, "last_name", $last_name);

        update_user_meta($user_id, 'account_activated', 'inactive');

        $this->bh_set_confirmation_account($user_id, $_POST['email']);

        wp_send_json(
          array(
            'success' => true
          )
        );
      }
    }

  }

  private function bh_set_confirmation_account($user_id, $email){
    $user_status = get_user_meta($user_id, 'account_activated', true);

    if(!empty($user_status) && $user_status == 'inactive'){
      $code = md5(time());

      $string = array('id' => $user_id, 'code' => $code);

      update_user_meta($user_id, 'activation_code', $code);

      $url = get_site_url(). '/?confirm_act=' . base64_encode(serialize($string));

      $body_email = file_get_contents(plugin_dir_path(__FILE__) . 'templates/mam_confirmation_email.html');

      $name = get_user_meta($user_id, 'first_name', true);
      $date = date('F j, Y');
      $date_year = date('Y');
      $link = 'Please click the following links <br/><br/> <a href="'.$url.'">'.$url.'</a>';

      $body_email = str_replace('{name}', $name, $body_email);
      $body_email = str_replace('{date}', $date, $body_email);
      $body_email = str_replace('{date_year}', $date_year, $body_email);
      $body_email = str_replace('{link}', $link, $body_email);

      if ($this->origin === 'mam') {
        $header = 'Money & Markets';
      } else {
        $header = 'Banyan Hill';
      }
      $headers = array('Content-Type: text/html; charset=UTF-8');
      wp_mail($email, 'Welcome to '.$header.'!', $body_email, $headers);
    }
  }

  private function bh_log_event($key, $val) {
    $this->db->query("INSERT INTO info
      (
        `name`,`val`,
        `created`
      )
        VALUES
      (
        '$key','".json_encode($val)."',
        '".gmdate('Y-m-d H:i:s')."'
      )");
  }

  public function bh_transition_post_status( $new_status, $old_status, $post ) {
    if ( 'trash' == $new_status ) {
      $uid = get_current_user_id();

      $d = array('post' => $post->ID, 'uid' => $uid);

      $this->db->query("INSERT INTO info
        (
          `name`,`val`,
          `created`
        )
          VALUES
        (
          'event-trash-".$this->deployment."-".$this->origin."','".json_encode($d)."',
          '".gmdate('Y-m-d H:i:s')."'
        )");

      //2020-06-02 AJL removing email notification
      //wp_mail( 'WebTeam@banyanhill.com', 'Post trashed: ' .  $post->ID, 'UserID: ' . $uid . ' trashed PostID:' . $post->ID);
    }
  }

  public function bh_sua($atts) {
    $this->bh_enqueue_plugin_scripts();

    $content = '';

    if(isset($atts['type']) && !empty($atts['type']) && $atts['type'] == 'two-input'){
      $content = $this->bh_html_two_input($atts);
    }

    return $content;
  }

  public function bh_register_user($atts) {
    $this->bh_enqueue_plugin_scripts();

    $content = '';

    $content = $this->bh_html_register($atts);

    return $content;
  }

  public function bh_activate() {
    // Example
    // if( !file_exists(get_stylesheet_directory() . '/js/jquery.modal.min.js') ) {
    //     deactivate_plugins( plugin_basename( __FILE__ ) );
    //     wp_die( __( 'Please upload jquery.modal.min.js to the current theme under js directory. Could not find within: ' . get_stylesheet_directory() . '/js/jquery.modal.min.js'), 'Plugin dependency check', array( 'back_link' => true ) );
    // }
  }

  public function bh_enqueue_plugin_scripts() {
    wp_enqueue_style('modal-styles');
    wp_enqueue_style('bh-extras');

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-modal');
    wp_enqueue_script('bh-extras');
    wp_localize_script( 'bh-extras', 'bhSearchExtras', array(
        'topstock'   => bh_stock_ticker_top(),
        'nonce'      => wp_create_nonce( 'bh-watchlist' ),
      )
    );
  }

  private function bh_html_register($atts) {
    $content =  '<form id="bh-register" method="POST">';
    $content .= '  <div id="bh-register-error"></div>';
    $content .= '  <div><input class="bh-register-form" type="email" name="email" placeholder="Email"/></div>';
    $content .= '  <div><input class="bh-register-form" type="text" name="first_name" placeholder="First Name"/></div>';
    $content .= '  <div><input class="bh-register-form" type="text" name="last_name" placeholder="Last Name"/></div>';
    $content .= '  <div><input class="bh-register-form" type="password" name="password_one" placeholder="******"/></div>';
    $content .= '  <div><input class="bh-register-form" type="password" name="password_two" placeholder="******"/></div>';
    $content .= '   <button id="bh-register-action" type="submit" class="btn btn-primary">CREATE ACCOUNT</button>';
    $content .= '</form>';

    return $content;
  }

  private function bh_html_two_input($atts) {
    $content = '';

    if(isset($atts['cta-button-one-background-color']) && !empty($atts['cta-button-one-background-color'])) {
      $cta_button_one_background_color = $atts['cta-button-one-background-color'];
    } else {
      $cta_button_one_background_color = '';
    }

    if(isset($atts['cta-button-one-color']) && !empty($atts['cta-button-one-color'])) {
      $cta_button_one_color = $atts['cta-button-one-color'];
    } else {
      $cta_button_one_color = '';
    }

    if(isset($atts['cta-button-one-background-color-gradient']) && !empty($atts['cta-button-one-background-color-gradient'])) {
      $cta_button_one_background_color_gradient = $atts['cta-button-one-background-color-gradient'];
    } else {
      $cta_button_one_background_color_gradient = '';
    }

    if(isset($atts['cta-button-two-background-color']) && !empty($atts['cta-button-two-background-color'])) {
      $cta_button_two_background_color = $atts['cta-button-two-background-color'];
    } else {
      $cta_button_two_background_color = '#113752';
    }

    if(isset($atts['cta-button-two-color']) && !empty($atts['cta-button-two-color'])) {
      $cta_button_two_color = $atts['cta-button-two-color'];
    } else {
      $cta_button_two_color = 'white';
    }

    if(isset($atts['cta-button-two-background-color']) && !empty($atts['cta-button-two-background-color-gradient'])) {
      $cta_button_two_background_color_gradient = $atts['cta-button-two-background-color-gradient'];
    } else {
      $cta_button_two_background_color_gradient = '#113752';
    }

    if(isset($atts['cta-action-size']) && !empty($atts['cta-action-size'])) {
      $cta_action_size = $atts['cta-action-size'];
    } else {
      $cta_action_size = '17px';
    }

    if(isset($atts['cta-action-size-email']) && !empty($atts['cta-action-size-email'])) {
      $cta_action_size_email = $atts['cta-action-size-email'];
    } else {
      $cta_action_size_email = '17px';
    }

    if(isset($atts['cta-action-size-padding']) && !empty($atts['cta-action-size-padding'])) {
      $cta_action_size_padding = $atts['cta-action-size-padding'];
    } else {
      $cta_action_size_padding = '25px';
    }

    if(!empty($cta_button_one_background_color_gradient)){
      $content .= '
      <style>
        #bh-sua-cta-button-one:hover {
          background-position: 100px;
        }
        #bh-sua-cta-button-one {
          background: -webkit-linear-gradient('.$cta_button_one_background_color.','.$cta_button_one_background_color_gradient.');
          background: -moz-linear-gradient('.$cta_button_one_background_color.','.$cta_button_one_background_color_gradient.');
          background: -o-linear-gradient('.$cta_button_one_background_color.','.$cta_button_one_background_color_gradient.');
          background: linear-gradient('.$cta_button_one_background_color.','.$cta_button_one_background_color_gradient.');

          -webkit-transition: background 1s ease-out;
          -moz-transition: background 1s ease-out;
          -o-transition: background 1s ease-out;
          transition: background 1s ease-out;
          background-size: 1px 200px;
        }
      </style>';
    }

    if(!empty($cta_button_two_background_color_gradient)){
      $content .= '
      <style>
        #bh-sua-cta-button-two:hover {
          background-position: 100px;
        }
        #bh-sua-cta-button-two {
          background: -webkit-linear-gradient('.$cta_button_two_background_color.','.$cta_button_two_background_color_gradient.');
          background: -moz-linear-gradient('.$cta_button_two_background_color.','.$cta_button_two_background_color_gradient.');
          background: -o-linear-gradient('.$cta_button_two_background_color.','.$cta_button_two_background_color_gradient.');
          background: linear-gradient('.$cta_button_two_background_color.','.$cta_button_two_background_color_gradient.');

          -webkit-transition: background 1s ease-out;
          -moz-transition: background 1s ease-out;
          -o-transition: background 1s ease-out;
          transition: background 1s ease-out;
          background-size: 1px 200px;
        }
      </style>';
    }

    $content .= '
    <div id="bh-sua">
      <div class="bh-sua-cta-initial-wrapper">
        <h3 style="display:block!important;">'.$atts['cta-header'].'</h3>
        <div class="bh-sua-cta-button-wrapper">
          <div class="bh-sua-cta-button-wrapper-container">
            <div style="padding-right:'.$cta_action_size_padding.'">
              <button class="bh-sua-cta-button" data-op="one" id="bh-sua-cta-button-one" style="
                font-size:'.$cta_action_size.';
                background-color:'.$cta_button_one_background_color.';
                color:'.$cta_button_one_color.';">'.$atts['cta-button-one-text'].'</button>
            </div>
          </div>
          <div class="bh-sua-cta-button-wrapper-container">
            <div style="padding-left:'.$cta_action_size_padding.'">
              <button class="bh-sua-cta-button" data-op="two" id="bh-sua-cta-button-two" style="
                font-size:'.$cta_action_size.';
                background-color:'.$cta_button_two_background_color.';
                color:'.$cta_button_two_color.';">'.$atts['cta-button-two-text'].'</button>
            </div>
          </div>
        </div>
      </div>
      <div class="bh-sua-cta-wrapper">
        <div class="bh-sua-cta bh-sua-cta-one" style="display:none;">
          <h4 class="bh-sua-cta-header">'.$atts['sua-cta-one-header'].'</h4>
          <div class="bh-sua-cta-desc">'.$atts['sua-cta-one-desc'].'</div>
          <div class="bh-sua-cta-input-wrapper">
            <strong>Email Address</strong>
            <div class="bh-sua-cta-input-action-wrapper">
              <input type="hidden" name="list-code" value="'.$atts['sua-cta-one-list-code'].'"/>
              <input placeholder="Enter your email address" id="bh-sua-cta-input-action-email" class="bh-sua-cta-input-action-email" type="email" name="email" style="font-size:'.$cta_action_size_email.';">
              <button class="bh-sua-cta-input-action" id="bh-sua-cta-input-action" data-redirect="'.$atts['sua-cta-one-redirect'].'" style="font-size:'.$cta_action_size_email.';">Continue</button>
            </div>
          </div>
        </div>
        <div class="bh-sua-cta bh-sua-cta-two" style="display:none;">
          <h4 class="bh-sua-cta-header">'.$atts['sua-cta-two-header'].'</h4>
          <div class="bh-sua-cta-desc">'.$atts['sua-cta-two-desc'].'</div>
          <div class="bh-sua-cta-input-wrapper">
            <strong>Email Address</strong>
            <div class="bh-sua-cta-input-action-wrapper">
              <input type="hidden" name="list-code" value="'.$atts['sua-cta-two-list-code'].'"/>
              <input placeholder="Enter your email address" id="bh-sua-cta-input-action-email" class="bh-sua-cta-input-action-email" type="email" name="email" style="font-size:'.$cta_action_size_email.';">
              <button class="bh-sua-cta-input-action" id="bh-sua-cta-input-action" data-redirect="'.$atts['sua-cta-two-redirect'].'" style="font-size:'.$cta_action_size_email.';">Continue</button>
            </div>
          </div>
        </div>
        <div class="bh-sua-cta-error" id="bh-sua-cta-error" style="display:none;"></div>
        <div class="bh-sua-cta-thank-you" style="display:none;"><h3 style="display:block!important;">Thank you for signing up</h3></div>
      </div>
    </div>';

    return $content;
  }

  private function bh_debug_var($obj) {
    print('<pre>');
    var_dump($obj);
    print('</pre>');
    exit();
  }
}

global $db_extra;

if (function_exists('agora')) {
  $bh_extras = new BhExtras(agora(), $db_extra);
} else {
  $bh_extras = new BhExtras(null, $db_extra);
}
