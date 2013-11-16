<?php
//require_once 'libs/PhotoRings_DB.php';
require_once 'libs/Ring.php';

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
    print json_encode($memberIds);
    //return true;
} else {
    return false;
}
?>
