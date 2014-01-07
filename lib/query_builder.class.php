<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of query_builder
 *
 * @author murdock
 */
class query_builder {

    var $_sql;
    var $_tables = array();
    var $_table;
    var $_key;
    var $_relationships = array();
    var $_models = array();
    var $From;
    var $_model;

    // $this->SelectAll()->FromTables($tables)->Where("field" => $value);

    public function __construct($obj) {
        if($obj)
        {
            $this->_table = $obj->_table;
            $this->_model = $obj;
        }
    }


    public function SelectAllRelated($tables, $fields = "*") {
        $this->_sql = "SELECT " . $fields . " FROM " . $tables . " WHERE ";
        $where = array();
        $tables_array = array_map('trim', explode(",", $tables));
        foreach ($this->_relationships as $tablesNames => $fieldsMatch) {
            $relationshipA = explode(":", $tablesNames);
            $relationshipB = explode(":", $fieldsMatch);
            if (in_array($relationshipA[0], $tables_array) && in_array($relationshipA[1], $tables_array)) {
                array_push($where, $relationshipA[0] . "." . $relationshipB[0] . " = " . $relationshipA[1] . "." . $relationshipB[1]);
            }
        }
        $this->_sql .= implode(" AND ", $where);
        return $this->_sql;
    }

    public function SelectAllRelatedC($tables, $fields = "*") {
        $qs = new query_selectors();
        $qs->_sql = $this->SelectAllRelated($tables, $fields);
        return $qs;
    }

    public function SelectAll($fields = "*") {
        if ($fields != "*")
            $this->_sql = "SELECT " . implode(",", $fields);
        else
            $this->_sql = "SELECT *";
        $selector = new table_selectors($tables);
        $selector->_sql .= $this->_sql;
        return $selector;
    }

    public function Parse($var) {
        if (is_object($var))
            return $var->_sql;
        else
            return $var;
    }

    public function Delete() {
        $this->_sql = "DELETE ";
        $selector = new table_selectors($tables);
        $selector->_sql .= $this->_sql;
        return $selector;
    }

    public function Insert($table_name = "") {
        $values = new insert_value_selectors();
        if ($table_name != "" && $this->_table == "") {
            $this->_sql = "INSERT INTO " . $table_name;
            $values->_sql = $this->_sql;
            return $values;
        } else if ($table_name == "" && $this->_table != "") {
            $this->_sql = "INSERT INTO " . $this->_table;
            $values->_sql = $this->_sql;
            return $values->Values($this->_model);
        }
        else
        {
            rescue::ErrorInsertQuery ();
        }
    }

    public static function string2Json($string) {
        $json = new stdClass();
        $array = explode(",", $string);
        foreach ($array as $value) {
            $pair = explode(":", $value);
            $json->$pair[0] = $pair[1];
        }
        return $json;
    }

}

class query_selectors {

    var $_sql;

    public function Where($relationships) {
        if (is_array($relationships)) {
            $temp_array = array();
            $this->_sql .= " WHERE ";
            foreach ($relationships as $key => $value) {
                array_push($temp_array, $key . "='" . $value . "'");
            }
            $this->_sql .= implode(" AND ", $temp_array);
            return $this->_sql;
        } else
            return $this->_sql .= " WHERE " . $relationships;
    }

    public function WhereLinked($relationships) {
        $temp_array = array();
        $this->_sql .= " WHERE ";
        foreach ($relationships as $key => $value) {
            array_push($temp_array, $key . "=" . $value . "");
        }
        $this->_sql .= implode(" AND ", $temp_array);
        return $this->_sql;
    }

    public function Conditionals($conditions) {
        return $this->_sql .= " AND " . $conditions;
    }

}

class table_selectors {

    var $_sql;

    public function From($tables) {
        if(is_array($tables)) $this->_sql .= " FROM " . implode(",", $tables);
        else $this->_sql .= " FROM " . $tables;
        $qs = new query_selectors();
        $qs->_sql .= $this->_sql;
        return $qs;
    }

}

class insert_value_selectors {

    var $_sql;

    public function Values($delta) {
        $fields = array();
        $values = array();
        if (is_string($delta)) {
            $delta = Utils::ParseDelta($delta);
            foreach ($delta as $key => $value) {
                array_push($fields, $key);
                array_push($values, Utils::Enclose($value));
            }
        } else if (is_object($delta)) {
            $class_vars = get_class_vars(get_class($delta));
            foreach ($class_vars as $name => $value) {
                if ($_REQUEST[$name] != "") {
                    array_push($fields, $name);
                    if (is_array($_REQUEST[$name]))
                        array_push($values, Utils::Enclose(implode(",", $_REQUEST[$name])));
                    else
                        array_push($values, Utils::Enclose($_REQUEST[$name]));
                }
            }
        }
        $this->_sql .= " (" . implode(",", $fields) . ") VALUES (" . implode(",", $values) . ")";
        return $this->_sql;
    }

}

?>
