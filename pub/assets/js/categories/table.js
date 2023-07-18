$(document).ready(function() {

    var searchValue = $('#searchValue').val();
    
    $.ajax({
        type: "GET",
        url: "categories/?search=" + searchValue,
        dataType: "html",
        success: function (data) {
           
            $('#categoryTableBody').html(data);
        }
    });

});

$(document).on('click', '.PAGE', function() {

    var searchValue = $('#searchValue').val();

    $.ajax({
        type: "GET",
        url: "categories/?page=" + this.id + "&search=" + searchValue,
        dataType: "html",
        success: function (data) {
           
            $('#categoryTableBody').html(data);
        }
    });
});