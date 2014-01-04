<?php
$test->GroupTests("Prails Class");
$prails = new prails();
$delta = $prails->ParseDelta("a:1,b:c");
$test->Assert($delta["a"] == 1, "Test Parsing deltas with integers");
$test->Assert($delta["b"] == "c", "Test Parsing deltas with strings");
$delta = $prails->ParseDelta("a");
$test->Assert($delta["a"] == null, "Test Parsing deltas with null");
$delta = $prails->ParseDelta();
$test->Assert($delta == null, "Test Parsing deltas with null");
?>
