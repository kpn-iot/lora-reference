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
 * Example for validating the token in the DevEUI_downlink_Sent API call
 */

require_once(__DIR__ . "/classes/QueryParameters.php");
require_once(__DIR__ . "/classes/ApplicationServerContext.php");

$queryParameters = new QueryParameters("LrnDevEui=0059AC0000000001&LrnFPort=1&LrnInfos=TWA_100000001.999.AS-1-12345678&AS_ID=TWA_100000001.999&Time=2018-01-12T10:00:00.000+01:00&Token=345dc204c72a9769441c4700cd0a0fb76c5b356e3931540e891fc7e68f236c98", true);
$bodyObject = json_decode('{"DevEUI_downlink_Sent": {"Time": "","DevEUI": "0059AC0000000001","FPort": "1","FCntDn": "1","FCntUp": "","Lrcid": "","SpFact": "","SubBand": "","Channel": "","Lrrid": "","DeliveryStatus": "","DeliveryFailedCause1": "","DeliveryFailedCause2": "","DeliveryFailedCause3": "","CustomerID": "100000001","CustomerData": {"alr": {"pro": "","ver": ""}}}}');

$context = new ApplicationServerContext($queryParameters->get('AS_ID'));
$tokenOk = $context->getTokenVerifier()->checkToken($queryParameters, ($bodyObject));

var_dump($tokenOk);
