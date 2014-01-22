<?php
$test->GroupTests("Migration Tests");
$test_migrations = new db_migrations();
$test->AssertTrue(strpos($test_migrations->_migrations_folder, "migrations"),"Test Constructions");
$test_migrations->create_db_migration_table();
$test->AssertTrue(strpos($test_migrations->sql, "_prails_database_migrations"),"Test migration table creation");
$migrations_folder = $test->CreateTempFolder();
$test_migrations->_migrations_folder = $migrations_folder;
$test_migrations->add_migration_file("Some query");
$test_migrations->LoadFixture("migrations_fixture");
$test_migrations->load_db_migrations();
$test->AssertEqual($test_migrations->migration_files[0], "file.sql","Test migration load");
$test_migrations->_migration_file = "file.sql";
$fp = fopen($test_migrations->_migrations_folder . $test_migrations->_migration_file, "x");
fwrite($fp, "Query 1");
fclose($fp);
$test_migrations->_migration_file = "file2.sql";
$fp = fopen($test_migrations->_migrations_folder . $test_migrations->_migration_file, "x");
fwrite($fp, "Query 2");
fclose($fp);
$test_migrations->check_db_migrations();
$test->AssertEqual($test_migrations->missing_files[0], "file2.sql","Test missing migration");
$test_migrations->run_migrations();
$test->AssertContains($test_migrations->sql, ".sql","Test run migration");
$test->DeleteTempFolder()
?>
