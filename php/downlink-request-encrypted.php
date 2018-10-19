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
 * Example script for queuing a downlink message that should be encrypted
 */

require_once(__DIR__ . "/classes/ApplicationServerContext.php");
require_once(__DIR__ . "/classes/DeviceContext.php");

// context parameters
$asId = "hsmtest";

// parameters required for downlink request
$devEUI = "0059AC000017338B";
$portId = 1;
$payload = "01020304";

$context = new ApplicationServerContext($asId);
$deviceContext = new DeviceContext($devEUI);

$result = $context->getDownlinkRequester()->request($devEUI, $portId, $deviceContext->encryptDownlink($payload));
var_dump($result);
