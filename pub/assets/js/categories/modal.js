$(document).on('click', '#BACK', function() {
    
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
            '<input type="text" name="title" id="TITLE" placeholder="Title" autofocus>' +
            '<label>Description: </label>' +
            '<textarea type="text" name="description" id="DESCRIPTION" placeholder="Description"></textarea>' +
            '<input type="submit" name="submit" value="Store"/>' +
        '</form>' + 
        '<button id="BACK">Close</button>'
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
            '<input type="text" name="title" id="TITLE" placeholder="Title" value="' + $(this).data("title") + '" autofocus' +
            '<label>Description: </label>' +
            '<textarea type="text" name="description" id="DESCRIPTION" placeholder="Description">' + $(this).data("description") + '</textarea>' +
            '<input type="submit" name="submit" value="Update"/>' +
            '<input type="hidden" name="id" value="' + $(this).data("id") + '"/>' +
        '</form>' + 
        '<button id="BACK">Close</button>'
    );

    modalForm.append(form);
});