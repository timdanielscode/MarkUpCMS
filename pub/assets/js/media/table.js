$(document).ready(function() {

    var searchValue = $('#searchValue').val();

    console.log(searchValue)
    
    $.ajax({
        type: "GET",
        url: "media/?search=" + searchValue,
        dataType: "html",
        success: function (data) {
           
            $('#mediaTableBody').html(data);
        }
    });
});

$(document).on('click', '.PAGE', function() {

    var searchValue = $('#searchValue').val();

    $.ajax({
        type: "GET",
        url: "media/?page=" + this.id + "&search=" + searchValue,
        dataType: "html",
        success: function (data) {
           
            $('#mediaTableBody').html(data);
        }
    });
});