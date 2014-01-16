<?php

$core = new demo_controller();
$core->
$core->_html = "Hello";
ob_start();
$core->Render();
$results = ob_get_contents();
ob_end_clean();
$test->AssertEqual($results, "Hello", "Test render");
$core->LoadFixture("demo_fixture.php");
$test->AssertEqual($core->_data_set->DataSet[0]->id, 1, "Test dataset with fixture");
$_SERVER["QUERY_STRING"] = "demo_controller/demo_action/";
$route_class = new routes();
$route_class->AnalizeAndProcessRoutes();
$controller = $route_class->controller;
$action = $route_class->action;
$test->AssertEqual($controller, "demo_controller", "Test controller route");
$test->AssertEqual($action, "demo_action", "Test action route");
$newcore = new $controller();
$newcore->LoadFixture("demo_fixture.php");
ob_start();
$newcore->$action();
$actual = ob_get_contents();
ob_end_clean();
$test->AssertEqual($actual, "Some name", "Test dinamic action instance");
$html = "<div>{start_repeat}Foo{end_repeat}</div>";
$extraction = $core->ProcessRepeat($html);
$test->AssertEqual($extraction,"Foo","Test repeat extraction");
$html = "<div>{start_repeat}Foo</div>";
$extraction = $core->ProcessRepeat($html);
$test->AssertEqual($extraction,$html,"Test repeat extraction bad repeat");
$html = "{start_repeat}Foo{end_repeat}";
$extraction = $core->ProcessRepeat($html);
$test->AssertEqual($extraction,"Foo","Test repeat extraction beginning");
$html = "start_repeat}Foo{end_repeat}";
$extraction = $core->ProcessRepeat($html);
$test->AssertEqual($extraction,$html,"Test repeat extraction beginning bad");
$token = $core->Tokenize();
$test->AssertEqual($token,$core->ValidateToken($token),"Test token validation");
?>
