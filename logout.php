<?php
require_once 'libs/Auth/UserAuth.php';
$auth = new UserAuth();
$auth->logout();
header('Location: http://photorings.codingallnight.com');
?>