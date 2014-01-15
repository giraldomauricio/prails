<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of context
 *
 * @author murdock
 */
class context extends db_driver {

    var $_table;
    var $_key;
    //TODO: Apply constraints to validate models
    var $_constraints;
    var $_required = array();

//  var $db_resource;
//  
//  public function GetConnectionId($dbserver, $dbuser, $dbpassword, $dbname)
//  {
//    $this->db_resource = mysql_connect($dbserver, $dbuser, $dbpassword);
//    mysql_select_db($dbname);
//    return $this->db_resource;
//  }
//  
//  public function ExecuteQuery($sql)
//  {
//    $this->db_resource = mysql_query($sql);
//    return $this->db_resource;
//  }
//
//  public function GetRowsCount()
//  {
//    return mysql_num_rows($this->db_resource);
//  }
//  
//  public function GetRowsAffected()
//  {
//    return mysql_affected_rows($this->db_resource);
//  }
//  
//  public function GetInsertId()
//  {
//    return mysql_insert_id();
//  }
//  
//  public function GetRecordObject()
//  {
//    return mysql_fetch_object($this->db_resource);
//  }

    public function LoadFixture($table_name) {
        $result = array();
        $fixture_file = realpath(dirname(__FILE__)) . "/../db/fixtures/" . $table_name . ".txt";
        if (file_exists($fixture_file)) {
            $data_table = file($fixture_file);
            
            $columns = array_map('trim',explode("|",$data_table[0]));
            for ($i = 1; $i < count($data_table); $i++) {
                $record = new stdClass();
                for ($j = 1; $j < count($columns); $j++) {
                    $column = $columns[$j];
                    $row = array_map('trim',explode("|",$data_table[$i]));
                    if(strlen($column)>0) $record->$column = $row[$j];
                }
                array_push($result, $record);
            }
        }
        else rescue::ErrorReadingFixture();
        $this->init($result);
    }
}

?>
