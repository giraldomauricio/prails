<?php

$PRAILS_ENV = "test";
require_once "../bootstrapper.php";
require_once "parser.php";
$test = new VerySimpleTests();
$mydir = dir("suite");
while (($file = $mydir->read())) {
    if (substr($file, 0, 1) != "." && substr($file, 0, 1) != "_") {
        try {
            if (DEBUG)
                logFactory::log($this, "Tests", " Loading [" . $file . "]..");
            require_once LIBRARY . "tests/suite/" . $file;
            if (DEBUG)
                logFactory::log($this, "Tests", "[" . $file . "] loaded successfully.");
        } catch (Exception $e) {
            echo 'Error loading test ['.$file.']: ', $e->getMessage(), "<br/>\n";
        }
    }
}
$test->Results();
$parser = new JunitParser();
$report = new report_ob();
$results = $parser->parse($report, "results/results.xml");
//print_r($results);
?>
