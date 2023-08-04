$(document).ready(function() {
    $(document).on('click', 'a[data-role=update]', function() {

        var id = $(this).data('id');
        var filename = $("#currentFilename").val();
        var folder = $("#currentFolder").data('folder');
        var message = $("#MESSAGE"); 

            $.ajax({
                type: "POST",
                url: "/admin/media/create/update-filename",
                dataType: "json",
                data: {
                    id: id,
                    filename: filename,
                    folder: folder
            },

                success: function(data) {

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