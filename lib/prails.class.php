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
  var $_private = false;
  var $_layout = "";

  public function index() {
    $this->_html = "Welcome to Prails";
  }

  /* Render logic/stragegy:
  1) Controller is instantiated. Controller inherits the model, wich
     inherits the core class. Controller has all the required methods
     to operate.
  2) Controller->Action is invoked and executed. The Action may fill the
     _html variable with some content or set a view to render.
  3) If the view is available, _html is ignored and its filled with the view.
  4) The view uses the information set by the controller.
  
  POST/GET methods
  1) Post and Get can be handled by the controller (Postback) or by
     an API via Ajax (Requires jQuery).
  */
  public function Render() {
    // Try to get the view based on the action
    if(!$this->_view)
    {
      if($this->_private)
      {
        if(file_exists(ROOT."app/views/public/".$this->_action.".php"))
        {
          $this->_view = $this->_action.".php";
        }
      }
      else
      {
        if(file_exists(ROOT."app/views/private/".$this->_action.".php"))
        {
          $this->_view = $this->_action.".php";
        }
      }
    }
    if($this->_view)
    {
      ob_start();
        // TODO: refactor public and private locations to set by user
        if($this->_private) include ROOT."app/views/private/".$this->_view;
        else include include ROOT."app/views/public/".$this->_view;
        $this->_html = ob_get_contents();
      ob_end_clean();
    }
    return $this->_html;
  }

  public function LoadFixture($fixture_name) {
    include ROOT."/db/fixtures/" . $fixture_name;
    $this->_data_set = $data;
  }

  public function GetAll() {
    return $this->_data_set;
  }
  
  public function ProcessRepeat($html)
  {
    $start_tag = "{start_repeat}";
    $end_tag = "{end_repeat}";
    if(strpos($html, $start_tag) && strpos($html, $end_tag))
    {
      $html_to_repeat = substr($html, strpos($html, $start_tag) + strlen($start_tag), strpos($html, $end_tag) - strpos($html, $start_tag)- strlen($start_tag)) ;
      return $html_to_repeat;
    }
    if(strpos($html, substr($start_tag, 1,  strlen($start_tag)-1)) && strpos($html, $end_tag) && strpos($html, substr($start_tag, 0,1)) == 0)
    {
      $html_to_repeat = substr($html, strpos($html, $start_tag) + strlen($start_tag), strpos($html, $end_tag) - strpos($html, $start_tag)- strlen($start_tag)) ;
      return $html_to_repeat;
    }
    else return $html;
  }

}

?>
