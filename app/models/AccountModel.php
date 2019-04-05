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