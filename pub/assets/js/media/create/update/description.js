$(document).ready(function() {
    $(document).on('click', 'a[data-role=update-description]', function() {

        var id = this.getAttribute('value');
        var description = $("#currentDescription").val();
        var selectedFile = document.querySelector('.selected');
        var message = $("#MESSAGE-DESCRIPTION"); 

            $.ajax({
                type: "POST",
                url: "/admin/media/update/description",
                dataType: "json",
                data: {
                    id: id,
                    description: description
            },

                success: function(data) {

                    if(selectedFile.classList.contains('iframeLayer') ) {

                        selectedFile.nextElementSibling.setAttribute('data-description', description);
                    } else {

                        selectedFile.setAttribute('data-description', description);
                    }

                    

                    message.html("<span>Updated successfully!</span>").fadeIn(10).fadeOut(2000);
                    message.addClass('message-success'); 
                    message.removeClass('message-failed');
            },
                error: function(xhr, status, error) {

                    message.html("<span>Description can't be empty, max 99 characters, no special characters!</span>").fadeIn(10);
                    message.addClass('message-failed'); 
                    message.removeClass('message-success');
            }
        });

    });
});