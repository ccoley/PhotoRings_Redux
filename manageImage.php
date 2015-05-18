<?php
require_once 'libs/UserAuth.php';
require_once 'libs/Config.php';
require_once 'libs/PhotoRings_DB.php';

$auth = new UserAuth();
// If the user is not logged in, redirect them to the splash page
if ($auth->isLoggedIn($_SESSION['loggedIn']) == false) {
    header("Location: index.php");
}

if (isset($_GET['img']) && isset($_GET['user']) && ($_GET['user'] == $_COOKIE['userId'])) {
    $userId = $_GET['user'];
    $fileName = $_GET['img'];
}
$config = new Config();
$imgSrc = $config->getImgUrl($userId, $fileName, true);

$db = new PhotoRings_DB();
$query = $db->prepare("SELECT id, upload_date, caption FROM images WHERE owner_id=? AND file_name=?");
$query->execute(array($userId, $fileName));
$imgData = $query->fetch(PDO::FETCH_ASSOC);

$query = $db->prepare("SELECT id, name, ring_images.ring_id IS NOT NULL AS shared FROM rings LEFT JOIN ring_images ON rings.id=ring_images.ring_id AND ring_images.image_id=? WHERE rings.owner_id=?");
$query->execute(array($imgData['id'], $userId));
$rings = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Photo - PhotoRings</title>
    <link rel="shortcut icon" href="images/photorings_favicon.ico"/>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="sidenav/sidenav.css">
    <link rel="stylesheet" href="css/manageImage.css">
</head>
<body>
    <!-- Side navigation -->
    <div class="sidebar pull-left">
        <? include 'sidenav/sidenav.html' ?>
    </div>

    <!-- Main page content -->
    <div class="main">
        <div class="container">
            <div class="well image-well">
                <img id="theImage" class="img-rounded" src="<? echo $imgSrc; ?>" alt=""/>
            </div>
            <div id="managementRow" class="row">
                <div id="leftPanel" class="col-sm-4 col-md-3 col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">Shared With</div>
                        <div class="panel-body">
                            <ul id="sharedWith" class="list-unstyled">
                                <?
                                foreach ($rings as $key=>$ring) {
                                    echo    "<li class='share-ring'>"
                                        .       "<div id='' class='checkbox'>"
                                        .           "<label>"
                                        .               "<input type='checkbox' name='ring' value='".$ring['id']."'".($ring['shared'] == 1 ? 'checked' : '')."/> "
                                        .               $ring['name']
                                        .           "</label>"
                                        .       "</div>"
                                        .   "</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="middlePanel" class="col-sm-6 col-md-7 col-lg-7">
                    <div class="panel panel-default">
                        <div class="panel-heading">Caption</div>
                        <div class="panel-body">
                            <textarea name="caption" id="caption" rows="5" maxlength="2000"><? echo $imgData['caption']; ?></textarea>
                        </div>
                    </div>
                </div>
                <div id="rightPanel" class="col-sm-2 col-md-2 col-lg-2">
                    <button id="deleteButton" type="button" class="btn btn-lg btn-block btn-danger" disabled="disabled">Delete Image</button>
                    <button id="saveButton" type="button" class="btn btn-lg btn-block" onclick="saveChanges(<? echo $imgData['id']; ?>)" disabled="disabled">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="js/manageImage.js"></script>
</body>
</html>
