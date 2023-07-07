$(document).ready(function() {

    $.ajax({
        type: "GET",
        url: "categories/table",
        dataType: "html",
        success: function (data) {
           
            $('#categoryTableBody').html(data);
        }
    });
});