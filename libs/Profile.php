<?php
include_once 'libs/PhotoRings_DB.php';
include_once 'libs/Config.php';

class Profile {
    // Object properties
    private $id;
    private $username;
    private $firstName;
    private $lastName;
    private $dob;
    private $picture;
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
        $this->picture      = $results[0]['profile_image'];
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
        $io = popen('/usr/bin/du -sh ' . $f . $config->getProfileImgDir(), 'r');
        $profile = fgets($io);
        $profile = substr($profile, 0, strpos($profile, "\t"));
        pclose($io);

        return array($fullSize, $resized, $profile);
    }

    public function isAdmin() {
        return ($this->privilege == 1);
    }

    public function getRingCount() {
        $db = new PhotoRings_DB();
        $query = $db->prepare("SELECT COUNT(id) FROM rings WHERE owner_id=?");
        $query->execute(array($this->id));
        $result = $query->fetch(PDO::FETCH_NUM);

        return $result[0];
    }

    public function getFriendCount() {
        $db = new PhotoRings_DB();
        $query = $db->prepare("SELECT COUNT(user_id) FROM ring_members WHERE ring_id=(SELECT id FROM rings WHERE owner_id=? AND spanning=TRUE)");
        $query->execute(array($this->id));
        $result = $query->fetch(PDO::FETCH_NUM);

        return $result[0];
    }

    /**
     * This method returns the array of DB IDs for all rings owned by this profile.
     * If the `spanning` ring is included, it will be the first ID in the array.
     *
     * @param bool $withSpanningRing Should the returned array include the `spanning` ring?
     * @return array An array of DB IDs for all the user's rings
     */
    public function getRingIds($withSpanningRing = false) {
        $db = new PhotoRings_DB();
        if ($withSpanningRing) {
            $query = $db->prepare("SELECT id FROM rings WHERE owner_id=? ORDER BY spanning DESC, id");
        } else {
            $query = $db->prepare("SELECT id FROM rings WHERE (owner_id=? AND spanning=FALSE) ORDER BY id");
        }
        $query->execute(array($this->id));
        $results = $query->fetchAll(PDO::FETCH_COLUMN, 0);

        return $results;
    }

    public function getProfilePictureURL() {
        $config = new Config();
        return $config->getProfileImgUrl($this->id, $this->picture);
    }
}
?>
