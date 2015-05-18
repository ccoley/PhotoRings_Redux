<?php
require_once 'libs/UserAuth.php';
require_once 'libs/PhotoRings_DB.php';
require_once 'libs/Profile.php';
require_once 'libs/Config.php';

$auth = new UserAuth();
// If the user is not logged in, redirect them to the splash page
if ($auth->isLoggedIn($_SESSION['loggedIn']) == false) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PhotoRings</title>
    <link rel="shortcut icon" href="images/photorings_favicon.ico"/>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="sidenav/sidenav.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <!-- Side navigation -->
    <div class="sidebar pull-left">
        <? include 'sidenav/sidenav.html' ?>
    </div>

    <!-- Main page content -->
    <div class="main">
        <div class="container">
            <?
            $config = new Config();
            $profile = new Profile();
            $profile->buildFromUsername($_SESSION['username']);
            $profileId = $profile->getId();

            $db = new PhotoRings_DB();
            $query = $db->prepare("SELECT DISTINCT(id), owner_id, upload_date, caption, file_name FROM images INNER JOIN ring_images ON ring_images.image_id=images.id INNER JOIN ring_members ON ring_members.ring_id=ring_images.ring_id WHERE ring_members.user_id=? ORDER BY upload_date DESC");
            $query->execute(array($profileId));
            $images = $query->fetchAll(PDO::FETCH_ASSOC);

            $ownerIds = array();
            foreach ($images as $image) {
                $ownerIds[] = $image['owner_id'];
            }
            $ownerIds = array_unique($ownerIds);

            $placeHolder = implode(',', array_fill(0, count($ownerIds), '?'));
            $query = $db->prepare("SELECT id, fname, lname, profile_image FROM users WHERE id IN ($placeHolder)");
            $query->execute($ownerIds);
            $owners = $query->fetchAll(PDO::FETCH_ASSOC);

            $users = array();
            foreach ($owners as $owner) {
                $users[$owner['id']] = array('name'=>$owner['fname']." ".$owner['lname'], 'profile_image'=>$owner['profile_image']);
            }

            if (count($images) > 0) {
                foreach ($images as $image) {
                    echo    "<div class='row panel post-box'>"
                        .       "<div class='col-md-6 post-img'>"
                        .           "<a href='".$config->getImgUrl($image['owner_id'], $image['file_name'], true)."'>"
                        .               "<img class='img-rounded img-responsive' src='".$config->getImgUrl($image['owner_id'], $image['file_name'])."'>"
                        .           "</a>"
                        .       "</div>"
                        .       "<div class='col-md-6 post-right-side'>"
                        .           "<div style='float:left;'>"
                        .               "<img class='post-profile-img' src='".$config->getProfileImgUrl($image['owner_id'], $users[$image['owner_id']]['profile_image'])."'>"
                        .               "</div>"
                        .           "<div class='comments-box'>"
                        .               "<span class='owner-name'>".$users[$image['owner_id']]['name']."</span>"
                        .               "<p>".$image['caption']."</p>"
                        .           "</div>"
                        .       "</div>"
                        .   "</div>";
                }
            } else {
                echo    "<div class='row panel'><h4 class='text-center'>No Images</h4></div>";
            }
            ?>
        </div>
    </div>

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>
