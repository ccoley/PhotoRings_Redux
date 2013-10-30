<?php
require_once 'libs/Auth/Profile.php';
require_once 'libs/Database/PhotoRings_DB.php';
require_once 'libs/Config/Config.php';

if (!empty($_FILES)) {
    $config = new Config();
    $profile = new Profile();
    $profile->buildFromUsername($_POST['username']);
    $userId = $profile->getId();

    // Get the file names and paths
    $tempFile = $_FILES['file']['tmp_name'];
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $base = md5_file($tempFile);
    $fileName = $base . '.' . $ext;
    $imgDir = $config->getImgUploadPath() . $userId;
    $fullSizeImg = $imgDir . $config->getOriginalImgDir() . $fileName;

    // Resize the image
    $resizedImg = $imgDir . $config->getResizedImgDir() . $fileName;
    // TODO resize the uploaded image

    // Move the files to their permanent locations
    move_uploaded_file($tempFile, $fullSizeImg);

    // Insert a row in the images table for the image
    $db = new PhotoRings_DB();
    $query = $db->prepare("INSERT INTO images (owner_id, file_name) VALUES (?, ?)");
    $flag = $query->execute(array($userId,$fileName));
    return ($flag != false);
}
