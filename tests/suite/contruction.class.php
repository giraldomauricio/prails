<?php
$route_class = new routes();
$_SERVER["QUERY_STRING"] = "";
$route_class->AnalizeAndProcessRoutes();
$test->Assert($route_class->controller == "prails", "Test empty controller route");
$test->Assert($route_class->action == "index", "Test empty action route");
$controller = $route_class->controller;
$action = $route_class->action;
$core = new $controller();
$core->$action();
$test->Assert($core->_html == "Welcome to Prails", "Test HTML");
?>
