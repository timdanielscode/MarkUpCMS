$(document).on('click', '.edit', function() {

    var modal = $('#modal');
    var id = $(this).data('id');
    var html = $('html');

    $(document).ready(function() {

        $.ajax({
            type: "GET",
            url: "categories/edit?id="+id,
            dataType: "html",
            success: function (data) {

                modal.addClass('display-block');

                modal.removeClass('modal-edit');
                modal.removeClass('modal-add');
                modal.addClass('modal-edit');

                $('#modalForm').html(data);
                html.addClass('dark-layer');
            }
        });
    });
});

$(document).ready(function() {
    $(document).on('click', '#EDITUPDATE', function() {

        var id = $('#ID').val();
        var title = $('#TITLE').val();
        var description = $('#DESCRIPTION').val();

        $.ajax({
                type: "POST",
                url: "categories/update",
                dataType: "json",
                data: {
                    id: id,
                    title: title,
                    description: description
            },
                success: function(data) {
                    $('#TABLE-TITLE-'+id).text(data.title);
                    $('.MESSAGE').html('<span>Updated successfully!</span>').fadeIn(10).fadeOut(2000);
                    $('.MESSAGE').addClass('message-success'); 
                    $('.MESSAGE').removeClass('message-failed');
            },
                error: function(xhr, status, error) {
                    $('.MESSAGE').html("<span class='message-failed-category-title'>Title can't be empty, must be unique, max 49 characters, no special characters!</span><span class='message-failed-category-description'>Description max 99 characters, no special characters!</span>").fadeIn(10);
                    $('.MESSAGE').addClass('message-failed'); 
                    $('.MESSAGE').removeClass('message-success');
            }
        });

    });
});