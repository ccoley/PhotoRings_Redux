<?php
require_once 'libs/Auth/UserAuth.php';
$auth = new UserAuth();
// If the user is not logged in, redirect them to the splash page
if ($auth->isLoggedIn($_SESSION['loggedIn']) == false) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PhotoRings</title>
    <link rel="shortcut icon" href="images/photorings_favicon.ico"/>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css">
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
            <?php
				require_once 'libs/Database/PhotoRings_DB.php';
				require_once 'libs/Auth/Profile.php';
                require_once 'libs/Config/Config.php';

                $config = new Config();
				$profile = new Profile();
				$profile->buildFromUsername($_SESSION['username']);
				
				try {
					$dbo = new PhotoRings_DB();				
				}
				catch (PDOException $e) {
					return false;
				}
				
				$query = $dbo->prepare("SELECT * FROM images WHERE owner_id = ?");				
				
				if($query != false) {
					$profileId = $profile->getId();
					if ($query->execute(array($profileId))) {
                        $result = $query->fetchAll(PDO::FETCH_ASSOC);
                        foreach($result as $row) {
                            echo    "<div class=\"row panel post-box\">"
                                .       "<div class=\"col-md-6 post-img\">"
                                .           "<img class=\"img-rounded img-responsive\" src=\"" . $config->getImgUrl($profileId, $row['file_name'], true) . "\">"
                                .       "</div>"
                                .       "<div class=\"col-md-6 post-text\">"
                                .           "<p>Some witty quip about how awesome my photo is.</p>"
                                .       "</div>"
                                .   "</div>";
                        }
                    }
				}
				
			?>
        </div>
    </div>

    <!-- Get them scripts. Load them last to improve page loading speeds. -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>
