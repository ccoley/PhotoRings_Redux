<?php
require_once 'libs/Auth/UserAuth.php';
$auth = new UserAuth();
// If the user is not logged in, redirect them to the splash page
if ($auth->isLoggedIn($_SESSION['loggedIn']) == false) {
//    print_r($_SESSION);
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
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <!-- Side navigation -->
    <div class="sidebar pull-left">
        <? include 'sidenav/sidenav.html' ?>
    </div>

    <!-- Main page content -->
    <div class="main">
        <div class="container">
            <script type="text/javascript">
                var imgPath = "images/test_images/";
                var images = ["ASU_Campus.jpg", "brave_frontier_desktop.jpg", "cassini_derelict_desktop.jpg", "DeltaNorth_1.jpg", "soccer_fail.gif"];

                // Write a post for each image
                images.forEach(function(image) {
                    document.write(""
                        +   "<div class=\"row panel post-box\">"
                        +       "<div class=\"col-md-6 post-img\">"
                        +           "<img class=\"img-rounded img-responsive\" src=\"" + imgPath + image + "\">"
                        +       "</div>"
                        +       "<div class=\"col-md-6 post-text\">"
                        +           "<p>Some witty quip about how awesome my photo is.</p>"
                        +       "</div>"
                        +   "</div>"
                    );
                });
//                document.write("<p class='white-text'>Screen Width: " + window.innerWidth + "</p>");
            </script>
        </div>
    </div>

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>
