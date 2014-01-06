<?php
$test->GroupTests("Bootstrapper");
$test->Assert(isset ($PRAILS_ENV), "Test environment is set by default");
$test->Assert(sizeof($architecture)>0, "Test architectures");
$test->Assert($PRAILS_ENV == "test", "Test ENV");
?>
