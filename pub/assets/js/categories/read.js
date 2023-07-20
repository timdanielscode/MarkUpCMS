$(document).on('click', '.read', function() {

    var modal = $('#modal');
    modal.addClass('display-block'); 
    modal.removeClass('modal-edit');
    modal.removeClass('modal-add');
    modal.addClass('modal-read');
    
    var id = $(this).data('id');
    var html = $('html');

    $(document).ready(function() {

        $.ajax({
            type: "GET",
            url: "categories/read?id="+id,
            dataType: "html",
            success: function (data) {

                $('#modalForm').html(data);
                html.addClass('dark-layer');
            }
        });
    });
});