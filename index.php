<?php
// Prails v.0.1
require 'bootstrapper.php';
// Routing file
// This file handles all the requests received and routes
// the calls acordingly.
// ?controller/view
$init = new routes();
$init->AnalizeAndProcessRoutes();
// MVC approach:
// 1) Load the Controller
// 2) Execute the method in the controller described by the action/view pair
// 3) Either render the view, render here the result or redirect
// $controller = new $controller;
$controller = $init->controller;
$controller_instance = new $controller();
$action = $init->action;
$controller_instance->$action();
print $controller_instance->_html;
?>