<?php
$test->GroupTests("Prails Specs");
$specs = new PrailsSpecs();
$obj = new stdClass();
$obj->home = "world";
$specs->obj = $obj;
$specs->spec (
	"Given the variable 'home'",
	"it doesn't have the value of 'world'"
)->Run();
$test->Assert($specs->result,"Test Spec true result");
$test->AssertContains($specs->message,"matches","Test Spec message");
$obj->home = "universe";
$specs->obj = $obj;
$specs->spec (
	"Given the variable 'home'",
	"it doesn't have the value of 'world'"
)->Run();
$test->Assert(!$specs->result,"Test Spec flase result");
$test->AssertContains($specs->message,"does not","Test Spec false result message");
?>
