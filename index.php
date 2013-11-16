<?php
// Requires
require_once 'libs/UserAuth.php';

// Check if the person is logged in.
$auth = new UserAuth();

if ($auth->isLoggedIn($_SESSION['loggedIn'])) {
    header('Location: home.php');
} else {
    header('Location: login.php');
}
?>
