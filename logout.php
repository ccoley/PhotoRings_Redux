<?php
require_once 'libs/UserAuth.php';
$auth = new UserAuth();
$auth->logout();
header('Location: index.php');
?>
