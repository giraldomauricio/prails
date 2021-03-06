<?php

/**
 * Description of Prails_MSSQL
 *
 * @author murdock
 */
class db_driver implements Prails_iDB {

    var $db_resource;
    var $db_type = "TEST";
    var $rows_count = 0;
    var $rows_affected = 0;
    var $insert_id = 0;
    var $record = null;
    var $row_pointer = -1;
    var $db_result;

    public function init($recordset) {
        $this->row_pointer = -1;
        $this->record = $recordset;
    }

    public function GetConnectionId($dbserver, $dbuser, $dbpassword, $dbname) {
        return true;
    }

    public function ExecuteQuery($sql) {
        return true;
    }

    public function GetRowsCount() {
        if ($this->record == null)
            return $this->rows_count;
        else
            return count($this->record);
    }

    public function GetRowsAffected() {
        return $this->rows_affected;
    }

    public function GetInsertId() {
        return $this->insert_id;
    }

    public function GetRecordObject() {
        if ($this->record == null)
            return false;
        else {
            if ($this->row_pointer < count($this->record)) {
                $this->row_pointer++;
                return $this->record[$this->row_pointer];
            } else
                return false;
        }
    }

    public function ResetRecord() {
        $this->row_pointer = -1;
    }

    public function CheckIfTableExists($table_name) {
        if ($this->rows_count == 0)
            return false;
        else
            return true;
    }

    public function GetDatabaseTables() {
        return true;
    }
    
    public function PageQuery($sql)
    {
      return true;
    }

}

?>