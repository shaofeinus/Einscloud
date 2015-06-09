<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 8/6/2015
 * Time: 5:16 PM
 */

class Input
{
    public $firstName;
    public $lastName;
    public $nric;
    public $phoneNo;
    public $email;
    public $username;
    public $password;
    public $confirmPassword;


    public function getInput()
    {
        $this->firstName = $_POST['firstName'];
        $this->lastName = $_POST['lastName'];
        $this->email = $_POST['email'];
        $this->nric = $_POST['nric'];
        $this->phoneNo = $_POST['phoneNo'];
        $this->username = $_POST['username'];
        $this->password = md5($_POST['password']);
    }
}

process_post();

function isFormValid() {
    if (isset($_POST['registerUser'])) {
        return true;
    } else {
        return false;
    }
}

function process_post() {
    if(isFormValid() == true) {
        $input = new Input();
        $input->getInput();
        echo "processing";
        make_sql_query($input);
    }
}

function make_sql_query($input) {
    require_once 'DB_connect/db_connect.php';
    $connector = new DB_CONNECT();
    $connector->connect();

    $query1 = "INSERT INTO User(firstname, lastname, nric, phone_no, username, password)
	VALUES('$input->firstName', '$input->lastName', '$input->nric', '$input->phoneNo', '$input->username', '$input->password')";

    $query2 = "INSERT INTO User(firstname, lastname, nric, phone_no, username, password, email)
	VALUES('$input->firstName', '$input->lastName', '$input->nric', '$input->phoneNo', '$input->username', '$input->password', '$input->email')";

    if(empty($input->email)) {
        echo $query1 . "<br>";
        if(mysqli_query($connector->conn, $query1)) {
            $connector->close();
            echo "success";
        } else {
            $connector->close();
            echo "error";
        }
    } else {
        echo $query2 . "<br>";
        if(mysqli_query($connector->conn, $query2)) {
            $connector->close();
            echo "success";
        } else {
            $connector->close();
            echo "error";
        }
    }
}
?>