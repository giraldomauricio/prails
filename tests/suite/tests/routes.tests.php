<?php
$test->GroupTests("Routes Class");
$route_class = new Routes();
$_SERVER["QUERY_STRING"] = "";
$route_class->AnalizeAndProcessRoutes();
$test->Assert($route_class->controller == "prails", "Test empty controller route");
$test->Assert($route_class->action == "index", "Test empty action route");
class foo{
  public function bar()
  {
    return "Hello";
  }
}
$_SERVER["QUERY_STRING"] = "foo/bar";
$route_class->AnalizeAndProcessRoutes();
$controller = $route_class->controller;
$action = $route_class->action;
$test->Assert($route_class->controller == "foo", "Test basic controller route");
$test->Assert($route_class->action == "bar", "Test basic action route");
$core = new $controller();
$test->Assert($core->$action() == "Hello", "Test basic action execution");
$_SERVER["QUERY_STRING"] = "";
$route_class->_default_action = "";
$route_class->_default_controller = "";
ob_start();
$route_class->AnalizeAndProcessRoutes();
$actual = ob_get_contents();
ob_end_clean();
$test->AssertContains($actual,"has no default Action and Controller", "Test no controller and action");
$_SERVER["QUERY_STRING"] = "foo/bar/?a=b&c=d&e&f";
$route_class->AnalizeAndProcessRoutes();
$test->Assert($route_class->controller == "foo", "Test basic controller route with QS");
$test->Assert($route_class->action == "bar", "Test basic action route with QS");
$test->Assert($route_class->a == "b", "Test QS var a");
$test->Assert($route_class->c == "d", "Test QS var c");
$test->Assert($route_class->e == "", "Test QS empty var e");
$test->Assert($route_class->f == "", "Test QS empty var f");
?>