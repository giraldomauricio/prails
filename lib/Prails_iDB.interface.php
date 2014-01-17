<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author murdock
 */
interface Prails_iDB {
  public function GetConnectionId($dbserver, $dbuser, $dbpassword, $dbname);
  public function ExecuteQuery($sql);
  public function GetRowsCount();
  public function GetRowsAffected();
  public function GetInsertId();
  public function GetRecordObject();
  public function ResetRecord();
  public function GetDatabaseTables();
  public function CheckIfTableExists($table_name);
}

?>
