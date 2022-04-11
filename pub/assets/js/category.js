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

$(document).ready(function() {
    $(document).on('click', 'a[data-role=update]', function() {

        alert('hoi');

    });
});

$(document).on('click', '.categoryEdit', function() {

    var modal = $('#modal');
    modal.addClass('display-block'); 

    var id = $(this).data('id');
    var filename = $('.categoryEdit').val();
    var html = $('html');

    $(document).ready(function() {

        $.ajax({
            type: "GET",
            url: "categories/category-modal-fetch?id="+id,
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
       
        var id = $('#categoryModalId').val();
        var categoryModalTitle = $('#categoryModalTitle').val();
        var categoryModalDescription = $('#categoryModalDescription').val();

        $.ajax({
                type: "POST",
                url: "categories",
                dataType: "json",
                data: {
                    id: id,
                    title: categoryModalTitle,
                    description: categoryModalDescription
            },
                success: function(data) {
                    $('#categoryTitle-'+id).text(data.title);
                    $('.modalUpdateMessage').html('Updated successfully!').fadeIn(10).fadeOut(1000);
            },
                error: function(xhr, status, error) {
                    $('.modalUpdateMessage').html('Oops, something went wrong!').fadeIn(10).fadeOut(1000);
            }
        });

    });
});
