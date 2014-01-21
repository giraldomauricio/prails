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
$migrations_folder = ROOT."temp_test_".date("ymdhis")."/";
$code_first_tests->_migrations->_migrations_folder = $migrations_folder;
mkdir($migrations_folder, 0777);
$test->AssertTrue($code_first_tests->SetModel("test_users"),"Test Succesful Invocations");
$test->AssertTrue($code_first_tests->_model instanceof test_users,"Test Succesful Instance");
$test->AssertTrue(!$code_first_tests->SetModel("foo_test_users"),"Test Failed Invocations");
$code_first_tests->GetTableInfo();
$test->AssertTrue($code_first_tests->_fields["use_name"] == "varchar(100)","Test Field extraction");
$test->AssertTrue($code_first_tests->_fields["id_user"] == "","Test Field extraction key");
$test->AssertTrue($code_first_tests->_fields["_html"] == "","Test Field interference extraction base field");
$test->AssertEqual($code_first_tests->_table,"test_users","Test table extraction");
$test->AssertEqual($code_first_tests->_key,"id_user","Test id extraction");
$code_first_tests->rows_count = 1;
$code_first_tests->LoadFixture("table_columns_fixture");
$code_first_tests->CompareTables();
$test->AssertContains($code_first_tests->_sql,"ALTER","Test to Alter a Table");
$migration_sql = file_get_contents($migrations_folder.$code_first_tests->_migrations->_migration_file);
unlink($migrations_folder.$code_first_tests->_migrations->_migration_file);
$test->AssertContains($code_first_tests->_sql,$migration_sql,"Test Migration");
$code_first_tests->rows_count = 0;
$code_first_tests->CompareTables();
$test->AssertContains($code_first_tests->_sql,"CREATE","Test to Create a Table");
Utils::DeleteDirectory($migrations_folder);
?>