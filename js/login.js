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
        valid = false;
        el.className = "form-group has-error";
        var err = document.getElementById("firstError");
        var ferr = document.getElementById("firstp");
        err.className = "alert alert-danger";
        ferr.innerHTML("You must enter a first name to use PhotoRings");
    }
    else {
        el.className = "form-group has-success";
        firstName = true;
        validCheck();
    }
}

// Checks to see if a last name has been entered.
function validateLName() {
    var el = document.getElementById("clname");

    var last  = document.forms["registerForm"]["lastName"].value;
    if (last.toString() == "") {
        valid = false;
        el.className = "form-group has-error";
    }
    else {
        el.className = "form-group has-success";
        lastName = true;
        validCheck();
    }
}

// this function checks for email validity.
function validateEmail() {
    var email = document.forms["registerForm"]["email"].value;
    var el = document.getElementById("cemail");

// Check for email validity.
    var atpos = email.indexOf("@");
    var dotpos = email.lastIndexOf(".");

    if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= email.length) {
        valid = false;
        el.className = "form-group has-error";
    }
    else {
        el.className = "form-group has-success";
        email = true;
        validCheck();
    }
}

// This function makes sure that user inputs a valid password.
// user must have password of 8 characters or more.
function validatePassword() {
    var pass  = document.forms["registerForm"]["password"].value;
    var el = document.getElementById("cpassword");

    if (pass.toString() == "") {
        valid = false;
        el.className = "form-group has-error";
    }
    if (pass.toString().length < 8) {
        el.className = "form-group has-error";
        // alert("Password must be at least 8 characters in length");
    }
    else {
        el.className = "form-group has-success";
        password = true;
        validCheck();
    }


}

// This function checks to see if a birthdate was entered.
// This function then checks to see if person is 18 or older. (NOT DONE YET!).
function validateBirthdate() {
    var el = document.getElementById("cbirthdate");

    var birth = document.forms["registerForm"]["birthdate"].value;
    if (birth.toString() == "") {
        valid = false;
        el.className = "form-group has-error";
    }
    else {
        el.className = "form-group has-success";
        birthdate = true;
        validCheck();
    }
}

// This function makes the create account button clickable when all values have been validated.
function validCheck() {
    if (firstName && lastName && email && password && birthdate) {
        var button = document.getElementById("csubmit");
        button.className = "";
        button.className = "btn btn-warning btn-default";
    }
}