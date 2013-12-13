<?php
$route_class = new routes();
$route_class->action = "foo";
$route_class->controller = "bar";
$route_class->GetQueryString("?a=b");
$test->AssertEqual($route_class->a,"b","Test basic query string decomposing");
$route_class->GetQueryString("?a=b&c=d");
$test->AssertEqual($route_class->a,"b","Test query string decomposing var 1");
$test->AssertEqual($route_class->c,"d","Test query string decomposing var 2");
$_SERVER["QUERY_STRING"] = "foo/bar/?x=y";
$route_class->AnalizeAndProcessRoutes();
$test->Assert($route_class->controller == "foo", "Test controller route");
$test->Assert($route_class->action == "bar", "Test action route");
$test->AssertEqual($route_class->x,"y","Test query string decomposing var 3");
$_SERVER["QUERY_STRING"] = "foo/bar";
$route_class->AnalizeAndProcessRoutes();
$test->Assert($route_class->controller == "foo", "Test controller route only controller and action");
$test->AssertEqual($route_class->action , "bar", "Test only action");
$_SERVER["QUERY_STRING"] = "foo";
$route_class->AnalizeAndProcessRoutes();
$test->Assert($route_class->controller == "foo", "Test controller route only controller");
$test->AssertEqual($route_class->action , "index", "Test only action");
?>
