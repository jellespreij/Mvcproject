<?php
  class Controller {

    public function model($model){
      // Require model file
      require_once '../app/models/' . $model . '.php';
      // Instantiate model
      return new $model();
    }

    public function view($url, $data = []){
      // Check for view file
      if(file_exists('../app/views/'.$url.'.php')){

        require_once '../app/views/'.$url.'.php';
      } else {
        die('View does not exist');
      }
    }
  }
