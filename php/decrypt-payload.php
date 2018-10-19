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
 * Example script for decrypting uplink payload
 */

require_once(__DIR__ . '/classes/PayloadEncryption.php');

// Input variables taken from the XML body.
$payload = "84EE4EE217";
$devAddr = "14C4CC04";
$fCntUp = 1;

$appSKey = "3d2fd3fa60060e5d91ab32dc7f75bf55"; //shared secret

$crypt = new PayloadEncryption($appSKey);
$decryptedPayload = $crypt->uplink($payload, $devAddr, $fCntUp);

var_dump($decryptedPayload);
