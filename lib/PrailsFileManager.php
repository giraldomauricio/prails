<?php

/**
 * FileManager is in charge of uplaoding files and renaming them
 * based in thge user's logic.
 * 
 * This class uses the default PICTURES folder.
 * 
 */
class PrailsFileManager {

  var $file_name = "";
  var $new_name = "";
  var $extension = "";
  var $ready = false;
  var $force_folder_creation = true;
  var $field_name;
  var $upload_folder;

  public function __construct($file_field_name) {
    $this->field_name = $file_field_name;
    $this->file_name = strtolower($_FILES[$file_field_name]["name"]);
    $this->extension = pathinfo($this->file_name, PATHINFO_EXTENSION);
    $this->ready = true;
    $this->upload_folder = PICTURES_PATH;
  }

  public function Upload($sub_folder = "", $rename = false) {
    if ($sub_folder != "") {
      $sub_folder .= "/";
    }
    if ($rename && $this->new_name != "") {
      $this->file_name = $this->new_name;
    }
    $this->CheckSubFolder($sub_folder);
    if ($this->ready) {
      // Legacy: add 077 permissions to old uploaded files
      chmod($this->upload_folder . $sub_folder . $this->file_name, 0777);
      move_uploaded_file($_FILES[$this->field_name]["tmp_name"], $this->upload_folder . $sub_folder . $this->file_name)."--";
      // Fix: add full permissions to the file for future updates.
      chmod($this->upload_folder . $sub_folder . $this->file_name, 0777);
    }
  }

  public function CheckSubFolder($sub_folder) {
    if (!is_dir($this->upload_folder . $sub_folder) && !file_exists($this->upload_folder . $sub_folder) && $this->force_folder_creation) {
      mkdir($this->upload_folder . $sub_folder, 777);
    }
    if (!is_dir($this->upload_folder . $sub_folder) && !file_exists($this->upload_folder . $sub_folder)) {
      $this->ready = false;
    }
  }

  public function RenameBasedOnId($id) {
    $this->file_name = $id . "." . $this->extension;
    $this->file_name = str_replace("..", ".", $this->file_name);
    $this->file_name = str_replace(" ", "", $this->file_name);
  }

}
