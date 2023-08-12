$(document).ready(function() {
    $(document).on('click', 'a[data-role=update]', function() {

        var id = this.getAttribute('value');

        console.log(id)


        var filename = $("#currentFilename").val();
        var folder = $("#currentFolder").data('folder');
        var message = $("#MESSAGE"); 
        var fileTextParts = document.getElementById('currentFolderFilename');
        var selectedFileContainer= document.querySelector('.selected');

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

                    fileTextParts.innerText = '/' + folder + '/' + filename;
                    selectedFileContainer.setAttribute('src', '/' + folder + '/' + filename);
                    selectedFileContainer.setAttribute('data-filename', filename);

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