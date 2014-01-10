<?php
$test->GroupTests("Core MVC");
class model_to_test extends prails {
  var $id;
  var $name;
  var $_table = "person";
  var $_key = "id";

}
class controller_to_test extends model_to_test {
  public function __construct() {
    $this->DynamicCall();
  }
  public function action_to_test() {
    $this->RenderHtml("Hello");
  }
}
$_SERVER["QUERY_STRING"] = "controller_to_test/action_to_test/";
ob_start();
include ROOT.'index.php';
$results = ob_get_contents();
ob_end_clean();
$test->AssertEqual($results,"Hello","Test Prails Core MVC");
?>