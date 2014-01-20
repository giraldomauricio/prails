<?php

class DatabaseFirst extends context {
  
  var $_tables;
  var $_table;
  var $_fields = array();
  
  public function GetAllTables()
  {
    $this->GetDatabaseTables();
    while ($row = $this->GetRecordObject()) {
      array_push($this->_tables, $row->TABLE_NAME);
    }
  }
  
  public function CreateModel()
  {
    foreach ($this->_tables as $table) {
      $this->_table = $table;
      $this->_fields = array();
      $sql = "SELECT * FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".DBNAME."' AND `TABLE_NAME`='".$table."';";
      $this->ExecuteQuery($sql);
      while ($row = $this->GetRecordObject()) {
        if($row->COLUMN_KEY == "PRI") $this->_key = $row->COLUMN_NAME;
        $this->_fields[$row->COLUMN_NAME] = $row->COLUMN_TYPE;
      }
      
    }
  }
  
}

?>
