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
    print "The host has no configuration assigned. Please go to the 'hosts' folder and setup a configuration for: ".$_SERVER["HTTP_HOST"];
  }
  
  public static function NoActionInController($action, $controller)
  {
    print "<div style=\"color:red\"><strong>Prails Error</strong></div><hr />The action [<i>".$action."</i>] is not yet configured in your controller [<i>".$controller."</i>].";
  }
  
}

?>
