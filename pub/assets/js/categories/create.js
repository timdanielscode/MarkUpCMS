$(document).on('click', '.create', function() {

    var modal = $('#modal');
    var html = $('html');

    $(document).ready(function() {

        $.ajax({
            type: "GET",
            url: "categories/create",
            dataType: "html",
            success: function (data) {

                modal.addClass('display-block'); 
                modal.removeClass('modal-edit');
                modal.removeClass('modal-add');
                modal.removeClass('modal-read');
                modal.addClass('modal-create');

                $('#modalForm').html(data);
                html.addClass('dark-layer');
            }
        });
    });
});

$(document).ready(function() {
    $(document).on('click', '#CREATESTORE', function() {

        var title = $('#TITLE').val();
        var description = $('#DESCRIPTION').val();

        $.ajax({
                type: "POST",
                url: "categories/store",
                dataType: "json",
                data: {
                    title: title,
                    description: description
            },
                success: function(data) {
 
                    $('.MESSAGE').html('<span>Created successfully!</span>').fadeIn(10).fadeOut(2000);
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