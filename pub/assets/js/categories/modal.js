$(document).on('click', '#CLOSE', function() {
    
    var modal = $('#modal');
    modal.addClass('display-none'); 
    modal.removeClass('display-block'); 
    var html = $('html');
    html.removeClass('dark-layer');
});

$(document).on('click', '#create', function() {

    $('#modalForm').empty();
    var modal = $('#modal');
    modal.removeClass('modal-add');
    modal.addClass('modal-create');
    var modalForm = $('#modalForm');
    modal.removeClass('display-none'); 
    var html = $('html');
    html.addClass('dark-layer');
    
    var form = $(
        '<form action="/admin/categories/store" method="POST">' + 
            '<label>Title: </label>' +
            '<input type="text" name="title" placeholder="Title" autofocus>' +
            '<label>Description: </label>' +
            '<textarea type="text" name="description" placeholder="Description"></textarea>' +
            '<div class="buttonContainer margin-t-20">' +
                '<input type="submit" name="submit" class="button greenButton margin-t-20 margin-r-20" value="Store"/>' +
                '<a id="CLOSE" class="button blueButton margin-t-20 box-sizing-border-box">Close</a>' +
            '</div>' +
        '</form>'
    );

    modalForm.append(form);
});

$(document).on('click', '.edit', function() {

    $('#modalForm').empty();
    var modal = $('#modal');
    modal.removeClass('modal-add');
    modal.addClass('modal-edit');
    var modalForm = $('#modalForm');
    modal.removeClass('display-none'); 
    var html = $('html');
    html.addClass('dark-layer');
    
    var form = $(
        '<form action="/admin/categories/update" method="POST">' + 
            '<label>Title: </label>' +
            '<input type="text" name="title" placeholder="Title" value="' + $(this).data("title") + '" autofocus>' +
            '<label>Description: </label>' +
            '<textarea type="text" name="description" placeholder="Description">' + $(this).data("description") + '</textarea>' +
            '<div class="buttonContainer margin-t-20">' +
                '<input type="submit" name="submit" value="Update" class="button greenButton margin-t-20 margin-r-20"/>' +
                '<input type="hidden" name="id" value="' + $(this).data("id") + '"/>' +
                '<a id="CLOSE" class="button blueButton margin-t-20 box-sizing-border-box">Close</a>' +
            '</div>' +
        '</form>'
    );

    modalForm.append(form);
});