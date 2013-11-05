<?php
require_once 'libs/Profile.php';
require_once 'libs/Database/PhotoRings_DB.php';
require_once 'libs/Config/Config.php';
require_once 'libs/ImageProc/Resize.php';

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
    $fullSizeImgPath = $imgDir . $config->getOriginalImgDir() . $fileName;
    move_uploaded_file($tempFile, $fullSizeImgPath);

    // Resize the image
    $pixels = 712;  // 712px because that is the max width on the image post
    $dimensions = getimagesize($fullSizeImgPath);
    $resizedImgPath = $imgDir . $config->getResizedImgDir() . $fileName;

    // Only resize the image if it is larger that a $pixels x $pixels square
    if ($dimensions[0] <= $pixels && $dimensions[1] <= $pixels) {
        // Image would be scaled up if we resized it, so don't.
        copy($fullSizeImgPath, $resizedImgPath);
    } else {
        $resized = new Resize($fullSizeImgPath);
        $resized->resizeImage($pixels, $pixels, 'auto');
        $resized->saveImage($resizedImgPath, '90');

        // If the resized image is larger in filesize, replace it with the original
        if (filesize($resizedImgPath) > filesize($fullSizeImgPath)) {
            copy($fullSizeImgPath, $resizedImgPath);
        }
    }

    // Insert a row in the images table for the image
    $db = new PhotoRings_DB();
    $query = $db->prepare("INSERT INTO images (owner_id, file_name) VALUES (?, ?)");
    $flag = $query->execute(array($userId,$fileName));
    return ($flag != false);
}
