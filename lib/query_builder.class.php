<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of query_builder
 *
 * @author murdock
 */
class query_builder {
  
  var $_sql;
  var $_tables = array();
  var $_relationships = array();
  var $_models = array();
  var $From;
  
  // $this->SelectAll()->FromTables($tables)->Where("field" => $value);
  
  public function SelectAll($fields = "*")
  {
    if($fields != "*") $this->_sql = "SELECT ".implode(",", $fields);
    else $this->_sql = "SELECT *";
    $selector = new table_selectors($tables);
    $selector->_sql .= $this->_sql;
    return $selector;
  }
  
  public function Parse($var)
  {
    if(is_object($var)) return $var->_sql;
    else return $var;
  }


  public function Delete()
  {
    $this->_sql = "DELETE ";
    $selector = new table_selectors($tables);
    $selector->_sql .= $this->_sql;
    return $selector;
  }
  
  public static function string2Json($string)
  {
    $json = new stdClass();
    $array = explode(",", $string);
    foreach ($array as $value) {
      $pair = explode(":", $value);
      $json->$pair[0] = $pair[1];
    }
    return $json;
  }
}

class query_selectors {
  
  var $_sql;
  
  public function Where($relationships)
  {
    if(is_array($relationships))
    {
      $temp_array = array();
      $this->_sql .= " WHERE ";
      foreach ($relationships as $key => $value) {
        array_push($temp_array, $key."='".$value."'");
      }
      $this->_sql .= implode(" AND ", $temp_array);
      return $this->_sql;
    }
    else return $this->_sql .= " WHERE ".$relationships;
  }
  
  public function WhereLinked($relationships)
  {
      $temp_array = array();
      $this->_sql .= " WHERE ";
      foreach ($relationships as $key => $value) {
        array_push($temp_array, $key."=".$value."");
      }
      $this->_sql .= implode(" AND ", $temp_array);
      return $this->_sql;
  }
  
}

class table_selectors {
  
  var $_sql;
  
  public function From($tables)
  {
    $this->_sql .= " FROM ".implode(",", $tables);
    $qs = new query_selectors();
    $qs->_sql .= $this->_sql;
    return $qs;
  }
  
}

?>
