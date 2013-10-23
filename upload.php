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
    <title>Upload - PhotoRings</title>
    <link rel="shortcut icon" href="images/photorings_favicon.ico"/>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="sidenav/sidenav.css">
    <link rel="stylesheet" href="css/dropzone.css">
    <link rel="stylesheet" href="css/upload.css">
</head>
<body>
    <!-- Side navigation -->
    <div class="sidebar pull-left">
        <? include 'sidenav/sidenav.html' ?>
    </div>

    <!-- Main page content -->
    <div class="main">
        <div class="container well">
            <form class="dropzone" action="uploadImage.php" id="uploadDropzone">
                <div class="fallback alert alert-danger">
                    <strong>JavaScript is Disabled!</strong> JavaScript is required to upload images on PhotoRings.
                </div>
            </form>
            <br>
            <h4 class="text-center">You can upload images that are less than 5mb in size and have one of these extensions: .jpg, .jpeg, .gif, .png</h4>
        </div>
    </div>

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <script src="js/dropzone.min.js"></script>
    <script src="js/upload.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>
