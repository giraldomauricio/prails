<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of prails_specs
 *
 * @author murdock
 */
class PrailsSpecs {

  var $result = false;
  var $obj;
  var $given = array();
  var $it_has = array();
  var $var_name;
  var $var_value;
  var $message;
  var $spec = array();
  var $spec_file;
  
  public function Load($file)
  {
    $this->spec_file = TESTS_FOLDER.$file;
    if(file_exists($this->spec_file))
    {
      $this->spec = file($this->spec_file);
    }
  }


  public function Spec() {
    for ($i = 0; $i < func_num_args(); $i++) {
      $argv = func_get_arg($i);
      $this->Given($argv);
      $this->ItHas($argv);
    }
    return $this;
  }
  
  public function SpecFromFile($file = "") {
    if($file != "") $this->Load ($file);
    foreach ($this->spec as $argv) {
      $argv = trim($argv);
      $this->Given($argv);
      $this->ItHas($argv);
    }
    return $this;
  }

  public function Extract($phrase)
  {
    preg_match( '/\'(.*?)\'/', $phrase, $match );
  }
  
  public function Given($phrase)
  {
    preg_match('/Given the (variable|array) \'(.*?)\'/', $phrase, $match );
    if(count($match)>2) $this->var_name = $match[2]; 
  }
  
  public function ItHas($phrase)
  {
    preg_match('/it (don\'t|doesn\'t|do) (has|have) the (.*?) of \'(.*?)\'/', $phrase, $match );
    if(count($match)>3) $this->var_value = $match[4];
  }
  
  public function Run()
  {
    $var = $this->var_name;
    if($this->obj->$var == $this->var_value)
    {
      $this->message = "The variable '".$var."' matches the value '".$this->var_value."'.";
      $this->result = true;
    }
    else
    {
      $this->message = "The variable '".$var."' does not match the value '".$this->var_value."'. It has '".$this->obj->$var."'.";
      $this->result = false;
    }
    
  }
  
}

?>
