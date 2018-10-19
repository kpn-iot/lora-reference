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
 * Example script for validating the token in the DevEUI_uplink API call from the Developer Portal
 */

require_once(__DIR__ . "/classes/QueryParameters.php");
require_once(__DIR__ . "/classes/TokenVerifier.php");

$queryParameters = new QueryParameters("LrnDevEui=0059AC0000180000&LrnFPort=1&LrnInfos=TWA_100006356.246.AS-1-8402463&AS_ID=KPN.developer&Time=2018-02-09T12:21:44.928Z&Token=127804619c039326a75c68327751917e9abc1bfed2d036aa58aca37038583e99", true);
$bodyObject = json_decode('{"DevEUI_uplink": {"Time": "2018-02-09T13:21:45.311+01:00","DevEUI": "0059AC0000180000","FPort": "1","FCntUp": "17","MType": "2","FCntDn": "2","payload_hex": "2030","mic_hex": "b3062456","Lrcid": "0059AC01","LrrRSSI": "-113.000000","LrrSNR": "3.000000","SpFact": "12","SubBand": "G2","Channel": "LC8","DevLrrCnt": "8","Lrrid": "FF010AD7","Late": "0","CustomerID": "100006356","CustomerData": {"alr": {"pro": "Static","ver": "1"}},"ModelCfg": "0","DevAddr": "14204BAE"}}');

$verifier = new TokenVerifier("c6f5e3b2-6309-2120-b876-6b9aed9c41b4", TokenVerifier::SOURCE_DEVELOPER_PORTAL);
$tokenOk = $verifier->checkToken($queryParameters, $bodyObject);

var_dump($tokenOk);
