<?php
// This code is an adaptation of the code here:
// http://www.emirplicanic.com/php/simple-phpmysql-authentication-class

// For security reasons, don't display any errors or warnings. Comment out in DEV.
//error_reporting(0);

// start session
session_start();

class UserAuth {
    // database setup
    var $hostname_auth = 'localhost';           // Database HOST
    var $database_auth = 'photo_rings';         // Database NAME
    var $username_auth = 'pr';                  // Database USERNAME
    var $password_auth = '5RTQrctz7feTCEcz';    // Database PASSWORD

    // table fields
    var $user_table = 'users';                  // Users table
    var $user_column = 'email';                 // User login name column
    var $pass_column = 'password';              // User password column
    var $user_level = 'privilege';              // User privileges column

    // password encryption
    var $encrypt = true;    // set to true to use SHA-256 encryption for the password
    var $hashSalt = 'z+9Ee>;nST0YtP^)I0%6<EeZ!tQ|/*eaB7!?Q%HwwCNSgUL;DRHb]9|MkM{c+N@8';

    // Connect to the database
    function dbConnect() {
        $connection = mysqli_connect($this->hostname_auth, $this->username_auth, $this->password_auth) or die('Unable to connect to the database');
        mysqli_select_db($connection, $this->database_auth) or die('Unable to select database');
        return $connection;
    }

    // Login function
    function login($username, $password) {
        // connect to DB
        $this->dbConnect();

        // Convert the username to lowercase
        $username = strtolower($username);

        // check if encryption is used
        if ($this->encrypt == true) {
            $password = $this->hashPassword($password, $username);
        }

//        echo $password;
//        echo "<br><br>";

        // execute login via qry function that prevents MySQL injections
        $result = $this->qry("SELECT * FROM " . $this->user_table . " WHERE " . $this->user_column . "= '?' AND " . $this->pass_column . " = '?';", $username, $password);
//        print_r($result);
//        echo "<br><br>";
        $row = mysqli_fetch_assoc($result);
        if ($row != "Error") {
            if ($row[$this->user_column] != "" && $row[$this->pass_column] != "") {
//                echo 'Made It';
                // register sessions
                // you can add additional sessions here if needed
                $_SESSION['loggedIn'] = $row[$this->pass_column];
                // privileges session is optional. Use it if you have different user privilege levels
                $_SESSION['privilege'] = $row[$this->user_level];
                return true;
            } else {
                session_destroy();
                return false;
            }
        } else {
            return false;
        }
    }

    // Logout function
    function logout() {
        session_destroy();
        return;
    }

    // check if logged in
    // $loginCode = $_SESSION['loggedIn']
    function isLoggedIn($loginCode) {
        // connect to DB
//        $this->dbConnect();

        // execute query
        $result = $this->qry("SELECT * FROM " . $this->user_table . " WHERE " . $this->pass_column . " = '?';", $loginCode);
//        echo '<br><br>';
//        print_r($result);
        $rownum = mysqli_num_rows($result);
//        echo '<br><br>';
//        print_r($rownum);
        if ($rownum != "Error" && $rownum > 0) {
//            echo 'User is valid';
            return true;
        } else {
//            echo 'User is NOT valid';
            return false;
        }
    }

    // reset password
    function passwordReset($username) {
        // connect to DB
        $db = $this->dbConnect();

        // Convert the username to lowercase
        $username = strtolower($username);

        // generate new password
        $newPass = $this->createPassword();

        // check if encryption is used
        if ($this->encrypt == true) {
            $newPassDB = $this->hashPassword($newPass, $username);
        } else {
            $newPassDB = $newPass;
        }

        // update database with new password
        $query = "UPDATE " . $this->user_table . " SET " . $this->pass_column . "='" . $newPassDB . "' WHERE " . $this->user_column . "='" . stripslashes($username) . "'";
        $result = mysqli_query($db, $query) or die(mysqli_error($db));


        // Send a notification email
        $to = stripslashes($username);
        // some injection protection
        $illegals = array("%0A","%0D","%0a","%0d","Content-Type","BCC:","Bcc:","bcc:","CC:","Cc:","cc:","TO:","To:","to:");
        $to = str_replace($illegals, "", $to);
        $getEmail = explode("@", $to);

        // send only if there is one email
        if (sizeof($getEmail) > 2) {
            return false;
        } else {
            // send email
            $from = "photorings@codingallnight.com";
            $subject = "Password Reset";
            $msg = "Your new password is: " . $newPass . " ";

            // set mail headers
            $headers = "MIME-Version: 1.0 \r\n";
            $headers .= "Content-Type: text/html; \r\n";
            $headers .= "From: $from \r\n";

            // now to send the email
            $sent = mail($to, $subject, $msg, $headers);
            if ($sent) {
                return true;
            } else {
                return false;
            }
        }
    }

    // create a random password with 10 alphanumeric characters
    function createPassword() {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";  // no 1 or L
        srand((double)microtime() * 1000000);
        $pass = "";
        for ($i = 0; $i < 10; $i++) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass .= $tmp;
        }

        return $pass;
    }

    // Encryption function
    function hashPassword($password, $username) {
        $password = str_split($password, (strlen($password)/2)+1);
        $hash = hash('sha256', $username . $password[0] . $this->hashSalt . $password[1]);
        return $hash;
    }

    // Create User function
    function registerUser($fname, $lname, $username, $password, $birthdate) {
        $this->dbConnect();

        // Convert the username to lowercase
        $username = strtolower($username);

        // Convert the fname and lname to title case
        $fname = ucwords($fname);
        $lname = ucwords($lname);

        // check if encryption is used
        if ($this->encrypt == true) {
            $password = $this->hashPassword($password, $username);
        }

        // execute the insert
        $result = $this->qry("INSERT INTO " . $this->user_table . "(email,password,fname,lname,birthdate) VALUES ('?','?','?','?','?');",
                $username, $password, $fname, $lname, $birthdate);

        //TODO: Error checking on $result

        return ($result != "Error");
    }

    // prevent SQL injection
    // TODO: fix this method so that it uses prepared statements
    function qry($query) {
        $db = $this->dbConnect();
        $args = func_get_args();
//        print_r($args);
//        echo "<br><br>1";
        $query = array_shift($args);
//        print_r($query);
//        echo "<br><br>2";
//        print_r($args);
//        echo "<br><br>3";
        $query = str_replace("?", "%s", $query);
//        print_r($query);
//        echo "<br><br>4";
        //$args = array_map('mysqli_real_escape_string', $args);
//        print_r($args);
//        echo "<br><br>5";
        array_unshift($args, $query);
//        print_r($args);
//        echo "<br><br>6";
        $query = call_user_func_array('sprintf', $args);
//        print_r($query);
//        echo "<br><br>7";
        $result = mysqli_query($db, $query) or die(mysqli_error($db));

        if ($result) {
            return $result;
        } else {
            $result = "Error";
            return $result;
        }
    }
}
?>
