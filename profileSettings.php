<?php
require_once 'libs/Auth/UserAuth.php';
$auth = new UserAuth();
// If the user is not logged in, redirect them to the splash page
if ($auth->isLoggedIn($_SESSION['loggedIn']) == false) {
    header("Location: index.php");
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
            <!-- Password Change form -->
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4 class=panel-title>Change Password</h4></div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label for="oldPassword" class="col-md-3 control-label">Old Password</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" id="oldPassword" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="newPassword" class="col-md-3 control-label">New Password</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" id="newPassword" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirmNewPassword" class="col-md-3 control-label">Confirm New Password</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" id="confirmNewPassword" placeholder="">
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
