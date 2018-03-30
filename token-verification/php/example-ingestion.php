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
 * Example script for an Application Server ingestion point with token verification.
 */

$queryString = urldecode($_SERVER['QUERY_STRING']); //fetch the query string from the request. Make sure the URL is url-decoded (So `%2B` => `+`)
$bodyContent = file_get_contents("php://input"); //fetch the body from the request
$lrcAsKey = "c6f5e3b263092120b8766b9aed9c41b4"; //define the LRC AS-Key. Is a shared secret between the Network Server and Application Server

// try to interpret the body as JSON
$bodyJSON = json_decode($bodyContent);
if (is_object($bodyJSON)) {
  $bodyObject = $bodyJSON;
} else {
  $bodyXML = simplexml_load_string($bodyContent);
  if (is_object($bodyXML)) {
    $bodyObject = $bodyXML;
  } else {
    throw new Exception("Request body could not be parsed as XML or JSON");
  }
}

require_once("TokenVerification.php");
$verifier = new TokenVerification($lrcAsKey);

$tokenOk = $verifier->checkToken($queryString, $bodyObject);

var_dump($tokenOk);
