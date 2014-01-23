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
        $res = "";
        $res .="<label for=\"" . $field . "\">" . $label . "</label>\n";
        $res .="<input type=\"text\" name=\"" . $field . "\" id=\"" . $field . "\" class=\"" . $class . "\" value=\"" . $value . "\" prails_validation=\"\" prails_validation_message=\"\"/>\n";
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
     * @param string $class The drop down css class
     *
     * @return string the Rendered DropDown
     */
    public function DropDown($field, $dataSet, $delta, $label = "", $class = "") {
        $deltaFields = explode($delta, ":");
        $value = $this->object->$field;
        $validator = 0;
        $res = "";
        $selected = "";
        $res .= "<select name=\"" . $field . "\" id=\"" . $field . "\"";
        foreach ($this->dropDownDataSet as $row) {
            $validator++;
            if ($row->$deltaFields[0] == $value)
                $selected = " selected";
            else
                $selected = "";
            $res .= "<option value=\"" . $row->$deltaFields[0] . "\"" . $selected . ">" . $row->$deltaFields[1] . "</option>\n";
        }
        $res .= "</select>\n";
        //if($validator==0) return "No records available.";
        if ($validator == 0)
            return "&nbsp;";
        else
            return $res;
    }

}

?>
