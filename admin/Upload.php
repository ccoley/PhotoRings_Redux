<?php
require_once('Config.php');

$config = new Config();
echo "PhotoRings directory: " . $config->getBaseDir() . "<br>";
echo "Images directory: " . $config->getPathToImgDir() . "<br>";

$targetPath = $config->getPathToImgDir();
$fileName = basename($_FILES['image']['name']);
if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath.$fileName)) {
    echo "The file " . basename($_FILES['image']['name']) . " has been uploaded";
} else {
    echo "There was a problem uploading the file, please try again";
}
?>
