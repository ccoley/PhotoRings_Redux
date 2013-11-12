<?php
require_once 'libs/Auth/UserAuth.php';
$auth = new UserAuth();
// If the user is not logged in, redirect them to the splash page
if ($auth->isLoggedIn($_SESSION['loggedIn']) == false) {
    header("Location: index.php");
}

require_once 'libs/Profile.php';
$profile = new Profile();
$profile->buildFromUsername($_SESSION['username']);
$rings = $profile->getRingIds(true);
$spanningRing = array_shift($rings);
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
                <h1>Manage Rings</h1>
            </div>
            <!-- All Friends Ring -->
            <div class="row panel main-ring">
                <?
                include_once 'libs/Database/PhotoRings_DB.php';
                $db = new PhotoRings_DB();
                $query = $db->prepare("SELECT rings.name, COUNT(ring_members.user_id) FROM rings, ring_members WHERE rings.id=? AND ring_members.ring_id=?");
                $query->execute(array($spanningRing,$spanningRing));
                $results = $query->fetch(PDO::FETCH_NUM);

                /*
                 * Trigonometric functions for the image locations where `radius` is the radius of the bounding box
                 *
                 * top:     radius - (radius - imgRadius)sin(theta)
                 * left:    radius + (radius - imgRadius)cos(theta)
                 */

                echo    "<a class='col-md-12 btn ring-box' href='manageRing.php?ring=$spanningRing'>"
                    .       "<div class='spanning-ring'>"
//                    .           "<div id='outerOval'></div>"
//                    .           "<div id='innerOval'></div>"
                    .           "<p class='h1'>$results[0] - $results[1]</p>"
                    .           "<img class='img-circle' src='images/debug/natalie1.jpg' style='top:32.0px; left:400.0px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie2.jpg' style='top:46.5px; left:250.3px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie3.jpg' style='top:87.6px; left:126.5px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie4.jpg' style='top:148.1px; left:50.0px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie5.jpg' style='top:217.6px; left:34.0px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie1.jpg' style='top:284.0px; left:81.3px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie2.jpg' style='top:335.9px; left:183.7px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie3.jpg' style='top:364.3px; left:323.5px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie4.jpg' style='top:364.3px; left:476.5px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie5.jpg' style='top:335.9px; left:616.3px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie1.jpg' style='top:284.0px; left:718.7px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie2.jpg' style='top:217.6px; left:766.0px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie3.jpg' style='top:148.1px; left:750.0px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie4.jpg' style='top:87.6px; left:673.5px;'>"
                    .           "<img class='img-circle' src='images/debug/natalie5.jpg' style='top:46.5px; left:549.7px;'>"
                    .       "</div>"
                    .   "</a>";
                ?>
            </div>
            <!-- Other Rings -->
            <div class="row panel other-rings">
                <?
                $db = new PhotoRings_DB();
                $query = $db->prepare("SELECT rings.name, COUNT(ring_members.user_id) FROM rings, ring_members WHERE rings.id=? AND ring_members.ring_id=?");
                foreach ($rings as $key=>$ringId) {
                    $query->execute(array($ringId,$ringId));
                    $results = $query->fetch(PDO::FETCH_NUM);

                    /*
                     * Trigonometric functions for the image locations where `radius` is the radius of the bounding box
                     *
                     * top:     radius - (radius - imgRadius)sin(theta)
                     * left:    radius + (radius - imgRadius)cos(theta)
                     */

                    echo    "<a class='col-md-3 btn ring-box' href='manageRing.php?ring=$ringId'>"
                        .       "<div class='img-ring'>"
//                        .           "<div id='outerCircle'></div>"
//                        .           "<div id='innerCircle'></div>"
                        .           "<p class='h1'>$results[1]</p>"
                        .           "<img class='img-circle' src='images/debug/natalie1.jpg' style='top:32.0px; left:100.0px;'>"
                        .           "<img class='img-circle' src='images/debug/natalie2.jpg' style='top:79.0px; left:35.3px;'>"
                        .           "<img class='img-circle' src='images/debug/natalie3.jpg' style='top:155.0px; left:60.0px;'>"
                        .           "<img class='img-circle' src='images/debug/natalie4.jpg' style='top:155.0px; left:140.0px;'>"
                        .           "<img class='img-circle' src='images/debug/natalie5.jpg' style='top:79.0px; left:164.7px;'>"
                        .       "</div>"
                        .       "<p>$results[0]</p>"
                        .   "</a>";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <!--<script src="js/viewRings.js"></script>-->
</body>
</html>
