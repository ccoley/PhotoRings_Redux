<?php
require_once 'libs/PhotoRings_DB.php';
require_once 'libs/Config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Skeleton - PhotoRings</title>
    <link rel="shortcut icon" href="images/photorings_favicon.ico"/>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="sidenav/sidenav.css">
    <link rel="stylesheet" href="css/sizeComparison.css">
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
            $profileId = 13;
            $config = new Config();
//            $db = new PhotoRings_DB();
//            $query = $db->prepare("SELECT file_name FROM images WHERE owner_id=?");
//            $query->execute(array($profileId));
//            $images = $query->fetchAll(PDO::FETCH_COLUMN, 0);
            $images = array("e9948042cc4155822e8050d194bc0ae9.png",
                            "f8eee7a751ca7e78e0ec60a49e3d9dbc.jpg",
                            "2b4bf13428e874efde92c281c8ca71ed.jpg",
                            "7a84b7fd11e52af75e8a2dd510a07afd.png",
                            "c830ff1867d77f70f39263507138aec0.jpg",
                            "c06cd65cd3e642e549706ab45474d6a7.jpeg",
                            "4fb1fad58ba93b8dc9ba3bcda571d9ad.jpg",
                            "f5475e4721f2b07c2a53dcf522234e2c.jpg",
                            "e089375e3e4e7f56cf52c47448a1da30.jpg",
                            "b804f1b90a16923e706547de12b02301.jpg");
            foreach($images as $image) {
                $ratio = filesize('user_images/'.$profileId.'/original/'.$image) / filesize('user_images/'.$profileId.'/resized/'.$image);
                echo    "<div class=\"row panel post-box\">"
                    .       "<div class=\"col-md-6 post-img\">"
                    .           "<h4 class='text-center'>Original</h4>"
                    .           "<a href='".$config->getImgUrl($profileId, $image, true)."'>"
                    .               "<img class='img-rounded img-responsive' src='".$config->getImgUrl($profileId, $image, true)."'>"
                    .           "</a>"
                    .           "<p class='text-center'>".round(filesize('user_images/'.$profileId.'/original/'.$image)/1024, 2)."K</p>"
                    .       "</div>"
                    .       "<div class=\"col-md-6 post-img\">"
                    .           "<h4 class='text-center'>Resized @ 90% Quality</h4>"
                    .           "<a href='".$config->getImgUrl($profileId, $image, false)."'>"
                    .               "<img class=\"img-rounded img-responsive\" src=\"" . $config->getImgUrl($profileId, $image, false) . "\">"
                    .           "</a>"
                    .           "<p class='text-center'>".round(filesize('user_images/'.$profileId.'/resized/'.$image)/1024, 2)."K - ".round($ratio, 2)."x smaller</p>"
                    .       "</div>"
                    .   "</div>";
            }
            ?>
        </div>
    </div>

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<!--    <script src="js/skeleton.js"></script>-->
</body>
</html>
