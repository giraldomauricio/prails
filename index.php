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
$controller = $init->controller;
$core = new $controller();
$action = $init->action;
$core->_controller = $controller;
$core->_action = $action;
$core->_view = $action;
$core->$action();
?>