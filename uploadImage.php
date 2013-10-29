<?php
require_once 'libs/Auth/Profile.php';
require_once 'libs/Database/PhotoRings_DB.php';
require_once 'libs/Config/Config.php';

if (!empty($_FILES)) {
    $profile = new Profile();
    $profile->buildFromUsername($_POST['username']);
    //$imgDir = $profile->getImageDirectory() . 'original/';
    $conf = new Config();
    $imgDir = $conf->getImgUploadPath() . $profile->getId() . '/original/';

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
