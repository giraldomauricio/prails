<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class demo_controller extends demo_model{
  //put your code here

  public function __construct() {
    
  }
  
  public function demo_action()
  {
    $this->GetAll();
    $this->_html = $this->_data_set->DataSet[0]->name;
    return $this->Render();
  }
  
}


/**
 * Description of demo_controller
 *
 * @author murdock
 */
?>
