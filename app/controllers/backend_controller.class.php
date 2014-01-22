<?php

class backend extends backend_model{
  
  public function index()
  {
    $this->_backend = true;
    return $this->RenderView("index");
  }
  
  public function index_post()
  {
    $this->_backend = true;
    $be = new prails_backend();
    $be->CreateAllModels();
    //$be->CreateBackendTableIfDoesntExist();
    //$df = new DatabaseFirst();
    //$df->_tables[0] = "test_table";
    //$df->CreateModel();
    return $this->RenderHtml("Table created and Model<br/>");
  }
  
}
?>
