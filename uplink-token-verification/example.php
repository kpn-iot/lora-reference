<?php

/*  _  __  ____    _   _ 
 * | |/ / |  _ \  | \ | |
 * | ' /  | |_) | |  \| |
 * | . \  |  __/  | |\  |
 * |_|\_\ |_|     |_| \_|
 * 
 * (c) 2017 KPN
 * License: MIT License
 * Author: Paul Marcelis
 * 
 * Example script for validating the token in the DevEUI_uplink API call
 */

$queryString = urldecode($_SERVER['QUERY_STRING']);
$bodyContent = file_get_contents("php://input");
$bodyObject = simplexml_load_string($bodyContent);
$lrcAsKey = "01234567890123456789012345678901";

$tokenOk = checkToken($queryString, $bodyObject, $lrcAsKey);

var_dump($tokenOk);

/**
 * To verify the token that accompanies the DevEUI_Uplink
 * 
 * @param string $queryString - for instance: 
 * @param type $bodyObject - for instance for XML: 
 * @param type $lrcAsKey - shared secret 128-bit key in HEX representation (32 characters) in lower case
 * @return bool Whether the token is correct
 */
function checkToken($queryString, $bodyObject, $lrcAsKey) {
  // split query string into query parameters and request token
  $re = '/(.+)&Token=([0-9a-f]{64})/';
  $queryStringPregMatches = [];
  preg_match($re, $queryString, $queryStringPregMatches);
  if (count($queryStringPregMatches) != 3) {
    return false;
  }
  $queryParameters = $queryStringPregMatches[1];
  $requestToken = $queryStringPregMatches[2];

  // check whether the body has the correct properties set
  $checkProperties = ['CustomerID', 'DevEUI', 'FPort', 'FCntUp', 'payload_hex'];
  foreach ($checkProperties as $property) {
    if (!property_exists($bodyObject, $property)) {
      return false;
    }
  }
  $bodyElements = $bodyObject->CustomerID . $bodyObject->DevEUI . $bodyObject->FPort . $bodyObject->FCntUp . $bodyObject->payload_hex;

  // transform LRC AS-Key
  $lrcAsKeyLower = strtolower($lrcAsKey);

  // Generate check token
  $hashFeed = $bodyElements . $queryParameters . $lrcAsKeyLower;
  $checkToken = hash('sha256', $hashFeed);

  return ($requestToken === $checkToken);
}
