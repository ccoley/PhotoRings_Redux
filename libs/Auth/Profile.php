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

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getFullName() {
        return $this->firstName . " " . $this->lastName;
    }

    public function getDob() {
        return $this->dob;
    }

    public function getPrettyDob() {
        return date("F j, Y", strtotime($this->dob));
    }

    public function getAge() {
        list($y, $m, $d) = explode('-', $this->dob);

        if (($m = (date('m') - $m)) < 0) {
            $y++;
        } elseif ($m == 0 && date('d') - $d < 0) {
            $y++;
        }

        return date('Y') - $y;
    }

    public function getImageDirectory() {
        $config = new Config();
        return $config->getImgUploadPath() . $this->id . "/";
    }

    public function getImageCount() {
        $db = new PhotoRings_DB();
        $query = $db->prepare("SELECT COUNT(id) FROM images WHERE owner_id=?");
        $query->execute(array($this->id));
        $result = $query->fetch(PDO::FETCH_NUM);

        return $result[0];
    }

    public function getDiskFootprint() {
        $config = new Config();

        $f = $config->getImgUploadPath() . $this->id;
        $io = popen('/usr/bin/du -sh ' . $f . $config->getOriginalImgDir(), 'r');
        $fullSize = fgets($io);
        $fullSize = substr($fullSize, 0, strpos($fullSize, "\t"));
        $io = popen('/usr/bin/du -sh ' . $f . $config->getResizedImgDir(), 'r');
        $resized = fgets($io);
        $resized = substr($resized, 0, strpos($resized, "\t"));
        pclose($io);

        return array($fullSize, $resized);
    }

    public function isAdmin() {
        return ($this->privilege == 1);
    }
}
?>
