<?php

/*  _  __  ____    _   _ 
 * | |/ / |  _ \  | \ | |
 * | ' /  | |_) | |  \| |
 * | . \  |  __/  | |\  |
 * |_|\_\ |_|     |_| \_|
 * 
 * (c) 2018 KPN
 * License: MIT License
 * Author: Paul Marcelis
 * 
 */

class DownlinkRequester {

  private $_asId, $_lrcAsKey;

  public function __construct($asId = null, $lrcAsKey = null) {
    if ($asId !== null && $lrcAsKey === null) {
      throw new \Exception('AS_ID and LRC AS-Key should be given both');
    }
    $this->_asId = $asId;
    $this->_lrcAsKey = $lrcAsKey;
  }

  public function getRequestUrl($devEUI, $portId, $payload) {
    $baseUrl = 'https://api.kpn-lora.com/thingpark/lrc/rest/downlink';

    $queryParameters = [
      'DevEUI' => $devEUI,
      'FPort' => $portId,
      'Payload' => $payload,
      'Time' => gmdate('Y-m-d\TH:i:s') //UTC time works best
    ];

    if ($this->_asId !== null) {
      $queryParameters['AS_ID'] = $this->_asId;
    }

    // prepare query string
    $queryString = '';
    foreach ($queryParameters as $key => $value) {
      $queryString .= $key . '=' . $value . '&';
    }
    $queryString = rtrim($queryString, '&');

    $url = $baseUrl . '?' . $queryString;

    if ($this->_asId !== null) {
      // calculate token
      $hashIn = $queryString . strtolower($this->_lrcAsKey);
      $token = hash('sha256', $hashIn);
      $url .= '&Token=' . $token;
    }

    return $url;
  }
    
  /**
   * Calculate token and queue a downlink message
   * 
   * @param string $devEUI
   * @param integer $portId
   * @param string $payload
   * @param string $asId
   * @param string $lrcAsKey
   * @return boolean|string - true if success, else the error message
   */
  public function request($devEUI, $portId, $payload) {
    $url = $this->getRequestUrl($devEUI, $portId, $payload);

    // use CURL to execute the API call
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'a=1');
    $response = curl_exec($ch);
    curl_close($ch);

    $success = (strpos($response, "Request queued by LRC") !== false);
    $result = str_replace(['<html><body>', '</body></html>'], ['', ''], $response);

    if ($success !== true) {
      throw new \Exception($result);
    }
    
    return true;
  }

}
