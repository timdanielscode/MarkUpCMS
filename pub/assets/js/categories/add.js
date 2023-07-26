$(document).on('click', '.add', function() {

    var modal = $('#modal');
    var id = $(this).data('id');
    var html = $('html');

    $(document).ready(function() {

        $.ajax({
            type: "GET",
            url: "categories/showaddable?id="+id,
            dataType: "html",
            success: function (data) {

                modal.addClass('display-block'); 

                modal.removeClass('modal-edit');
                modal.removeClass('modal-read');
                modal.addClass('modal-add'); 

                $('#modalForm').html(data);
                html.addClass('dark-layer');
            }
        });
    });
});

$(document).ready(function() {
    $(document).on('click', '.notAssingedPage', function() {

        this.classList.toggle('selectedPage')
    });
});

$(document).ready(function() {
    $(document).on('click', '.assingedPage', function() {

        this.classList.toggle('selectedPage')
    });
});

$(document).ready(function() {
    $(document).on('click', '.notAssingedSubCategory', function() {

        this.classList.toggle('selectedCategory')
    });
});

$(document).ready(function() {
    $(document).on('click', '.assingedSubCategory', function() {

        this.classList.toggle('selectedCategory')
    });
});

$(document).ready(function() {
    $(document).on('click', '#ASSIGNCATEGORY', function() {

        var categoryid = $('#CATEGORYID').val();
        var subcategoryid = [];
        var selectedOptionElements = $(".selectedCategory");
        var message = $("#CATEGORYMESSAGE");

        if(selectedOptionElements.length !== 0) {
            
            $(selectedOptionElements).each(function() {

                subcategoryid.push(this.value)
            });
        }

        $.ajax({
            type: "POST",
            url: "categories/addcategory",
            dataType: "json",
            data: {
                id: categoryid,
                subcategoryid: subcategoryid
        },
            success: function(data) {
    
                if(selectedOptionElements.length !== 0) {
            
                    $(selectedOptionElements).each(function() {

                        updateListedCategorySlug(this, this.value)
                        updateSubCategories(this);
                    });
                }

                message.html("<span>Updated successfully!</span>").fadeIn(10).fadeOut(2000);
                message.addClass('message-success'); 
                message.removeClass('message-failed');

            },error: function(xhr, status, error) {

                if(selectedOptionElements.length === 0) {

                    message.html("<span>Category is not selected!</span>").fadeIn(10);
                } else {
                    message.html("<span>Cannot apply if any page is assigned!</span>").fadeIn(10);
                }

                message.removeClass('message-success'); 
                message.addClass('message-failed');
            }
        });
    });
});


function updateListedCategorySlug(element, id) {

    var listedCategoryContainer = $('#SUBCATEGORYSLUGCONTAINER');
    var listedCategory = $('#LISTEDCATEGORY-'+id);

    if(element.parentNode.id === 'NOTASSINGEDSUBCATEGORYID') {

        var div = $('<div></div>').attr("id", "LISTEDCATEGORY-"+id).addClass('listedItem').text("/" + element.innerText);
        listedCategoryContainer.append(div);

    } else if(element.parentNode.id === 'ASSINGEDSUBCATEGORYID') {

        listedCategory.remove();
    }
}

function updateSubCategories(element) {

    var assingedCategorySubSelectElement = $('#ASSINGEDSUBCATEGORYID');
    var notAssingedCategorySubSelectElement = $('#NOTASSINGEDSUBCATEGORYID');

    element.classList.remove('selectedCategory');

    if(element.parentNode.id === 'NOTASSINGEDSUBCATEGORYID') {

        element.classList.remove('notAssingedSubCategory');
        element.classList.add('assingedSubCategory');

        assingedCategorySubSelectElement.append(element);

    } else if (element.parentNode.id === 'ASSINGEDSUBCATEGORYID') {

        element.classList.remove('assingedSubCategory');
        element.classList.add('notAssingedSubCategory');

        notAssingedCategorySubSelectElement.append(element);
    }
}

$(document).ready(function() {
    $(document).on('click', '#ASSIGNPAGES', function() {

        var categoryid = $('#CATEGORYID').val();
        var pageid = [];
        var selectedOptionElements = $(".selectedPage");
        var message = $("#PAGEMESSAGE");

        if(selectedOptionElements.length !== 0) {
            
            $(selectedOptionElements).each(function() {

                pageid.push(this.value)
            });
        }

        $.ajax({
                type: "POST",
                url: "categories/addpage",
                dataType: "json",
                data: {
                    id: categoryid,
                    pageid: pageid
            },
                success: function(data) {
        
                    if(selectedOptionElements.length !== 0) {
            
                        $(selectedOptionElements).each(function() {
            
                            updatePages(this);
                        });
                    }

                    message.html("<span>Updated successfully!</span>").fadeIn(10).fadeOut(2000);
                    message.addClass('message-success'); 
                    message.removeClass('message-failed');

                },error: function(xhr, status, error) {

                    message.html("<span>Page is not selected or page slug may not be unique after applying!</span>").fadeIn(10);
                    message.addClass('message-failed'); 
                    message.removeClass('message-success');
                }
            });
    });
});

function updatePages(element) {

    var assingedPageSelectElement = $('#ASSIGNEDPAGEID');
    var notAssingedPageSelectElement = $('#NOTASSIGNEDPAGEID');

    element.classList.remove('selectedPage');

    if(element.parentNode.id === 'NOTASSIGNEDPAGEID') {

        element.classList.remove('notAssingedPage');
        element.classList.add('assingedPage');

        assingedPageSelectElement.append(element);

    } else if (element.parentNode.id === 'ASSIGNEDPAGEID') {

        element.classList.remove('assingedPage');
        element.classList.add('notAssingedPage');

        notAssingedPageSelectElement.append(element);
    }
}