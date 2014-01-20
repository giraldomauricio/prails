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
      $vars = "";
      while ($row = $this->GetRecordObject()) {
        if($row->COLUMN_KEY == "PRI")
        {
            $this->_key = $row->COLUMN_NAME;
        }
        else
        {
            $this->_fields[$row->COLUMN_NAME] = $row->COLUMN_TYPE;
            $vars .= "\t var $".$row->COLUMN_NAME.";\n";
        }
      }
      
      $class =  "";
      $class .= "<"."?\n";
      $class .= "class ".$table."_model extends prails {\n";
      $class .=  "\n";
      $class .= $vars;
      $class .=  "\n";
      $class .=  "\t var $"."_table = '".$table."';\n";
      $class .=  "\t var $"."_key = '".$this->_key."';\n";
      $class .= "}\n";
      $class .= "?".">";
      
    }
  }
  
}

?>