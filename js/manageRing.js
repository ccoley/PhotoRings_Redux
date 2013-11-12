$(function() {
    var data = {ring:1, user:13};

    $.ajax({
        type: "POST",
        url: "getRingData.php",
        data: data,
        dataType: 'json',
        success: function(data) {
            console.log("Success");
            var members = JSON.parse(data);
            
            /*
            for (i = 0; i < members.length; i++) {
                var item = $("#ringDisplay > .template").clone();
                item.removeClass("template");
                item.addClass("img-circle");
                item.attr("src", members[i]);
                item.offset({top:0, left:0});
                $("#ringDisplay").append(item);
                //item.hide();
                //item.fadeIn("slow");
            }*/
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
