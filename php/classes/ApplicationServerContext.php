<?php

require_once(__DIR__ . '/TokenVerifier.php');
require_once(__DIR__ . '/DownlinkRequester.php');

class ApplicationServerContext {
  const LRC_AS_KEYS = [
    'hsmtest' => '17D107FD47387B714D9E5AACCE614233',
    'TWA_100000001.999' => 'c6f5e3b263092120b8766b9aed9c41b4',
    '100000000.000' => '01234567890123456789012345678901'
  ];

  private $_asId, $_lrcAsKey, $_tokenVerifier = null, $_downlinkRequester = null;

  public function __construct($asId) {
    $this->_asId = $asId;
    $this->_lrcAsKey = static::getLrcAsKeyByAsId($asId);
  }

  public function getTokenVerifier() {
    if ($this->_tokenVerifier === null) {
      $this->_tokenVerifier = new TokenVerifier($this->_lrcAsKey);
    }
    return $this->_tokenVerifier;
  }

  public function getDownlinkRequester() {
    if ($this->_downlinkRequester === null) {
      $this->_downlinkRequester = new DownlinkRequester($this->_asId, $this->_lrcAsKey);
    }
    return $this->_downlinkRequester;
  }

  public static function getLrcAsKeyByAsId($asId) {
    if (!isset(static::LRC_AS_KEYS[$asId])) {
      throw new \Exception("AS ID " . $asId . " unknown");
    }
    return static::LRC_AS_KEYS[$asId];
  }

}