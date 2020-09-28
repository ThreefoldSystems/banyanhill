<?php

class BhCarlController
{
  private $url = 'https://carl.pubsvs.com/';
  private $orgId = 10;
  private $token = '';

  public function getListsByEmail($email) {
    $rsp = $this->getRequest('listsByEmail', $email);
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
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic ' . $this->token));

    $output = curl_exec($ch);
    $output = json_decode($output);

    curl_close($ch);

    return $output;
  }

  private function getRequest($path, $req) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);

    curl_setopt($ch, CURLOPT_URL, $this->url . $path . '/' . $this->orgId . '/' . $req);

    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic ' . $this->token));

    $output = curl_exec($ch);
    $output = json_decode($output);

    curl_close($ch);

    return $output;
  }
}

?>
