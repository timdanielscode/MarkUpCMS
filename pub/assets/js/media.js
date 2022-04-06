$(document).on('click', '.mediaEdit', function() {

    var modal = $('#modal');
    modal.addClass('display-block'); 

    var id = $(this).data('id');
    var filename = $('.mediaEdit').val();
    var html = $('html');

    $(document).ready(function() {

        $.ajax({
            type: "GET",
            url: "media/media-modal-fetch?id="+id,
            dataType: "html",
            success: function (data) {

                $('#mediaModelForm').html(data);
                html.addClass('dark-layer');
                $('#mediaModalTitle').focus();
            }
        });
    });
});

$(document).on('click', '#mediaModalClose', function() {
    var modal = $('#modal');
    modal.removeClass('display-block'); 
    var html = $('html');
    html.removeClass('dark-layer');
});

$(document).ready(function() {
    $(document).on('click', '#updateMediaModal', function() {
       
        var id = $('#mediaModalId').val();
        var mediaModalTitle = $('#mediaModalTitle').val();
        var mediaModalDescription = $('#mediaModalDescription').val();

        $.ajax({
                type: "POST",
                url: "media",
                dataType: "json",
                data: {
                    id: id,
                    title: mediaModalTitle,
                    description: mediaModalDescription
            },
                success: function(data) {
                    $('#mediaTitle-'+id).text(data.title);
                    $('.modalUpdateMessage').html('Updated successfully!').fadeIn(10).fadeOut(1000);
            },
                error: function(xhr, status, error) {
                    $('.modalUpdateMessage').html('Oops, something went wrong!').fadeIn(10).fadeOut(1000);
            }
        });

    });
});

$(document).ready(function() {

    $.ajax({
        type: "GET",
        url: "media/fetch-data",
        dataType: "html",
        success: function (data) {
           
            $('#mydata').html(data);
        }
    });
});

$(document).ready(function() {
    $(document).on('click', 'a[data-role=update]', function() {

        var id = $(this).data('id');
        var filename = $("#filename-"+id).val();
        var message = $("#message-"+id); 

            $.ajax({
                type: "POST",
                url: "media",
                dataType: "json",
                data: {
                    id: id,
                    filename: filename
            },
                success: function(data) {
                    
                message.html("Updated successfully!").fadeIn(10).fadeOut(1000);
                message.addClass('message'); 
            },
                error: function(xhr, status, error) {

                message.html("Oops, something went wrong!").fadeIn(10).fadeOut(1000);
                message.addClass('message'); 
            }
        });

    });
});