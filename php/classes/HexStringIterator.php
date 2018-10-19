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

require_once(__DIR__ . '/Byte.php');

class HexStringIterator {
  private $_hexString, $_pointer = 0;

  public function __construct($hexString) {
    $this->_hexString = $hexString;
  }

  public function reset() {
    $this->pointer = 0;
  }

  public function get($nrBytes) {
    $ret = $this->peek($nrBytes);
    if ($ret === false) {
      return false;
    }
    $this->_pointer += $nrBytes * 2;
    return $ret;
  }

  public function getRest() {
    $ret = new Byte(substr($this->_hexString, $this->_pointer));
    $this->_pointer = strlen($this->_hexString);
    return $ret;
  }

  public function peek($nrBytes) {
    if ($this->_pointer + $nrBytes*2 > strlen($this->_hexString)) {
      return false;
    }
    return new Byte(substr($this->_hexString, $this->_pointer, $nrBytes*2));
  }

}
