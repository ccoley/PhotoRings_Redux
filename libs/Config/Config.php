<?php
/*
 * This file contains all the configuration settings for the PhotoRings server side systems.
 * To see a pretty output of all these settings, and any errors, open ViewConfig.php in your browser.
 */
class Config {
    private $httpHost = "frame.thebrokenbinary.com:8080";   // SERVER_NAME[:SERVER_PORT]
    private $documentRoot = "/var/www";                     // The server's document root
    private $baseRequestUrl = "/photorings";                // The website's base directory
    private $imgDir = "/user_images";                       // User images directory, sub-directory of baseRequestUrl
    private $originalImgDir = "/original";                  // Sub-directory that holds a user's original images
    private $resizedImgDir = "/800px";                      // Sub-directory that holds a user's resized images
//    private $libDir = "/libs";                              // Libraries directory, sub-directory of baseRequestUrl


    public function getHttpHost() {
        return $this->httpHost;
    }

    public function getDocRoot() {
        return $this->documentRoot;
    }

    public function getBaseRequestUrl() {
        return $this->baseRequestUrl;
    }

    public function getOriginalImgDir() {
        return $this->originalImgDir . "/";
    }

    public function getResizedImgDir() {
        return $this->resizedImgDir . "/";
    }

    public function getImgUploadPath() {
        return $this->documentRoot . $this->baseRequestUrl . $this->imgDir . "/";
    }

    public function getImgUrl($userId, $fileName, $fullSize = false) {
        $sizeDir = $fullSize ? $this->originalImgDir : $this->resizedImgDir;
        return "//" . $this->httpHost . $this->baseRequestUrl . $this->imgDir . "/" . $userId . $sizeDir . "/" . $fileName;
    }

//    public function getPathToLibDir() {
//        return $this->baseRequestUrl.$this->libDir;
////        return ltrim($this->libDir, '/');
//    }
}
?>
