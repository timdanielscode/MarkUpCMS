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

        if(selectedOptionElements.length !== 0) {
            
            $(selectedOptionElements).each(function() {

                updateListedCategorySlug(this, this.value)
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
        
                        updateSubCategories(this);
                    });
                }
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
                },error: function(xhr, status, error) {

                    message.html("Page is already assinged!").fadeIn(10).fadeOut(1000);
                    message.addClass('message');
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