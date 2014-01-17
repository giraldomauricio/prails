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
class CodeFirst {

  var $_model;
  
  public function setModel($model_name) {
    if (class_exists($model_name)) {
      $this->_model = new $model_name;
      return true;
    } else {
      return false;
    }
  }

}

?>
