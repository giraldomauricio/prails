<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rescue
 *
 * @author murdock
 */
class rescue {
  
  public static function NoConfigurationAvailable()
  {
      print "<div style=\"color:red\"><strong>Prails Configuration Error</strong></div><hr />The host has no configuration assigned. Please go to the 'hosts' folder and setup a configuration for: ".$_SERVER["HTTP_HOST"];
  }
  
  public static function NoActionInController($action = "nil", $controller = "nil")
  {
    print "<div style=\"color:red\"><strong>Prails MVC Error</strong></div><hr />The action [<i>".$action."</i>] is not yet configured in your controller [<i>".$controller."</i>].";
  }
  
  public static function NoDefaultActionAndController()
  {
    print "<div style=\"color:red\"><strong>Prails MVC Error</strong></div><hr />The application has no default Action and Controller. Set them in the config file</i>].";
  }
  
  public static function NoDefaultDatabaseDriver()
  {
    print "<div style=\"color:red\"><strong>Prails Database Error</strong></div><hr />The application doesn't have a database driver available</i>.";
  }
  
  public static function ErrorReadingFixture()
  {
    print "<div style=\"color:red\"><strong>Prails Fixture Error</strong></div><hr />The application cant load the fixture file</i>].";
  }
  
  public static function ErrorInsertQuery()
  {
    print "<div style=\"color:red\"><strong>Prails Query Builder Error</strong></div><hr />There is no table defined</i>].";
  }
  
  public static function ViewRequiresAuthentication()
  {
    print "<div style=\"color:red\"><strong>Prails Private View</strong></div><hr />View requires authentication</i>.";
  }
  
}

?>