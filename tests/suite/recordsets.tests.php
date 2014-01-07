<?php
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
class demo_db extends prails
{
  var $id;
  var $_table = "person";
  var $_key = "id";
  var $email;
  var $name;
}
$_REQUEST["email"] = "Test";
$_REQUEST["name"] = "User";
$obj_to_test = new demo_db();
$obj_to_test->DeleteOne(1);
$test->AssertEqual($obj_to_test->sql,"DELETE FROM person WHERE id = 1","Test delete query");
$obj_to_test->InsertOne();
$test->AssertEqual($obj_to_test->sql,"INSERT INTO person (email,name) VALUES ('Test','User')","Test insert query");
$obj_to_test->UpdateOne(1);
$test->AssertEqual($obj_to_test->sql,"UPDATE person SET email = 'Test',name = 'User' WHERE id = 1","Test update query");
$obj_to_test->Find("email:test");
$test->AssertEqual($obj_to_test->sql,"SELECT * FROM person WHERE email= 'test'","Test search query with one field");
$obj_to_test->Find("email:test,foo:bar");
$test->AssertEqual($obj_to_test->sql,"SELECT * FROM person WHERE email= 'test' AND foo= 'bar'","Test search query with multiple fields");
$obj_to_test->Find("email:test,foo:bar","email");
$test->AssertEqual($obj_to_test->sql,"SELECT email FROM person WHERE email= 'test' AND foo= 'bar'","Test search query with multiple fields and one selector");
$obj_to_test->GetOne(1);
$test->AssertEqual($obj_to_test->sql,"SELECT * FROM person WHERE id = 1","Test search query Get One");
$obj_to_test->FindById(1);
$test->AssertEqual($obj_to_test->sql,"SELECT * FROM person WHERE id = 1","Test search query Get One Alias: Find By Id");
$recordset = $prails->LoadFixture("test");
$dataset = $prails->GetDataSet();
$test->AssertEqual(count($dataset),3,"Test full dataset retrieval.");
?>