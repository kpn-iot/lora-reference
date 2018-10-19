<?php

require_once(__DIR__ . '/TokenVerifier.php');
require_once(__DIR__ . '/DownlinkRequester.php');

class RoutingProfileContext {

  private $_asKey;

  public function __construct($asKey) {
    $this->_asKey = $asKey;
  }

  public function decryptAppSKey($appSKey) {
    $appSKeyBin = hex2bin($appSKey);
    $asKeyBin = hex2bin($this->_asKey);
    
    $decryptedAppSKeyBin = openssl_decrypt($appSKeyBin, 'aes128' , $asKeyBin, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
    return bin2hex($decryptedAppSKeyBin);
  }

}