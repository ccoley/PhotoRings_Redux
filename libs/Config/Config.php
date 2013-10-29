<?php
/*
 * This file contains all the configuration settings for the PhotoRings server side systems.
 * To see a pretty output of all these settings, and any errors, open ViewConfig.php in your browser.
 */
class Config {
    private $baseDir = "/var/www/photorings";       // Base directory for PhotoRings
    private $imgDir = "/user_images";       // Images directory, sub-directory of baseDir
    private $libDir = "/libs";              // Libraries directory, sub-directory of baseDir


    public function getBaseDir() {
        return $this->baseDir;
    }

    public function getImgDir() {
        return $this->imgDir;
    }

    public function getPathToImgDir() {
        return $this->baseDir . $this->imgDir . '/';
    }

    public function getLibDir() {
        return $this->libDir;
    }

    public function getPathToLibDir() {
        return $this->baseDir . $this->libDir . '/';
    }
}
?>
