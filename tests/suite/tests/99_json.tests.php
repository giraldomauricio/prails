<?php
$prails = new prails();
$test->GroupTests("Prails Json output");
$recordset = $prails->LoadFixture("test");

ob_start();
$prails->DataSetToJson();
$actual = ob_get_contents();
$expected = '[{"id":"1","name":"Test 1","email":"test@test.com"},{"id":"2","name":"Test 2","email":"test2@test.com"},{"id":"3","name":"Test 3","email":"test3@test.com"}]';
ob_end_clean();
$test->AssertEqual($actual,$expected,"Test Json output");