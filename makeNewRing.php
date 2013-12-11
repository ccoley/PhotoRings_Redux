<?php
require_once 'libs/PhotoRings_DB.php';
require_once 'libs/Ring.php';
require_once 'libs/Config.php';

if (isset($_REQUEST['user'])) {
    $userID = intval($_REQUEST['user']);
    //echo "<br>Ring: $ringID<br>User: $userID<br>";
    // TODO: Do authentication of some sort to prevent just anyone from inserting a ring

    $db = new PhotoRings_DB();
    $query = $db->prepare("INSERT INTO rings (owner_id) VALUE (?)");
    if ($query->execute(array($userID))) {
        print $db->lastInsertId();
    } else {
        return false;
    }
}
?>
