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

require_once(__DIR__ . "/classes/ApplicationServerContext.php");

// context parameters
$asId = "100000000.000";

// parameters required for downlink request
$devEUI = "0123456789012345";
$portId = 1;
$payload = "00";

$context = new ApplicationServerContext($asId);
$result = $context->getDownlinkRequester()->request($devEUI, $portId, $payload);
var_dump($result);
