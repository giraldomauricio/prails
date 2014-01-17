<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of code_first
 *
 * @author murdock
 */
class CodeFirst extends context {

  var $_model;
  var $_fields;
  var $_table;
  var $_key;

  public function SetModel($model_name) {
    if (class_exists($model_name)) {
      $this->_model = new $model_name;
      return true;
    } else {
      return false;
    }
  }

  public function GetTableInfo() {
    $this->_table = $this->_model->_table;
    $this->_key = $this->_model->_key;
    $base = new prails();
    $base_vars = get_class_vars(get_class($base));
    $class_vars = get_class_vars(get_class($this->_model));
    foreach ($class_vars as $name => $value) {
      $exists_in_base = false;
      foreach ($base_vars as $base_name => $$base_value) {
        if($base_name == $name || $name == $this->_key) $exists_in_base = true;
      }
      if (!$exists_in_base) $this->_fields[$name] = "varchar(100)";
    }
  }
}

?>
