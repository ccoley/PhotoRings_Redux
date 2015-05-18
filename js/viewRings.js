function createRing() {
    $.ajax({
        type: "POST",
        url: "makeNewRing.php",
        data: {user: getCookie('userId')},
        success: function(data) {
            console.log("SUCCESS");
            console.log("New Ring ID: " + data);
            window.location.href = 'manageRing.php?ring='+data;
        },
        error: function(data) {
            console.log("ERROR");
            console.log(data);
        }
    });
}

function getCookie(name) {
    var parts = document.cookie.split(name + '=');
    if (parts.length == 2) {
        return parts.pop().split(';').shift();
    }
    return false;
}