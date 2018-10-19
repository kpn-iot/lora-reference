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
 * Example script for decrypting the payload in an XML DevEUI_uplink API call
 */

require_once(__DIR__ . '/classes/HexStringIterator.php');
require_once(__DIR__ . '/classes/PayloadEncryption.php');

// pull the join accept from the wlogger
// get the appkey from your memory, put it in $appKey
// strip the first byte (MHDR, 0x20 probably) and the last 4 bytes (MIC) from the join accept payload, put it in $joinAcceptHex

$joinAcceptHex = '9951ba5f7081fdef79377d3da6d4d82ae4fd966d41f5fab2cf17a70c';
$appKey = '9F159A49D0287CE1E2C640806FB86ECB';

$crypt = new PayloadEncryption($appKey);
$decryptedPayload = $crypt->joinAccept($joinAcceptHex);

var_dump($decryptedPayload);

$iterator = new HexStringIterator($decryptedPayload);

$parts = [
  'joinNonce' => $iterator->get(3)->flip()->string(),
  'homeNetID' => $iterator->get(3)->flip()->string(),
  'devAddr' => $iterator->get(4)->flip()->string(),
  'dlSettings' => $iterator->get(1)->string(),
  'rxDelay' => $iterator->get(1)->string(),
  'cfList' => $iterator->getRest()->flip()->string()
];

print_r($parts);
