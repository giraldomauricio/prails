<?

// ###################################################################
// Prails
// (R) 2013-2014
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
require_once realpath(dirname(__FILE__))."/lib/rescue.class.php";
// Load configuration file based on the environment
if(!isset($PRAILS_ENV))
{
  $PRAILS_ENV = $_SERVER["HTTP_HOST"];
}
try
{
  require_once $hosts[$PRAILS_ENV];
}  catch (Exception $e)
{
  rescue::NoConfigurationAvailable();
}
// Define where Prails is running. Can be overrided by the configuration variable if desired.
define("ROOT",__DIR__."/");
define("MIGRATIONS_FOLDER",__DIR__."/db/migrations/");
define("LOGFOLDER",__DIR__."/logs/");
define("TESTS_FOLDER",__DIR__."/tests/");
define("LIBRARY",__DIR__."/");
define("DEFAULT_CONTROLLER","prails");
define("DEFAULT_ACTION","index");
define("AUTHENTICATION_VARIABLE","logged");
define("LASTQUERY","");


foreach ($_config_vars as $_config_var => $_config_val) {
  define($_config_var, $_config_val);
}

if(isset($_SERVER['HTTPS']))
{
  if ($_SERVER['HTTPS'] != "on" && SECURE) {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect");
  }
}

// ###################################################################
// ################# FRAMEWORK #######################################
// ###################################################################
// Load all classes

// Load database driver
require_once LIBRARY . "lib/Prails_iDB.interface.php";
$db_driver_location = LIBRARY."db/drivers/prails_".DBTYPE.".class.php";
if(file_exists($db_driver_location))
{
  require_once $db_driver_location;
}
else{
  rescue::NoDefaultDatabaseDriver();
  exit;
}

// Load base classes

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