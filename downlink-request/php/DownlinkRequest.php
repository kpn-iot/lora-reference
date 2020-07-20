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

class DownlinkRequest {
    
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
  static function queue($devEUI, $portId, $payload, $asId, $lrcAsKey) {
    $baseUrl = 'https://api.kpn-lora.com/thingpark/lrc/rest/downlink';

    $queryParameters = [
      'DevEUI' => $devEUI,
      'FPort' => $portId,
      'Payload' => $payload,
      'AS_ID' => $asId,
      'Time' => gmdate('Y-m-d\TH:i:s') //UTC time works best
    ];

    // prepare query string
    $queryString = '';
    foreach ($queryParameters as $key => $value) {
      $queryString .= $key . '=' . $value . '&';
    }
    $queryString = rtrim($queryString, '&');

    // calculate token
    $hashIn = $queryString . strtolower($lrcAsKey);
    $token = hash('sha256', $hashIn);

    $url = $baseUrl . '?' . $queryString . '&Token=' . $token;

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

    if ($success === true) {
      return true;
    } else {
      return $result;
    }
  }

}
