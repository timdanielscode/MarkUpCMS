$(document).ready(function() {
    $(document).on('click', 'a[data-role=update]', function() {

        var id = this.getAttribute('value');
        var filename = $("#filename-"+id).val();
        var folder = $(this).data('folder');
        var message = $("#MESSAGE-"+id); 
        var mediaPath = $("#mediaPath-"+id)[0];

        console.log(mediaPath)

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

                    mediaPath.innerText = "/" + folder + "/" + filename;

                    message.html("<span>Updated successfully!</span>").fadeIn(10).fadeOut(2000);
                    message.addClass('message-success'); 
                    message.removeClass('message-failed');
            },
                error: function(xhr, status, error) {

                    message.html("<span>Filename can't be empty, must be unique, max 49 characters, no special characters!</span>").fadeIn(10);
                    message.addClass('message-failed'); 
                    message.removeClass('message-success');
            }
        });

    });
});