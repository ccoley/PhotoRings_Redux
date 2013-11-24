$(function() {
    var urlVars = location.search.substring(1).split('&');
    var data = {ring:urlVars[0].split('=')[1],
                user: getCookie('userId')};

    // Make the ringDisplay a square
//    $("#ringDisplay").height($("#ringDisplay").width());

//    console.log(data);

    $.ajax({
        type: "POST",
        url: "getRingData.php",
        data: data,
        dataType: 'json',
        success: function(data) {
            console.log("Success");
            console.log(data);
            console.log(data['members']);

            var members = data['members'];
            var otherFriends = data['otherFriends'];

            for (var i = 0; i < members.length; i++) {
                var memberItem = $("#memberTemplate").clone();
                memberItem.removeClass('template');
                memberItem.addClass('member-list-item');
                memberItem.attr('id', 'person_'+members[i]['id']);
                memberItem.find('i').attr('onclick', 'removeMember('+members[i]['id']+')');
                memberItem.find('p').append(members[i]['name']);
                $("#members").append(memberItem);
            }

            for (var i = 0; i < otherFriends.length; i++) {
                var otherFriendItem = $("#otherFriendTemplate").clone();
                otherFriendItem.removeClass('template');
                otherFriendItem.addClass('member-list-item');
                otherFriendItem.attr('id', 'person_'+otherFriends[i]['id']);
                otherFriendItem.find('p').append(otherFriends[i]['name']);
                $("#otherFriends").append(otherFriendItem);
            }

            $("#ringDisplay > p").text(members.length + " Members");

            var interval = 2 * Math.PI / members.length;
            var theta = Math.PI / 2;
            var halfWidth = $("#ringDisplay").width() / 2;
            var halfHeight = $("#ringDisplay").height() / 2;
            var imgRadius = 32;
            var paddingLeft = parseInt($("#ringDisplay").css('padding-left'));
            var paddingTop = parseInt($("#ringDisplay").css('padding-top'));

            // Trigonometric functions for the image locations where `radius` is the radius of the bounding box
            // top:     radius - (radius - imgRadius)sin(theta)
            // left:    radius + (radius - imgRadius)cos(theta)
            var animations = [];
            for (var i = 0; i < members.length; i++) {
                var topOffset = halfHeight - ((halfHeight - imgRadius) * Math.sin(theta)) + paddingTop;
                var leftOffset = halfWidth + ((halfWidth - imgRadius) * Math.cos(theta)) + (imgRadius - paddingLeft/2);
                theta += interval;
                var image = $("#ringImgTemplate").clone();
                image.removeClass("template");
                image.addClass("img-circle");
                image.attr("id", "profileImg_"+members[i]['id']);
                image.attr("src", members[i]['profile_image']);
                image.css('top', topOffset + 'px');
                image.css('left', leftOffset + 'px');
                $("#ringDisplay").append(image);
                image.hide();
                animations.push(image);
            }

            doQueuedAnimations(animations, 500 / animations.length);
        },
        error: function(data) {
            console.log("ERROR");
            console.log(data);
        }
    });

    $('#ringName').change(function() {
        $('#saveButton').addClass('btn-success').removeAttr('disabled');
    });
});

function doQueuedAnimations(animations, speed) {
    speed |= 0;   // Truncate all digits after the decimal point
    if (animations.length > 0) {
        animations.shift().fadeIn(speed, function() {
            doQueuedAnimations(animations, speed);
        });
    }
//    console.log(speed);
}

function getCookie(name) {
    var parts = document.cookie.split(name + '=');
    if (parts.length == 2) {
        return parts.pop().split(';').shift();
    }
    return false;
}

function removeMember(id) {
    console.log("Removing person " + id + ".");
}