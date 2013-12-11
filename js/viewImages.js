$(function() {
    // Put the current tab name in the dropdown link
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var tabName = e.target.innerHTML;
        if (tabName == 'All Photos' || tabName == 'Unshared Photos') {
            document.getElementById('currentTab').innerHTML = '';
        } else {
            document.getElementById('currentTab').innerHTML = e.target.innerHTML;
        }
    });
});

function getCookie(name) {
    var parts = document.cookie.split(name + '=');
    if (parts.length == 2) {
        return parts.pop().split(';').shift();
    }
    return false;
}