<?php
// Requires
require_once 'admin/Config.php';
$config = new Config();
$libPath = $config->getPathToLibDir();
require_once($libPath . "Auth/UserAuth.php");


// Check if the person is logged in.
$auth = new UserAuth();
if ($auth->isLoggedIn($_SESSION['loggedIn'])) {
    header('Location: http://frame.codingallnight.com:8080/photorings/home.php');
} else {
    header('Location: http://frame.codingallnight.com:8080/photorings/login.php');
}
?>
