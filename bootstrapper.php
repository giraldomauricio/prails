<?

// ###################################################################
// Prails
// (R) 2013
// ###################################################################
// Start application configuration

error_reporting(E_ERROR);

ini_set('max_execution_time', '1800');
ini_set('max_input_time', '1800');
ini_set('memory_limit', '256M');
ini_set('post_max_size', '256M');
ini_set('upload_max_filesize', '256M');

session_start();

date_default_timezone_set('America/New_York');

require_once realpath(dirname(__FILE__))."/hosts.php";
// Load configuration file
require_once $hosts[$_SERVER["HTTP_HOST"]];

define("DBTYPE", $db_type);
define("DBSERVER", $db_server);
define("SECURE", $secure);
define("DBNAME", $db_name);
define("DBUSER", $db_user);
define("DBPASSWORD", $db_password);
define("LIBRARY", $app_root);
// TODO: Refactor following constant
//define("LIBRARY", $app_root."lib");
define("LOGTYPE", $log_type);
define("LOGFOLDER", $log_folder);
define("DEBUG", $debug);
define("VERBOSE", $verbose);
define("VISUALERRORS", $visual_errors);
define("ROOT", $app_root);
define("FROMEMAIL", $from_email);
define("TEMPLATES", $templates);
define("URI", $uri);
define("ADMINEMAIL", $adminEmail);

if ($_SERVER['HTTPS'] != "on" && SECURE) {
  $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  header("Location: $redirect");
}

// ###################################################################
// ################# FRAMEWORK #######################################
// ###################################################################
// Load all classes

// Load base classes
require_once LIBRARY . "lib/Prails_iDB.interface.php";
require_once LIBRARY . "lib/context.class.php";
require_once LIBRARY . "lib/prails.class.php";
require_once LIBRARY . "lib/log.php";
require_once LIBRARY . "lib/utils.class.php";

$architecture = array("lib", "app/models", "app/controllers");

foreach ($architecture as $loader) {

  $mydir = dir(LIBRARY . $loader . "/");
  while (($file = $mydir->read())) {
    if (substr($file, 0, 1) != "." && substr($file, 0, 1) != "_") {
      if (DEBUG)
        logFactory::log($this, "Framework", " Loading [" . $file . "]..");
      require_once LIBRARY . $loader . "/" . $file;
      if (DEBUG)
        logFactory::log($this, "Framework", "[" . $file . "] loaded successfully.");
    }
  }
}
?>