<?php
/*
 * This file contains all the configuration settings for the PhotoRings server side systems.
 * To see a pretty output of all these settings, and any errors, open ViewConfig.php in your browser.
 */
class Config {
    // Server config settings
    private $httpHost       = "frame.thebrokenbinary.com:8080"; // SERVER_NAME[:SERVER_PORT]
    private $documentRoot   = "/var/www";                       // The server's document root
    private $baseRequestUrl = "/photorings";                    // The website's base directory
    private $imgDir         = "/user_images";                   // User images directory, sub-directory of baseRequestUrl
    private $originalImgDir = "/original";                      // Sub-directory that holds a user's original images
    private $resizedImgDir  = "/resized";                       // Sub-directory that holds a user's resized images
    private $profileImgDir  = "/profile";                       // Sub-directory that holds a user's profile images

    // PEAR::Mail settings
    private $mailHost       = "ssl://smtp.gmail.com";
    private $mailPort       = "465";
    private $mailUsername   = "photorings@codingallnight.com";
    private $mailPassword   = "SO6WBA36l77T0bj";

    public function getHttpHost() {
//        return $this->httpHost;
        return $_SERVER['HTTP_HOST'];   // For test purposes
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

    public function getProfileImgDir() {
        return $this->profileImgDir . "/";
    }

    public function getImgUploadPath() {
        return $this->documentRoot . $this->baseRequestUrl . $this->imgDir . "/";
    }

    public function getImgUrl($userId, $fileName, $fullSize = false) {
        $sizeDir = $fullSize ? $this->originalImgDir : $this->resizedImgDir;
        return "//" . $this->httpHost . $this->baseRequestUrl . $this->imgDir . "/" . $userId . $sizeDir . "/" . $fileName;
    }

    public function getPEARMailSMTPParams() {
        return array("host"=>$this->mailHost, "port"=>$this->mailPort, "auth"=>true, "username"=>$this->mailUsername, "password"=>$this->mailPassword);
    }
}
?>
