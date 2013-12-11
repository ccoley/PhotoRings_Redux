<?php
require_once 'libs/PhotoRings_DB.php';

if (isset($_REQUEST['image']) && isset($_REQUEST['user']) && isset($_REQUEST['caption']) && isset($_REQUEST['rings'])) {
    $imageID = intval($_REQUEST['image']);
    $userID = intval($_REQUEST['user']);
    $rings = json_decode($_REQUEST['rings']);
    // TODO: Do authentication of some sort to prevent anyone from running this script
    // TODO: Make sure the 'user' actually owns the image being modified

    $db = new PhotoRings_DB();
    $query = $db->prepare("SELECT ring_id FROM ring_images WHERE image_id=?");
    $query->execute(array($imageID));
    $originalRings = $query->fetchAll(PDO::FETCH_COLUMN, 0);

    foreach ($rings as $key=>$val) {
        $in = in_array($key, $originalRings);
        if ($in && !$val) {
            $ringsToRemove[] = intval($key);
        } else if (!$in && $val) {
            $ringsToAdd[] = intval($key);
        }
    }

    $db->beginTransaction();
    $return = "";

    // Remove the rings that ought to be removed
    if (!empty($ringsToRemove)) {
        $placeHolder = implode(',', array_fill(0, count($ringsToRemove), "?"));
        $query = $db->prepare("DELETE FROM ring_images WHERE image_id=? AND ring_id IN ($placeHolder)");
        if (!$query->execute(array_merge(array($imageID), $ringsToRemove))) {
            $db->rollBack();
            print json_encode($query->queryString);
            return false;
        }
        $return .= "\nRemoved Rings " . implode(',', $ringsToRemove);
    }

    if (!empty($ringsToAdd)) {
        // Add the rings that ought to be added
        $placeHolder = implode(',', array_fill(0, count($ringsToAdd), "(?,?)"));
        $query = $db->prepare("INSERT INTO ring_images (ring_id, image_id) VALUES " . $placeHolder);

        // Build a new array with the image ID as every other value
        $execArray = array();
        foreach($ringsToAdd as $ring) {
            $execArray[] = $ring;
            $execArray[] = $imageID;
        }

        if (!$query->execute($execArray)) {
            $db->rollBack();
            print json_encode($query->queryString);
            return false;
        }
        $return .= "\nAdded Rings: " . implode(',', $ringsToAdd);
    }

    // TODO: Only update caption if it changed
    $query = $db->prepare("UPDATE images SET caption=? WHERE id=?");
    if (!$query->execute(array($_REQUEST['caption'], $imageID))) {
        $db->rollBack();
        return false;
    }
    $return .= "\nUpdated Caption";

    $db->commit();
    print json_encode($return);
    return true;
}
?>