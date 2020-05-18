<?php
  class Pages extends Controller{
    public function __construct(){

    }

    public function index(){
      //Check voor session
      if(isset($_SESSION['userId'])){
        redirect('posts');
      }

      //Set Data
      $data = [
        'title' => 'Mvcproject',
        'description' => 'Site voor php'
      ];

      // Load view
      $this->view('pages/index', $data);
    }
  }
