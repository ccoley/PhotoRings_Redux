<!DOCTYPE html>
<html>
<head>
    <title>SideNav Example</title>
    <!-- SideNav needs Bootstrap CSS and Font Awesome CSS, so include them if you haven't already -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css">
    <!-- Include SideNav's stylesheet after the Bootstrap stylesheet -->
    <link rel="stylesheet" href="sidenav.css">
</head>
<body>
    <!-- This div contains the SideNav and must be of class 'sidebar' -->
    <div class="sidebar pull-left">
        <? include 'sidenav.html' ?>
    </div> <!-- END sidebar -->

    <!-- This div contains the main page content and must be of class 'main' -->
    <div class="main">
        <!-- This content could be anything, as long as it is inside the 'main' div -->
        <div class="row">
            <div class="container">
                <div class="jumbotron">
                    This is a jumbotron
                </div>
            </div>
        </div>
    </div> <!-- END main -->

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <!-- Bootstrap needs jQuery, so include it if you haven't already -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- SideNav needs Bootstrap JS, so include it if you haven't already -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>
