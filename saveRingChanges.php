<?php
require_once 'libs/Ring.php';

if (isset($_REQUEST['ring']) && isset($_REQUEST['user']) && isset($_REQUEST['members']) && isset($_REQUEST['name'])) {
    $ringID = intval($_REQUEST['ring']);
    $userID = intval($_REQUEST['user']);
    //echo "<br>Ring: $ringID<br>User: $userID<br>";
    // TODO: Do authentication of some sort to prevent anyone from running this script

    $return = "Return String:";

    $ring = new Ring();
    $ring->buildFromId($ringID);
    if ($ring->getOwnerId() != $userID) {
        return false;
    }

    $oldMembers = $ring->getMemberIds();
    $modifiedMembers = $_REQUEST['members'];
    $return .= "\nMembers:\n" . json_encode($oldMembers) . "\n-----------------------------";
    $return .= "\nModified Members:\n" . json_encode($modifiedMembers) . "\n-----------------------------";

    // Update the member list, if necessary
    foreach ($modifiedMembers as $id=>$val) {
        $val = strtolower($val);
        $id = strval($id);
//        $return .= "\nID: $id\t\tVAL: $val\t\tBOOL: " . ($val == 'true') . "\t\tIN_ARRAY: " . in_array($id, $oldMembers);
        if ($val == 'true' && !in_array($id, $oldMembers)) {
            $ring->addMember($id);
        } elseif ($val == 'false' && in_array($id, $oldMembers)) {
            $ring->removeMember($id);
        }
    }

    $return .= "-----------------------------\nMembers after loop:\n" . json_encode($ring->getMemberIds());

    // Update the name, if necessary
    $modifiedName = trim($_REQUEST['name']);
    if (!$ring->isSpanning() && $ring->getName() != $modifiedName) {
        $ring->setName($modifiedName);
    }

    $temp = $ring->save();
    if (is_array($temp)) {
        $return .= "\n" . ($temp[0] ? "Saved" : "Not Saved");
        $return .= "\nQuery:\n$temp[1]\nValues:\n$temp[2]";
    } else {
        $return .= "\n" . ($temp ? "Saved" : "Not Saved");
    }

    print json_encode($return);
} else {
    return false;
}
?>