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

class db_driver implements Prails_iDB {

    var $db_resource;
    var $db_type = "MSSQLI";
    var $rows_count;
    var $rows_affected;
    var $insert_id;
    var $record;
    var $row_pointer;
    var $db_result;

    public function GetConnectionId($dbserver, $dbuser, $dbpassword, $dbname) {
      $this->db_resource = new mysqli($dbserver, $dbuser, $dbpassword, $dbname);  
    }

    public function ExecuteQuery($sql) {
        $this->db_result = $this->db_resource->query($sql);
    }

    public function GetRowsCount() {
      return $this->db_result->num_rows;
    }

    public function GetRowsAffected() {
        return $this->db_resource->affected_rows;
    }

    public function GetInsertId() {
        return $this->db_result->insert_id;
    }

    public function GetRecordObject() {
        return $this->db_result->fetch_object();
    }

    public function ResetRecord() {
        $this->db_result->data_seek(0);
    }

    public function CheckIfTableExists($table_name) {
        $sql = "select COUNT(*) as total from information_schema.tables WHERE table_name = '" . $table_name . "'";
        $this->ExecuteQuery($sql);
        $res = $this->GetRecordObject();
        $count = $res->total;
        if($count == 0) return false;
        else return true;
    }

    public function GetDatabaseTables() {
        $sql = "select * from information_schema.tables WHERE TABLE_SCHEMA = '".DBNAME."'";
        $this->ExecuteQuery($sql);
    }

}

?>