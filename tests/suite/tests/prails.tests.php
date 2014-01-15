<?php
$test->GroupTests("Prails Class");
$prails = new prails();
$delta1 = Utils::ParseDelta("a:1,b:c");
$test->Assert($delta1["a"] == 1, "Test Parsing deltas with integers");
$test->Assert($delta1["b"] == "c", "Test Parsing deltas with strings");
$delta2 = Utils::ParseDelta("a");
$test->Assert($delta2["a"] == null, "Test Parsing deltas with null");
$delta3 = Utils::ParseDelta();
$test->Assert($delta3 == null, "Test Parsing deltas with null");
$test->Assert(strpos($db_driver_location, "prails_test.class.php") > 0, "Test Driver");
class demo_class extends prails
{
  var $id;
  var $_table = "person";
  var $_key = "id";
  var $email;
  var $name;
  var $phone;
}
$obj_to_test = new demo_class();
$obj_to_test->_constraints = "email:string,name:string,phone:integer";
$obj_to_test->_required = "email";
$obj_to_test->GetAll();
$test->AssertEqual($obj_to_test->sql,"SELECT * FROM person", "Test Prails Queries: GetAll");
$obj_to_test->GetOne(1);
$test->Assert($obj_to_test->sql == "SELECT * FROM person WHERE id=1", "Test Prails Queries: GetOne");
$obj_to_test->rows_count = 1;
$test->Assert($obj_to_test->GetRowsCount() == 1, "Test Prails Injection: Row Count");
$obj_to_test->rows_affected = 2;
$test->Assert($obj_to_test->Affected() == 2, "Test Prails Injection: Rows Affected");
$obj_to_test->insert_id = 3;
$test->Assert($obj_to_test->GetInsertId() == 3, "Test Prails Injection: Insert ID");
$token = $obj_to_test->Tokenize();
$test->Assert($obj_to_test->ValidateToken($token),"Test token validation");
$test->Assert(Utils::Enclose(1) == 1,"Test enclosing a number");
$test->AssertEqual(Utils::Enclose("Aa"),"'Aa'","Test enclosing a string");
$test->AssertEqual(Utils::Enclose("\""),"'&quot;'","Test enclosing with slashes");
$_REQUEST["email"] = "foo";
$_REQUEST["name"] = "bar";
$_REQUEST["phone"] = "333A";
$test->Assert(!$obj_to_test->IsValid(),"Test Model Validity False");
$test->AssertContains($obj_to_test->_errors[0]->message,"Constraint","Test Model Validity Error Message");
$_REQUEST["phone"] = 333;
$test->Assert($obj_to_test->IsValid(),"Test Model Validity True");
?>