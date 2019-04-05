<?php

/**
 * Created by PhpStorm.
 * User: egimple
 * Date: 2019-04-03
 * Time: 9:43 PM
 */
class Users extends Controller
{
    public function createAccount()
    {
        //check if it is a post request

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'){

        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo "post request non ajax";
            if (isset($_POST["submit"])) {
                //check they are set later
                $username = $_POST["username"];
                $password = $_POST["password"];

                //how do we reference the model
                $model=$this->model("AccountModel");
                $success=$model->createAccount($username,$password);
                if($success){
                    echo "successfully inserted";
                }
                else{
                    echo "insert failed";
                }
            }
        } else {
            return $this->view("Users/createAccount");
        }
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

                //how do we reference the model
                $model=$this->model("AccountModel");
                $success=$model->createAccount($username,$password);
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
}