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
    public $useDefault;

    public function getInput()
    {
        require_once 'clean_up_input.php';
        $this->fullName = cleanUpInput($_POST['fullName']);
        $this->email = cleanUpInput($_POST['email']);
        $this->email = cleanUpInput(filter_var($this->email, FILTER_SANITIZE_EMAIL));
        if(strlen(trim($this->email)) == 0){
            $this->email = NULL;
        }

        $this->phoneNo = cleanUpInput($_POST['phoneNo']);
        $this->username = cleanUpInput($_POST['username']);
        $this->password = md5($_POST['password']);

        $this->useDefault = $_POST['default_username'];

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
    require_once 'DB_connect/db_utility.php';

    if(empty($input->email)) {
        //echo $query1 . "<br>";
        $link = get_conn();
        $registerNoEmailStmt = mysqli_prepare($link, "insert into RegisteredViewer(fullname, phone_no, username, password, rvtype) values (?,?,?,?,?)");
        $registerNoEmailStmt->bind_param("sssss", $input->fullName, $input->phoneNo, $input->username, $input->password, $input->rvtype);
        $registerResult = $registerNoEmailStmt->execute();
        if($registerResult == true) {
            if($input->useDefault == 'yes') {
                ?>
                <script> alert("Your registration as a caregiver is successful! Your username is '<?php echo $input->username ?>' and your password is your phone number.");
                window.location.assign('../index.php')</script>
                <?php
            }
            else{
                ?>
                <script> alert("Your registration as a caregiver is successful!");
                window.location.assign('../index.php')</script>
                <?php
            }


        } else {
            echo "<script> alert('Your registration as a caregiver failed! Please try again'); window.location.assign('../caregiver_registration.html')</script>";
                //header("Location: ../caregiver_registration.html");
        }
            $link->close();
        } else {
            //echo $query2 . "<br>";
            $link = get_conn();
            $registerWEmailStmt = mysqli_prepare($link, "insert into RegisteredViewer(fullname, phone_no, username, password, rvtype, email) values (?,?,?,?,?,?)");
            $registerWEmailStmt->bind_param("ssssss", $input->fullName, $input->phoneNo, $input->username, $input->password, $input->rvtype, $input->email);
            $registerResult = $registerWEmailStmt->execute();
            if($registerResult == true) {
                if($input->useDefault == 'yes') {
                    ?>
                    <script> alert("Your registration as a caregiver is successful! Your username is '<?php echo $input->username ?>' and your password is your phone number.");
                        window.location.assign('../index.php')</script>
                <?php
                }
                else{
                    ?>
                    <script> alert("Your registration as a caregiver is successful!");
                        window.location.assign('../index.php')</script>
                <?php
                }
        } else {
            echo "<script> alert('Your registration as a caregiver failed! Please try again'); window.location.assign('../caregiver_registration.html')</script>";
            //header("Location: ../caregiver_registration.html");
        }
        $link->close();
    }
}
?>