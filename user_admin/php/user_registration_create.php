<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 8/6/2015
 * Time: 5:16 PM
 */

class Input
{
    public $fullName;
    public $nric;
    public $phoneNo;
    public $email;
    public $username;
    public $password;
    public $confirmPassword;
    public $birthday;
    public $gender;
    public $address;
    public $race;

    public function getInput()
    {
        require_once 'clean_up_input.php';

        $this->fullName = cleanUpInput($_POST['fullName']);
        $this->email = cleanUpInput($_POST['email']);
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        if(strlen(trim($this->email)) == 0){
            $this->email = NULL;
        }
        $this->nric = cleanUpInput($_POST['nric']);
        $this->phoneNo = cleanUpInput($_POST['phoneNo']);
        $this->username = cleanUpInput($_POST['username']);
        $this->password = md5($_POST['password']);
        $this->birthday = $_POST['birthday'];
        $this->gender = $_POST['gender'];
        $this->address = cleanUpInput($_POST['address']);
        $this->race = cleanUpInput($_POST['select_race'] === 'otherRaces' ? $_POST['otherRace'] : $_POST['select_race']);
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
        //echo "processing";
        make_sql_query($input);
    }
}

function make_sql_query($input) {
    require_once 'DB_connect/db_connect.php';
    $connector = new DB_CONNECT();
    $connector->connect();

    $query1 = "INSERT INTO User(fullname, nric, phone_no, username, password, birthday, gender, address, race)
	VALUES('$input->fullName', '$input->nric', '$input->phoneNo', '$input->username', '$input->password', '$input->birthday', '$input->gender', '$input->address', '$input->race')";

    $query2 = "INSERT INTO User(fullname, nric, phone_no, username, password, email, birthday, gender, address, race)
	VALUES('$input->fullName', '$input->nric', '$input->phoneNo', '$input->username', '$input->password', '$input->email', '$input->birthday', '$input->gender', '$input->address', '$input->race')";

    if(empty($input->email)) {
        //echo $query1 . "<br>";
        if(mysqli_query($connector->conn, $query1)) {
            $connector->close();
            //echo "success";

            echo "<script> alert('Your registration as a user is successful!'); window.location.assign('../index.php');</script>";
        } else {
            $connector->close();
            echo "<script> alert('Your registration as a user failed! Please try again'); window.location.assign('../user_registration.html');</script>";
            //echo "error";
        }
    } else {
        //echo $query2 . "<br>";
        if(mysqli_query($connector->conn, $query2)) {
            $connector->close();
            echo "<script> alert('Your registration as a user is successful!'); window.location.assign('../index.php')</script>";
            //echo "success";
        } else {
            $connector->close();
            echo "<script> alert('Your registration as a user failed! Please try again'); window.location.assign('../user_registration.html')</script>";
           // echo "error";
        }
    }
}
?>

