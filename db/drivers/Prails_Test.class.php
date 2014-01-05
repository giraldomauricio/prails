<?php
/**
 * Description of Prails_MSSQL
 *
 * @author murdock
 */
class db_driver implements Prails_iDB {
  
  var $db_resource;
  var $db_type = "MSSQL";
  var $rows_count = 0;
  var $rows_affected = 0;
  var $insert_id = 0;
  var $record;
  
  public function GetConnectionId($dbserver, $dbuser, $dbpassword, $dbname)
  {
    return true;
  }
  
  public function ExecuteQuery($sql)
  {
    return true;
  }

  public function GetRowsCount()
  {
    return $this->rows_count;
  }
  
  public function GetRowsAffected()
  {
    return $this->rows_affected;
  }
  
  public function GetInsertId()
  {
    return $this->insert_id;
  }
  
  public function GetRecordObject()
  {
    $this->record = new stdClass;
    return $this->record;
  }
}
?>