<?php

/*  _  __  ____    _   _ 
 * | |/ / |  _ \  | \ | |
 * | ' /  | |_) | |  \| |
 * | . \  |  __/  | |\  |
 * |_|\_\ |_|     |_| \_|
 * 
 * (c) 2018 KPN
 * License: MIT License
 * Author: Paul Marcelis
 * 
 */

class Byte {
  private $_hexString;

  public function __construct(string $hexString) {
    if (strlen($hexString) % 2 !== 0) {
      return false;
    }
    $this->_hexString = $hexString;
  }

  public function __toString() {
    return $this->_hexString;
  }

  public function flip() {
    $newHexString = '';
    for ($i = strlen($this->_hexString)-2; $i >= 0; $i -= 2) {
      $newHexString .= substr($this->_hexString, $i, 2);
    }
    $this->_hexString = $newHexString;
    return $this;
  }

  public function string() {
    return $this->_hexString;
  }
}
