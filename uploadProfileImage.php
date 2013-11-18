<?php
require_once 'libs/Profile.php';
require_once 'libs/PhotoRings_DB.php';
require_once 'libs/Config.php';
require_once 'libs/Resize.php';

if (!empty($_FILES)) {
    $config = new Config();
    $profile = new Profile();
    $profile->buildFromUsername($_POST['username']);
    $userId = $profile->getId();

    // Get the file names and paths
    $tempFile = $_FILES['file']['tmp_name'];
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $ext = strtolower($ext);
    $base = md5_file($tempFile);
    $fileName = $base . '.' . $ext;
    $imgDir = $config->getImgUploadPath() . $userId;

    // You have to move the file from the temporary upload directory before
    // trying to resize it. Otherwise the output is an all black image.
    $tmpImgPath = $imgDir . $config->getProfileImgDir() . "tmp_" . $fileName;
    move_uploaded_file($tempFile, $tmpImgPath);

    // Resize the image
    $pixels = 192;  // 192px
    $dimensions = getimagesize($tmpImgPath);
    $finalImgPath = $imgDir . $config->getProfileImgDir() . $fileName;

    // Only resize the image if it is larger that a $pixels x $pixels square
    if ($dimensions[0] <= $pixels && $dimensions[1] <= $pixels) {
        // Image would be scaled up if we resized it, so don't.
        copy($tmpImgPath, $finalImgPath);
    } else {
        $resized = new Resize($tmpImgPath);
        $resized->resizeImage($pixels, $pixels, 'crop');
        $resized->saveImage($finalImgPath, '90');

        // If the resized image is larger in filesize, replace it with the original
        if (filesize($finalImgPath) > filesize($tmpImgPath)) {
            copy($tmpImgPath, $finalImgPath);
        }
    }

    // Delete the tmp_ copy
    unlink($tmpImgPath);

    // Insert a row in the images table for the image
    $db = new PhotoRings_DB();
    $query = $db->prepare("UPDATE users SET profile_image=? WHERE id=?");
    $flag = $query->execute(array($fileName, $userId));
    return ($flag != false);
}
