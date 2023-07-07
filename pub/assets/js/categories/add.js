$(document).on('click', '.add', function() {

    var modal = $('#modal');
    modal.addClass('display-block'); 

    var id = $(this).data('id');
    var html = $('html');

    $(document).ready(function() {

        $.ajax({
            type: "GET",
            url: "categories/showaddable?id="+id,
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

        var categoryid = $('#CATEGORYID').val();
        var notassignedpageid = $('#NOTASSIGNEDPAGEID').val();
        
        
        console.log(typeof notassignedpageid)


        $.ajax({
                type: "POST",
                url: "categories/add",
                dataType: "json",
                data: {
                    id: categoryid,
                    pageid: notassignedpageid
            },
                success: function(data) {
                    console.log('success')
                    //$('#TABLE-TITLE-'+id).text(data.title);
                    //$('.MESSAGE').html('Updated successfully!').fadeIn(10).fadeOut(1000);
            },
                error: function(xhr, status, error) {

                    console.log('error')
                    //$('.MESSAGE').html('Oops, something went wrong!').fadeIn(10).fadeOut(1000);
            }
        });
    });
});