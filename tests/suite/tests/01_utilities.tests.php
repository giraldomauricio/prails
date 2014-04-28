<?php

class extended_class extends prails{
  
  var $varOne;
  var $varTwo;
  
}

class no_extended_class{
  
  var $varOne;
  var $varTwo;
  var $varThree;
}

$obj_to_test = new extended_class();
$obj_to_test_simple = new no_extended_class();

$res = Utils::GetRealClassVariables($obj_to_test);
$test->AssertEqual(sizeof($res),2,"Test real class variables extraction with inheritance");
$res = Utils::GetRealClassVariables($obj_to_test_simple);
$test->AssertEqual(sizeof($res),3,"Test real class variables extraction no inheritance");
?>