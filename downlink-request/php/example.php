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

$result = DownlinkRequest::queue($devEUI, $portId, $payload, $asId, $lrcAsKey);

var_dump($result);
