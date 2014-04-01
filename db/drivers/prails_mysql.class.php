<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Prails_MySQL
 *
 * @author murdock
 */
//TODO: Change from MySQL to iMySQL
class db_driver implements Prails_iDB {

  var $db_resource;
  var $db_type = "MSSQL";
  var $rows_count;
  var $rows_affected;
  var $insert_id;
  var $record;
  var $row_pointer;
  var $db_result;

  public function GetConnectionId($dbserver, $dbuser, $dbpassword, $dbname) {
    $this->db_resource = mysql_connect($dbserver, $dbuser, $dbpassword);
    mysql_select_db($dbname);
    return $this->db_resource;
  }

  public function ExecuteQuery($sql) {
    $this->db_resource = mysql_query($sql);
    return $this->db_resource;
  }

  public function GetRowsCount() {
    return mysql_num_rows($this->db_resource);
  }

  public function GetRowsAffected() {
    return mysql_affected_rows($this->db_resource);
  }

  public function GetInsertId() {
    return mysql_insert_id();
  }

  public function GetRecordObject() {
    return mysql_fetch_object($this->db_resource);
  }

  public function ResetRecord() {
    mysql_data_seek($this->RES, 0);
  }

  public function CheckIfTableExists($table_name) {
    $sql = "select COUNT(*) as total from information_schema.tables WHERE table_name = '" . $table_name . "'";
    $this->ExecuteQuery($sql);
    $res = $this->GetRecordObject();
    $count = $res->total;
    if ($count == 0)
      return false;
    else
      return true;
  }

  public function GetDatabaseTables() {
    $sql = "select * from information_schema.tables WHERE TABLE_SCHEMA = '" . DBNAME . "'";
    $this->ExecuteQuery($sql);
  }

  public function PageQuery($page) {
    if (strpos($this->sql, "LIMIT") <= 0) {
      $this->total_pages = intval($this->GetRowsCount() / $this->page_size);
      if ($this->GetRowsCount() % $this->page_size > 0)
        $this->total_pages++;
      $start = $page + ($page - 1) * $this->page_size;
      $end = $this->page_size * $page;
      $this->sql .= " LIMIT " . $start . "," . $end;
      $this->ExecuteQuery($this->sql);
    }
  }

}

?>