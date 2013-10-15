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
	<script src="js/photouploader.js"></script>
    <!--<link rel="stylesheet" href="css/upload.css">-->
	
	<!-- Uploader Stuff -->
	<link rel="stylesheet" type="text/css" href="css/uploader.css" />
	<link rel="stylesheet" type="text/css" href="css/uploader-std.css" />
	<link rel="stylesheet" type="text/css" href="css/uploader-pacifico.css" />
	<script src="js/uploader.js"></script>
	
</head>
<body>
    <!-- Side navigation -->
    <div class="sidebar pull-left">
        <? include 'sidenav/sidenav.html' ?>
    </div>

    <!-- Main page content -->
    <div class="main">
		

		<!-- Drag and Drop Photo Uploader -->
<div class="container panel">	
<div id="header">
	<div id="center">
		<h1><span>PhotoRings Photo Uploader</span></h1>
	</div>
	
</div>
<div class="content center" >
	<div id="drop-files" ondragover="return false">
		Drop Images Here
	</div>
	
	<div id="uploaded-holder" class="center">
		<div id="dropped-files">
			<div id="upload-button">
				<a href="#" class="upload"><i class="ss-upload"> </i> Upload!</a>
				<a href="#" class="delete"><i class="ss-delete"> </i></a>
				<span>0 Files</span>
			</div>
		</div>
		<div id="extra-files">
			<div class="number">
				0
			</div>
			<div id="file-list">
				<ul></ul>
			</div>
		</div>
	</div>
	
	<div id="loading">
		<div id="loading-bar">
			<div class="loading-color"> </div>
		</div>
		<div id="loading-content">Uploading file.jpg</div>
	</div>
	
	<div id="file-name-holder">
		<ul id="uploaded-files">
			<h1>Uploaded Files</h1>
		</ul>
	</div>
</div>
</div>
		
		
		
    </div>

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>
