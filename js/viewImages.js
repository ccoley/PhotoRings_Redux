function getCookie(name) {
    var parts = document.cookie.split(name + '=');
    if (parts.length == 2) {
        return parts.pop().split(';').shift();
    }
    return false;
}