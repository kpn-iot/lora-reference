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

$queryString = "LrnDevEui=0059ac0000000001&LrnFPort=0&LrnInfos=TWA_100000001.999.AS-1-12345678&AS_ID=TWA_100000001.999&Time=2018-01-12T10:00:00.000+01:00&Token=d23b3507663d817c77d124cfbe4f1d5a67ba957e91471b39512b8f103fe46a17";

$bodyContent = '{
  "DevEUI_location": {
    "DevEUI": "0059AC0000000001",
    "DevAddr": "",
    "Lrcid": "",
    "NwGeolocAlgo": "",
    "NwGeolocTdoaOpt": "",
    "Time": "",
    "DevLocTime": "",
    "DevLAT": "",
    "DevLON": "",
    "DevAlt": "",
    "DevAcc": "",
    "DevLocRadius": "",
    "DevAltRadius": "",
    "CustomerID": "100000001"
  }
}';

require_once("TokenVerification.php");
$verifier = new TokenVerification("c6f5e3b263092120b8766b9aed9c41b4");

$tokenOk = $verifier->checkToken($queryString, json_decode($bodyContent));

var_dump($tokenOk);
