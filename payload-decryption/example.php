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
 * Example script for decrypting the payload in a DevEUI_uplink API call
 */

$bodyContent = file_get_contents("php://input");
$bodyObject = simplexml_load_string($bodyContent);

// Input variables
$payload = "2395731450984756098732458763451201"; //$bodyObject->payload_hex
$devAddr = "20212223"; //$bodyObject->DevAddr
$fCntUp = 513; //$bodyObject->FCntUp

$appSKey = "98312FB65F11D00E7DEB5154F861C3B8"; //shared secret

$crypt = new PayloadEncryption($appSKey);

var_dump($payload);
$payloadEncrypted = $crypt->payload($payload, $crypt::DIR_UPLINK, $devAddr, $fCntUp);
var_dump($payloadEncrypted);
$roundtrip = $crypt->payload($payloadEncrypted, $crypt::DIR_UPLINK, $devAddr, $fCntUp);
var_dump($roundtrip);

$symmetric = ($payload === $roundtrip);
var_dump($symmetric);

class PayloadEncryption {

  const DIR_UPLINK = 0;
  const DIR_DOWNLINK = 1;

  private $_appSKey;

  /**
   * 
   * @param string $appSKey The AppSKey to be used for encryption/decryption
   */
  public function __construct(string $appSKey) {
    $this->_appSKey = $appSKey;
  }

  /**
   * 
   * @param string $payloadHexString
   * @param int $dir
   * @param string $devAddr
   * @param int $fCntUp
   * @return string
   * @throws Exception
   */
  public function payload(string $payloadHexString, int $dir, string $devAddr, int $fCntUp) {
    // check payload length
    if ((strlen($payloadHexString) % 2) !== 0) {
      throw new Exception("Payload has invalid length. Should be an even number of hex characters");
    }
    
    // calculate number of 128-bit blocks to encrypt/decrypt blockwise
    $payloadSize = strlen($payloadHexString) / 2; //bytes
    $blockSize = 16; //bytes
    $nrBlocks = ceil($payloadSize / $blockSize);
    
    // perform zero padding
    $zeroPaddingSize = ($nrBlocks * $blockSize) - $payloadSize;
    for ($i = 0; $i < $zeroPaddingSize; $i++) {
      $payloadHexString .= "00";
    }

    // perform blockwise encryption/decryption
    $payloadHexStringBlocks = str_split($payloadHexString, $blockSize * 2);
    $payloadEncryptedHexString = "";
    for ($i = 0; $i < $nrBlocks; $i++) {
      $blockEncryptedHexString = $this->payloadBlock($payloadHexStringBlocks[$i], $dir, $devAddr, $fCntUp, $i + 1);
      $payloadEncryptedHexString .= $blockEncryptedHexString;
    }
    return substr($payloadEncryptedHexString, 0, $payloadSize * 2);
  }

  /**
   * 
   * @param string $blockHexString
   * @param int $dir
   * @param string $devAddr
   * @param int $fCntUp
   * @param int $blockNr 1-based index number of the block to be encrypted/decrypted
   * @return string
   */
  public function payloadBlock(string $blockHexString, int $dir, string $devAddr, int $fCntUp, int $blockNr) {
    // prepare block A_i as described in LW102 Page 19, Line 12
    $devAddrByteArray = Helper::hexStringtoByteArray($devAddr, 4);
    $fCntUpByteArray = Helper::integerToByteArray($fCntUp, 4);
    $blockNrByteArray = Helper::integerToByteArray($blockNr, 1);

    $a = [
      0x01,
      0x00, 0x00, 0x00, 0x00,
      $dir,
      $devAddrByteArray[0], $devAddrByteArray[1], $devAddrByteArray[2], $devAddrByteArray[3],
      $fCntUpByteArray[0], $fCntUpByteArray[1], $fCntUpByteArray[2], $fCntUpByteArray[3],
      0x00,
      $blockNrByteArray[0]
    ];
    $aHexString = Helper::byteArrayToHexString($a);
    
    // perform encryption/decryption
    $data = hex2bin($aHexString);
    $method = "aes128";
    $key = hex2bin($this->_appSKey);
    $s = @openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA);
    $sHexString = bin2hex($s);
    
    // perform bytewise XOR'ing 
    $sByteArray = Helper::hexStringtoByteArray($sHexString);
    $blockByteArray = Helper::hexStringtoByteArray($blockHexString);
    $encryptedBlockByteArray = [];
    for ($i = 0; $i < count($blockByteArray); $i++) {
      $encryptedBlockByteArray[$i] = $blockByteArray[$i] ^ $sByteArray[$i];
    }
    
    return Helper::byteArrayToHexString($encryptedBlockByteArray);
  }

}

/**
 * 
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
