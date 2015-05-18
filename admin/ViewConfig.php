<?php
/*
 * This file only examines the configuration and echos the status and any errors.
 * To change the configuration, look in Config.php
 */

require_once('../libs/Config.php');

$config = new Config("../config.ini");

//$settings = parse_ini_file("../config.ini", TRUE);


echo "<link rel='stylesheet' href='ViewConfig.css' type='text/css'>";
echo "<h2>Configuration</h2><h4>Note: Errors may cascade downwards. Fix problems at the top first.</h4>";
echo "<table><tr><th>Setting</th><th>Value</th><th>Error(s), if any</th></tr>";


### Apache User ID
echo "<tr><td>Apache User ID</td><td>" . exec('whoami') . "</td><td></td></tr>";


### HTTP Host
$httpHost = $config->getHttpHost();
//$httpHost = $settings['server']['host'];
echo "<tr><td>HTTP Host</td><td>$httpHost</td><td>";
if ($httpHost != $_SERVER['HTTP_HOST']) {
    echo "HTTP Host is misconfigured. This could cause many issues throughout the website.<br>PHP sees it as ".$_SERVER['HTTP_HOST'];
}
echo "</td></tr>";

### Base Request URL
$baseURL = $config->getBaseRequestUrl();
$observedBaseURL = $_SERVER['HTTP_HOST'] . explode('admin', $_SERVER['REQUEST_URI'])[0];
echo "<tr><td>Base Request URL</td><td>$baseURL</td><td>";
if ($baseURL != $observedBaseURL) {
    echo "HTTP Host is misconfigured. This could cause many issues throughout the website.<br>PHP sees it as ".$observedBaseURL;
}
echo "</td></tr>";


### Base directory
//$baseDir = $config->getBaseRequestUrl();
//$baseDir = $settings['server']['root'];
//echo "<tr><td>Base Directory</td><td>$baseDir</td><td>";
//if (!file_exists($baseDir)) {
//    echo "$baseDir does not exist";
//} elseif (!is_writable($baseDir)) {
//    echo "$baseDir is not writable";
//}
//echo "</td></tr>";


### Libraries Directory
//$libDir = $config->getPathToLibDir();
//echo "<tr><td>Libraries Directory</td><td>$libDir</td><td>";
//if (!file_exists($libDir)) {
//    echo "$libDir does not exist";
//}
//echo '</td></tr>';


### Base Images Directory
$baseImgDir = $config->getImgUploadPath();
//$imgDir = $settings['server']['root'] . $settings['images']['base_dir'] . '/';
echo "<tr><td>Image Upload Directory</td><td>$baseImgDir</td><td>";
if (!file_exists($baseImgDir)) {
    echo "$baseImgDir does not exist";
} elseif (!is_writable($baseImgDir)) {
    echo "$baseImgDir is not writable";
}
echo '</td></tr>';


### User Image Directories
$origImgDir = $config->getOriginalImgDir('XX');
$resizedImgDir = $config->getResizedImgDir('XX');
$profileImgDir = $config->getProfileImgDir('XX');
echo "<tr><td>Original Image Directory</td><td>$origImgDir</td><td></td></tr>";
echo "<tr><td>Resized Image Directory</td><td>$resizedImgDir</td><td></td></tr>";
echo "<tr><td>Profile Image Directory</td><td>$profileImgDir</td><td></td></tr>";


### Example Image URLs
$fullImageURL = $config->getImgUrl('XX','example.png', TRUE);
$resizedImageURL = $config->getImgUrl('XX', 'example.png');
$profileImageURL = $config->getProfileImgUrl('XX', 'profile-pic.png');
echo "<tr><td>Original Image URL</td><td>$fullImageURL</td><td></td></tr>";
echo "<tr><td>Resized Image URL</td><td>$resizedImageURL</td><td></td></tr>";
echo "<tr><td>Profile Image URL</td><td>$profileImageURL</td><td></td></tr>";


### PHP ini settings
$maxUpload  = ini_get('upload_max_filesize');
$maxPost    = ini_get('post_max_size');
echo "<tr><td>PHP.ini: upload_max_filesize</td><td>$maxUpload</td><td>";
if (intval($maxUpload, 10) < 5) {
    echo "upload_max_filesize should be at least 5M for best performance";
}
echo "</td></tr>";

echo "<tr><td>PHP.ini: post_max_size</td><td>$maxPost</td><td>";
if (intval($maxPost, 10) < intval($maxUpload, 10) + 1) {
    echo "post_max_size should be at least 1M larger than upload_max_filesize";
}
echo "</td></tr>";


### PDO settings
echo "<tr><td>PHP Data Objects (PDO) Extension</td>";
if (extension_loaded('pdo')) {
    echo "<td>Loaded</td><td></td></tr>";
} else {
    echo "<td>Not Loaded!</td><td>PDO is required for PhotoRings to function correctly.</td></tr>";
}

echo "<tr><td>PHP Data Objects (PDO) MySQL Driver</td>";
if (extension_loaded('pdo_mysql')) {
    echo "<td>Loaded</td><td></td></tr>";
} else {
    echo "<td>Not Loaded!</td><td>PhotoRings uses a MySQL database, so PDO requires the MySQL driver.</td></tr>";
}


### PHP PEAR packages
include_once 'System.php';
echo "<tr><td>PHP PEAR Framework</td>";
if (class_exists('System')) {
    echo "<td>Found</td><td></td></tr>";
} else {
    echo "<td>Not Found!</td><td>PhotoRings requires the PEAR framework for some functions.</td></tr>";
}

include_once 'Mail.php';
echo "<tr><td>PHP PEAR::Mail</td>";
if (class_exists('Mail')) {
    echo "<td>Found</td><td></td></tr>";
} else {
    echo "<td>Not Found!</td><td>PhotoRings uses PEAR::Mail to send email to users.</td></tr>";
}


echo "</table>";
?>
