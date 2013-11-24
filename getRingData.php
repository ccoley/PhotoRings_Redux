<?php
require_once 'libs/PhotoRings_DB.php';
require_once 'libs/Ring.php';
require_once 'libs/Config.php';
require_once 'libs/Profile.php';

if (isset($_REQUEST['ring']) && isset($_REQUEST['user'])) {
    $ringID = intval($_REQUEST['ring']);
    $userID = intval($_REQUEST['user']);
    //echo "<br>Ring: $ringID<br>User: $userID<br>";
    // TODO: Do authentication of some sort to prevent anyone from pulling this info

    $ring = new Ring();
    $ring->buildFromId($ringID);
    if ($ring->getOwnerId() != $userID) {
        return false;
    }
    $memberIds = $ring->getMemberIds();

    $db = new PhotoRings_DB();
    $query = $db->prepare("SELECT user_id FROM ring_members WHERE ring_id=(SELECT id FROM rings WHERE owner_id=? AND spanning=TRUE)");
    $query->execute(array($userID));
    $friendIds = $query->fetchAll(PDO::FETCH_COLUMN);

    $config = new Config();
    $people = array('members'=>array(), 'otherFriends'=>array());

//    $db = new PhotoRings_DB();
    $query = $db->prepare("SELECT profile_image, fname, lname FROM users WHERE id=?");
    foreach ($memberIds as $id) {
        $query->execute(array($id));
        $result = $query->fetch(PDO::FETCH_NUM);
        $people['members'][] = array('id' => $id, 'profile_image'=>$config->getProfileImgUrl($id, $result[0]), 'name'=>$result[1].' '.$result[2]);
    }

    foreach ($friendIds as $id) {
        if (!in_array($id, $memberIds)) {
            $query->execute(array($id));
            $result = $query->fetch(PDO::FETCH_NUM);
            $people['otherFriends'][] = array('id' => $id, 'profile_image'=>$config->getProfileImgUrl($id, $result[0]), 'name'=>$result[1].' '.$result[2]);
        }
    }
    print json_encode($people);
//    return true;
} else {
    return false;
}
?>
