<?php
require "../../lib/prails.class.php";
require "../../lib/routes.class.php";
require "../../app/models/demo_model.class.php";
require "../../app/controllers/demo_controller.class.php";
require "../../lib/tests.class.php";
$test = new VerySimpleTests();

$core = new demo_controller();
$core->_html = "Hello";
$test->AssertEqual($core->Render(), "Hello", "Test render");
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
$actual = $newcore->$action();
$test->AssertEqual($actual, "Some name", "Test dinamic action instance");
$test->Results();
?>
