$(document).ready(function() {

    $.ajax({
        type: "GET",
        url: "categories/",
        dataType: "html",
        success: function (data) {
           
            $('#categoryTableBody').html(data);
        }
    });

});

$(document).on('click', '.PAGE', function() {

    $.ajax({
        type: "GET",
        url: "categories/?page=" + this.id,
        dataType: "html",
        success: function (data) {
           
            $('#categoryTableBody').html(data);
        }
    });
});