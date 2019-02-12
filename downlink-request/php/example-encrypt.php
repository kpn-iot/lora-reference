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
 * Example script for queuing a downlink message
 */

require(__DIR__ . '/DownlinkRequest.php');

// parameters required for downlink request
$devEUI = "0123456789012345";
$portId = 1;
$payload = "00";
$asId = "100000000.000";
$lrcAsKey = "01234567890123456789012345678901";

require_once(__DIR__ . "/../../payload-decryption/PayloadEncryption.php");

// parameters needed for payload encryption
$appSKey = "01234567890123456789012345678901";
$devAddr = "01234567";
$fCntDn = 1;

$crypt = new PayloadEncryption($appSKey);
$payloadEncrypted = $crypt->downlink($payload, $devAddr, $fCntDn);


$result = DownlinkRequest::queue($devEUI, $portId, $payloadEncrypted, $asId, $lrcAsKey);

var_dump($result);
