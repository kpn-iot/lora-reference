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
 * Class to do help with encryption/decryption of LoRaWAN Payload
 */

class Helper {

  /**
   * 
   * @param int $int
   * @param type $size
   * @return type
   */
  public static function integerToByteArray(int $int, $size = null) {
    $hex = dechex($int);
    return static::hexStringtoByteArray($hex, $size);
  }

  /**
   * Transform a HEX string representation to its corresponding byte array
   * 
   * @param string $hex
   * @param int $size
   * @return array
   */
  public static function hexStringtoByteArray(string $hex, int $size = null) {
    if (strlen($hex) % 2 !== 0) {
      $hex = "0" . $hex;
    }
    $hexArray = str_split($hex, 2);
    $byteArray = [];
    foreach ($hexArray as $hex) {
      $byteArray[] = hexdec($hex);
    }
    return static::paddByteArray($byteArray, $size);
  }

  /**
   * Perform zero padding on a byte array to get a fixed size
   * 
   * @param array $byteArray
   * @param int|null $size
   * @return array 
   * @throws Exception when the byte array does not fit the requested size
   */
  public static function paddByteArray(array $byteArray, int $size = null) {
    $byteArraySize = count($byteArray);
    if ($size === null || $byteArraySize == $size) {
      return $byteArray;
    } else if ($byteArraySize < $size) {
      do {
        array_unshift($byteArray, 0);
      } while (count($byteArray) < $size);
      return $byteArray;
    } else {
      throw new Exception("The HEX string is longer than the requested size");
    }
  }

  /**
   * Transform an array of bytes to its corresponding hex string
   * 
   * @param array $byteArray
   * @return string
   */
  public static function byteArrayToHexString(array $byteArray) {
    $hexString = "";
    foreach ($byteArray as $byte) {
      $hexString .= sprintf("%02X", $byte);
    }
    return $hexString;
  }

}
