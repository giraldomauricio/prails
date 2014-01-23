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
  
  public function CreateAllModels()
  {
    print "<strong>&raquo;Pulling Database information.</strong><br/>";
    $code_first = new CodeFirst();
    $code_first->GetDatabaseTables();
    $df = new DatabaseFirst();
    while($row = $code_first->GetRecordObject())
    {
      array_push($df->_tables, $row->TABLE_NAME);
      print "<i>&raquo;Found table ".$row->TABLE_NAME.".</i><br/>";
    }
    $df->CreateModels();
  }

}

?>
