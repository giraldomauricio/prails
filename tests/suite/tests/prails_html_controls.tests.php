<?php
$test->GroupTests("Prails HTML Controls");
class aModel extends prails{
    var $aName = "Hello";
}
$aModelInstance = new aModel();
$aModelInstance->aName = "Hello";
$aModelInstance->DynamicCall();
$text_field = $aModelInstance->htmlControl->TextField("aName", "A label", "A class");
$test->AssertContains($text_field,"value=\"Hello\"","Test value assignation");
$test->AssertContains($text_field,"class=\"A class\"","Test class assignation");