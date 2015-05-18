<?php
/*
 * This file contains all the configuration settings for the PhotoRings server side systems.
 * To see a pretty output of all these settings, and any errors, open ViewConfig.php in your browser.
 */
class Config {
    private $settings;

    public function __construct($file = "config.ini") {
        $this->settings = parse_ini_file($file, TRUE);
    }

    public function getHttpHost() {
        return $this->settings['server']['host'];
    }

    public function getDocRoot() {
        return $this->settings['server']['root'];
    }

    public function getBaseRequestUrl() {
        return $this->settings['server']['host']. $this->settings['server']['request_uri'] . "/";
    }

    public function getImgUploadPath() {
        return $this->settings['server']['root'] . $this->settings['images']['base_dir'] . "/";
    }

    public function getOriginalImgDir() {
        return $this->settings['images']['original_dir'] . "/";
    }

    public function getResizedImgDir() {
        return $this->settings['images']['resized_dir'] . "/";
    }

    public function getProfileImgDir() {
        return $this->settings['images']['profile_dir'] . "/";
    }

    public function getImgUrl($userId, $fileName, $fullSize = false) {
        $sizeDir = $fullSize ? $this->settings['images']['original_dir'] : $this->settings['images']['resized_dir'];
        return "//" . $this->settings['server']['host'] . $this->settings['server']['request_uri']
            . $this->settings['images']['base_dir'] . "/" . $userId . $sizeDir . "/" . $fileName;
    }

    public function getProfileImgUrl($userId, $fileName) {
        return "//" . $this->settings['server']['host'] . $this->settings['server']['request_uri']
            . $this->settings['images']['base_dir'] . "/" . $userId . $this->settings['images']['profile_dir'] . "/" . $fileName;
    }

    public function getPEARMailSMTPParams() {
        //TODO make this function work for PHP's builtin mail() function too
        return  array(
                    "host"=>$this->settings['email']['host'],
                    "port"=>$this->settings['email']['port'],
                    "auth"=>true,
                    "username"=>$this->settings['email']['username'],
                    "password"=>$this->settings['email']['password']
                );
    }

    public function createUserDirectories($userId) {
        $flag1 = $flag2 = $flag3 = true;
        $base =  $this->getImgUploadPath() . $userId;

        if (!is_dir($base.$this->settings['images']['original_dir'])) {
            $flag1 = mkdir($base.$this->settings['images']['original_dir'], 0700, true);
        }
        if (!is_dir($base.$this->settings['images']['resized_dir'])) {
            $flag2 = mkdir($base.$this->settings['images']['resized_dir'], 0700, true);
        }
        if (!is_dir($base.$this->settings['images']['profile_dir'])) {
            $flag3 = mkdir($base.$this->settings['images']['profile_dir'], 0700, true);
        }

        return $flag1 && $flag2 && $flag3;
    }
}
?>
