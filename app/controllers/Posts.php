<?php
  class Posts extends Controller{

    public function __construct(){

      if(!isset($_SESSION['userId'])){
        redirect('users/login');
      }

      $this->postModel = $this->model('Post');
      $this->userModel = $this->model('User');
    }

    public function index(){
      $posts = $this->postModel->getPosts();

      $data = [
        'posts' => $posts
      ];

      $this->view('posts/index', $data);
    }

    //Show single post
    public function show($id){
      $post = $this->postModel->getPostById($id);
      $user = $this->userModel->getUserById($post->user_id);

      $data = [
        'post' => $post,
        'user' => $user
      ];

      $this->view('posts/show', $data);
    }

    public function add(){

      $data = [
        'title' => ''),
        'body' => '')
      ];

      if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
          'title' => trim($_POST['title']),
          'body' => trim($_POST['body']),
          'userId' => $_SESSION['userId']
        ];

         if(empty($data['title'])){
          $data['title_error'] = 'Please enter title';
        } else {
          $data['title_error'] = '';
        }

        if(empty($data['body'])){
          $data['body_error'] = 'Please enter body';
        } else {
          $data['title_error'] = '';
        }

        //Check if errors are empty
        if(empty($data['title_error']) && empty($data['body_error'])){
          $this->postModel->addPost($data);
          redirect('posts');
        } else {
          //Load view with errors
          $this->view('posts/add', $data);
        }
      } else {
        $this->view('posts/add', $data);
      }
    }

    public function delete($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $this->postModel->deletePost($id);
        redirect('posts');
      } else {
        redirect('posts');
      }
    }
  }
