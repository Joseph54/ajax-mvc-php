<?php
/**
 * Created by PhpStorm.
 * User: egimple
 * Date: 2019-04-03
 * Time: 10:18 PM
 */

class AccountModel
{
    private $db = null;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function checkUserExists($username)
    {
        $query = "select count(*) from users where username=:username";
        $this->db->query($query);
        $this->db->bind(":username", $username, PDO::PARAM_STR);
        $this->db->execute();
        $rowCount = $this->db->fetchColumn();

        if ($rowCount == 0) {
            //"invalid login"
            return false;
        } else {
            return true;
        }
    }

    // Regsiter user
    public function register($data){
        $this->db->query('INSERT INTO users (name, email, password, age) VALUES(:name, :email, :password, :age)');
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':age', $data['age']);


        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function createAccount($username, $password)
    {
        $alreadyExists = $this->checkUserExists($username);
        if ($alreadyExists) {
            return false;
        }
        $query = "insert into users(username, password) values(:username,:password)";
        $this->db->query($query);
        $this->db->bind(":username", $username);
        $this->db->bind(":password", $password);

        $success = $this->db->execute();
        return $success;
    }

}