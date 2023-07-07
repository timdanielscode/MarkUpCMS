$(document).on('click', '.add', function() {

    var modal = $('#modal');
    modal.addClass('display-block'); 
    var id = $(this).data('id');
    var html = $('html');

    $(document).ready(function() {

        $.ajax({
            type: "GET",
            url: "categories/add?id="+id,
            dataType: "html",
            success: function (data) {

                $('#mediaModelForm').html(data);
                html.addClass('dark-layer');
                $('#mediaModalTitle').focus();
            }
        });
    });
});