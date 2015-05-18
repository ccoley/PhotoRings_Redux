// Globals
var originalChecks = {}, modifiedChecks = {};
var originalChecksString = null;
var originalCaption = null;

$(function() {
    // Get the original caption
    originalCaption = $('#caption').val();

    // Get the original checks
    var checkBoxes = document.getElementsByName('ring');
    for (var i = 0; i < checkBoxes.length; i++) {
        originalChecks[checkBoxes[i].value] = checkBoxes[i].checked;
        modifiedChecks[checkBoxes[i].value] = checkBoxes[i].checked;
    }
    originalChecksString = JSON.stringify(originalChecks);

    console.log("Caption: " + originalCaption + "\nOriginal Checks: " + originalChecksString);

    // Register event handlers
    $("input[name='ring']").change(function(e) {
//        console.log(e.target.value);
        modifiedChecks[e.target.value] = !modifiedChecks[e.target.value];
        needToSave();
    });

    $("#caption").bind('input', function() {
        needToSave();
    });
});

function getCookie(name) {
    var parts = document.cookie.split(name + '=');
    if (parts.length == 2) {
        return parts.pop().split(';').shift();
    }
    return false;
}

function needToSave() {
    // Enable or Disable the button
    if (originalChecksString !== JSON.stringify(modifiedChecks) || $('#caption').val() != originalCaption) {
        $('#saveButton').addClass('btn-warning').removeAttr('disabled');
        return true;
    } else {
        $('#saveButton').removeClass('btn-warning').attr('disabled', 'disabled');
        return false;
    }
}

function saveChanges(imageId) {
    // Make sure we actually need to save
    if (needToSave()) {
        console.log("Saving...");
        var data = {image:      imageId,
                    user:       getCookie('userId'),
                    caption:    $('#caption').val(),
                    rings:      JSON.stringify(modifiedChecks)};

        $.ajax({
            type: "POST",
            url: "saveImageChanges.php",
            data: data,
            dataType: 'json',
            success: function(data) {
                console.log("Success");
                console.log(data);
                location.reload();
            },
            error: function(data) {
                console.log("ERROR");
                console.log(data);
            }
        });
    }
}