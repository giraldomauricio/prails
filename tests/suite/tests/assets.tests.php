<?php
$test->GroupTests("Prails Assets");
$assets_test = new prails();
$assets_test->GetAssets();
$test->AssertTrue(array_key_exists("jquery.js", $assets_test->_assets));