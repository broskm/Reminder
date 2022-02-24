<?php
class Users extends Controller {
    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function index(){
        header('location: ' . URLROOT . '/Users/login');
    }


    public function login() {
        $data = [
            'title' => 'Login page',
            'username' => '',
            'password' => '',
            'Errors' => $errors=[],
            'successful'=>false
        ];

        //Check for post
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Sanitize post data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'username' => strtolower(trim($_POST['username'])),
                'password' => trim($_POST['password']),
                'Errors' => $errors=[],
                'successful'=>false
            ];
            //Validate username
            if (empty($data['username'])) {
                $data['Error'][] = 'Please enter a username.';
            }
            //Validate password
            if (empty($data['password'])) {
                $data['Error'][] = 'Please enter a password.';
            }

            echo($data['password']);
            //Check if all errors are empty
            if (empty($data['Error'])) {
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);
                
                if ($loggedInUser) {
                    $this->createUserSession($loggedInUser);
                    
                } else {
                    $data['Error'][] = 'Password or username is incorrect. Please try again.';

                    $this->view('Users/login', $data);
                }
            }

        } else {
            $data = [
                'username' => '',
                'password' => '',
                'Errors' => $errors=[],
                'successful'=>false
            ];
        }
        
        $this->view('Users/login', $data);
    }


    public function register() {
        $data = [
            'username' => '',
            'email' => '',
            'password' => '',
            'confirmPassword' => '',
            'Errors' => $errors=[],
            'successful'=>false
        ];

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

              $data = [
                'username' => strtolower(trim($_POST['username'])),
                'email' => strtolower(trim($_POST['email'])),
                'password' => trim($_POST['password']),
                'confirmPassword' => trim($_POST['confirmPassword']),
                'Errors' => $errors=[],
                'successful'=>false
            ];

            $nameValidation = "/^[a-zA-Z0-9]*$/";
            $passwordValidation = "/^(.{0,7}|[^a-z]*|[^\d]*)$/i";

            //Validate username on letters/numbers
            if (empty($data['username'])) {
                $data['Error'][] = 'Please enter username.';
            }else{
                if ($this->userModel->findUserByUsername($data['username'])) {
                    $data['Error'][] = 'Username is already taken.';
                } elseif (!preg_match($nameValidation, $data['username'])) {
                    $data['Error'][] = 'Name can only contain letters and numbers.';
                }  
            }
            //Validate email
            if (empty($data['email'])) {
                $data['Error'][] = 'Please enter email address.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['Error'][] = 'Invalid Email.';
            }
            //Check if email exists.
            if ($this->userModel->findUserByEmail($data['email'])) {
                $data['Error'][] = 'Email is already taken.';
            }
        

           // Validate password on length, numeric values,
            if(empty($data['password'])){
              $data['Error'][] = 'Please enter password.';/* 
            } elseif(strlen($data['password']) < 8){
              $data['Error'][] = 'Password must be at least 8 characters'; */
            } elseif (preg_match($passwordValidation, $data['password'])) {
              $data['Error'][] = 'Password must be at least 8 characters including a number and a lowercase letter.';
            }

            //Validate confirm password
             if (empty($data['confirmPassword'])) {
                $data['Error'][] = 'Please repeat password.';
            } else {
                if ($data['password'] != $data['confirmPassword']) {
                $data['Error'][] = 'Passwords do not match, please try again.';
                }
            }

            // Make sure that errors are empty
            if (empty($data['Error'])) {

                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                //Register user from model function
                if ($this->userModel->register($data)) {
                    //Redirect to the login page
                    $data['successful'] = true; 
                    $this->view('Users/login', $data);
                    /*header('location: ' . URLROOT . '/users/login'); */
                } else {
                    die('Something went wrong.');
                }
            }else{
                $this->view('Users/register', $data);
            }
        }else{
            $this->view('Users/register', $data);
        }
    }

    



    public function createUserSession($user) {
        
        $_SESSION['user_id'] = $user["user_id"];
        $_SESSION['username'] = $user["username"];
        $_SESSION['email'] = $user["email"];
        header('location:' . URLROOT . '/Reminders/index');
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        header('location:' . URLROOT . '/Users/login');
    }
}