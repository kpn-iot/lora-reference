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
 * Example for validating the token in the DevEUI_location API call
 */

require_once(__DIR__ . '/classes/QueryParameters.php');
require_once(__DIR__ . '/classes/ApplicationServerContext.php');

$queryParameters = new QueryParameters("LrnDevEui=0059ac0000000001&LrnFPort=0&LrnInfos=TWA_100000001.999.AS-1-12345678&AS_ID=TWA_100000001.999&Time=2018-01-12T10:00:00.000+01:00&Token=d23b3507663d817c77d124cfbe4f1d5a67ba957e91471b39512b8f103fe46a17", true);
$bodyObject = json_decode('{"DevEUI_location": {"DevEUI": "0059AC0000000001","DevAddr": "","Lrcid": "","NwGeolocAlgo": "","NwGeolocTdoaOpt": "","Time": "","DevLocTime": "","DevLAT": "","DevLON": "","DevAlt": "","DevAcc": "","DevLocRadius": "","DevAltRadius": "","CustomerID": "100000001"}}');

$context = new ApplicationServerContext($queryParameters->get('AS_ID'));
$tokenOk = $context->getTokenVerifier()->checkToken($queryParameters, ($bodyObject));

var_dump($tokenOk);
