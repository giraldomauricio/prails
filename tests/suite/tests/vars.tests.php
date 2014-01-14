<?php
$test->GroupTests("Prails Vars");
$test->AssertEqual(PrailsVars::App,"app","Test PrailsApp var");
$test->AssertEqual(PrailsVars::Controllers,"app/controllers","Test PrailsController var");
$test->AssertEqual(PrailsVars::Models,"app/models","Test PrailsModel var");
$test->AssertEqual(PrailsVars::Views,"app/views","Test PrailsViews var");
$test->AssertEqual(PrailsVars::Root,ROOT,"Test PrailsRoot var");