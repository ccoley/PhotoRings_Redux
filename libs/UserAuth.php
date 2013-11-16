<?php
/**
 * Class UserAuth handles all aspects of user authentication for PhotoRings
 *
 * @author Chris Coley <chris at codingallnight dot com>
 */

require_once 'libs/PhotoRings_DB.php';
require_once 'libs/Config.php';
include_once 'Mail.php';    // This is PEAR::Mail


// For security reasons, don't display any errors or warnings. Comment out in DEV.
//error_reporting(0);

// start session
session_start();

//TODO: Figure out how to use secure sessions

class UserAuth {
    // Database table fields
    var $user_table = 'users';                  // Users table
    var $user_column = 'email';                 // User login name column
    var $pass_column = 'password';              // User password column
    var $user_level = 'privilege';              // User privileges column

    // Password encryption
    var $encrypt = true;    // set to true to use SHA-256 encryption for the password
    var $hashSalt = 'z+9Ee>;nST0YtP^)I0%6<EeZ!tQ|/*eaB7!?Q%HwwCNSgUL;DRHb]9|MkM{c+N@8';

    /**
     * Attempt to log in the user with the provided username and password.
     *
     * @param string $username The username of the user who is logging in.
     * @param string $password The password of the user who is logging in.
     * @return bool TRUE if login is successful, FALSE otherwise.
     */
    function login($username, $password) {
        // Connect to the DB
        try {
            $db = new PhotoRings_DB();
        } catch (PDOException $e) {
            return false;
        }

        // Convert the username to lowercase
        $username = strtolower($username);

        // Check if encryption is used
        if ($this->encrypt == true) {
            $password = $this->hashPassword($password, $username);
        }

        // Execute the login
        $query = $db->prepare("SELECT ".$this->user_column.", ".$this->pass_column.", ".$this->user_level." FROM ".$this->user_table." WHERE ".$this->user_column."=? AND ".$this->pass_column."=?");
        if ($query != false) {
            if ($query->execute(array($username, $password))) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                if ($result != false && count($result) == 1) {
                    // Only this session variable is required
                    $_SESSION['loggedIn'] = $result[0][$this->pass_column];

                    // These session variables are only needed for additional functionality
                    $_SESSION['username'] = $result[0][$this->user_column];
                    $_SESSION['privilege'] = $result[0][$this->user_level];
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Log the user out.
     */
    function logout() {
        session_destroy();
        return;
    }

    /**
     * Check if a user is logged in.
     *
     * @param string $loginCode The hashed password of the user, probably obtained from $_SESSION['loggedIn'].
     * @return bool TRUE if the user is logged in, FALSE otherwise.
     */
    function isLoggedIn($loginCode) {
        // Connect to the DB
        try {
            $db = new PhotoRings_DB();
        } catch (PDOException $e) {
            return false;
        }

        // Execute the query
        $query = $db->prepare("SELECT * FROM ".$this->user_table." WHERE ".$this->pass_column."=?");
        if ($query != false) {
            if ($query->execute(array($loginCode))) {
                $result = $query->fetchAll(PDO::FETCH_NUM);
                if ($result != false && count($result) == 1) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Change the password for the account with the specified username.
     *
     * @param string $username The username we are changing the password for.
     * @param string $oldPassword The old password.
     * @param string $newPassword The new password.
     * @return bool TRUE if password change was successful, FALSE otherwise.
     */
    function changePassword($username, $oldPassword, $newPassword) {
        $changed = false;

        // Connect to the DB
        try {
            $db = new PhotoRings_DB();
        } catch (PDOException $e) {
            return false;
        }

        // Convert the username to lowercase
        $username = strtolower($username);

        // Check if encryption is used
        if ($this->encrypt == true) {
            $oldPassword = $this->hashPassword($oldPassword, $username);
            $newPassword = $this->hashPassword($newPassword, $username);
        }

        // Change the password
        $db->beginTransaction();
        $query = $db->prepare("UPDATE ".$this->user_table." SET ".$this->pass_column."=? WHERE ".$this->user_column."=? AND ".$this->pass_column."=?");
        if ($query != false) {
            if ($query->execute(array($newPassword, $username, $oldPassword))) {
                if ($query->rowCount() == 1) {
                    $changed = $db->commit();   // TRUE if the password change was saved, FALSE otherwise.
                }
            }
        }

        // If the password was not changed rollback the transaction
        if ($changed == false) {
            $db->rollBack();
        } else {
            $config = new Config();

            $from = "PhotoRings <photorings@codingallnight.com>";
            $to = $username;    // This will only work if the username is a valid email address.
            $subject = "Password Changed";
            $body = "Your password was changed. If you did this, you should ignore this email. If you did not do this, be scared";
            $headers = array("From"=>$from, "To"=>$to, "Subject"=>$subject);

            $smtp = Mail::factory('smtp', $config->getPEARMailSMTPParams());
            $mail = $smtp->send($to, $headers, $body);

//            if (PEAR::isError($mail)) {
//                //TODO Email wasn't sent, do something
//            } else {
//                //TODO Email was successfully sent, do something
//            }
        }

        return $changed;
    }

    /**
     * Resets a user's password to a randomly generated one and attempts to
     * send an email informing the user of their new password.
     *
     * @param string $username The username of the user whose password is being reset.
     * @return bool TRUE if the password was successfully reset, FALSE otherwise.
     */
    function passwordReset($username) {
        $reset = false;

        // Connect to the DB
        try {
            $db = new PhotoRings_DB();
        } catch (PDOException $e) {
            return false;
        }

        // Convert the username to lowercase
        $username = strtolower($username);

        // Generate a new password
        $newPass = $this->createPassword();

        // Check if encryption is used
        if ($this->encrypt == true) {
            $newPassDB = $this->hashPassword($newPass, $username);
        } else {
            $newPassDB = $newPass;
        }

        // Update the DB with the new password
        $db->beginTransaction();
        $query = $db->prepare("UPDATE ".$this->user_table." SET ".$this->pass_column."=? WHERE ".$this->user_column."=?");
        if ($query != false) {
            if ($query->execute(array($newPassDB, $username))) {
                if ($query->rowCount() == 1) {
                    $reset = $db->commit(); // TRUE if commit was successful, FALSE otherwise
                }
            }
        }

        // If password was reset send a notification email, Else rollback the transaction
        if ($reset) {
            $to = stripslashes($username);
            // some injection protection
            $illegals = array("%0A", "%0D", "%0a", "%0d", "Content-Type", "BCC:", "Bcc:", "bcc:", "CC:", "Cc:", "cc:", "TO:", "To:", "to:");
            $to = str_replace($illegals, "", $to);
            $getEmail = explode("@", $to);

            // Only send if there is only one email address
            if (sizeof($getEmail) > 2) {
                return false;
            } else {
                $config = new Config();

                $from = "PhotoRings <photorings@codingallnight.com>";
                $subject = "Password Reset";
                $body = "Your new password is: ".$newPass." ";
                $headers = array("From"=>$from, "To"=>$to, "Subject"=>$subject);

                $smtp = Mail::factory('smtp', $config->getPEARMailSMTPParams());
                $mail = $smtp->send($to, $headers, $body);

                if (PEAR::isError($mail)) {
                    //TODO Email wasn't sent, do something
                } else {
                    //TODO Email was successfully sent, do something
                }
            }
        } else {
            $db->rollBack();
        }
        return $reset;
    }

    /**
     * Creates a pseudo-random 10 character lowercase alphanumeric password.
     * For the sake of clarity, the password will never contain a 1 (number 1)
     * or an l (letter L).
     *
     * @return string The new password.
     */
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

    /**
     * Creates a SHA-256 hash of a double salted password. One salt is the
     * username for uniqueness, and the other salt is a constant random string.
     *
     * @param string $password The password to be hashed.
     * @param string $username The username of the account that the password belongs to.
     * @return string The 64 character hash of the password.
     */
    function hashPassword($password, $username) {
        $password = str_split($password, (strlen($password)/2)+1);
        $hash = hash('sha256', $username . $password[0] . $this->hashSalt . $password[1]);
        return $hash;
    }

    /**
     * Creates a new user in the database.
     *
     * @param string $fname The user's first name.
     * @param string $lname The user's last name.
     * @param string $username The user's username.
     * @param string $password The user's password.
     * @param string $birthdate The user's birthdate in 'YYYY-MM-DD' format.
     * @return bool TRUE if new user registration was successful, FALSE otherwise.
     */
    function registerUser($fname, $lname, $username, $password, $birthdate) {
        $registered = false;

        // Connect to the DB
        try {
            $db = new PhotoRings_DB();
        } catch (PDOException $e) {
            return false;
        }

        // Convert the username to lowercase
        $username = strtolower($username);

        // Convert the fname and lname to title case
        $fname = ucwords($fname);
        $lname = ucwords($lname);

        // Check if encryption is used
        if ($this->encrypt == true) {
            $password = $this->hashPassword($password, $username);
        }

        // Execute the insert
        $newUserId = null;
        $db->beginTransaction();
        $query = $db->prepare("INSERT INTO ".$this->user_table." (email,password,fname,lname,birthdate) VALUES (?,?,?,?,?)");
        if ($query != false) {
            if ($query->execute(array($username, $password, $fname, $lname, $birthdate))) {
                if ($query->rowCount() == 1) {
                    $newUserId = $db->lastInsertId();
                    $registered = $db->commit();    // TRUE if new user was saved, FALSE otherwise.
                }
            }
        }

        // If the insert was successful, create the user's image directories
        if ($registered && !is_null($newUserId)) {
            $config = new Config();
            $registered = $config->createUserDirectories($newUserId);
        }

        // If anything failed along the way, rollback the DB transaction
        if (!$registered) {
            $db->rollBack();
        }

        return $registered;
    }
}
?>
