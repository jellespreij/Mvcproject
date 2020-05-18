<?php
  class Welcome extends Controller{
    public function __construct(){
      if(isset($_SESSION['userId'])){
        redirect('posts');
      }
    }

    public function index(){
      $this->view('welcome', ['title' => 'Welcome']);
    }
  }
