$(document).ready(function() {
    $(document).on('click', 'a[data-role=update]', function() {

        var id = $(this).data('id');
        var slug = $("#slug-"+id).val();
        var message = $("#message-"+id); 

            $.ajax({
                type: "POST",
                url: "categories/slug",
                dataType: "json",
                data: {
                    id: id,
                    slug: slug
            },
                success: function(data) {
                    
                    message.html("<span>Updated successfully!</span>").fadeIn(10).fadeOut(2000);
                    message.addClass('message-success'); 
                    message.removeClass('message-failed');
            },
                error: function(xhr, status, error) {

                    message.html("<span>Slug can't be empty, max 49 characters, no special characters, page slug must be unique after update!</span>").fadeIn(10);
                    message.addClass('message-failed'); 
                    message.removeClass('message-success');
            }
        });

    });
});