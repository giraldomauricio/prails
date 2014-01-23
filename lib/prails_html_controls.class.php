<?php

class PrailsHtmlControls {
  
  var $name;
  var $class;
  var $object;

  public function TextField($field, $label="", $class="") {
    if($label == "") $label = $field;
    if($class == "") $class = $this->class;
    $value = $this->object->$field;
    $res = "";
    $res .="<label for=\"" . $field . "\">" . $label . "</label>\n";
    $res .="<input type=\"text\" name=\"" . $field . "\" id=\"" . $field . "\" class=\"".$class."\" value=\"" . $value . "\"/>\n";
    print $res;
  }
  
}

?>
