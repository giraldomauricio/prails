<?php
$input = HtmlHelper::Input("foo", "bar");
$test->Assert(strpos($input, "foo")>0, "Test input field for type");
$test->Assert(strpos($input, "ar")>0, "Test input field for name");
$input = HtmlHelper::Input("foo");
$test->Assert(strpos($input, "text")>0, "Test input field for default type");
$input = HtmlHelper::Input("foo","bar", false);
$test->Assert(strpos($input, "input")<=0, "Test input field and no labels");
?>
