<?php

/**
 * Migrations class handles the database migrations
 *
 * @author murdock
 */
class db_migrations extends prails {

  var $migration_files = array();
  var $missing_files = array();
  var $_migrations_folder = "";
  var $_migration_file = "";

  public function __construct() {
    $this->_migrations_folder = ROOT . "/db/migrations/";
    $this->Connect();
  }

  public function create_db_migration_table() {
    // Create the table
    $this->sql = "CREATE TABLE IF NOT EXISTS _prails_database_migrations (\n";
    $this->sql .= "migration_id VARCHAR(100) NOT NULL,\n";
    $this->sql .= "migration_file VARCHAR(100) NULL,\n";
    $this->sql .= "migration_executed DATETIME NULL,\n";
    $this->sql .= "migration_status INT(1) NULL,\n";
    $this->sql .= "PRIMARY KEY (migration_id),\n";
    $this->sql .= "UNIQUE INDEX migration_id_UNIQUE (migration_id ASC),\n";
    $this->sql .= "UNIQUE INDEX migration_file_UNIQUE (migration_file ASC),\n";
    $this->sql .= "UNIQUE INDEX migration_executed_UNIQUE (migration_executed ASC));";
    $this->ExecuteQuery($this->sql);
  }

  public function load_db_migrations() {
    // Check current migrations
    $this->sql = "SELECT * FROM " . DBNAME . "._prails_database_migrations ORDER BY migration_executed";
    $this->Query();
    while ($this->Load()) {
      array_push($this->migration_files, $this->migration_file);
    }
    logFactory::log("db_migrations", "Found " . count($this->migration_files) . " already executed.");
  }

  public function check_db_migrations() {
    // Compare database migrations to the existing ones
    $mydir = dir($this->_migrations_folder);
    while (($migration = $mydir->read())) {
      if (!in_array($migration, $this->migration_files) && $migration != "." && $migration != ".." && $migration != ".DS_Store") {
        array_push($this->missing_files, $migration);
      }
    }
    logFactory::log("db_migrations", "Found " . count($this->missing_files) . " migratons not run yet.");
  }

  public function run_missing_migrations() {
    // Run each new migration
    $this->Connect();
    foreach ($this->missing_files as $new_migration) {
      logFactory::log("db_migrations", "Running " . $new_migration . " file.");
      $query = file_get_contents($this->_migrations_folder . $new_migration);
      $this->Query($query);
      $this->sql = "INSERT INTO _prails_database_migrations VALUES ('" . str_replace(".sql", "", $new_migration) . "','" . $new_migration . "',now(),1)";
      $this->Query($this->sql);
    }
  }

  public function add_migration_file($sql) {
    $this->_migration_file = "migration_" . date("Y-m-d-h-i-s") . ".sql";
    $fp = fopen($this->_migrations_folder . $this->_migration_file, "x");
    fwrite($fp, $sql);
    fclose($fp);
  }

  public function run_migrations() {

    $this->Connect();
    $this->load_db_migrations();
    $this->check_db_migrations();
    $this->run_missing_migrations();
  }

}

?>
