var firstName = false;
var lastName = false;
var email = false;
var password = false;
var birthdate = false;

// checks to see if user has entered a First Name.
function validateFName() {
    // alert("Name validation attempted.");
    var el = document.getElementById("cfname");

    var first = document.forms["registerForm"]["firstName"].value;
    if (first.toString() == "") {
        el.className = "form-group has-error";
        document.getElementById("firstError").style.display = "block";
        firstName = false;
    } else {
        el.className = "form-group has-success";
        document.getElementById("firstError").style.display = "none";
        firstName = true;
    }
    validCheck();
}

// Checks to see if a last name has been entered.
function validateLName() {
    var el = document.getElementById("clname");

    var last  = document.forms["registerForm"]["lastName"].value;
    if (last.toString() == "") {
        el.className = "form-group has-error";
        document.getElementById("lastError").style.display = "block";
        lastName = false;
    } else {
        el.className = "form-group has-success";
        document.getElementById("lastError").style.display = "none";
        lastName = true;
    }
    validCheck();
}

// this function checks for email validity.
// TODO: use a regular expression to do better email validation
function validateEmail() {
    var address = document.forms["registerForm"]["email"].value;
    var el = document.getElementById("cemail");

    // Check for email validity.
    var atPos = address.indexOf("@");
    var dotPos = address.lastIndexOf(".");

    if (atPos < 1 || dotPos < atPos + 2 || dotPos + 2 >= address.length) {
        el.className = "form-group has-error";
        document.getElementById("emailError").style.display = "block";
        email = false;
    } else {
        el.className = "form-group has-success";
        document.getElementById("emailError").style.display = "none";
        email = true;
    }
    validCheck();
}

// This function makes sure that user inputs a valid password.
// user must have password of 8 characters or more.
// TODO: make the minimum password requirements stronger
function validatePassword() {
    var pass  = document.forms["registerForm"]["password"].value;
    var el = document.getElementById("cpassword");

    if (pass.toString().length < 8) {
        el.className = "form-group has-error";
        document.getElementById("passwordError").style.display = "block";
        password = false;
    } else {
        el.className = "form-group has-success";
        document.getElementById("passwordError").style.display = "none";
        password = true;
    }
    validCheck();
}

// This function checks to see if a birthdate was entered.
// This function then checks to see if person is 18 or older. (NOT DONE YET!).
function validateBirthdate() {
    var el = document.getElementById("cbirthdate");

    var birth = document.forms["registerForm"]["birthdate"].value;
    if (birth.toString() == "") {
        el.className = "form-group has-error";
        document.getElementById("birthdateError").style.display = "block";
        birthdate = false;
    } else {
        el.className = "form-group has-success";
        document.getElementById("birthdateError").style.display = "none";
        birthdate = true;
    }
    validCheck();
}

// This function makes the create account button clickable when all values have been validated.
function validCheck() {
    document.getElementById("createSubmit").disabled = !(firstName && lastName && email && password && birthdate);
}