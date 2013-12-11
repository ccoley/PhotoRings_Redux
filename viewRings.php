<?php
require_once 'libs/UserAuth.php';
require_once 'libs/Profile.php';
require_once 'libs/PhotoRings_DB.php';
require_once 'libs/Config.php';
require_once 'libs/Ring.php';

$auth = new UserAuth();
// If the user is not logged in, redirect them to the splash page
if ($auth->isLoggedIn($_SESSION['loggedIn']) == false) {
    header("Location: index.php");
}

$config = new Config();

$profile = new Profile();
$profile->buildFromUsername($_SESSION['username']);
$rings = $profile->getRingIds(true);
$spanningRingId = array_shift($rings);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rings - PhotoRings</title>
    <link rel="shortcut icon" href="images/photorings_favicon.ico"/>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="sidenav/sidenav.css">
    <link rel="stylesheet" href="css/viewRings.css">
</head>
<body>
    <!-- Side navigation -->
    <div class="sidebar pull-left">
        <? include 'sidenav/sidenav.html' ?>
    </div>

    <!-- Main page content -->
    <div class="main">
        <div class="container">
            <div class="page-header orange-border white-text text-center">
                <h1>All Your Rings</h1>
            </div>
            <!-- All Friends Ring -->
            <div class="panel main-ring text-center">
                <a class="col-lg-12 btn ring-box" href="manageRing.php?ring=<? echo $spanningRingId; ?>">
                    <div class="spanning-ring">
                        <?
                        $spanningRing = new Ring();
                        $spanningRing->buildFromId($spanningRingId);
                        $allFriends = $spanningRing->getMemberIds();

                        $db = new PhotoRings_DB();
                        $query = $db->prepare("SELECT profile_image, fname, lname FROM users WHERE id=?");
                        $friendProfiles = array();
                        foreach ($allFriends as $friend) {
                            $query->execute(array($friend));
                            $result = $query->fetch(PDO::FETCH_NUM);
                            $friendProfiles[$friend] = array('image'=>$result[0], 'name'=>$result[1].' '.$result[2]);
                        }

                        $name = $spanningRing->getName();
                        $count = $spanningRing->getMemberCount();
                        echo "<p class='h1'>$name - $count</p>";


                        // Trigonometric functions for the image locations where `radius` is the radius of the bounding box
                        // top:     radius - (radius - imgRadius)sin(theta)
                        // left:    radius + (radius - imgRadius)cos(theta)
                        $interval = 2*M_PI/$count;
                        $theta = M_PI_2;
                        foreach ($friendProfiles as $id=>$profileImage) {
                            $top  = 200 - (200 - 32) * sin($theta);
                            $left = 350 + (350 - 32) * cos($theta);
                            $src = $config->getProfileImgUrl($id, $profileImage['image']);
                            echo "<img class='img-circle' src='$src' title='".$profileImage['name']."' style='top:".$top."px; left:".$left."px;'>";
                            $theta += $interval;
                        }
                        ?>
                    </div>
                </a>
            </div>
            <!-- Other Rings -->
            <div class="row other-rings">
                <?
                $boxRadius = 100;
                foreach ($rings as $ringId) {
                    $ring = new Ring();
                    $ring->buildFromId($ringId);
                    $ringMembers = array_slice($ring->getMemberIds(), 0, 5);

                    echo    "<div class='col-sm-6 col-md-4 col-lg-3 ring-box'>"
                        .       "<div class='panel inner-box'>"
                        .       "<a class='img-ring btn' href='manageRing.php?ring=$ringId'>"
                        .           "<p class='h1'>".$ring->getMemberCount()."</p>";

                    // Trigonometric functions for the image locations where `radius` is the radius of the bounding box
                    // top:     radius - (radius - imgRadius)sin(theta)
                    // left:    radius + (radius - imgRadius)cos(theta)
                    $interval = 2*M_PI/count($ringMembers);
                    $theta = M_PI_2;
                    foreach ($ringMembers as $member) {
                        $memberProfile = $friendProfiles[$member];
                        $top  = $boxRadius - ($boxRadius - 32) * sin($theta);
                        $left = $boxRadius + ($boxRadius - 32) * cos($theta);
                        $src = $config->getProfileImgUrl($member, $memberProfile['image']);
                        echo        "<img class='img-circle' src='$src' title='".$memberProfile['name']."' style='top:".$top."px; left:".$left."px;'>";
                        $theta += $interval;
                    }

                    echo        "</a>"
                        .       "<p class='ring-label'>".$ring->getName()."</p>"
                        .       "</div>"
                        .   "</div>";
                }
                ?>
                <!-- Create New Ring -->
                <div id="createRingBox" class="col-sm-6 col-md-4 col-lg-3 ring-box">
                    <div class="panel inner-box">
                        <button class="img-ring btn" onclick="createRing()">
                            <i class="big-plus fa fa-plus"></i>
                        </button>
                        <p class="ring-label">Add New Ring</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="js/viewRings.js"></script>
</body>
</html>
