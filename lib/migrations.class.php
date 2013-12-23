<?php

/**
 * Migrations class handles the database migrations
 *
 * @author murdock
 */
class db_migrations extends prails {
  
  var $migration_files = array();
  var $missing_files = array();
  
  public function create_db_migration_table()
  {
    // Drop the table
    $this->sql = "DROP TABLE IF EXISTS _prails_database_migrations";
    $this->ExecuteQuery($this->sql);
    // Create the table
    $this->sql   = "CREATE TABLE _prails_database_migrations (\n";
    $this->sql  .= "migration_id VARCHAR(100) NOT NULL,\n";
    $this->sql  .= "migration_file VARCHAR(100) NULL,\n";
    $this->sql  .= "migration_executed DATETIME NULL,\n";
    $this->sql  .= "migration_status INT(1) NULL,\n";
    $this->sql  .= "PRIMARY KEY (migration_id),\n";
    $this->sql  .= "UNIQUE INDEX migration_id_UNIQUE (migration_id ASC),\n";
    $this->sql  .= "UNIQUE INDEX migration_file_UNIQUE (migration_file ASC),\n";
    $this->sql  .= "UNIQUE INDEX migration_executed_UNIQUE (migration_executed ASC));";
    $this->ExecuteQuery($this->sql);
  }
  
  private function load_db_migrations()
  {
    // Check current migrations
    $this->sql = "SELECT * FROM _prails_database_migrations ORDER BY migration_executed";
    $this->Query();
    while ($this->Load())
    {
      array_push($this->migration_files, $this->migration_file);
    }
    logFactory::log("db_migrations", "Found ".count($this->migration_files)." already executed.");
  }

  private function check_db_migrations()
  {
    // Comprare database migrations to the existing ones
    $migrations = realpath(dirname(__FILE__))."/db/migrations/";
    $mydir = dir($migrations);
    while (($migration = $mydir->read())) {
      if(!in_array($migration, $this->migration_files))
      {
        array_push($this->missing_files, $migration);
      }
    }
    logFactory::log("db_migrations", "Found ".count($this->missing_files)." migratons not run yet.");
  }
  
  private function run_missing_migrations()
  {
    // Run each new migration
    foreach ($this->missing_files as $new_migration) {
      logFactory::log("db_migrations", "Running ".$new_migration." file.");
      $query = file_get_contents($migrations.$new_migration);
      $this->Query($query);
      $this->sql = "INSERT INTO _prails_database_migrations VALUES ('".  str_replace(".sql", "", $new_migration)."','".$new_migration."',now(),1)";
      $this->Query();
    }
  }
  
  public function run_migrations()
  {
    $this->load_db_migrations();
    $this->check_db_migrations();
    $this->run_missing_migrations();
  }
  
}

?>
