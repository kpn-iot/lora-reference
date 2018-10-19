<?php

require_once(__DIR__ . '/Encrypter.php');

class DeviceContext {
  const DEVICE_CONTEXT_VALUES = [
    '0059AC000017338B' => [
      'devAddr' => '14C4CC04',
      'fCntDn' => 4,
      'appSKey' => '3d2fd3fa60060e5d91ab32dc7f75bf55'
    ]
  ];

  private $_devEUI, $_devAddr, $_fCntDn, $_appSKey, $_encrypter = null;

  public function __construct($devEUI, $contextValues = null) {
    $this->_devEUI = $devEUI;
    if ($contextValues === null) {
      $values = static::getContextValuesByDevEUI($devEUI);
      $this->_devAddr = $values['devAddr'];
      $this->_fCntDn = $values['fCntDn'];
      $this->_appSKey = $values['appSKey'];
    } else {
      $this->_devAddr = $contextValues['devAddr'];
      $this->_fCntDn = $contextValues['fCntDn'];
      $this->_appSKey = $contextValues['appSKey'];
    }
  }

  public function updateContextValues($devAddr, $fCntDn, $appSKey) {
    $this->_devAddr = $devAddr;
    $this->_fCntDn = $fCntDn;
    $this->_appSKey = $appSKey;

    $this->_encrypter = null;
  }

  public function getEncrypter() {
    if ($this->_encrypter === null) {
      $this->_encrypter = new Encrypter($this->_appSKey);
    }
    return $this->_encrypter;
  }

  public function encryptDownlink($payload) {
    return $this->getEncrypter()->downlink($payload, $this->_devAddr, $this->_fCntDn);
  }

  public function decryptUplink($payload, $fCntUp) {
    return $this->getEncrypter()->uplink($payload, $this->_devAddr, $fCntUp);
  }
  
  public static function getContextValuesByDevEUI($devEUI) {
    if (!isset(static::DEVICE_CONTEXT_VALUES[$devEUI])) {
      throw new \Exception("Context values of device " . $devEUI . " unknown");
    }
    return static::DEVICE_CONTEXT_VALUES[$devEUI];
  }

}