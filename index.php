<?php
// Requires
require_once 'libs/Auth/UserAuth.php';

// Check if the person is logged in.
$auth = new UserAuth();

//echo '<br><br>';
//print_r($_SESSION);

if ($auth->isLoggedIn($_SESSION['loggedIn'])) {
    header('Location: home.php');
} else {
    header('Location: login.php');
}
?>
