<?php
require_once 'libs/Config/Config.php';
$config = new Config();
$imgDir = $config->getPathToImgDir() . "test/original/";

if (!empty($_FILES)) {
    $tempFile = $_FILES['file']['tmp_name'];
    $targetFile = $imgDir . $_FILES['file']['name'];
    move_uploaded_file($tempFile, $targetFile);
}