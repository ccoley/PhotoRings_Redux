<?php
/*
 * This file only examines the configuration and echos the status and any errors.
 * To change the configuration, look in Config.php
 */

require_once('../libs/Config/Config.php');

$config = new Config();


echo "<link rel='stylesheet' href='ViewConfig.css' type='text/css'>";
echo "<h2>Configuration</h2><h4>Note: Errors may cascade downwards. Fix problems at the top first.</h4>";
echo "<table><tr><th>Setting</th><th>Value</th><th>Error(s), if any</th></tr>";


### Apache User ID
echo "<tr><td>Apache User ID</td><td>" . exec('whoami') . "</td><td></td></tr>";


### HTTP Host
$httpHost = $config->getHttpHost();
echo "<tr><td>HTTP Host</td><td>$httpHost</td><td>";
if ($httpHost != $_SERVER['HTTP_HOST']) {
    echo "HTTP Host is misconfigured. This could cause many issues throughout the website.<br>PHP sees it as ".$_SERVER['HTTP_HOST'];
}
echo "</td></tr>";


### Base directory
$baseDir = $config->getBaseRequestUrl();
echo "<tr><td>Base Directory</td><td>$baseDir</td><td>";
if (!file_exists($baseDir)) {
    echo "$baseDir does not exist";
} elseif (!is_writable($baseDir)) {
    echo "$baseDir is not writable";
}
echo "</td></tr>";


### Libraries Directory
//$libDir = $config->getPathToLibDir();
//echo "<tr><td>Libraries Directory</td><td>$libDir</td><td>";
//if (!file_exists($libDir)) {
//    echo "$libDir does not exist";
//}
//echo '</td></tr>';


### Images Directory
$imgDir = $config->getImgUploadPath();
echo "<tr><td>Image Upload Directory</td><td>$imgDir</td><td>";
if (!file_exists($imgDir)) {
    echo "$imgDir does not exist";
} elseif (!is_writable($imgDir)) {
    echo "$imgDir is not writable";
}
echo '</td></tr>';


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


echo "</table>";
?>
