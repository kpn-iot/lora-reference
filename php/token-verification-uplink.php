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

require_once(__DIR__ . "/classes/QueryParameters.php");
require_once(__DIR__ . "/classes/ApplicationServerContext.php");

$queryParameters = new QueryParameters("AS_ID=hsmtest&LrnDevEui=0059AC000017338B&LrnFPort=1&LrnInfos=TWA_100006211.1150.AS-1-9207310&Time=2018-10-19T13%3A20%3A48.598%2B02%3A00&Token=0923ce42d4b584aeb8f319e76d5c6c384021d173f8cc4abf9b6ec2c2bba356d0");
$bodyObject = json_decode('{"DevEUI_uplink": {"Time": "2018-10-19T13:20:48.291+02:00","DevEUI": "0059AC000017338B","FPort": 1,"FCntUp": 0,"MType": 2,"FCntDn": 0,"payload_hex": "7639","mic_hex": "1b400c44","Lrcid": "0059AC01","LrrRSSI": -117.000000,"LrrSNR": -19.000000,"SpFact": 12,"SubBand": "G1","Channel": "LC3","DevLrrCnt": 1,"Lrrid": "FF0101C2","Late": 0,"LrrLAT": 51.905693,"LrrLON": 4.463330,"Lrrs": {"Lrr": [{"Lrrid": "FF0101C2","Chain": 0,"LrrRSSI": -117.000000,"LrrSNR": -19.000000,"LrrESP": -136.054337}]},"CustomerID": "100006211","CustomerData": {"alr":{"pro":"Static","ver":"1"}},"ModelCfg": "0","AppSKey": "d27a7d90c00362fd1628796f9e339ef0","DevAddr": "14C4CC04"}}');

$context = new ApplicationServerContext($queryParameters->get('AS_ID'));
$tokenOk = $context->getTokenVerifier()->checkToken($queryParameters, ($bodyObject));

var_dump($tokenOk);
