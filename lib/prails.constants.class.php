<?php

abstract class BasicEnum {

  private static $constCache = NULL;

  private static function getConstants() {
    if (self::$constCache === NULL) {
      $reflect = new ReflectionClass(get_called_class());
      self::$constCache = $reflect->getConstants();
    }

    return self::$constCache;
  }

  public static function isValidName($name, $strict = false) {
    $constants = self::getConstants();

    if ($strict) {
      return array_key_exists($name, $constants);
    }

    $keys = array_map('strtolower', array_keys($constants));
    return in_array(strtolower($name), $keys);
  }

  public static function isValidValue($value) {
    $values = array_values(self::getConstants());
    return in_array($value, $values, $strict = true);
  }

}

abstract class PrailsConstants extends BasicEnum {
  const prails_edit_mode = "EDIT";
  const prails_delete_mode = "DELETE";
}

?>
