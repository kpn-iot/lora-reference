<?php

class QueryParameters {
  private $_parameters;

  /**
   * If the url is already decoded, there should NOT be the `=` and `&` character in keys or values!
   */
  public function __construct(string $string, bool $decoded = false) {
    $queryParameterParts = explode("&", $string);
    $queryParameters = [];
    foreach ($queryParameterParts as $queryParameter) {
      $parts = explode("=", $queryParameter);
      if ($decoded) {
        $queryParameters[$parts[0]] = $parts[1];
      } else {
        $queryParameters[urldecode($parts[0])] = urldecode($parts[1]);

      }
    }
    $this->_parameters = $queryParameters;
  }

  public function get($key, $defaultValue = null) {
    if (isset($this->_parameters[$key])) {
      return $this->_parameters[$key];
    } else {
      return $defaultValue;
    }
  }

  public function set($key, $value) {
    $this->_parameters[$key] = $value;
    return true;
  }

  public function remove($key) {
    unset($this->_parameters[$key]);
    return true;
  }

  public function toStringInOrder($keysInOrder) {
    $stringParts = [];
    foreach ($keysInOrder as $key) {
      if (!isset($this->_parameters[$key])) {
        throw new \Exception("Missing key " . $key . " in array");
      }
      $stringParts[] = $key . "=" . $this->_parameters[$key];
    }
    return implode("&", $stringParts);
  }
  

  public function __toString() {
    $stringParts = [];
    foreach ($this->_parameters as $key => $value) {
      $stringParts[] = $key . "=" . $value;
    }
    return implode("&", $stringParts);
  }
}
