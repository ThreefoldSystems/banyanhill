<?php

class BhLeadController
{
  private $db;
  private $prefix = 'bh/v1';
  private $tokens = [
    'IST90BM7XNF5', //trading tips
    'Y4CD22H6BA3E', //INO
	'6X2LSUH2JE34', //After Hours
    'KHK8XZ1ROIZ1'  //banyan	  
  ];
  private $bh_carl_controller;

  public function __construct($db, $bh_carl_controller) {
    $this->db = $db;
    $this->bh_carl_controller = $bh_carl_controller;

    $this->bh_register_routes();
  }

  private function bh_register_routes() {
    add_action('rest_api_init', array($this, 'bh_lead_register'));
  }

  public function bh_check_auth($request) {
    return in_array($request->get_header('Token'), $this->tokens);
  }

  public function bh_lead_register() {
    register_rest_route( $this->prefix, '/lead/(?P<email>\S+)', array(
        'methods' => 'GET',
        'callback' =>  array($this, 'bh_lead_email'),
        'permission_callback' => array($this, 'bh_check_auth'),
        'args' => array(
          'email' => array(
            'validate_callback' => function($param, $request, $key) {
              return !empty( $param );
            }
          ),
        ),
      )
    );
  }

  public function bh_lead_email(WP_REST_Request $request) {
    if (!filter_var(urldecode($request['email']), FILTER_VALIDATE_EMAIL)) {
      return new WP_REST_Response(array(
        'nta' => null
      ), 422);
    }

    $list_codes = $this->bh_carl_controller->getListsByEmail($request['email']);

    $d = array(
      'token' => $this->db->real_escape_string($request->get_header('Token')),
      'email' => $this->db->real_escape_string($request['email']),
      'nta' => empty((array)$list_codes)
    );

    $this->db->query("INSERT INTO info
      (
        `name`,`val`,
        `created`
      )
        VALUES
      (
        'event-lead-lookup','".json_encode($d)."',
        '".gmdate('Y-m-d H:i:s')."'
      )");

    return new WP_REST_Response(array(
      'nta' => empty((array)$list_codes)
    ), 200);
  }
}

?>
