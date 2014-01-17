<?php
$test->GroupTests("CodeFirst Tests");
class test_users extends prails{
  
  var $id_user;
  var $use_name;
  var $use_last;
  
  var $_table = "test_users";
  var $_key = "id_user";
  
}
$code_first_tests = new CodeFirst();
$test->AssertTrue($code_first_tests->setModel("test_users"),"Test Succesful Invocations");
$test->AssertTrue($code_first_tests->_model instanceof test_users,"Test Succesful Instance");
$test->AssertTrue(!$code_first_tests->setModel("foo_test_users"),"Test Failed Invocations");
?>