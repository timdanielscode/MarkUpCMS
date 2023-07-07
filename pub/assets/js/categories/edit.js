$(document).on('click', '.edit', function() {

    var modal = $('#modal');
    modal.addClass('display-block');

    var id = $(this).data('id');
    var html = $('html');

    $(document).ready(function() {

        $.ajax({
            type: "GET",
            url: "categories/edit?id="+id,
            dataType: "html",
            success: function (data) {

                $('#modalForm').html(data);
                html.addClass('dark-layer');
            }
        });
    });
});

$(document).ready(function() {
    $(document).on('click', '#UPDATE', function() {

        var id = $('#ID').val();
        var title = $('#TITLE').val();
        var description = $('#DESCRIPTION').val();

        $.ajax({
                type: "POST",
                url: "categories",
                dataType: "json",
                data: {
                    id: id,
                    title: title,
                    description: description
            },
                success: function(data) {
                    $('#TABLE-TITLE-'+id).text(data.title);
                    $('.MESSAGE').html('Updated successfully!').fadeIn(10).fadeOut(1000);
            },
                error: function(xhr, status, error) {
                    $('.MESSAGE').html('Oops, something went wrong!').fadeIn(10).fadeOut(1000);
            }
        });

    });
});