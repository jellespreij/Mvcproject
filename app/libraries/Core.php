<?php
  class Core {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){
      $url = $this->getUrl();
      //Look in controllers for controller
      if(file_exists('../app/controllers/'.ucwords($url[0]).'.php')){
        //set as controller
        $this->currentController = ucwords($url[0]);
        //Unset 0 index
        unset($url[0]);
      }

      //Require current controller
      require_once('../app/controllers/' . $this->currentController . '.php');

      $this->currentController = new $this->currentController;

      //Check if second part of url is set
      if(isset($url[1])){
        //Check if method/function exists in current controller
        if(method_exists($this->currentController, $url[1])){
          //Set current method
          $this->currentMethod = $url[1];
          //Unset 1 index
          unset($url[1]);
        }
      }

      $this->params = $url ? array_values($url) : [];

      // Call a callback with an array of parameters
      call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    // Construct URL From $_GET['url']
    public function getUrl(){
        if(isset($_GET['url'])){
          $url = rtrim($_GET['url'], '/');
          $url = filter_var($url, FILTER_SANITIZE_URL);
          $url = explode('/', $url);
          return $url;
        }
    }
  }
