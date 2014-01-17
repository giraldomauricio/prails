<?php

/**
 * CodeFirst lets you to create the databases based on the models you create.
 */

/**
 * CodeFirst handles the creation and modification of the data model
 * 
 * @autor Mauricio Giraldo Mutis <mgiraldo@gmail.com>
 * 
 * */
class CodeFirst extends context {

    var $_model;
    var $_fields;
    var $_table;
    var $_key;
    var $_sql;

    public function SetModel($model_name) {
        if (class_exists($model_name)) {
            $this->_model = new $model_name;
            return true;
        } else {
            return false;
        }
    }

    public function GetTableInfo() {
        $this->_table = $this->_model->_table;
        $this->_key = $this->_model->_key;
        $base = new prails();
        $base_vars = get_class_vars(get_class($base));
        $class_vars = get_class_vars(get_class($this->_model));
        foreach ($class_vars as $name => $value) {
            $exists_in_base = false;
            foreach ($base_vars as $base_name => $$base_value) {
                if ($base_name == $name || $name == $this->_key)
                    $exists_in_base = true;
            }
            if (!$exists_in_base)
                $this->_fields[$name] = "varchar(100)";
        }
    }

    public function CompareTables() {
        if (!$this->CheckIfTableExists($this->_table)) {
            $this->CreateTable();
        } else {
            $this->AlterTable();    
        }
    }

    public function CreateTable() {
        $this->_sql = "CREATE TABLE " . $this->_table . " (";
        $this->_sql .= $this->_key . " INT NOT NULL AUTO_INCREMENT PRIMARY KEY,";
        $fields = array();
        foreach ($this->_fields as $column => $type) {
            array_push($fields, $column . " " . $type);
        }
        $fields_string = implode(",", $fields);
        $this->_sql .= $fields_string . ");";
    }

    public function AlterTable() {
        $sql = "SELECT `COLUMN_NAME` as col_name FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='" . DBNAME . "' AND `TABLE_NAME`='" . $this->_table . "'";
        $this->ExecuteQuery($sql);
        $db_obj = $this->GetRecordObject();
    }

}

?>
