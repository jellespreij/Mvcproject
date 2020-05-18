<?php
  class Users extends Controller{
    public function __construct(){
      $this->userModel = $this->model('User');
    }

    public function index(){
      redirect('welcome');
    }

    public function login(){

      // Init data
      $data = [
        'email' => '',
        'password' => '',
        'email_error' => '',
        'password_error' => ''
      ];

      if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password'])
        ];

        //Check for email
        if(empty($data['email'])){
          $data['email_error'] = 'Please enter email.';
        }

        //Check for name
        if(empty($data['name'])){
          $data['name_error'] = 'Please enter name.';
        }

        //Check if user exists
        if($this->userModel->findUserByEmail($data['email'])){
        } else {
          $data['email_error'] = 'This email is not registered.';
        }

        //Check if errors are empty
        if(empty($data['email_error']) && empty($data['password_error'])){

          //Set logged in user
          $loggedInUser = $this->userModel->login($data['email'], $data['password']);

          if($loggedInUser){
            //Create user session
            $this->createUserSession($loggedInUser);

          } else {
            $data['password_error'] = 'Password incorrect.';
            //Load view
            $this->view('users/login', $data);
          }

        } else {
          //Load view
          $this->view('users/login', $data);
        }

      } else {
        //Load view
        $this->view('users/login', $data);
      }
    }

    public function register(){

      //Init data
      $data = [
        'name' => '',
        'email' => '',
        'password' => '',
        'confirm_password' => '',
        'name_error' => '',
        'email_error' => '',
        'password_error' => '',
        'confirm_password_error' => ''
      ];

      if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $passwordValidation = "/^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/";

        $data = [
          'name' => trim($_POST['name']),
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password'])
        ];

        //Check if name is empty
        if(empty($data['name'])){
              $data['name_error'] = 'Please enter a name';
        }

        //Check if email is empty
        if(empty($data['email'])){
            $data['email_error'] = 'Please enter an email';
          //check if email is correct format
        } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $data['email_error'] = 'Email is not correct.';
          //check if email is already taken
        } elseif ($this->userModel->findUserByEmail($data['email'])){
            $data['email_error'] = 'Email is already taken.';
        }

        //Check password
        if(empty($data['password'])){
          $password_error = 'Please enter a password.';
        } elseif(strlen($data['password']) < 5){
          $data['password_error'] = 'Password must be atleast 5 characters long.';
        } elseif(!preg_match($passwordValidation, $data['password'])){
          $data['password_error'] = 'Password must have at least one numeric value, one uppercase character and one lowercase character';
        }

        //Check confirm password
        if(empty($data['confirm_password'])){
            $data['confirm_password_error'] = 'Please re-type your password.';
        } elseif($data['password'] != $data['confirm_password']){
            $data['confirm_password_error'] = 'Password does not match.';
        }

        //Check if errors are empty
        if(empty($data['name_error']) && empty($data['email_error']) && empty($data['password_error']) && empty($data['confirm_password_error'])){

          //Hash Password
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
          $this->userModel->register($data)
            redirect('users/login');
        } else {
          //Load view
          $this->view('users/register', $data);
        }
      } else {
        //Load view
        $this->view('users/register', $data);
      }
    }

    //Create session with user info
    public function createUserSession($user){
      $_SESSION['userId'] = $user->id;
      $_SESSION['userEmail'] = $user->email;
      $_SESSION['userName'] = $user->name;
      redirect('posts');
    }

    //Destroy Session
    public function logout(){
      unset($_SESSION['userId']);
      unset($_SESSION['userEmail']);
      unset($_SESSION['userName']);
      session_destroy();
      redirect('users/login');
    }

  }
