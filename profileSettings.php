<?php
require_once 'libs/Auth/UserAuth.php';
$auth = new UserAuth();
// If the user is not logged in, redirect them to the splash page
if ($auth->isLoggedIn($_SESSION['loggedIn']) == false) {
    header("Location: index.php");
}

//print_r($_POST);

if ($_POST['action'] == 'changePassword') {
    if ($auth->changePassword($_SESSION['username'], $_POST['oldPassword'], $_POST['newPassword'])) {
        //TODO alert the user that their password was successfully changed
    } else {
        //TODO alert the user that their password was not changed
//        echo 'Password not changed';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Settings - PhotoRings</title>
    <link rel="shortcut icon" href="images/photorings_favicon.ico"/>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="sidenav/sidenav.css">
    <link rel="stylesheet" href="css/profileSettings.css">
</head>
<body>
    <!-- Side navigation -->
    <div class="sidebar pull-left">
        <? include 'sidenav/sidenav.html' ?>
    </div>

    <!-- Main page content -->
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4 class="panel-title">Your Profile Information</h4></div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>
                                <div class="col-md-9">
                                    <p class="form-control-static">Chris Coley</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email</label>
                                <div class="col-md-9">
                                    <p class="form-control-static">chris@codingallnight.com</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Birthdate</label>
                                <div class="col-md-9">
                                    <p class="form-control-static">February 19, 1990</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Password Change form -->
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4 class="panel-title">Change Password</h4></div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" action="profileSettings.php" method="post">
                            <input type="hidden" name="action" value="changePassword">
                            <div class="form-group">
                                <label for="oldPassword" class="col-md-3 control-label">Old Password</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="newPassword" class="col-md-3 control-label">New Password</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirmNewPassword" class="col-md-3 control-label">Confirm New Password</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn btn-default btn-submit">Update Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- END Password Change form -->
        </div>
    </div>

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>
