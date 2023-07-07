$(document).on('click', '.edit', function() {

    var modal = $('#modal');
    modal.addClass('display-block'); 

    var id = $(this).data('id');
    var filename = $('.edit').val();
    var html = $('html');

    $(document).ready(function() {

        $.ajax({
            type: "GET",
            url: "categories/edit?id="+id,
            dataType: "html",
            success: function (data) {

                $('#mediaModelForm').html(data);
                html.addClass('dark-layer');
                $('#mediaModalTitle').focus();
            }
        });
    });
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