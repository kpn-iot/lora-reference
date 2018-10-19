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
 * Example script for an Application Server ingestion point with token verification.
 */

$queryString = $_SERVER['QUERY_STRING']; //fetch the query string from the request. Make sure the URL is url-decoded (So `%2B` => `+`)
$bodyContent = file_get_contents("php://input"); //fetch the body from the request

//$queryString = "AS_ID=hsmtest&LrnDevEui=0059AC000017338B&LrnFPort=1&LrnInfos=TWA_100006211.1150.AS-1-9729352&Time=2018-10-19T16%3A39%3A17.302%2B02%3A00&Token=6dbe41b7585fabf9523254224ac22714b085173e5211146a210d78edd55b0e0b";
//$bodyContent = '{"DevEUI_uplink": {"Time": "2018-10-19T16:39:16.922+02:00","DevEUI": "0059AC000017338B","FPort": 1,"FCntUp": 13,"MType": 2,"FCntDn": 6,"payload_hex": "aa27","mic_hex": "5c00ad47","Lrcid": "0059AC01","LrrRSSI": -110.000000,"LrrSNR": -15.000000,"SpFact": 12,"SubBand": "G1","Channel": "LC1","DevLrrCnt": 5,"Lrrid": "FF01045A","Late": 0,"LrrLAT": 51.906963,"LrrLON": 4.383102,"Lrrs": {"Lrr": [{"Lrrid": "FF01045A","Chain": 0,"LrrRSSI": -110.000000,"LrrSNR": -15.000000,"LrrESP": -125.135208},{"Lrrid": "FF0101C2","Chain": 0,"LrrRSSI": -114.000000,"LrrSNR": -16.000000,"LrrESP": -130.107742},{"Lrrid": "FF0106EE","Chain": 0,"LrrRSSI": -113.000000,"LrrSNR": -22.000000,"LrrESP": -135.027313}]},"CustomerID": "100006211","CustomerData": {"alr":{"pro":"Static","ver":"1"}},"ModelCfg": "0","AppSKey": "d27a7d90c00362fd1628796f9e339ef0","DevAddr": "14C4CC04"}}';

require_once(__DIR__ . '/classes/QueryParameters.php');
require_once(__DIR__ . '/classes/RoutingProfileContext.php');
require_once(__DIR__ . '/classes/ApplicationServerContext.php');
require_once(__DIR__ . '/classes/DeviceContext.php');
require_once(__DIR__ . '/classes/Encrypter.php');
require_once(__DIR__ . '/classes/Helper.php');

$queryParameters = new QueryParameters($queryString);
$bodyObject = json_decode($bodyContent);

$context = new ApplicationServerContext($queryParameters->get('AS_ID'));

// token check
$tokenOk = $context->getTokenVerifier()->checkToken($queryParameters, $bodyObject);
if (!$tokenOk) {
  die('Token is not ok!');
}

if (property_exists($bodyObject, 'DevEUI_uplink')) {
  $uplinkObject = $bodyObject->DevEUI_uplink;

  // decrypt appskey
  $routingProfileContext = new RoutingProfileContext('c26cbb4e64fa2598e083207676972385');
  $appSKey = $routingProfileContext->decryptAppSKey($uplinkObject->AppSKey);
  
  // decrypt payload
  $deviceContext = new DeviceContext($uplinkObject->DevEUI);
  $payload = $deviceContext->decryptUplink($uplinkObject->payload_hex, $uplinkObject->FCntUp);

  var_dump($payload);

  // set context for next downlink
  $deviceContext->updateContextValues($uplinkObject->DevAddr, $uplinkObject->FCntDn, $appSKey);

  // send downlink
  file_put_contents(__DIR__ . '/debug.txt', $context->getDownlinkRequester()->getRequestUrl($uplinkObject->DevEUI, $uplinkObject->FPort, $deviceContext->encryptDownlink($payload)));
  $result = $context->getDownlinkRequester()->request($uplinkObject->DevEUI, $uplinkObject->FPort, $deviceContext->encryptDownlink($payload));
  var_dump($result);
}
