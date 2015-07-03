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
        $this->fullName = $_POST['fullName'];
        $this->email = $_POST['email'];
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        if(strlen(trim($this->email)) == 0){
            $this->email = NULL;
        }
        $this->nric = $_POST['nric'];
        $this->phoneNo = $_POST['phoneNo'];
        $this->username = $_POST['username'];
        $this->password = md5($_POST['password']);
        $this->birthday = $_POST['birthday'];
        $this->gender = $_POST['gender'];
        $this->address = $_POST['address'];
        $this->race = $_POST['select_race'] === 'otherRaces' ? $_POST['otherRace'] : $_POST['select_race'];
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

    if(empty($input->email)) {

        require_once 'DB_connect/db_utility.php';
        $link = get_conn();
        $query1 = mysqli_prepare($link,
            "INSERT INTO User(fullname, nric, phone_no, username, password, birthday, gender, address, race) " .
            "VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $query1->bind_param("sssssssss",
            $input->fullName,
            $input->nric,
            $input->phoneNo,
            $input->username,
            $input->password,
            $input->birthday,
            $input->gender,
            $input->address,
            $input->race);
        $result = $query1->execute();
        $link->close();

        if($result) {
            echo "<script> alert('Your registration as a user is successful!'); " .
                "window.location.assign('../index.php');</script>";
        } else {
            echo "<script> alert('Your registration as a user failed! Please try again'); " .
                "window.location.assign('../user_registration.html');</script>";
        }
    } else {

        require_once 'DB_connect/db_utility.php';
        $link = get_conn();
        $query1 = mysqli_prepare($link,
            "INSERT INTO User(fullname, nric, phone_no, username, password, email, birthday, gender, address, race) " .
            "VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $query1->bind_param("ssssssssss",
            $input->fullName,
            $input->nric,
            $input->phoneNo,
            $input->username,
            $input->password,
            $input->email,
            $input->birthday,
            $input->gender,
            $input->address,
            $input->race);
        $result = $query1->execute();
        $link->close();

        if($result) {
            echo "<script> alert('Your registration as a user is successful!'); " .
                "window.location.assign('../index.php')</script>";
        } else {
            echo "<script> alert('Your registration as a user failed! Please try again'); " .
                "window.location.assign('../user_registration.html')</script>";
        }
    }
}
?>

