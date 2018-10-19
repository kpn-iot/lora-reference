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
 * Class to do encryption/decryption of LoRaWAN Payload. Since AES-128 is a symmetric algorithm, the encryption function can also be used for decryption.
 */

 require_once(__DIR__ . "/Helper.php");

class Encrypter {

  const DIR_UPLINK = 0;
  const DIR_DOWNLINK = 1;
  const REGEX_16BYTES = '/^[0-9a-fA-F]{32}$/';
  const REGEX_4BYTES = '/^[0-9a-fA-F]{8}$/';

  private $_appSKey;

  /**
   * 
   * @param string $appSKey - The AppSKey to be used for encryption/decryption, case insensitive
   */
  public function __construct(string $appSKey) {
    if (preg_match(static::REGEX_16BYTES, $appSKey) !== 1) {
      throw new Exception("AppSKey is incorrect. Should be 16 bytes in HEX representation.");
    }
    $this->_appSKey = $appSKey;
  }

  public function uplink($payloadHexString, $devAddr, $fCntUp) {
    return $this->payload($payloadHexString, static::DIR_UPLINK, $devAddr, $fCntUp);
  }
  
  public function downlink($payloadHexString, $devAddr, $fCntDn) {
    return $this->payload($payloadHexString, static::DIR_DOWNLINK, $devAddr, $fCntDn);
  }

  public function joinAccept($payloadHexString) {
    return $this->_aes($payloadHexString, $this->_appSKey);
  }

  /**
   * 
   * @param string $payloadHexString - case insensitive
   * @param int $dir - DIR_UPLINK or DIR_DOWNLINK
   * @param string $devAddr - case insensitive
   * @param int $fCnt - fCntUp for uplink, fCntDown for downlink
   * @return string - the encrypted/decrypted payload
   * @throws Exception
   */
  public function payload($payloadHexString, $dir, $devAddr, $fCnt) {
    // check payload length
    if ((strlen($payloadHexString) % 2) !== 0 || strlen($payloadHexString) === 0) {
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
      $blockEncryptedHexString = $this->payloadBlock($payloadHexStringBlocks[$i], $dir, $devAddr, $fCnt, $i + 1);
      $payloadEncryptedHexString .= $blockEncryptedHexString;
    }
    return substr($payloadEncryptedHexString, 0, $payloadSize * 2);
  }

  /**
   * 
   * @param string $blockHexString
   * @param int $dir
   * @param string $devAddr
   * @param int $fCnt
   * @param int $blockNr 1-based index number of the block to be encrypted/decrypted
   * @return string
   */
  public function payloadBlock($blockHexString, $dir, $devAddr, $fCnt, $blockNr) {
    if (preg_match(static::REGEX_16BYTES, $blockHexString) !== 1) {
      throw new Exception("Block is incorrect. Should be 16 bytes in HEX representation.");
    } else if (preg_match(static::REGEX_4BYTES, $devAddr) !== 1) {
      throw new Exception("DevAddr is incorrect. Should be 4 bytes in HEX representation.");
    } else if (!is_int($fCnt)) {
      throw new Exception("FCnt should be an integer.");
    }
    $devAddrByteArray = Helper::hexStringtoByteArray($devAddr, 4);
    $fCntByteArray = Helper::integerToByteArray($fCnt, 4);
    $blockNrByteArray = Helper::integerToByteArray($blockNr, 1);

    // prepare block A_i as described in LW102 Page 19, Line 12. Put DevAddr and FCnt Big Endian in.
    $a = [
      0x01,
      0x00, 0x00, 0x00, 0x00,
      $dir,
      $devAddrByteArray[3], $devAddrByteArray[2], $devAddrByteArray[1], $devAddrByteArray[0],
      $fCntByteArray[3], $fCntByteArray[2], $fCntByteArray[1], $fCntByteArray[0],
      0x00,
      $blockNrByteArray[0]
    ];
    $aHexString = Helper::byteArrayToHexString($a);


    $sHexString = $this->_aes($aHexString, $this->_appSKey);

    // perform bytewise XOR'ing 
    $sByteArray = Helper::hexStringtoByteArray($sHexString);
    $blockByteArray = Helper::hexStringtoByteArray($blockHexString);
    $encryptedBlockByteArray = [];
    for ($i = 0; $i < count($blockByteArray); $i++) {
      $encryptedBlockByteArray[$i] = $blockByteArray[$i] ^ $sByteArray[$i];
    }

    return Helper::byteArrayToHexString($encryptedBlockByteArray);
  }

  private function _aes($dataHexString, $keyHexString) {
    $dataHexStringLength = strlen($dataHexString);
    $data = hex2bin($dataHexString);
    $method = "aes128";
    $key = hex2bin($keyHexString);
    $s = @openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA);
    return substr(bin2hex($s), 0, $dataHexStringLength);
  }

}
