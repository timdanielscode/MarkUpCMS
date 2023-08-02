$(document).on('click', '.mediaPreview', function() {
    
    var id = $(this).data('id');
    var html = $('html');

    $(document).ready(function() {

        $.ajax({
            type: "GET",
            url: "media/read?id="+id,
            dataType: "html",
            success: function (data) {
                
                html.addClass('dark-layer');
                $('#mediaPreview').html(data);
                $('#mediaPreviewFile').removeClass('display-none');
            }
        });
    });

});

$(document).on('click', '#mediaPreviewFile', function() {
    var html = $('html');
    html.removeClass('dark-layer');
    $('#mediaPreview').html("");
});