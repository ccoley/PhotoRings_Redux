<?php
require_once 'libs/UserAuth.php';
require_once 'libs/Config.php';
require_once 'libs/Profile.php';
require_once 'libs/PhotoRings_DB.php';

$auth = new UserAuth();
// If the user is not logged in, redirect them to the splash page
if ($auth->isLoggedIn($_SESSION['loggedIn']) == false) {
    header("Location: index.php");
}

$config = new Config();
$db = new PhotoRings_DB();

$profile = new Profile();
$profile->buildFromId($_COOKIE['userId']);
$profileId = $profile->getId();
$ringNames = $profile->getRingNames(true);
$ringIds = $profile->getRingIds(true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Skeleton - PhotoRings</title>
    <link rel="shortcut icon" href="images/photorings_favicon.ico"/>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="sidenav/sidenav.css">
    <link rel="stylesheet" href="css/viewImages.css">
</head>
<body>
    <!-- Side navigation -->
    <div class="sidebar pull-left">
        <? include 'sidenav/sidenav.html' ?>
    </div>

    <!-- Main page content -->
    <div class="main">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header orange-border white-text text-center">
                <h1>Your Photos</h1>
            </div>
            <div class="well">
                <!-- Tab Bar -->
                <ul id="tabBar" class="nav nav-pills">
                    <li class="text"><a>You are viewing:</a></li>
                    <li class="active"><a href="#All-Photos" data-toggle="tab">All Photos</a></li>
                    <li><a href="#Unshared-Photos" data-toggle="tab">Unshared Photos</a></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            Photos Shared with <span id='currentTab'></span> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <?
                            foreach ($ringNames as $ring) {
                                echo "<li><a href='#".str_replace(' ','-',$ring)."-Photos' data-toggle='tab'>$ring</a></li>";
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
                <hr class='orange-border'>
                <!-- Tab Content -->
                <div id="tabContent" class="tab-content text-center">
                    <div class="tab-pane active" id="All-Photos">
                        <?
                        $query = $db->prepare("SELECT id, file_name FROM images WHERE owner_id=?");
                        $query->execute(array($profileId));
                        $allPhotos = $query->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($allPhotos as $photo) {
                            $src = $config->getImgUrl($profileId, $photo['file_name']);
                            echo "<a class='photo-box' style='background-image: url(".$src.");' href='manageImage.php?user=".$profileId."&img=".$photo['file_name']."'></a>";
                        }
                        ?>
                    </div>
                    <div class="tab-pane" id="Unshared-Photos">
                        <?
                        $query = $db->prepare("SELECT id, file_name FROM images LEFT OUTER JOIN ring_images ON images.id=ring_images.image_id WHERE images.owner_id=? AND ring_images.image_id IS NULL");
                        $query->execute(array($profileId));
                        $sharedPhotos = $query->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($sharedPhotos as $photo) {
                            $src = $config->getImgUrl($profileId, $photo['file_name']);
                            echo "<a class='photo-box' style='background-image: url(".$src.");' href='manageImage.php?user=".$profileId."&img=".$photo['file_name']."'></a>";
                        }
                        ?>
                    </div>
                    <?
                    $query = $db->prepare("SELECT id, file_name FROM images LEFT JOIN ring_images ON images.id=ring_images.image_id WHERE images.owner_id=? AND ring_images.ring_id=?");
                    foreach ($ringNames as $key=>$ring) {
                        echo "<div class='tab-pane' id='".str_replace(' ','-',$ring)."-Photos'>";

                        $query->execute(array($profileId, $ringIds[$key]));
                        $ringPhotos = $query->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($ringPhotos as $photo) {
                            $src = $config->getImgUrl($profileId, $photo['file_name']);
                            echo "<a class='photo-box' style='background-image: url(".$src.");' href='manageImage.php?user=".$profileId."&img=".$photo['file_name']."'></a>";
                        }

                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="js/viewImages.js"></script>
</body>
</html>
