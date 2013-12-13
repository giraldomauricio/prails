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
  
  public static function Submit ($name, $id="submit")
  {
    $html = "";
    $html .= "\t".$label." <input type=\"submit\" id=\"".$id."\" name=\"".$id."\" value=\"".$name."\" />\n";
    return $html;
  }
  
  public static function Input ($name, $label, $type="text")
  {
    $html = "";
    $html .= "\t".$label." <input type=\"".$type."\" id=\"".$name."\" name=\"".$name."\" />\n";
    return $html;
  }
  
  public static function InputLabel ($name, $label, $type="text")
  {
    $html = "";
    $html .= "<label for=\"".$name."\">\n";
    $html .= "\t".$label." <input type=\"".$type."\" id=\"".$name."\" name=\"".$name."\" />\n";
    $html .= "</label>\n";
    return $html;
  }
  
  public static function InputLabelValue ($name, $label, $value, $type="text")
  {
    $html = "";
    $html .= "<label for=\"".$name."\">\n";
    $html .= "\t".$label." <input type=\"".$type."\" id=\"".$name."\" name=\"".$name."\" value=\"".$value."\" />\n";
    $html .= "</label>\n";
    return $html;
  }
  
}

?>
