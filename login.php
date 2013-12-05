<?php
require_once 'libs/UserAuth.php';

$auth = new UserAuth();
$registerFailed = "display:none;";

if ($_POST['action'] == 'login') {
    if (($userId = $auth->login($_POST['email'], $_POST['password'])) !== false) {
//        echo '<br>Session:<br>';
//        print_r($_SESSION);
        setcookie('userId', $userId);
        header('Location: home.php');
    } else {
//        echo '<br>';
//        print_r($_POST);
    }
} else if ($_POST['action'] == 'register') {
//    print_r($_POST);
	if ($auth->registerUser($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password'], $_POST['birthdate'])) {
		header("Location: index.php");
	}
	else {
		$registerFailed = "display:inherit";
	} 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - PhotoRings</title>
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
       	<div class="alert alert-danger alert-dismissable" style="<?php echo $registerFailed; ?>">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		    <strong>That email is already registered.</strong>
		</div>
		
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
                    <div class="alert alert-danger" style="display:none;" id="firstError">You must enter a first name to use PhotoRings.</div>
                    <div class="alert alert-danger" style="display:none;" id="lastError">You must enter a last name to use PhotoRings.</div>
                    <div class="alert alert-danger" style="display:none;" id="emailError">You must enter a valid email address to use PhotoRings.</div>
                    <div class="alert alert-danger" style="display:none;" id="passwordError">You must enter a password with 8 or more characters.</div>
                    <div class="alert alert-danger" style="display:none;" id="birthdateError">You must enter a date-of-birth, and you must be 18 or older to use PhotoRings.</div>

                    <form name="registerForm" class="form-horizontal" action="login.php" method="POST" role="form">
                        <input type="hidden" name="action" value="register">
                        <div class="form-group" id="cfname">
                            <label class="col-md-2 control-label" for="createFName">First Name</label>
                            <div class="col-md-10">
                                <input class="form-control" onblur="validateFName()" type="text" id="createFName" name="firstName" placeholder="First Name"/>
                            </div>
                        </div>
                        <div class="form-group" id="clname">
                            <label class="col-md-2 control-label" for="createLName">Last Name</label>
                            <div class="col-md-10">
                                <input class="form-control" onblur="validateLName()" type="text" id="createLName" name="lastName" placeholder="Last Name"/>
                            </div>
                        </div>
                        <div class="form-group" id="cemail">
                            <label for="createEmail" class="col-md-2 control-label">Email</label>
                            <div class="col-md-10">
                                <input type="email" onblur="validateEmail()" class="form-control" id="createEmail" name="email" placeholder="Email"/>
                            </div>
                        </div>
                        <div class="form-group" id="cpassword">
                            <label for="createPassword" class="col-md-2 control-label">Password</label>
                            <div class="col-md-10">
                                <input type="password" onblur="validatePassword()" class="form-control" id="createPassword" name="password" placeholder="Password"/>
                            </div>
                        </div>
                        <div class="form-group" id="cbirthdate">
                            <label class="col-md-2 control-label" for="createDate">Birthdate</label>
                            <div class="col-md-10">
                                <input class="form-control" onblur="validateBirthdate()" type="date" id="createDate" name="birthdate" placeholder="yyyy-mm-dd"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <button id="createSubmit" type="submit" class="btn btn-warning" disabled="disabled">Create Account</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="js/login.js"></script>
</body>
</html>
