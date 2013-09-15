<?php


$qb = new query_builder();
$sql = $qb->SelectAll()->From(array("table"))->Where(array("a" => 1));
$test->AssertEqual($sql, "SELECT * FROM table WHERE a='1'", "Test query builder");
$sql = $qb->SelectAll(array("name","last_name"))->From(array("table1", "table2"))->Where(array("a" => 1, "b"=>2));
$test->AssertEqual($sql, "SELECT name,last_name FROM table1,table2 WHERE a='1' AND b='2'", "Test query builder more tables and fields");
?>
