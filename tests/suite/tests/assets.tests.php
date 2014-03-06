<?php
$test->GroupTests("Prails Assets");
$assets_test = new prails();
$assets_test->_assets_priority = array("_jquery.js");
$assets_test->GetAssets();
$asset_present = false;
foreach ($assets_test->_assets as $key => $value) {
    if(strpos($value, "_jquery.js"))
    {
        $asset_present = true;
    }
}
$test->AssertTrue($asset_present);