<?php
require_once 'libs/PhotoRings_DB.php';
require_once 'libs/Ring.php';
require_once 'libs/Config.php';

if (isset($_REQUEST['ring']) && isset($_REQUEST['user'])) {
    $ringID = intval($_REQUEST['ring']);
    $userID = intval($_REQUEST['user']);
    //echo "<br>Ring: $ringID<br>User: $userID<br>";
    // TODO: Do authentication of some sort to prevent anyone from pulling this info
    
    $ring = new Ring();
    //print_r($ring);
    //echo "<br>";
    //var_dump($ringID); 
    $ring->buildFromId($ringID);
    //print_r($ring);
    if ($ring->getOwnerId() != $userID) {
        return false;
    }
    $memberIds = $ring->getMemberIds();

    $config = new Config();
    $members = array();

    $db = new PhotoRings_DB();
    $query = $db->prepare("SELECT profile_image FROM users WHERE id=?");
    foreach ($memberIds as $id) {
        $query->execute(array($id));
        $image = $query->fetchColumn(0);
        $members[] = array('id' => $id, 'profile_image'=>$config->getProfileImgUrl($id, $image));
    }
    print json_encode($members);
//    return true;
} else {
    return false;
}
?>
