<?php

class BhCallFire
{
  private $url = 'https://api.callfire.com/v2/';
  private $token = 'ZGY0YjA5N2ZmMzQ5OmVmNWY1ZGYxYWEzYzljMzE=';

  public function getList($name) {
    $rsp = $this->getRequest('contacts/lists', array('name' => $name));
    return $rsp;
  }

  public function insertList($name) {
    $params = '{
      "name" : "'. pg_escape_string($name) . '"
    }';

    $rsp = $this->postRequest('contacts/lists', $params);
    return $rsp;
  }

  public function insertContact($listid, $name, $cell) {
    $cell = preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $cell);

    $params = '{
      "contacts":
        [
          {
            "firstName": "' . pg_escape_string($name) . '",
            "homePhone": "' . pg_escape_string($cell) . '"
          }
        ]
    }';

    $rsp = $this->postRequest('contacts/lists/'.$listid.'/items', $params);
    return $rsp;
  }

  public function sendText($cell, $message) {
    $cell = preg_replace("/[^0-9]/", "", $cell );

    $payload = (object) array("phoneNumber" => "1$cell", "message" => stripslashes($message));
    $params = json_encode(
      array($payload)
    );

    $rsp = $this->postRequest('texts', $params);
    return $rsp;
  }

  private function postRequest($path, $params) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

    curl_setopt($ch, CURLOPT_URL, $this->url . $path);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic ' . $this->token));

    $output = curl_exec($ch);
    $output = json_decode($output);

    curl_close($ch);

    return $output;
  }

  private function getRequest($path, $params) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);

    curl_setopt($ch, CURLOPT_URL, $this->url . $path . '?' . http_build_query($params));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic ' . $this->token));

    $output = curl_exec($ch);
    $output = json_decode($output);

    curl_close($ch);

    return $output;
  }
}

?>
