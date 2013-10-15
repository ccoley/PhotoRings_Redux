<?php
require_once 'libs/Auth/UserAuth.php';

$auth = new UserAuth();

if ($_POST['action'] == 'login') {
    if ($auth->login($_POST['email'], $_POST['password']) == true) {
//        print_r($_SESSION);
//        echo "<br><br>";
        header('Location: home.php');
    } else {
        print_r($_POST);
    }
} else if ($_POST['action'] == 'register') {
//    print_r($_POST);
    $auth->registerUser($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password'], $_POST['birthdate']);
    header("Location: index.php");
} else {
    echo '
<!DOCTYPE html>
<html>
<head>
    <title>PhotoRings Redux</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="page-header">
        <div class="container text-center">
            <h1>PhotoRings Login</h1>
        </div>
    </div>

    <div class="content container">
        <div class="well col-md-offset-1 col-md-10">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#login" data-toggle="tab">Login</a></li>
                <li><a href="#create" data-toggle="tab">Create Account</a></li>
            </ul>
            <div id="tabContent" class="tab-content">
                <div class="tab-pane active in" id="login">
                    <form class="form-horizontal" action="login.php" method="POST" role="form">
                        <input type="hidden" name="action" value="login">
                        <div class="form-group">
                            <label for="loginEmail" class="col-md-2 control-label">Email</label>
                            <div class="col-md-10">
                                <input type="email" name="email" class="form-control" id="loginEmail" placeholder="Email"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="loginPassword" class="col-md-2 control-label">Password</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="rememberMe"/> Remember me</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <button type="submit" class="btn btn-warning">Sign in</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="create">
                    <form class="form-horizontal" action="login.php" method="POST" role="form">
                        <input type="hidden" name="action" value="register">
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="createFName">First Name</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="createFName" name="firstName" placeholder="First Name"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="createLName">Last Name</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="createLName" name="lastName" placeholder="Last Name"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="createEmail" class="col-md-2 control-label">Email</label>
                            <div class="col-md-10">
                                <input type="email" class="form-control" id="createEmail" name="email" placeholder="Email"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="createPassword" class="col-md-2 control-label">Password</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control" id="createPassword" name="password" placeholder="Password"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="createDate">Birthdate</label>
                            <div class="col-md-10">
                                <input class="form-control" type="date" id="createDate" name="birthdate" placeholder="yyyy-mm-dd"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <button type="submit" class="btn btn-warning">Create Account</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?
            print_r($_REQUEST);
            ?>
        </div>
    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>
';
}
