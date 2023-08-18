$(document).ready(function() {
    $(document).on('click', 'a[data-role=update-description]', function() {

        var id = this.getAttribute('value');
        var description = $("#description-"+id).val();
        var message = $("#MESSAGE-DESCRIPTION-"+id); 

            $.ajax({
                type: "POST",
                url: "/admin/media/create/update-description",
                dataType: "json",
                data: {
                    id: id,
                    description: description,
            },

                success: function(data) {

                    message.html("<span>Updated successfully!</span>").fadeIn(10).fadeOut(2000);
                    message.addClass('message-success'); 
                    message.removeClass('message-failed');
            },
                error: function(xhr, status, error) {

                    console.log('failed')

                    message.html("<span>Description can't be empty, max 99 characters, no special characters!</span>").fadeIn(10);
                    message.addClass('message-failed'); 
                    message.removeClass('message-success');
            }
        });

    });
});