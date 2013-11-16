$(function() {
    var urlVars = location.search.substring(1).split('&');
    var data = {ring:urlVars[0].split('=')[1],
                user:13};

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
            console.log(data[2]);
//            var members = $.parseJSON(data);
//            console.log(members);

            $("#ringDisplay > p").text(data.length + " Members");

            var interval = 2 * Math.PI / data.length;
            var theta = Math.PI / 2;
            var halfWidth = $("#ringDisplay").width() / 2;
            var halfHeight = $("#ringDisplay").height() / 2;
            var imgRadius = 32;
            var paddingLeft = parseInt($("#ringDisplay").css('padding-left'));
            var paddingTop = parseInt($("#ringDisplay").css('padding-top'));
//            var paddingLeft = 0;
//            var paddingTop = 0;

            /*
             * Trigonometric functions for the image locations where `radius` is the radius of the bounding box
             *
             * top:     radius - (radius - imgRadius)sin(theta)
             * left:    radius + (radius - imgRadius)cos(theta)
             */

            var animations = new Array();
            for (var i = 0; i < data.length; i++) {
                var topOffset = (halfHeight - ((halfHeight - imgRadius) * Math.sin(theta)) + paddingTop);
                var leftOffset = halfWidth + ((halfWidth - imgRadius) * Math.cos(theta)) + paddingLeft;
                theta += interval;
                var item = $("#ringDisplay > img.template").clone();
                item.removeClass("template");
                item.addClass("img-circle");
                item.attr("id", "profileImg_"+data[i]['id']);
                item.attr("src", data[i]['image']);
                item.css('top', topOffset.toFixed(2) + 'px');
                item.css('left', leftOffset.toFixed(2) + 'px');
//                item.offset({top:topOffset, left:leftOffset});
                $("#ringDisplay").append(item);
                item.hide();
//                item.delay(i * 50).show();
//                item.delay(i * 50).fadeIn("fast");
                animations.push(item);
            }

            doQueuedAnimations(animations, 500 / animations.length);
        },
        error: function(data) {
            console.log("ERROR");
            console.log(data);
        }
    });

/*
    $.post("getRingData.php", data, function(data) {
        console.log("success");
        var members = JSON.parse(data);
        var y = 0;
        var x = 0;
        // foreach member in members
       for (i = 0; i < members.length; i++) {
           var item = $("#ringDisplay > .template").clone();
           item.removeClass("template");
           item.addClass("img-circle");
           item.attr("src", members[i])
           item.offset({top:y, left:x});
           $("#ringDisplay").append(item);
           //item.hide();
           //item.fadeIn("slow");
       }
    });
*/
});

function doQueuedAnimations(animations, speed) {
    speed = speed>>0;
    if (animations.length > 0) {
        animations.shift().fadeIn(speed, function() {
            doQueuedAnimations(animations, speed);
        });
    }
    console.log(speed);
}
