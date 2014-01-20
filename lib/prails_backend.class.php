<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of prails_backend
 *
 * @author murdock
 */
class prails_backend extends prails {

  public function CreateBackendTableIfDoesntExist() {
    $this->Connect();
    if (!$this->CheckIfTableExists("prails_backend")) {
      $migration = new db_migrations();
      $migration->create_db_migration_table();
      $code_first = new CodeFirst();
      $code_first->Run("backend_model");
    }
  }

}

?>
