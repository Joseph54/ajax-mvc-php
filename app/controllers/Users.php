<?php

/**
 * Created by PhpStorm.
 * User: egimple
 * Date: 2019-04-03
 * Time: 9:43 PM
 */
class Users extends Controller
{
    public function __construct(){
        $this->userModel = $this->model('User');
    }


    public function createAccountAjax(){
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'){
        echo "in ajax call";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "post request ajax";
            if (isset($_POST["submit"])) {
                //check they are set later
                $username = $_POST["username"];
                $password = $_POST["password"];
                $age = $_POST["age"];



                //how do we reference the model
                $model=$this->model("AccountModel");
                $success=$model->createAccount($username,$password, $age);
                if($success){
                    echo "successfully inserted";
                }
                else{
                    echo "insert failed";
                }
            }
        } else {
            return $this->view("Users/createAccountAjax");
        }
    }


    public function index()
    {

    }

    public function register(){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process form

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data =[
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'age' => trim($_POST['age'])
            ];

            // Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            } else {
                // Check email
                if($this->userModel->findUserByEmail($data['email'])){
                    $data['email_err'] = 'Email is already taken';
                }
            }

            // Validate Name
            if(empty($data['name'])){
                $data['name_err'] = 'Pleae enter name';
            }

            // Validate Password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            } elseif(strlen($data['password']) < 8){
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate age
            if(empty($data['age'])){
                $data['age_err'] = 'Please enter age';
            } elseif($data['age'] < 0){
                $data['age_err'] = 'age must be 1 & older';
            }


            // Make sure errors are empty
            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err'])){
                // Validated

                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register User
                if($this->userModel->register($data)){
                    flash('register_success', 'You are registered and can log in');
                    redirect('users/login');
                } else {
                    die('Something went wrong');
                }

            } else {
                // Load view with errors
                $this->view('users/register', $data);
            }

        } else {
            // Init data
            $data =[
                'name' => '',
                'email' => '',
                'password' => '',
               'age' => ''
            ];

            // Load view
            $this->view('users/register', $data);
        }
    }

    public function login(){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data =[
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
            ];

            // Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Pleae enter email';
            }

            // Validate Password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/email
            if($this->userModel->findUserByEmail($data['email'])){
                // User found
            } else {
                // User not found
                $data['email_err'] = 'No user found';
            }

            // Make sure errors are empty
            if(empty($data['email_err']) && empty($data['password_err'])){
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if($loggedInUser){
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';

                    $this->view('users/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/login', $data);
            }


        } else {
            // Init data
            $data =[
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];

            // Load view
            $this->view('users/login', $data);
        }
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        redirect('pages/index');
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/login');
    }

    public function isLoggedIn(){
        if(isset($_SESSION['user_id'])){
            return true;
        } else {
            return false;
        }
    }
}