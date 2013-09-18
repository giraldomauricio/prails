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
  var $ignoredTests = 0;
  var $ignore = false;
  var $version = "1.5.0";
  var $xml = "";
  var $tests = array();
  var $test;

  public function __construct() {
    print "Very Simple PHP Test v " . $this->version . "</br>";
    $_SESSION['start_time'] = time();
  }

  public function AssertEqual($actual, $expected, $name = "Test") {
    $this->test = new test();
    $this->test->name = $name;
    $this->test->type = "AssertEqual";
    if ($this->ignore) {
      $this->ignoredTests++;
      $this->ignore = false;
      $this->messages[$this->testCount] = str_pad($name, 100, ".", STR_PAD_RIGHT) . ": ignored.";
      array_push($this->tests, $this->test);
      return true;
    }
    $this->testCount++;
    if ($actual != $expected) {
      if(is_object($actual)) $actual = get_object_vars($actual);
      $this->messages[$this->testCount] = str_pad($name, 100, ".", STR_PAD_RIGHT) . ": failed. Expected [" . $expected . "], actual: [" . $actual."]";
      $this->errorCounter++;
      array_push($this->tests, $this->test);
      $this->test->success = false;
      $this->test->message = "Expected [" . $expected . "], actual: [" . $actual."]";
      return false;
    } else {
      $this->messages[$this->testCount] = str_pad($name, 100, ".", STR_PAD_RIGHT) . ": passed.";
      array_push($this->tests, $this->test);
      return true;
    }
  }

  public function Assert($assertion, $name = "Test") {
    $this->test = new test();
    $this->test->name = $name;
    $this->test->type = "Assert";
    if ($this->ignore) {
      $this->ignoredTests++;
      $this->ignore = false;
      $this->messages[$this->testCount] = str_pad($name, 100, ".", STR_PAD_RIGHT) . ": ignored.";
      array_push($this->tests, $this->test);
      return true;
    }
    $this->testCount++;
    if (!assert($assertion)) {
      $this->messages[$this->testCount] = str_pad($name, 100, ".", STR_PAD_RIGHT) . ": failed.";
      $this->errorCounter++;
      array_push($this->tests, $this->test);
      $this->test->success = false;
      array_push($this->tests, $this->test);
      $this->test->message = "Failed";
      return false;
    } else {
      $this->messages[$this->testCount] = str_pad($name, 100, ".", STR_PAD_RIGHT) . ": passed.";
      $this->test->name = $name;
      array_push($this->tests, $this->test);
      return true;
    }
  }

  public function AssertNotNull($actual, $name = "Test") {
    $this->test = new test();
    $this->test->name = $name;
    $this->test->type = "AssertNotNull";
    if ($this->ignore) {
      $this->ignoredTests++;
      $this->ignore = false;
      $this->messages[$this->testCount] = str_pad($name, 100, ".", STR_PAD_RIGHT) . ": ignored.";
      array_push($this->tests, $this->test);
      return true;
    }
    $this->testCount++;

    if (strlen($actual) <= 0) {
      $this->messages[$this->testCount] = str_pad($name, 100, ".", STR_PAD_RIGHT) . ": failed. The value is empty";
      $this->errorCounter++;
      $this->test->name = $name;
      array_push($this->tests, $this->test);
      $this->test->success = false;
      array_push($this->tests, $this->test);
      $this->test->message = "Value is empty";
      return false;
    } else {
      $this->messages[$this->testCount] = str_pad($name, 100, ".", STR_PAD_RIGHT) . ": passed.";
      $this->test->name = $name;
      array_push($this->tests, $this->test);
      return true;
    }
  }

  public function NotEqual($actual, $expected, $name = "Test") {
    $this->test = new test();
    $this->test->name = $name;
    $this->test->type = "NotEqual";
    if ($this->ignore) {
      $this->ignoredTests++;
      $this->ignore = false;
      $this->messages[$this->testCount] = str_pad($name, 100, ".", STR_PAD_RIGHT) . ": ignored.";
      array_push($this->tests, $this->test);
      return true;
    }
    $this->testCount++;
    if ($actual == $expected) {
      $this->messages[$this->testCount] = str_pad($name, 100, ".", STR_PAD_RIGHT) . ": failed.";
      $this->errorCounter++;
      $this->test->name = $name;
      array_push($this->tests, $this->test);
      $this->test->success = false;
      array_push($this->tests, $this->test);
      $this->test->message = "Failed";
      return false;
    } else {
      $this->messages[$this->testCount] = str_pad($name, 100, ".", STR_PAD_RIGHT) . ": passed.";
      array_push($this->tests, $this->test);
      return true;
    }
  }

  public function Results() {
    
    print "-----------------------------------------</br>";
    print "Results</br>";
    print "-----------------------------------------</br>";
    foreach ($this->messages as $key => $value) {
      print $key . ") " . $value . "<br/>";
    }
    print "-----------------------------------------</br>";
    print "Passed:" . ($this->testCount - $this->errorCounter - $this->ignoredTests) . "<br/>";
    print "Failed:" . $this->errorCounter . "<br/>";
    print "Ignored:" . $this->ignoredTests . "<br/>";
    print "Writing results.<br/>";
    $this->output_junit("results/results.xml");
  }
  
  public function output_junit($path)
  {
    $end_time = time();
    $this->xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    // TODO: Set timestamp as 2013-09-06T12:11:40-04:00
    $this->xml .= "\t<testsuites errors=\"".$this->errorCounter."\" failures=\"".$this->errorCounter."\" skipped=\"".$this->ignoredTests."\" tests=\"".$this->testCount."\" time=\"".($end_time - $_SESSION['start_time'])."\" timestamp=\"".date("Y-m-d T h:i:s")."\">\n";
    $this->xml .= "\t\t<testsuite name=\"Very Simple Test Suite\" tests=\"".$this->testCount."\" errors=\"0\" failures=\"0\" skipped=\"0\">\n";
    $this->xml .= "\t\t<properties/>\n";
    foreach ($this->tests as $value) {
      // TODO: calculate individual test elapsed time
      $this->xml .= "\t\t\t<testcase name=\"".$value->name."\" time=\"0.00\">\n";
      if(!$value->success) $this->xml .= "\t\t\t<failure type=\"".$value->type."\">".$value->message."</failure>\n";
      $this->xml .= "\t\t</testcase>\n"; 
    }
    $this->xml .= "\t</testsuite>\n";
    $this->xml .= "</testsuites>\n";
    file_put_contents($path, $this->xml);
  }
}


class test
{
  var $name   = "";
  var $result = "";
  var $detail = "";
  var $success = true;
  var $message = "";
  var $type = "";
}
?>