<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of prails
 *
 * @author murdock
 */
class prails {
  //put your code here
  
  var $_html = "";
  var $_table = "";
  var $_id = "";
  var $_data_set;
  
  var $_controller = "";
  var $_action = "";
  var $_view = "";
  
  public function Render()
  {
    return $this->_html;
  }
  
  public function LoadFixture($fixture_name)
  {
    include getcwd().'/../../db/fixtures/'.$fixture_name;
    $this->_data_set = $data;
  }
  
  public function GetAll()
  {
    return $this->_data_set;
  }
  
}

?>
