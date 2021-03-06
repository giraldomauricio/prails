<?php

// ######################################################################
// class for sendmail
// Class builder is Open Source, but for copyright issues, please keep
// this copy on any class that uses it.
// (R) 2005-2010
// ######################################################################
class SendMail {

  var $to;
  var $from;
  var $subject;
  var $body;

  function set_to($data) {
    return $this->to = $data;
  }

  function set_from($data) {
    return $this->from = $data;
  }

  function set_subject($data) {
    return $this->subject = $data;
  }

  function set_body($data) {
    return $this->body = $data;
  }

  function set_template($template = "") {
    if ($template != "" && file_exists(TEMPLATES . $template)) {
      $this->body = file_get_contents(TEMPLATES . $template);
    }
    else
      $this->body = "<table width='100%'  border='0' cellspacing='3' cellpadding='3'><tr><td><font face='Verdana, Arial, Helvetica, sans-serif' size='+1'><i>##TEXT##</i></font></td></tr></table>";
  }

  function get_to() {
    return $this->to;
  }

  function get_from() {
    return $this->from;
  }

  function get_subject() {
    return $this->subject;
  }

  function get_body() {
    return $this->body;
  }

  function sendMail() {
    $this->to = trim($this->to);
    $this->from = FROMEMAIL;
    logFactory::log("SendMail", "recipient = \"" . $this->to . "\" subject= \"" . $this->subject . "\" body = \"" . $this->body . "\" script = \"" . $_SERVER['SCRIPT_FILENAME'] . "\" user_id = \"" . $_SESSION["user_id"] . "\"");
    if ($this->subject && $this->from) {
      mail($this->to, $this->subject, $this->body, "From:" . $this->from . "\r\nReply-to: " . $this->from . "\r\nContent-type: text/html; charset=us-ascii");
      return true;
    }
    else
      return false;
  }

  function message($to, $subject, $body, $template = "") {
    $this->to = $to;
    $this->subject = $subject;
    $this->set_template($template);
    $this->body = str_replace("##TEXT##", $body, $this->body);
    $this->sendMail();
  }

}

?>