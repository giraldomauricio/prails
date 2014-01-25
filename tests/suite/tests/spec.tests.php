<?php
$test->GroupTests("Prails Specs");
$specs = new PrailsSpecs();
$obj = new stdClass();
$obj->home = "world";
$specs->obj = $obj;
$specs->Spec (
	"Given the variable 'home'",
	"it doesn't have the value of 'world'"
)->Run();
$test->Assert($specs->result,"Test Spec true result");
$test->AssertContains($specs->message,"matches","Test Spec message");
$obj->home = "universe";
$specs->obj = $obj;
$specs->Spec (
	"Given the variable 'home'",
	"it doesn't have the value of 'world'"
)->Run();
$test->Assert(!$specs->result,"Test Spec flase result");
$test->AssertContains($specs->message,"does not","Test Spec false result message");

$obj->test = "testing";
$specs->obj = $obj;

$specs->SpecFromFile("specs/spec1.txt")->Run();

$test->Assert($specs->result,"Test Spec from file");
$test->AssertContains($specs->message,"matches","Test Spec file result message");
?>
