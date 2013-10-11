<?php
/*
 * This file only examines the configuration and echos the status and any errors.
 * To change the configuration, look in Config.php
 */

require_once('Config.php');

$config = new Config();


echo "<link rel='stylesheet' href='ViewConfig.css' type='text/css'>";
echo "<h2>Configuration</h2><h4>Note: Errors may cascade downwards. Fix problems at the top first.</h4>";
echo "<table><tr><th>Setting</th><th>Value</th><th>Error(s), if any</th></tr>";


### Apache User ID
echo "<tr><td>Apache User ID</td><td>" . exec('whoami') . "</td><td></td>";


### Base directory
$baseDir = $config->getBaseDir();
echo "<tr><td>Base Directory</td><td>$baseDir</td><td>";
if (!file_exists($baseDir)) {
    echo "$baseDir does not exist";
} elseif (!is_writable($baseDir)) {
    echo "$baseDir is not writable";
}
echo "</td></tr>";


### Images Directory
$imgDir = $config->getPathToImgDir();
echo "<tr><td>Images Directory</td><td>$imgDir</td><td>";
if (!file_exists($imgDir)) {
    echo "$imgDir does not exist";
} elseif (!is_writable($imgDir)) {
    echo "$imgDir is not writable";
}
echo '</td></tr>';


### Libraries Directory
$libDir = $config->getPathToLibDir();
echo "<tr><td>Libraries Directory</td><td>$libDir</td><td>";
if (!file_exists($libDir)) {
    echo "$libDir does not exist";
}
echo '</td></tr>';


echo "</table>";
?>
