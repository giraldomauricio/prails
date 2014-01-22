<?php

/**
 * A very simple test class
 *
 * @author giraldomauricio
 */
class VerySimpleTests {

    var $errorCounter = 0;
    var $messages = array();
    var $testCount = 0;
    var $lineCount = 0;
    var $ignoredTests = 0;
    var $ignore = false;
    var $version = "1.8.2";
    var $xml = "";
    var $tests = array();
    var $test;
    var $trail = 90;
    var $_temp_folder = "";

    public function __construct() {
        print "Very Simple PHP Test v " . $this->version . "</br>";
        $_SESSION['start_time'] = microtime(true);
    }

    public function Aware($class) {
        $class->customFunction['__call'] = function ($method, $arguments) {
            print "Call";
        };
    }

    public function IgnoreNextTest() {
        $this->ignore = true;
        $this->testCount++;
    }

    public function Coverage($class) {
        $object = new ReflectionObject($class);
        print $object->getFileName();
        //$method = $object->getMethod('__initComponents');
        //print $method."<br/>";
        //$declaringClass = $method->getDeclaringClass();
        //print $declaringClass."<br/>";
        //$filename = $declaringClass->getFilename();
        //print $filename."<br/>";
    }

    public function GroupTests($name) {
        $this->lineCount++;
        $this->messages[$this->lineCount] = str_pad("=========Starting suite: " . $name, $this->trail, "=", STR_PAD_RIGHT);
    }

    public function AssertEqual($actual, $expected, $name = "Test") {
        $this->lineCount++;
        $this->test = new test();
        $this->test->name = $name;
        $this->test->type = "AssertEqual";
        if ($this->ignore) {
            $this->ignoredTests++;
            $this->ignore = false;
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": ignored.";
            array_push($this->tests, $this->test);
            return true;
        }
        $this->testCount++;
        if ($actual != $expected) {
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": failed. Expected [" . $expected . "], actual: [" . $actual . "]";
            $this->errorCounter++;
            array_push($this->tests, $this->test);
            $this->test->success = false;
            $this->test->message = "Expected [" . $expected . "], actual: [" . $actual . "]";
            return false;
        } else {
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": passed.";
            array_push($this->tests, $this->test);
            return true;
        }
    }

    public function AssertContains($haystack, $needle, $name = "Test") {
        $this->lineCount++;
        $this->test = new test();
        $this->test->name = $name;
        $this->test->type = "AssertContains";
        if ($this->ignore) {
            $this->ignoredTests++;
            $this->ignore = false;
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": ignored.";
            array_push($this->tests, $this->test);
            return true;
        }
        $this->testCount++;
        if (strpos($haystack, $needle) === false) {
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": failed. Can't find [" . $needle . "], into: [" . $haystack . "]";
            $this->errorCounter++;
            array_push($this->tests, $this->test);
            $this->test->success = false;
            $this->test->message = "Can't find [" . $needle . "], into: [" . $haystack . "]";
            return false;
        } else {
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": passed.";
            array_push($this->tests, $this->test);
            return true;
        }
    }

    public function Assert($assertion, $name = "Test") {
        $this->lineCount++;
        $this->test = new test();
        $this->test->name = $name;
        $this->test->type = "Assert";
        if ($this->ignore) {
            $this->ignoredTests++;
            $this->ignore = false;
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": ignored.";
            array_push($this->tests, $this->test);
            return true;
        }
        $this->testCount++;
        if (!assert($assertion)) {
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": failed.";
            $this->errorCounter++;
            array_push($this->tests, $this->test);
            $this->test->success = false;
            array_push($this->tests, $this->test);
            $this->test->message = "Failed";
            return false;
        } else {
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": passed.";
            $this->test->name = $name;
            array_push($this->tests, $this->test);
            return true;
        }
    }

    public function AssertTrue($assertion, $name = "Test") {
        $this->Assert($assertion, $name);
    }

    public function AssertNotNull($actual, $name = "Test") {
        $this->lineCount++;
        $this->test = new test();
        $this->test->name = $name;
        $this->test->type = "AssertNotNull";
        if ($this->ignore) {
            $this->ignoredTests++;
            $this->ignore = false;
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": ignored.";
            array_push($this->tests, $this->test);
            return true;
        }
        $this->testCount++;

        if (strlen($actual) <= 0) {
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": failed. The value is empty";
            $this->errorCounter++;
            $this->test->name = $name;
            array_push($this->tests, $this->test);
            $this->test->success = false;
            array_push($this->tests, $this->test);
            $this->test->message = "Value is empty";
            return false;
        } else {
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": passed.";
            $this->test->name = $name;
            array_push($this->tests, $this->test);
            return true;
        }
    }

    public function NotEqual($actual, $expected, $name = "Test") {
        $this->lineCount++;
        $this->test = new test();
        $this->test->name = $name;
        $this->test->type = "NotEqual";
        if ($this->ignore) {
            $this->ignoredTests++;
            $this->ignore = false;
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": ignored.";
            array_push($this->tests, $this->test);
            return true;
        }
        $this->testCount++;
        if ($actual == $expected) {
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": failed.";
            $this->errorCounter++;
            $this->test->name = $name;
            array_push($this->tests, $this->test);
            $this->test->success = false;
            array_push($this->tests, $this->test);
            $this->test->message = "Failed";
            return false;
        } else {
            $this->messages[$this->lineCount] = str_pad($name, $this->trail, ".", STR_PAD_RIGHT) . ": passed.";
            array_push($this->tests, $this->test);
            return true;
        }
    }

    public function Results() {
        print "<div style=\"font-family:Courier; font-size:12px;\">\n";
        print "-----------------------------------------</br>\n";
        print "Results</br>";
        print "-----------------------------------------</br>\n";
        foreach ($this->messages as $key => $value) {
            if (strpos($value, ": failed."))
                print "<span style=\"color:red\"><strong>&raquo; " . $value . "</strong></span><br/>\n";
            else if (strpos($value, ": ignored."))
                print "<span style=\"color:blue\"><strong>&raquo; " . $value . "</strong></span><br/>\n";
            else
                print "&raquo; " . $value . "<br/>\n";
        }
        print "-----------------------------------------</br>";
        print "Passed:" . ($this->testCount - $this->errorCounter - $this->ignoredTests) . "<br/>\n";
        print "Failed:" . $this->errorCounter . "<br/>\n";
        print "Ignored:" . $this->ignoredTests . "<br/>\n";
        $end_time = time();
        print "Time taken:" . (round(microtime(true) - $_SESSION['start_time'], 6)) . "<br/>\n";
        print "</div>\n";
        //print "Writing results.<br/>";
        $this->output_junit("results/results.xml");
    }

    public function output_junit($path) {
        $end_time = microtime(true);
        $this->xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        // TODO: Set timestamp as 2013-09-06T12:11:40-04:00
        $this->xml .= "\t<testsuites errors=\"" . $this->errorCounter . "\" failures=\"" . $this->errorCounter . "\" skipped=\"" . $this->ignoredTests . "\" tests=\"" . $this->testCount . "\" time=\"" . round($end_time - $_SESSION['start_time'], 6) . "\" timestamp=\"" . date("Y-m-d T h:i:s") . "\">\n";
        $this->xml .= "\t\t<testsuite name=\"Very Simple Test Suite\" tests=\"" . $this->testCount . "\" errors=\"0\" failures=\"0\" skipped=\"0\">\n";
        $this->xml .= "\t\t<properties/>\n";
        foreach ($this->tests as $value) {
            // TODO: calculate individual test elapsed time
            $this->xml .= "\t\t\t<testcase name=\"" . $value->name . "\" time=\"0.00\">\n";
            if (!$value->success)
                $this->xml .= "\t\t\t<failure type=\"" . $value->type . "\">" . $value->message . "</failure>\n";
            $this->xml .= "\t\t</testcase>\n";
        }
        $this->xml .= "\t</testsuite>\n";
        $this->xml .= "</testsuites>\n";
        file_put_contents($path, $this->xml);
    }
    
    public function CreateTempFolder()
    {
      $this->_temp_folder = ROOT."temp_test_".date("ymdhis")."/";
      mkdir($this->_temp_folder, 0777);
      return $this->_temp_folder;
    }
    
    public function DeleteTempFolder()
    {
      Utils::DeleteDirectory($this->_temp_folder);
    }
    

}

class test {

    var $name = "";
    var $result = "";
    var $detail = "";
    var $success = true;
    var $message = "";
    var $type = "";
    var $group = "Main";

}

?>