$(document).on('click', '.mediaEdit', function() {

    var modal = $('#modal');
    var id = $(this).data('id');
    var html = $('html');

    $(document).ready(function() {

        $.ajax({
            type: "GET",
            url: "media/edit?id="+id,
            dataType: "html",
            success: function (data) {

                modal.addClass('display-block'); 
                modal.addClass('modal-edit');
                $('#mediaModelForm').html(data);
                html.addClass('dark-layer');
                $('#mediaModalTitle').focus();
            }
        });
    });
});

$(document).ready(function() {
    $(document).on('click', '#updateMediaModal', function() {
       
        var id = $('#mediaModalId').val();
        var mediaModalTitle = $('#mediaModalTitle').val();
        var mediaModalDescription = $('#mediaModalDescription').val();

        $.ajax({
                type: "POST",
                url: "media/update",
                dataType: "json",
                data: {
                    id: id,
                    title: mediaModalTitle,
                    description: mediaModalDescription
            },
                success: function(data) {
                    $('#mediaTitle-'+id).text(data.title);
                    $('.modalUpdateMessage').html('<span>Updated successfully!</span>').fadeIn(10).fadeOut(2000);
                    $('.modalUpdateMessage').addClass('message-success'); 
                    $('.modalUpdateMessage').removeClass('message-failed');

            },
                error: function(xhr, status, error) {
                    $('.modalUpdateMessage').html("<span class='message-failed-media-title'>Title can't be empty, max 49 characters, no special characters!</span><span class='message-failed-media-description'>Description max 99 characters, no special characters!</span>").fadeIn(10);
                    $('.modalUpdateMessage').addClass('message-failed'); 
                    $('.modalUpdateMessage').removeClass('message-success');
            }
        });

    });
});

$(document).ready(function() {
    $(document).on('click', 'a[data-role=update]', function() {

        var id = $(this).data('id');
        var filename = $("#filename-"+id).val();
        var folder = $(this).data('folder');
        var message = $("#message-"+id); 
        var mediaPath = $('#mediaPath-'+id)[0];
        var mediaPathParts = mediaPath.innerText.split('/');

            $.ajax({
                type: "POST",
                url: "media/update-filename",
                dataType: "json",
                data: {
                    id: id,
                    filename: filename,
                    folder: folder
            },

                success: function(data) {
                    
                    mediaPathParts[4] = filename;
                    mediaPath.innerText = mediaPathParts.join('/');

                    message.html("<span>Updated successfully!</span>").fadeIn(10).fadeOut(2000);
                    message.addClass('message-success'); 
                    message.removeClass('message-failed');
            },
                error: function(xhr, status, error) {

                    message.html("<span>Title can't be empty, must be unique, max 49 characters, no special characters!</span>").fadeIn(10);
                    message.addClass('message-failed'); 
                    message.removeClass('message-success');
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