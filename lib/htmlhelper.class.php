<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of htmlhelper
 *
 * @author murdock
 */
class HtmlHelper {

  public static function Submit($name, $id="submit") {
    $html = "";
    $html .= "\t" . $label . " <input type=\"submit\" id=\"" . $id . "\" name=\"" . $id . "\" value=\"" . $name . "\" />\n";
    return $html;
  }

  public static function Input($name, $label, $type="text") {
    $html = "";
    $html .= "\t" . $label . " <input type=\"" . $type . "\" id=\"" . $name . "\" name=\"" . $name . "\" />\n";
    return $html;
  }

  public static function InputLabel($name, $label, $type="text") {
    $html = "";
    $html .= "<label for=\"" . $name . "\">\n";
    $html .= "\t" . $label . " <input type=\"" . $type . "\" id=\"" . $name . "\" name=\"" . $name . "\" />\n";
    $html .= "</label>\n";
    return $html;
  }

  public static function InputLabelValue($name, $label, $value, $type="text") {
    $html = "";
    $html .= "<label for=\"" . $name . "\">\n";
    $html .= "\t" . $label . " <input type=\"" . $type . "\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $value . "\" />\n";
    $html .= "</label>\n";
    return $html;
  }

  public function DropDown($field_name, $id_field, $label_field, $default = 0) {
    $validator = 0;
    $res = "";
    $selected = "";
    $res .= "<select name=\"" . $field_name . "\" id=\"" . $field_name . "\"";
    if ($this->onChange != "")
      $res .= " onChange=\"" . $this->onChange . "\"";
    $res .= " >\n";
    $res .= "<option value=\"\">Please select...</option>\n";
    while ($this->load()) {
      $validator++;
      if ($this->field->$id_field == $default)
        $selected = " selected";
      else
        $selected = "";
      $res .= "<option value=\"" . $this->field->$id_field . "\"" . $selected . ">" . $this->field->$label_field . "</option>\n";
    }
    $res .= "</select>\n";
    //if($validator==0) return "No records available.";
    if ($validator == 0)
      return "&nbsp;";
    else
      return $res;
  }

  public static function TextField($field, $value="", $label="", $class="") {
    $res = "";
    $res .="<label for=\"" . $field . "\">" . $label . "</label>\n";
    $res .="<input type=\"text\" name=\"" . $field . "\" id=\"" . $field . "\" class=\"".$class."\" value=\"" . $value . "\"/>\n";
    print $res;
  }

}
?>