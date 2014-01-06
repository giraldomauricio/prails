<?php
$test->GroupTests("Prails Class");
$prails = new prails();
$delta1 = $prails->ParseDelta("a:1,b:c");
$test->Assert($delta1["a"] == 1, "Test Parsing deltas with integers");
$test->Assert($delta1["b"] == "c", "Test Parsing deltas with strings");
$delta2 = $prails->ParseDelta("a");
$test->Assert($delta2["a"] == null, "Test Parsing deltas with null");
$delta3 = $prails->ParseDelta();
$test->Assert($delta3 == null, "Test Parsing deltas with null");
$test->Assert(strpos($db_driver_location, "prails_test.class.php") > 0, "Test Driver");
class demo_class extends prails
{
  var $id;
  var $_table = "person";
  var $_key = "id";
}
$obj_to_test = new demo_class();
$obj_to_test->GetAll();
$test->Assert($obj_to_test->sql == "SELECT * FROM person", "Test Prails Queries: GetAll");
$obj_to_test->GetOne(1);
$test->Assert($obj_to_test->sql == "SELECT * FROM person WHERE id = 1", "Test Prails Queries: GetOne");
$obj_to_test->rows_count = 1;
$test->Assert($obj_to_test->GetRowsCount() == 1, "Test Prails Injection: Row Count");
$obj_to_test->rows_affected = 2;
$test->Assert($obj_to_test->Affected() == 2, "Test Prails Injection: Rows Affected");
$obj_to_test->insert_id = 3;
$test->Assert($obj_to_test->GetInsertId() == 3, "Test Prails Injection: Insert ID");
$token = $obj_to_test->Tokenize();
$test->Assert($obj_to_test->ValidateToken($token),"Test token validation");
$test->GroupTests("Prails Fixtures and Recordsets");
$recordset = $prails->LoadFixture("test");
$test->AssertEqual($prails->GetRowsCount(),3,"Test fixture size");
$test->Assert($prails->Load(),"Test fixture load one time");
$test->AssertEqual($prails->email,"test@test.com","Test fixture record");
$test->Assert($prails->Load(),"Test fixture load second time");
$test->AssertEqual($prails->email,"test2@test.com","Test fixture record");
$test->Assert($prails->Load(),"Test fixture load third time");
$test->AssertEqual($prails->email,"test3@test.com","Test fixture record");
$test->Assert(!$prails->Load(),"Test fixture load empty recordset");
?>