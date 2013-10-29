<?php
include_once 'libs/Database/PhotoRings_DB.php';
include_once 'libs/Config/Config.php';

class Profile {
    // Object properties
    private $id;
    private $username;
    private $firstName;
    private $lastName;
    private $dob;
    private $privilege;

    public function buildFromUsername($username) {
        $db = new PhotoRings_DB();
        $query = $db->prepare("SELECT * FROM users WHERE email=?");
        $query->execute(array($username));
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $this->id           = $results[0]['id'];
        $this->username     = $results[0]['email'];
        $this->firstName    = $results[0]['fname'];
        $this->lastName     = $results[0]['lname'];
        $this->dob          = $results[0]['birthdate'];
        $this->privilege    = $results[0]['privilege'];
    }

    public function getDob() {
        return $this->dob;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getFullName() {
        return $this->firstName . " " . $this->lastName;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getImageDirectory() {
        $config = new Config();
        return ($config->getPathToImgDir() . $this->id . "/");
    }

    public function getImageCount() {
        $db = new PhotoRings_DB();
        $query = $db->prepare("SELECT COUNT(id) FROM images WHERE owner_id=?");
        $query->execute(array($this->id));
        $result = $query->fetch(PDO::FETCH_NUM);

        return $result[0];
    }

    public function getDiskFootprint() {
        $f = $this->getImageDirectory();
        $io = popen('/usr/bin/du -sh ' . $f, 'r');
        $size = fgets($io);
        $size = substr($size, 0, strpos($size, "\t"));
        pclose($io);

        return $size;
    }

    public function isAdmin() {
        return ($this->privilege == 1);
    }
}
?>