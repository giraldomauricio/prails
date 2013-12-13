<?php
// Prails v.1
require 'bootstrapper.php';
// Routing file
// This file handles all the requests received and routes
// the calls acordingly.
// ?controller/view/Q_S
$init = new routes();
$init->_default_controller = DEFAULT_CONTROLLER;
$init->_default_action = DEFAULT_ACTION;
$init->AnalizeAndProcessRoutes();
// MVC approach:
// 1) Load the Controller
// 2) Execute the method in the controller described by the action/view pair
// 3) Either render the view, render here the result or redirect
$controller = $init->controller;
$core = new $controller();
$action = $init->action;
$id = $init->id;
$core->_controller = $controller;
$core->_action = $action;
$core->_view = $action;
$core->_id = $id;
// Handle Post requests:
if($_POST["PRAILS_POST"] == "TRUE" && method_exists($core, $action."_post")) $action = $action."_post";
// Prails can invoke a generic rescue method on every controller to handle any request
if(method_exists($core, "rescue") && !method_exists($core, $action)) $action = "rescue";
// Verify if the method exists
if(method_exists($core, $action))
{
  $core->$action();
}
else
{
  rescue::NoActionInController($action, $controller);
}
?>