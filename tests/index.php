<?php

$PRAILS_ENV = "test";
require_once "../bootstrapper.php";
require_once "parser.php";

$test = new VerySimpleTests();
$tests->lib = LIBRARY;
$suites = array("suite/before", "suite/tests", "suite/after");
//$suites = array("suite/tests");
foreach ($suites as $suite) {
  
  // BEFORE
  $mydir = dir("suite/always_before");
  while (($file = $mydir->read())) {
    
    if (substr($file, 0, 1) != "." && substr($file, 0, 1) != "_") {
      try {
        if (DEBUG)
          logFactory::log($this, "Tests", " Loading [" . "suite/always_before"."/".$file . "]..");
        require_once LIBRARY . "tests/"."suite/always_before"."/" . $file;
        if (DEBUG)
          logFactory::log($this, "Tests", "[" . "suite/always_before"."/".$file . "] loaded successfully.");
      } catch (Exception $e) {
        echo 'Error loading test [' . "suite/always_before"."/".$file . ']: ', $e->getMessage(), "<br/>\n";
      }
    }
  }
  //
  
  // DURING
  $mydir = dir($suite);
  while (($file = $mydir->read())) {
    
    if (substr($file, 0, 1) != "." && substr($file, 0, 1) != "_") {
      try {
        if (DEBUG)
          logFactory::log($this, "Tests", " Loading [" . $suite."/".$file . "]..");
        require_once LIBRARY . "tests/".$suite."/" . $file;
        if (DEBUG)
          logFactory::log($this, "Tests", "[" . $suite."/".$file . "] loaded successfully.");
      } catch (Exception $e) {
        echo 'Error loading test [' . $suite."/".$file . ']: ', $e->getMessage(), "<br/>\n";
      }
    }
  }
  
  // AFTER
  $mydir = dir("suite/always_after");
  while (($file = $mydir->read())) {
    
    if (substr($file, 0, 1) != "." && substr($file, 0, 1) != "_") {
      try {
        if (DEBUG)
          logFactory::log($this, "Tests", " Loading [" . "suite/always_after"."/".$file . "]..");
        require_once LIBRARY . "tests/"."suite/always_after"."/" . $file;
        if (DEBUG)
          logFactory::log($this, "Tests", "[" . "suite/always_after"."/".$file . "] loaded successfully.");
      } catch (Exception $e) {
        echo 'Error loading test [' . "suite/always_after"."/".$file . ']: ', $e->getMessage(), "<br/>\n";
      }
    }
  }
  //
  
}
$test->Results();
$parser = new JunitParser();
$report = new report_ob();
$results = $parser->parse($report, "results/results.xml");
//print_r($results);
?>