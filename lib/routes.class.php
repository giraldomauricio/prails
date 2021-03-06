<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of routes
 *
 * @author murdock
 */
class Routes {

  var $controller;
  var $action;
  var $query_string;
  var $id;
  var $_default_controller = "prails";
  var $_default_action = "index";

  public function AnalizeAndProcessRoutes() {
    $initial_query_string = $_SERVER["QUERY_STRING"];
    $request = explode("/", $initial_query_string);
    if ($request[0]) $this->controller = $request[0];
    else $this->controller = $this->_default_controller;
    if($request[1]) $this->action = $request[1];
    else $this->action = $this->_default_action;
    if(!$this->controller && !$this->action) rescue::NoDefaultActionAndController ();
    if(isset($request[2])) $this->query_string = $this->GetQueryString($request[2]);
  }

  public function GetQueryString($query_string) {
    $query_string = str_replace("?", "", $query_string);
    $query_string_array = explode("&", $query_string);
    foreach ($query_string_array as $key_value_pair) {
      $key_value_pair_array = explode("=", $key_value_pair);
      if($key_value_pair_array[0]) $this->$key_value_pair_array[0] = $key_value_pair_array[1];
    }
    if(count($query_string_array)==1) $this->id = $query_string_array[0];
  }

}

?>