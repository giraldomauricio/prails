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
  var $version = "1.4.0";

  public function __construct() {
    print "Very Simple PHP Test v " . $this->version . "</br>";
  }

  public function AssertEqual($actual, $expected, $name = "Test") {
    if ($this->ignore) {
      $this->ignoredTests++;
      $this->ignore = false;
      $this->messages[$this->testCount] = str_pad($name, 60, ".", STR_PAD_RIGHT) . ": ignored.";
      return true;
    }
    $this->testCount++;
    if ($actual != $expected) {
      $this->messages[$this->testCount] = str_pad($name, 60, ".", STR_PAD_RIGHT) . ": failed. Expected " . $expected . ", actual: " . $actual;
      $this->errorCounter++;
      return false;
    } else {
      $this->messages[$this->testCount] = str_pad($name, 60, ".", STR_PAD_RIGHT) . ": passed.";
      return true;
    }
  }

  public function Assert($assertion, $name = "Test") {
    if ($this->ignore) {
      $this->ignoredTests++;
      $this->ignore = false;
      $this->messages[$this->testCount] = str_pad($name, 60, ".", STR_PAD_RIGHT) . ": ignored.";
      return true;
    }
    $this->testCount++;
    if (!assert($assertion)) {
      $this->messages[$this->testCount] = str_pad($name, 60, ".", STR_PAD_RIGHT) . ": failed.";
      $this->errorCounter++;
      return false;
    } else {
      $this->messages[$this->testCount] = str_pad($name, 60, ".", STR_PAD_RIGHT) . ": passed.";
      return true;
    }
  }

  public function AssertNotNull($actual, $name = "Test") {
    if ($this->ignore) {
      $this->ignoredTests++;
      $this->ignore = false;
      $this->messages[$this->testCount] = str_pad($name, 60, ".", STR_PAD_RIGHT) . ": ignored.";
      return true;
    }
    $this->testCount++;

    if (strlen($actual) <= 0) {
      $this->messages[$this->testCount] = str_pad($name, 60, ".", STR_PAD_RIGHT) . ": failed. The value is empty";
      $this->errorCounter++;
      return false;
    } else {
      $this->messages[$this->testCount] = str_pad($name, 60, ".", STR_PAD_RIGHT) . ": passed.";
      return true;
    }
  }

  public function NotEqual($actual, $expected, $name = "Test") {
    if ($this->ignore) {
      $this->ignoredTests++;
      $this->ignore = false;
      $this->messages[$this->testCount] = str_pad($name, 60, ".", STR_PAD_RIGHT) . ": ignored.";
      return true;
    }
    $this->testCount++;
    if ($actual == $expected) {
      $this->messages[$this->testCount] = str_pad($name, 60, ".", STR_PAD_RIGHT) . ": failed.";
      $this->errorCounter++;
      return false;
    } else {
      $this->messages[$this->testCount] = str_pad($name, 60, ".", STR_PAD_RIGHT) . ": passed.";
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
  }

}

?>