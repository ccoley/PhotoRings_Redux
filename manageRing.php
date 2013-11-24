<?php
require_once 'libs/UserAuth.php';
require_once 'libs/PhotoRings_DB.php';
require_once 'libs/Ring.php';
require_once 'libs/Config.php';

$auth = new UserAuth();
// If the user is not logged in, redirect them to the splash page
if ($auth->isLoggedIn($_SESSION['loggedIn']) == false) {
    header("Location: index.php");
}

if (isset($_GET['ring'])) {
    $db = new PhotoRings_DB();
    $query = $db->prepare("SELECT id FROM users WHERE email=?");
    $query->execute(array($_SESSION['username']));
    $userId = $query->fetch(PDO::FETCH_COLUMN, 0);

    $query = $db->prepare("SELECT id FROM rings WHERE id=? AND owner_id=?");
    $query->execute(array($_GET['ring'], $userId));
    $result = $query->fetch(PDO::FETCH_COLUMN, 0);

    if ($result) {
        $ring = new Ring();
        $ring->buildFromId($result);

        // Get the ID and name of every member of this ring
        $memberIds = $ring->getMemberIds();
        $placeHolder = implode(',', array_fill(0, count($memberIds), '?'));
        $query = $db->prepare("SELECT id, fname, lname, profile_image FROM users WHERE id IN ($placeHolder)");
        $query->execute($memberIds);
        $members = $query->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Either ringId doesn't exist, or it isn't owned by the user trying to access it
        // TODO show an error
    }
} else {
    // $_GET['ring'] wasn't set
    // TODO show an error
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><? echo $ring->getName(); ?> Ring - PhotoRings</title>
    <link rel="shortcut icon" href="images/photorings_favicon.ico"/>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="sidenav/sidenav.css">
    <link rel="stylesheet" href="css/manageRing.css">
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
                <h1><? echo $ring->getName(); ?></h1>
            </div>
            <div class="row">
                <div class="col-lg-9">
                    <!-- Ring Display -->
                    <div id="ringDisplay" class="panel panel-default text-center">
                        <img id="ringImgTemplate" class='template' src='' style=''>
                        <p class="h1"></p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <!-- Member List -->
                    <div id="memberList" class="panel panel-default">
                        <div class="panel-heading"><h3 class="panel-title">Members</h3></div>
                        <div class="panel-body">
                            <ul id="members" class="list-unstyled">
                                <li id="memberTemplate" class="template"><p><button class="btn btn-default btn-xs"><i class='fa fa-minus'></i></button></p></li>
                            </ul>
                            <hr>
                            <ul id="otherFriends" class="list-unstyled">
                                <li id="otherFriendTemplate" class="template"><p><button class="btn btn-default btn-xs"><i class='fa fa-plus'></i></button></p></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h3 class="panel-title">Ring Settings</h3></div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" action="manageRing.php" method="post">
                                <input type="hidden" name="action" value="updateRing">
                                <div class="form-group">
                                    <label for="ringName" class="col-md-2 control-label">Ring Name</label>
                                    <div class="col-md-10">
                                        <?
                                        if ($ring->isSpanning()) {
                                            echo '<p class="form-control-static">'.$ring->getName().'</p>';
                                        } else {
                                            echo '<input type="text" class="form-control" id="ringName" name="ringName" placeholder="'.$ring->getName().'">';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <button id="saveButton" type="button" class="btn btn-lg btn-block" disabled="disabled">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="js/manageRing.js"></script>
</body>
</html>
