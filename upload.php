<?php


require_once 'libs/Auth/UserAuth.php';
require_once 'libs/Config/Config.php';
$auth = new UserAuth();
$conf = new Config();
$imagePath = $conf->getPathToImgDir();
// If the user is not logged in, redirect them to the splash page
if ($auth->isLoggedIn($_SESSION['loggedIn']) == false) {
    header("Location: http://photorings.codingallnight.com");
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>PhotoRings Reduxxxxxxxxxxxxxxxxxxxxxxxx</title>
    <link rel="shortcut icon" href="images/photorings_favicon.ico"/>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="sidenav/sidenav.css">	
	
	<link rel="stylesheet" href="css/uploader.css">
	<script src="js/ImageUpload.js"></script>	
	
	<!-- Google web fonts -->
    <link href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700" rel='stylesheet' />
</head>

<body>
    <!-- Side navigation -->
    <div class="sidebar pull-left">
        <? include 'sidenav/sidenav.html' ?>
    </div>

    <!-- Main page content -->
    <div class="main">
	
	
	<!-- Upload Images here -->
	
	<form id="upload" method="post" action="UploadImage.php" enctype="multipart/form-data">
            <div id="drop">
                Drop Here

                <a>Browse</a>
                <input type="file" name="upl" multiple />
            </div>

            <ul>
                <!-- The file uploads will be shown here -->
            </ul>

        </form>	
	
	<!-- End Image Uploading here -->	
    </div>

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	
	<!-- Scripts for the uploading jQuery -->
	<script src="js/jqueryKnob.js"></script>
	<script src="js/jqueryUIwidget.js"></script>
	<script src="js/jqueryiframe.js"></script>
	<script src="js/jqueryFileUpload.js"></script>
	<script src="js/jqueryMain.js"></script>
	
</body>
</html>
