<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of prails
 *
 * @author murdock
 */
class prails {

  //put your code here

  var $_html = "";
  var $_table = "";
  var $_id = "";
  var $_data_set;
  var $_controller = "";
  var $_action = "";
  var $_view = "";
  var $_private = false;
  var $_layout = "layout";
  var $_assets = "";

  public function index() {
    $this->_html = "Welcome to Prails";
  }

  /* Render logic/stragegy:
  1) Controller is instantiated. Controller inherits the model, wich
     inherits the core class. Controller has all the required methods
     to operate.
  2) Controller->Action is invoked and executed. The Action may fill the
     _html variable with some content or set a view to render.
  3) If the view is available, _html is ignored and its filled with the view.
  4) The view uses the information set by the controller.
  
  POST/GET methods
  1) Post and Get can be handled by the controller (Postback) or by
     an API via Ajax (Requires jQuery).
  */
  public function Render() {
    // Try to get the view based on the action
    
    if(!$this->_view)
    {
      if($this->_private)
      {
        if(file_exists(ROOT."app/views/public/".$this->_controller."/".$this->_action.".php"))
        {
          $this->_view = $this->_action.".php";
        }
      }
      else
      {
        if(file_exists(ROOT."app/views/private/".$this->_controller."/".$this->_action.".php"))
        {
          $this->_view = $this->_action.".php";
        }
      }
    }
    if($this->_view)
    { 
      ob_start();
        // TODO: refactor public and private locations to set by user
        if($this->_private) include ROOT."app/views/private/".$this->_controller."/".$this->_view.".php";
        else include include ROOT."app/views/public/".$this->_controller."/".$this->_view.".php";
        $this->_html = ob_get_contents();
      ob_end_clean();
    }
    print $this->_html;
  }

  // Layouts files are in the form of "_layout_name.php, but is
  // called "layout_name".
  public function RenderView()
  {
    $layout_file = ROOT."app/views/_".$this->_layout.".php";
    if($layout_file != "" && file_exists($layout_file))
    {
      ob_start();
        include $layout_file;
        $this->_html = ob_get_contents();
      ob_end_clean();
    }
    print $this->_html;
  }


  public function LoadFixture($fixture_name) {
    include ROOT."/db/fixtures/" . $fixture_name;
    $this->_data_set = $data;
  }

  public function GetAllDS() {
    return $this->_data_set;
  }
  
  public function ProcessRepeat($html)
  {
    $start_tag = "{start_repeat}";
    $end_tag = "{end_repeat}";
    if(strpos($html, $start_tag) && strpos($html, $end_tag))
    {
      $html_to_repeat = substr($html, strpos($html, $start_tag) + strlen($start_tag), strpos($html, $end_tag) - strpos($html, $start_tag)- strlen($start_tag)) ;
      return $html_to_repeat;
    }
    if(strpos($html, substr($start_tag, 1,  strlen($start_tag)-1)) && strpos($html, $end_tag) && strpos($html, substr($start_tag, 0,1)) == 0)
    {
      $html_to_repeat = substr($html, strpos($html, $start_tag) + strlen($start_tag), strpos($html, $end_tag) - strpos($html, $start_tag)- strlen($start_tag)) ;
      return $html_to_repeat;
    }
    else return $html;
  }
  // MySQL
  public function Connect() {
    $logger = new logFactory();
    logFactory::log($this, "Connecting to " . DBUSER . "@" . DBSERVER . "/" . DBNAME);
    $this->ID = mysql_connect(DBSERVER, DBUSER, DBPASSWORD) or $logger->log($this, "'Error Connecting:" . mysql_error());
    mysql_select_db(DBNAME) or $logger->log($this, "Error Selecting DB:" . mysql_error());
  }

  public function DynamicCall() {
    
    $this->Connect();

    $call = $_REQUEST["doCall"];

    //print $call;

    foreach ($_REQUEST as $key => $value) {
      $this->$key = $value;
    }

    if (method_exists($this, $call)) {
      call_user_func(array($this, $call));
    }

    //$this->$this->idName = "A";
  }
  
  public function QueryAndLoad($sql = "") {
    $logger = new logFactory();
    if (!$sql)
      $sql = $this->sql;
    // Validate malicious code is not present:
    if (!strpos(strtolower($sql), "alter table") && !strpos(strtolower($sql), "drop table") && !strpos(strtolower($sql), "create table")) {
      logFactory::log($this, $sql);
      $this->RES = mysql_query($sql) or $logger->log($this, "SQL ERROR: " . $sql . ", " . mysql_error());
      $this->Load();
      return true;
    }
    else
      return false;
  }
  
  public function Query($sql = "") {
    $logger = new logFactory();
    if (!$sql)
      $sql = $this->sql;
    // Validate malicious code is not present:
    if (!strpos(strtolower($sql), "alter table") && !strpos(strtolower($sql), "drop table") && !strpos(strtolower($sql), "create table")) {
      logFactory::log($this, $sql);
      $this->RES = mysql_query($sql) or $logger->log($this, "SQL ERROR: " . $sql . ", " . mysql_error());
      return true;
    }
    else
      return false;
  }
  
  public function Count() {
    //$this->filas = mysql_num_rows($this->RES) or die(mysql_error());
    $this->recordCount = mysql_num_rows($this->RES) or print(mysql_error());
    return $this->recordCount;
  }
  
  public function GetLastId() {
    $this->last_id = mysql_insert_id();
    return $this->last_id;
  }
  
  public function Lines() {
    //$this->filas = mysql_num_rows($this->RES) or die(mysql_error());
    $this->recordCount = mysql_num_rows($this->RES) or print(mysql_error());
    return $this->recordCount;
  }
  
  public function Affected() {
    return mysql_affected_rows($this->RES);
  }
  
  public function Load() {
    $this->field = mysql_fetch_object($this->RES);
    if ($this->field) {
      foreach ($this->field as $key => $value) {
        $this->$key = $value;
      }
      return true;
    }
    else
      return false;
  }
  
  public function GetParameters() {
    $class_vars = get_class_vars(get_class($this));
    foreach ($class_vars as $name => $value) {
      $this->data[$name] = $_REQUEST[$name];
    }
    return true;
  }
  
  public function InsertOne() {
    $d = $this->GetInsertValues();
    $this->sql = "INSERT INTO " . $this->tableName . " (" . $d[0] . ") VALUES (" . $d[1] . ")";
    if ($this->Query()) {
      $this->lastID = mysql_insert_id();
      return $this->lastID;
    }
    else
      return false;
  }
  
  public function GetAll() {
    $this->sql = "SELECT * FROM " . $this->tableName;
    return $this->Query();
  }
  
  public function GetOne($id) {
    $this->sql = "SELECT * FROM " . $this->tableName . " WHERE " . $this->idName . " = " . $id;
    return $this->Query();
  }
  
  public function UpdateOne($id) {
    $d = $this->GetUpdateValues();
    $this->sql = "UPDATE " . $this->tableName . " SET " . $d . " WHERE " . $this->idName . " = " . $id;
    return $this->Query();
  }
  
  public function DeleteOne($id) {
    $d = $this->getInsertValues();
    $this->sql = "DELETE FROM " . $this->tableName . " WHERE " . $this->idName . " = " . $id;
    return $this->Query();
  }
  
  public function GetInsertValues() {
    $class_vars = get_class_vars(get_class($this));
    foreach ($class_vars as $name => $value) {
      if ($_REQUEST[$name] != "") {
        $keys .= $name . ",";
        if (is_array($_REQUEST[$name]))
          $values .= $this->Enclose(implode(",", $_REQUEST[$name])) . ",";
        else
          $values .= $this->Enclose($_REQUEST[$name]) . ",";
      }
    }
    return array(substr($keys, 0, -1), substr($values, 0, -1));
  }
  
  public function Enclose($data) {
    if (is_numeric($data))
      return $data;
    else {
      return "'" . addslashes(htmlentities($data)) . "'";
    }
  }
  
  public function GetUpdateValues() {
    $sets = "";
    $class_vars = get_class_vars(get_class($this));
    foreach ($class_vars as $name => $key) {
      if ($_REQUEST[$name] != "") {
        if (is_array($_REQUEST[$name]))
          $sets .= $name . " = " . $this->Enclose(implode(",", $_REQUEST[$name])) . ",";
        else
          $sets .= $name . " = " . $this->Enclose($_REQUEST[$name]) . ",";
      }
      if (is_numeric($_REQUEST[$name]) && $_REQUEST[$name] == 0) $sets .= $name . " = 0 ,";
    }
    return substr($sets, 0, -1);
  }

}

?>
