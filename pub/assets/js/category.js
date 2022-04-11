$(document).ready(function() {

    $.ajax({
        type: "GET",
        url: "categories/fetch-table",
        dataType: "html",
        success: function (data) {
           
            $('#categoryTableBody').html(data);
        }
    });
});
