<?php
require_once "../bootstrapper.php";
$test = new VerySimpleTests();
$mydir = dir("suite");
  while (($file = $mydir->read())) {
    if (substr($file, 0, 1) != "." && substr($file, 0, 1) != "_") {
      if (DEBUG)
        logFactory::log($this, "Tests", " Loading [" . $file . "]..");
      require_once LIBRARY . "tests/suite/" . $file;
      if (DEBUG)
        logFactory::log($this, "Tests", "[" . $file . "] loaded successfully.");
    }
  }
  $test->Results();
?>
