<?php
/*
 * This file contains all the configuration settings for the PhotoRings server side systems.
 * To see a pretty output of all these settings, and any errors, open ViewConfig.php in your browser.
 */
class Config {
    private $baseDir = "/photorings";       // Base directory for PhotoRings
    private $imgDir = "/images";            // Images directory, sub-directory of baseDir


    public function getBaseDir()
    {
        return $this->baseDir;
    }

    public function getImgDir()
    {
        return $this->imgDir;
    }

    public function getPathToImgDir() {
        return $this->baseDir . $this->imgDir . '/';
    }
}
?>