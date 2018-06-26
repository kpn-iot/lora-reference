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

require_once(__DIR__ . '/Helper.php');
require_once(__DIR__ . '/PayloadEncryption.php');

$bodyContent = file_get_contents("php://input");
$bodyObject = simplexml_load_string($bodyContent);

// Input variables taken from the XML body.
$payload = $bodyObject->payload_hex;
$devAddr = $bodyObject->DevAddr;
$fCntUp = $bodyObject->FCntUp;

$appSKey = "01234567890123456789012345678901"; //shared secret


$crypt = new PayloadEncryption($appSKey);
$decryptedPayload = $crypt->uplink($payload, $devAddr, $fCntUp);

var_dump($decryptedPayload);
