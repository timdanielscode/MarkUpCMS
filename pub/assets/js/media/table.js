$(document).ready(function() {

    $.ajax({
        type: "GET",
        url: "media/",
        dataType: "html",
        success: function (data) {
           
            $('#mydata').html(data);
        }
    });
});