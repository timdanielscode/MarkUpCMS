$(document).ready(function() {

    $.ajax({
        type: "GET",
        url: "categories/fetch-table",
        dataType: "html",
        success: function (data) {
           
            $('#categoryTableBody').html(data);
        }
    });
});

$(document).ready(function() {
    $(document).on('click', 'a[data-role=update]', function() {

        var id = $(this).data('id');
        var slug = $("#slug-"+id).val();
        var message = $("#message-"+id); 

            $.ajax({
                type: "POST",
                url: "categories",
                dataType: "json",
                data: {
                    id: id,
                    slug: slug
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