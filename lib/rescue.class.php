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
  
}

?>
