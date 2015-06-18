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
    public $rvtype;


    public function getInput()
    {
        $this->firstName = $_POST['firstName'];
        $this->lastName = $_POST['lastName'];
        $this->email = $_POST['email'];
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        if(strlen(trim($this->email)) == 0){
            $this->email = NULL;
        }
        $this->nric = $_POST['nric'];
        $this->phoneNo = $_POST['phoneNo'];
        $this->username = $_POST['username'];
        $this->password = md5($_POST['password']);
        $this->rvtype = $_POST['rvtype'];
    }
}

process_post();

function isFormValid() {
    if (isset($_POST['registerViewer'])) {
        return true;
    } else {
        return false;
    }
}

function process_post() {
    if(isFormValid() == true) {
        $input = new Input();
        $input->getInput();
        //echo "processing";
        make_sql_query($input);
    }
}

function make_sql_query($input) {
    require_once __DIR__.'/DB_connect/db_connect.php';
    $connector = new DB_CONNECT();
    $connector->connect();

    $query1 = "INSERT INTO RegisteredViewer(firstname, lastname, nric, phone_no, username, password, rvtype)
	VALUES('$input->firstName', '$input->lastName', '$input->nric', '$input->phoneNo', '$input->username', '$input->password', '$input->rvtype')";

    $query2 = "INSERT INTO RegisteredViewer(firstname, lastname, nric, phone_no, username, password, email, rvtype)
	VALUES('$input->firstName', '$input->lastName', '$input->nric', '$input->phoneNo', '$input->username', '$input->password', '$input->email', '$input->rvtype')";

    if(empty($input->email)) {
        //echo $query1 . "<br>";
        if(mysqli_query($connector->conn, $query1)) {
            $connector->close();
            //echo "success";
            //header("Location: ../index.php");
            echo "<script> alert('Your registration as a viewer is successful!'); window.location.assign('../index.php')</script>";
        } else {
            $connector->close();
            echo "<script> alert('Your registration as a viewer failed! Please try again'); window.location.assign('../viewer_registration.html')</script>";
            //header("Location: ../viewer_registration.html");
        }
    } else {
        //echo $query2 . "<br>";
        if(mysqli_query($connector->conn, $query2)) {
            $connector->close();
            //echo "success";
            //header("Location: ../index.php");
            echo "<script> alert('Your registration as a viewer is successful!'); window.location.assign('../index.php')</script>";
        } else {
            $connector->close();
            echo "<script> alert('Your registration as a viewer failed! Please try again'); window.location.assign('../viewer_registration.html')</script>";
            //header("Location: ../viewer_registration.html");
        }
    }
}
?>