<?php
$test->GroupTests("QueryNuilder Class");
$qb = new query_builder();
$sql = $qb->SelectAll()->From(array("table"))->Where(array("a" => 1));
$test->AssertEqual($sql, "SELECT * FROM table WHERE a='1'", "Test query builder");
$sql = $qb->SelectAll(array("name","last_name"))->From(array("table1", "table2"))->Where(array("a" => 1, "b"=>2));
$test->AssertEqual($sql, "SELECT name,last_name FROM table1,table2 WHERE a='1' AND b='2'", "Test query builder more tables and fields");
$sql = $qb->SelectAll()->From(array("table3", "table4"))->WhereLinked(array("table3.a" => "table4.b"));
$test->AssertEqual($sql, "SELECT * FROM table3,table4 WHERE table3.a=table4.b", "Test query builder with foreign keys");
$sql = $qb->SelectAll()->From(array("table3", "table4"))->Where("1=1");
$test->AssertEqual($sql, "SELECT * FROM table3,table4 WHERE 1=1", "Test query builder with foreign keys");
$sql = $qb->SelectAll()->From(array("table3", "table4"));
$test->AssertEqual($qb->Parse($sql), "SELECT * FROM table3,table4", "Test query builder parser with object");
$sql = $qb->SelectAll()->From(array("table3", "table4"))->Where("1=1");
$test->AssertEqual($qb->Parse($sql), "SELECT * FROM table3,table4 WHERE 1=1", "Test query builder parser with literal");
?>