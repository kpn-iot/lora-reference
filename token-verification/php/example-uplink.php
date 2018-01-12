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

$queryString = "LrnDevEui=0059AC0000000001&LrnFPort=1&LrnInfos=TWA_100000001.999.AS-1-12345678&AS_ID=TWA_100000001.999&Time=2018-01-12T10:00:00.000+01:00&Token=5b7ec8b0b2c250fc792c76348de3b16ff04e27050baf8a37c891dcc85e88b0d4";

$bodyContent = '{
  "DevEUI_uplink": {
    "Time": "",
    "DevEUI": "0059AC0000000001",
    "FPort": "1",
    "FCntUp": "1",
    "ADRbit": "",
    "MType": "",
    "FCntDn": "",
    "payload_hex": "000102030405",
    "mic_hex": "",
    "Lrcid": "",
    "LrrRSSI": "",
    "LrrSNR": "",
    "SpFact": "",
    "SubBand": "",
    "Channel": "",
    "DevLrrCnt": "",
    "Lrrid": "",
    "Late": "",
    "LrrLAT": "",
    "LrrLON": "",
    "Lrrs": {
      "Lrr": []
    },
    "CustomerID": "100000001",
    "CustomerData": {
      "alr": {
        "pro": "",
        "ver": ""
      }
    },
    "ModelCfg": "",
    "InstantPER": "",
    "MeanPER": "",
    "DevAddr": ""
  }
}';

require_once("TokenVerification.php");
$verifier = new TokenVerification("c6f5e3b263092120b8766b9aed9c41b4");

$tokenOk = $verifier->checkToken($queryString, json_decode($bodyContent));

var_dump($tokenOk);
