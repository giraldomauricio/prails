<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Prails_MSSQL
 *
 * @author murdock
 */
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
        $sql = "select * from information_schema.tables WHERE table_name = '" . $table_name . "'";
        $this->ExecuteQuery($sql);
    }

    public function GetDatabaseTables() {
        $sql = "select * from information_schema.tables";
        $this->ExecuteQuery($sql);
    }
    
    public function PageQuery($page) {
      return true;
    }

}

?>