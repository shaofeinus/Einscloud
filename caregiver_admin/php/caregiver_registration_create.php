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
    public $phoneNo;
    public $email;
    public $username;
    public $password;
    public $confirmPassword;

    public $rvtype;
    public $nric;
    public $gender;

    public function getInput()
    {
        $this->fullName = $_POST['fullName'];
        $this->email = $_POST['email'];
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        if(strlen(trim($this->email)) == 0){
            $this->email = NULL;
        }

        $this->phoneNo = $_POST['phoneNo'];
        $this->username = $_POST['username'];
        $this->password = md5($_POST['password']);

        $this->rvtype = 'Family'; // default value now as rvtype is deemed redundant
        //$this->nric = 'S0000000A'; //dummy value
        //$this->gender = 'X'; //dummy value
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
    require_once 'DB_connect/db_connect.php';
    $connector = new DB_CONNECT();
    $connector->connect();

    $query1 = "INSERT INTO RegisteredViewer(fullname, phone_no, username, password, rvtype)
	VALUES('$input->fullName', '$input->phoneNo', '$input->username', '$input->password', '$input->rvtype')";

    $query2 = "INSERT INTO RegisteredViewer(fullname, phone_no, username, password, email, rvtype)
	VALUES('$input->fullName', '$input->phoneNo', '$input->username', '$input->password', '$input->email', '$input->rvtype')";

    $responseString = "Your registration as a caregiver is successful! Your username is '" . $input->username . "' and your password is '" . $input->phoneNo . "'.'";
    if(empty($input->email)) {
        //echo $query1 . "<br>";
        if(mysqli_query($connector->conn, $query1)) {
            $connector->close();
            //echo "success";
            //header("Location: ../index.php");
            ?>
                    <script> alert("Your registration as a caregiver is successful! Your username is '<?php echo $input->username ?>' and your password is your phone number.");
                window.location.assign('../index.php')</script>
            <?php

        } else {
            $connector->close();
            echo "<script> alert('Your registration as a caregiver failed! Please try again'); window.location.assign('../caregiver_registration.html')</script>";
            //header("Location: ../caregiver_registration.html");
        }
    } else {
        //echo $query2 . "<br>";
        if(mysqli_query($connector->conn, $query2)) {
            $connector->close();
            //echo "success";
            //header("Location: ../index.php");
            ?>
            <script> alert("Your registration as a caregiver is successful! Your username is '<?php echo $input->username ?>' and your password is your phone number.");
                window.location.assign('../index.php')</script>
        <?php
        } else {
            $connector->close();
            echo "<script> alert('Your registration as a caregiver failed! Please try again'); window.location.assign('../caregiver_registration.html')</script>";
            //header("Location: ../caregiver_registration.html");
        }
    }
}
?>