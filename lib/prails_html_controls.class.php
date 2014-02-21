<?php

/**
 * Prails HTML Controls help rendering HTML controls using the Model data
 */

/**
 * PrailsHTMLControls Class
 * 
 * Renders HTML Controls
 * 
 * @autor Mauricio Giraldo Mutis <mgiraldo@gmail.com>
 * 
 * */
class PrailsHtmlControls {

  var $name;
  var $class;
  var $object;
  var $validations = array();
  var $dropDownDelta;
  var $dropDownDataSet;

  /**
   * Renders a Text Field using the Data Model
   *
   * @return string the Rendered TextField
   */
  public function TextField($field, $label = "", $class = "") {
    if ($label == "")
      $label = $field;
    if ($class == "")
      $class = $this->class;
    $value = $this->object->$field;
    $field_error = "";
    if (count($this->object->_errors) > 0) {
      
      foreach ($this->object->_errors as $error) {
        
        if ($error->field == $field)
        {
          
          $field_error = $error->detail;
        }
          
      }
    }
    if($field_error != "") $field_error = "<div>".$field_error."</div>";
    $res = "";
    $res .="<label for=\"" . $field . "\">" . $label . "</label>\n";
    $res .="<input type=\"text\" name=\"" . $field . "\" id=\"" . $field . "\" class=\"" . $class . "\" value=\"" . $value . "\" prails_validation=\"\" prails_validation_message=\"\"/>".$field_error."\n";
    return $res;
  }

  /**
   * Renders a Hidden Field using the Data Model
   *
   * @return string the Rendered HiddenField
   */
  public function HiddenField($field) {
    $value = $this->object->$field;
    $res = "";
    $res .="<input type=\"hidden\" name=\"" . $field . "\" id=\"" . $field . "\" value=\"" . $value . "\"/>\n";
    return $res;
  }
  
  /**
   * Renders a Password Field using the Data Model
   *
   * @return string the Rendered TextField
   */
  public function PasswordField($field, $label = "", $class = "") {
    if ($label == "")
      $label = $field;
    if ($class == "")
      $class = $this->class;
    $value = $this->object->$field;
    $res = "";
    $res .="<label for=\"" . $field . "\">" . $label . "</label>\n";
    $res .="<input type=\"password\" name=\"" . $field . "\" id=\"" . $field . "\" class=\"" . $class . "\" value=\"" . $value . "\" prails_validation=\"\" prails_validation_message=\"\"/>\n";
    return $res;
  }

  /**
   * Renders a Text Field using the Data Model
   *
   * @return string the Rendered TextField
   */
  public function DateField($field, $label = "", $class = "") {
    if ($label == "")
      $label = $field;
    if ($class == "")
      $class = $this->class;
    $value = $this->object->$field;
    $res = "";
    $res .="<label for=\"" . $field . "\">" . $label . "</label>\n";
    $res .="<input type=\"date\" name=\"" . $field . "\" id=\"" . $field . "\" class=\"" . $class . "\" value=\"" . $value . "\"/>\n";
    return $res;
  }

  /**
   * Renders a Drop Down
   * 
   * @param string $field Field Name
   * @param object $dataSet The dataset that feeds the dropdown
   * @param string $delta The option/value delta in the form valueField:labelField
   * @param string $label Optional the Label of the field
   * @param string $class Optional The drop down css class
   *
   * @return string the Rendered DropDown
   */
  public function DropDown($field, $dataSet, $delta, $label = "", $class = "") {
    $deltaFields = explode(":", $delta);
    $value = $this->object->$field;
    $validator = 0;
    $res = "";
    $selected = "";
    $res .="<label for=\"" . $field . "\">" . $label . "</label>\n";
    $res .= "<select name=\"" . $field . "\" id=\"" . $field . "\" class=\"" . $class . "\" >";
    if (is_object($dataSet[0])) {
      foreach ($dataSet as $row) {
        $validator++;

        print $row->$deltaFields[0] . "=" . $value;

        if ($row->$deltaFields[0] == $value)
          $selected = " selected";
        else
          $selected = "";
        $res .= "<option value=\"" . $row->$deltaFields[0] . "\"" . $selected . ">" . $row->$deltaFields[1] . "</option>\n";
      }
    }
    else {
      foreach ($dataSet as $key => $dsvalue) {
        $validator++;
        if ($key == $value)
          $selected = " selected";
        else
          $selected = "";
        $res .= "<option value=\"" . $key . "\"" . $selected . ">" . $dsvalue . "</option>\n";
      }
    }
    $res .= "</select>\n";
    if ($validator == 0)
      return "&nbsp;";
    else
      return $res;
  }

  public function TextArea($field, $cols=80, $rows=10, $label = "", $class = "") {
    $value = $this->object->$field;
    $res = "";
    $res .="<label for=\"" . $field . "\">" . $label . "</label>\n";
    $res .="<textarea class=\"" . $class . "\" cols=\"" . $cols . "\" name=\"" . $field . "\" id=\"" . $field . " rows=\"" . $rows . "\">" . stripslashes($value) . "</textarea>";
    return $res;
  }

}

?>
