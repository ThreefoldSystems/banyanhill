<?php
/**
 * Plugin Name: BanyanHill Magic Link
 * Description: Function to create magic link.
 * Version: 2.01
 * Author: BanyanHill Web Team
 */

class BhMagicLink
{
  private $db;
  private $agora;
  private $role = 'customer_care';

  public function __construct($agora, $db) {
    if (!$db) {
      error_log("Database connection not established db_extras.", 0);
      return;
    } else if ($db->connect_error) {
      error_log("Connection failed: " . $db->connect_error, 0);
      return;
    } else {
      $this->db = $db;
    }

    if (!$agora) {
      return;
    }

    $this->agora = $agora;

    wp_enqueue_script('bh-magic-link', plugins_url('/js/bh-magic-link.js?date=2020-01-27-000', __FILE__));
    $args = array(
      'url' => admin_url( 'admin-ajax.php' )
    );
    wp_localize_script('bh-magic-link', 'bhMagicLink', $args);

    wp_enqueue_script('jquery');

    add_action( 'wp_ajax_bh_verify_magic_link', array($this, 'bh_verify_magic_link'));
    add_action( 'wp_ajax_nopriv_bh_verify_magic_link', array($this, 'bh_verify_magic_link'));

    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array($this,  'bh_plugin_action_links' ));
    add_action( 'admin_menu', array($this, 'bh_register_settings' ));
    add_action( 'admin_footer', array($this, 'bh_register_settings_admin' ));
  }

  public function bh_verify_magic_link() {
    header( 'Content-type: application/json;charset=utf-8' );

    $username = '';
    $password = '';
    $success = true;

    $rs = $this->db->query("SELECT * FROM MagicLink WHERE hash='".$this->db->real_escape_string($_GET['token'])."' AND used=0");
    if($rs->num_rows == 0){
      $success = false;
    } else {
      $this->db->query("UPDATE MagicLink SET password='',used=1 WHERE hash='".$this->db->real_escape_string($_GET['token'])."'");

      $r = $rs->fetch_assoc();
      $username = $r['username'];
      $password = $r['password'];
    }

    echo json_encode(
      array(
        "success" => $success,
        "username" => $username,
        "password" => $password
      )
    );
    die();
  }

  public function bh_plugin_action_links( $links ) {
    $links[] = '<a href="'. menu_page_url( 'bh-magic-link', false ) .'">Plugin</a>';
    return $links;
  }

  public function bh_register_settings_admin() {
    $user = wp_get_current_user();
    if ( in_array( $this->role, (array) $user->roles ) ) {
      echo '<style>
        #adminmenu{display:none;}
        #wp-admin-bar-comments{display:none;}
        #wp-admin-bar-new-content{display:none;}
        .notice {display:none;}
      </style>';

      echo '<script>
        jQuery(document).ready(function (){
          jQuery("#adminmenuwrap").append(`<ul id="adminmenu" style="display:block !important;">
            <li class="wp-first-item current menu-top menu-top-first menu-icon-dashboard menu-top-first">
              <a href="/wp-admin/options-general.php?page=bh-magic-link" class="wp-first-item current menu-top menu-top-first menu-icon-dashboard menu-top-first" aria-current="page">
                <div class="wp-menu-arrow"><div></div></div><div class="wp-menu-image dashicons-before dashicons-dashboard"><br></div><div class="wp-menu-name">Magic Link</div>
              </a>
            </li>
          </ul>`);
        });

        if(!location.pathname.includes("/wp-admin/options-general.php")) {
          location.href = "/wp-admin/options-general.php?page=bh-magic-link";
        }
      </script>';
    }
  }

  public function bh_register_settings() {
    add_options_page( 'BH Magic Link', 'BH Magic Link', 'manage_options', 'bh-magic-link',  array($this, 'bh_plugin_settings_page' ));
  }

  public function bh_plugin_settings_page() {
    if(get_role($this->role) == null){
      add_role(
        $this->role,
        'Customer Care',
        [
          'read'            => true,
          'manage_options'  => true,
          'edit_posts'      => true
        ]
      );
    }

    if(isset($_REQUEST['op'])) {
      if($_REQUEST['op'] == 'eval'){
        if(isset($_REQUEST['username']) && isset($_REQUEST['password']) && !empty($_REQUEST['username']) && !empty($_REQUEST['password'])) {
          $token = $this->bh_generate_magic_link($_REQUEST['username'], $_REQUEST['password']);
        } else if(isset($_REQUEST['email']) && !empty($_REQUEST['email'])) {
          $users = $this->agora->mw->get_account_by_email(trim($_REQUEST['email']));
        } else if(isset($_REQUEST['customerNumber']) && !empty($_REQUEST['customerNumber'])) {
          $users = $this->agora->mw->get_account_by_id(str_pad(intval($_REQUEST['customerNumber']), 12, "0", STR_PAD_LEFT));
        }
      }
    }

    if(isset($users)){
      if(isset($_REQUEST['email']) && !empty($_REQUEST['email'])) {
        $content_header_user = 'Email - ' . $_REQUEST['email'];
      } else {
        $content_header_user = 'Customer Number - ' . $_REQUEST['customerNumber'];
      }
      $content_search_user = '
        <section style="padding:10px;">
        <h3><a href="/wp-admin/options-general.php?page=bh-magic-link">[Go Back]</a> - ' . $content_header_user  . '</h3>
        <div style="margin-bottom:20px; padding-bottom:10px; border-bottom:1px solid #d3d3d3;">
          <div style="display: flex;">
            <div style="flex-basis: 15%;"><strong>Customer Number</strong></div>
            <div style="flex-basis: 25%;"><strong>Associated Email</strong></div>
            <div style="flex-basis: 10%;"><strong>Action</strong></div>
          </div>
        </div>';

      foreach(array_reverse($users) as $i => $user) {
        if(isset($_REQUEST['email']) && !empty($_REQUEST['email']) && strtolower(trim($_REQUEST['email'])) != strtolower($user->id->userName)){
          continue;
        }

        if($i == 1){
          $content_search_user .= '
            <div style="margin-bottom:20px; padding-bottom:10px; border-bottom:1px solid #d3d3d3; color:red; padding-top: 50px;">
              Warning more than 1 user found associated to this email address:
            </div>
          ';
        }

        $wp_user = get_user_by( 'email', $user->id->userName );
        if($wp_user){
          if ( !in_array( 'subscriber', $wp_user->roles ) ) {
            continue;
          }
        }

        if (strpos($user->id->userName, '@banyanhill.com') !== false) {
          continue;
        }

        if(empty($user->password)) {
          $htmlAction = '
          <div style="display: flex;">
            <div style="flex-basis: 15%;">'.$user->customerNumber.'</div>
            <div style="flex-basis: 25%;">'.$user->id->userName.'</div>
            <div style="flex-basis: 10%;">User account missing password</div>
          </div>';
        } else {
          $htmlAction = '<form method="POST">
            <input type="hidden" name="username" value="'.$user->id->userName.'"/>
            <input type="hidden" name="password" value="'.$user->password.'"/>
            <input type="hidden" name="redirectTo" value="'.$_REQUEST['redirectTo'].'"/>
            <input type="hidden" name="passwordReset" value="'.$_REQUEST['passwordReset'].'"/>
            <div style="display: flex;">
              <div style="flex-basis: 15%;">'.$user->customerNumber.'</div>
              <div style="flex-basis: 25%;">'.$user->id->userName.'</div>
              <div style="flex-basis: 10%;"><button class="button button-primary" type="submit" name="op" value="eval">Create</button></div>
            </div>
          </form>';
        }

        $content_search_user .= '
          <div style="margin-bottom:20px; padding-bottom:10px; border-bottom:1px solid #d3d3d3;">
            '.$htmlAction.'
          </div>
        ';
      }
      $content_search_user .= '</section>';
    }

    if(!empty($content_search_user)) {
      echo $content_search_user;
    } else if ($token) {
      $redirect_to = '';
      if(isset($_REQUEST['redirectTo']) && !empty($_REQUEST['redirectTo'])) {
        $redirect_to = '&redirect_to=' . $_REQUEST['redirectTo'];
      }
      $password_reset = '';
      if(isset($_REQUEST['passwordReset']) && !empty($_REQUEST['passwordReset'])) {
        $password_reset = '&password_reset=' . $_REQUEST['passwordReset'];
      }

      $url_token_link = 'https://' . $_SERVER['SERVER_NAME'] . '?token='. $token . $redirect_to . $password_reset;

      $content = '<section style="padding:10px;">';
      $content .= '<h3 style="border-bottom:1px solid #d3d3d3;padding-bottom:10px;"><a href="/wp-admin/options-general.php?page=bh-magic-link">[Go Back]</a> - '.$_REQUEST['username'].'</h3>';
      $content .= 'One Time Use Token : <br><textarea readonly style="padding: 5px; width: 100%; background-color: transparent; color: initial;" id="bh-token-url-link">' . $url_token_link . '</textarea><br><button class="button button-primary" onclick="bhMagicLinkCopyToClipboard(\'bh-token-url-link\')">Copy to Clipboard</button>';
      $content .= '</section>';
      $content .= '<div id="bh-magic-link-snackbar"></div>';
      $content .= '<style>
        #bh-magic-link-snackbar {
          visibility: hidden; /* Hidden by default. Visible on click */
          min-width: 400px; /* Set a default minimum width */
          width:400px;
          margin-left: -200px; /* Divide value of min-width by 2 */
          background-color: #333; /* Black background color */
          color: #fff; /* White text color */
          text-align: center; /* Centered text */
          border-radius: 2px; /* Rounded borders */
          padding: 16px; /* Padding */
          position: fixed; /* Sit on top of the screen */
          z-index: 999999; /* Add a z-index if needed */
          left: 50%; /* Center the snackbar */
          bottom: 30px; /* 30px from the bottom */
        }

        /* Show the snackbar when clicking on a button (class added with JavaScript) */
        #bh-magic-link-snackbar.show {
          visibility: visible; /* Show the snackbar */
          /* Add animation: Take 0.5 seconds to fade in and out the snackbar.
          However, delay the fade out process for 2.5 seconds */
          -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
          animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        @-webkit-keyframes fadein {
          from {bottom: 0; opacity: 0;}
          to {bottom: 30px; opacity: 1;}
        }

        @keyframes fadein {
          from {bottom: 0; opacity: 0;}
          to {bottom: 30px; opacity: 1;}
        }

        @-webkit-keyframes fadeout {
          from {bottom: 30px; opacity: 1;}
          to {bottom: 0; opacity: 0;}
        }

        @keyframes fadeout {
          from {bottom: 30px; opacity: 1;}
          to {bottom: 0; opacity: 0;}
        }
      </style>';

      echo $content;
    } else {
      $content = '<section style="padding:10px;">';
      $content .= '<h3 style="border-bottom:1px solid #d3d3d3;padding-bottom:10px;">Magic Link</h3>';
      $content .= '<div id="bh-wrapper-magic-link">';
      $content .= '<input type="hidden" name="page" value="bh-magic-link" />';
      $content .= '<div style="display: flex;">';
      $content .= ' <div style="flex-basis: 15%;">Customer Number:</div>';
      $content .= ' <div style="flex-basis: 20%;"><input type="text" name="customerNumber-'.time().'" id="bh-customer-number-magic-link" style="width:99%" value=""/></div>';
      $content .= ' <div style=""></div>';
      $content .= '</div>';
      $content .= '<div style="display: flex;">';
      $content .= ' <div style="flex-basis: 15%;"><div style="text-align:center;margin-bottom:10px;">...Or...</div></div>';
      $content .= ' <div style="flex-basis: 20%;"></div>';
      $content .= ' <div style=""></div>';
      $content .= '</div>';
      $content .= '<div style="display: flex;">';
      $content .= ' <div style="flex-basis: 15%;">Email Address:</div>';
      $content .= ' <div style="flex-basis: 20%;"><input type="text" name="email-'.time().'" id="bh-email-magic-link" style="width:99%" value=""/></div>';
      $content .= ' <div style=""></div>';
      $content .= '</div>';
      $content .= '<br>';
      $content .= '<div style="display: flex; margin-top:10px;">';
      $content .= ' <div style="flex-basis: 15%;border-top:1px solid #d3d3d3; padding-top:10px">Redirect to (optional):</div>';
      $content .= ' <div style="flex-basis: 20%;border-top:1px solid #d3d3d3; padding-top:10px"><input type="text" id="bh-redirect-to" name="redirectTo-'.time().'" style="width:99%" value=""/></div>';
      $content .= ' <div style=""></div>';
      $content .= '</div>';
      $content .= '<div style="display: flex; margin-top:10px;">';
      $content .= ' <div style="flex-basis: 15%;padding-top:10px">Require Password Reset (optional):</div>';
      $content .= ' <div style="flex-basis: 20%;padding-top:10px"><input type="checkbox" name="passwordReset"/></div>';
      $content .= ' <div style=""></div>';
      $content .= '</div>';
      $content .= '<div style="display: flex;">';
      $content .= ' <div style="flex-basis: 15%;"><div class="button button-primary" id="bh-magic-link-clear" onClick="bhMagicLinkClear()" style="float:left;margin-top:10px;">Clear</div></div>';
      $content .= ' <div style="flex-basis: 20%;"><button class="button button-primary" name="op" value="eval" onClick="bhOnMagicLink()" style="float:right;margin-top:10px;">Create</button></div>';
      $content .= ' <div style=""></div>';
      $content .= '</div>';
      $content .= '</div>';
      $content .= '</section>';

      $content .= '<script>
        function bhMagicLinkClear() {
          jQuery("[type=\'text\']").val(\'\');
          jQuery("[type=\'checkbox\']").prop( "checked", false );
        }

        function bhOnMagicLink() {
          jQuery("#bh-customer-number-magic-link").attr("name", "customerNumber");
          jQuery("#bh-email-magic-link").attr("name", "email");
          jQuery("#bh-redirect-to").attr("name", "redirectTo");

          jQuery("#bh-wrapper-magic-link").wrap("<form method=\'GET\'></form>");
          jQuery("#bh-wrapper-magic-link").find("form").submit();
        }
      </script>';

      echo $content;
    }

  }

  private function bh_generate_magic_link($username, $password) {
    $hash = $this->build_code();

    $this->db->query("INSERT INTO MagicLink
      (`username`, `password`, `hash`, `used`, `created`)
        VALUES
      ('".$this->db->real_escape_string($username)."', '".$this->db->real_escape_string($password)."', '$hash', 0, '".gmdate('Y-m-d H:i:s')."')");

    return $hash;
  }

  private function build_code($input = '23456789ABCDEFGHIJKLMNPQRSTUVWXYZ', $strength = 64) {
    $input_length = strlen($input);
    $rnd_string = '';
    for($i = 0; $i < $strength; $i++) {
        $rnd_character = $input[mt_rand(0, $input_length - 1)];
        $rnd_string .= $rnd_character;
    }

    return $rnd_string;
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
  $bh_magic_link = new BhMagicLink(agora(), $db_extra);
} else {
  $bh_magic_link = new BhMagicLink(null, $db_extra);
}
