<?php
require_once 'libs/Auth/Profile.php';
require_once 'libs/Database/PhotoRings_DB.php';

if (!empty($_FILES)) {
    $profile = new Profile();
    $profile->buildFromUsername($_POST['username']);
    $imgDir = $profile->getImageDirectory() . 'original/';

    $tempFile = $_FILES['file']['tmp_name'];
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $base = md5_file($tempFile);
    $fileName = $base . '.' . $ext;
    $targetFile = $imgDir . $fileName;
    move_uploaded_file($tempFile, $targetFile);

    $db = new PhotoRings_DB();
    $query = $db->prepare("INSERT INTO images (owner_id, file_name) VALUES (?, ?)");
    $flag = $query->execute(array($profile->getId(),$fileName));
    return ($flag != false);
}