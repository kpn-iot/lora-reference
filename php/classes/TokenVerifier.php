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
 * Class to do perform token verification for the API calls to your Application Server.
 * From KPN IoT LoRa Reference Code: https://github.com/kpn-iot/lora-reference
 */

require_once(__DIR__ . "/QueryParameters.php");

class TokenVerifier {

  const TYPE_UPLINK = 1;
  const TYPE_DOWNLINK_SENT = 2;
  const TYPE_LOCATION = 3;

  const SOURCE_THINGPARK = 4;
  const SOURCE_DEVELOPER_PORTAL = 5;

  protected $_lrcAsKey, $_source;

  /**
   *
   * @param string $lrcAsKey - the shared secret key for token calculation
   * @param $source - the source from which the message comes to be checked (thingpark or developer portal)
   *
   * @throws \Exception
   */
  public function __construct(string $lrcAsKey, $source = self::SOURCE_THINGPARK) {
    $lrcAsKeyLower = strtolower($lrcAsKey);
    if (!in_array($source, [self::SOURCE_THINGPARK, self::SOURCE_DEVELOPER_PORTAL])) {
      throw new \Exception("Key type definition is not correct");
    }

    switch ($source) {
      case self::SOURCE_THINGPARK:
        if (preg_match('/^[0-9a-f]{32}$/', $lrcAsKeyLower) !== 1) {
          throw new \Exception("LRC AS-Key not correct. Should be 16 bytes in HEX representation");
        }
        break;
      case self::SOURCE_DEVELOPER_PORTAL:
        if (preg_match('/^[0-9a-f]{8}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{12}$/', $lrcAsKeyLower) !== 1) {
          throw new \Exception("LRC AS-Key not correct. Should be in format: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx");
        }
        break;
    }
    $this->_source = $source;
    $this->_lrcAsKey = $lrcAsKeyLower;
  }

  /**
   *
   * @param QueryParameters $queryParameters
   * @param object $bodyObject
   * @return bool
   * @throws \Exception
   */
  public function checkToken(QueryParameters $queryParameters, $bodyObject) {
    if (property_exists($bodyObject, 'DevEUI_uplink')) {
      return $this->checkUplinkToken($queryParameters, $bodyObject->DevEUI_uplink);
    } elseif (property_exists($bodyObject, 'DevEUI_location')) {
      return $this->checkLocationToken($queryParameters, $bodyObject->DevEUI_location);
    } elseif (property_exists($bodyObject, 'DevEUI_downlink_Sent')) {
      return $this->checkDownlinkSentToken($queryParameters, $bodyObject->DevEUI_downlink_Sent);
    } else {
      throw new \Exception("No valid body object");
    }
  }

  /**
   *
   * @param QueryParameters $queryParameters
   * @param object $bodyObject
   * @return bool
   * @throws \Exception
   */
  public function checkUplinkToken(QueryParameters $queryParameters, $bodyObject) {
    return $this->_innerCheckToken(static::TYPE_UPLINK, $queryParameters, $bodyObject);
  }

  /**
   * @param QueryParameters $queryParameters
   * @param object $bodyObject
   * @return bool
   * @throws \Exception
   */
  public function checkLocationToken(QueryParameters $queryParameters, $bodyObject) {
    return $this->_innerCheckToken(static::TYPE_LOCATION, $queryParameters, $bodyObject);
  }

  /**
   * @param QueryParameters $queryParameters
   * @param object $bodyObject
   * @return bool
   * @throws \Exception
   */
  public function checkDownlinkSentToken(QueryParameters $queryParameters, $bodyObject) {
    return $this->_innerCheckToken(static::TYPE_DOWNLINK_SENT, $queryParameters, $bodyObject);
  }

  /**
   * To verify the token that accompanies the DevEUI_Uplink
   *
   * @param integer $type
   * @param QueryParameters $queryParameters
   * @param object $bodyObject
   * @return bool Whether the token is correct
   * @throws \Exception
   */
  private function _innerCheckToken($type, QueryParameters $queryParameters, $bodyObject) {
    if (!in_array($type, [static::TYPE_UPLINK, static::TYPE_DOWNLINK_SENT, static::TYPE_LOCATION])) {
      throw new \Exception("Type incorrect");
    }

    // split query string into query parameters and request token
    $requestToken = $queryParameters->get('Token');
    $queryParameterString = $queryParameters->toStringInOrder(['LrnDevEui', 'LrnFPort', 'LrnInfos', 'AS_ID', 'Time']);

    switch ($type) {
      case static::TYPE_UPLINK:
        static::checkForPropertiesInObject(['CustomerID', 'DevEUI', 'FPort', 'FCntUp', 'payload_hex'], $bodyObject);
        $bodyElements = $bodyObject->CustomerID . $bodyObject->DevEUI . $bodyObject->FPort . $bodyObject->FCntUp . $bodyObject->payload_hex;
        break;
      case static::TYPE_LOCATION:
        static::checkForPropertiesInObject(['CustomerID', 'DevEUI'], $bodyObject);
        // The DevEUI value from the body should be transformed to lowercase to have a correct token calculation for DevEUI_location messages.
        $devEUIForToken = ($this->_source === static::SOURCE_THINGPARK) ? strtolower($bodyObject->DevEUI) : $bodyObject->DevEUI;
        $bodyElements = $bodyObject->CustomerID . $devEUIForToken;
        break;
      case static::TYPE_DOWNLINK_SENT:
        static::checkForPropertiesInObject(['CustomerID', 'DevEUI', 'FPort', 'FCntDn'], $bodyObject);
        $bodyElements = $bodyObject->CustomerID . $bodyObject->DevEUI . $bodyObject->FPort . $bodyObject->FCntDn;
        break;
    }

    // Generate check token
    $hashFeed = $bodyElements . $queryParameterString . $this->_lrcAsKey;
    $checkToken = hash('sha256', $hashFeed);

    return ($requestToken === $checkToken);
  }

  /**
   * check whether the body has certain properties set
   *
   * @param array $checkProperties
   * @param object $object
   * @throws \Exception
   */
  public static function checkForPropertiesInObject($checkProperties, $object) {
    foreach ($checkProperties as $property) {
      if (!property_exists($object, $property)) {
        throw new \Exception("Missing property " . $property . " in object");
      }
    }
  }

}
