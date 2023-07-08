$(document).on('click', '.add', function() {

    var modal = $('#modal');
    modal.addClass('display-block'); 

    var id = $(this).data('id');
    var html = $('html');

    var assingedCategory = $('#ASSIGNEDCATEGORY');
    console.log(assingedCategory)

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

        this.classList.toggle('selected')
    });
});

$(document).ready(function() {
    $(document).on('click', '.assingedPage', function() {

        this.classList.toggle('selected')
    });
});

$(document).ready(function() {
    $(document).on('click', '#ASSIGNCATEGORY', function() {


        var id = $('#CATEGORYID').val();
        var categoryId = $('#CATEGORIES').val()[0];


        console.log(id)
        console.log(categoryId)


        $.ajax({
            type: "POST",
            url: "categories/assigncategory",
            dataType: "json",
            data: {
                id: id,
                categoryId: categoryId
        },
            success: function(data) {
    
                console.log('success')
                //$('.MESSAGE').html('Updated successfully!').fadeIn(10).fadeOut(1000);
        },
            error: function(xhr, status, error) {

                console.log('failed')

                //$('.MESSAGE').html('Oops, something went wrong!').fadeIn(10).fadeOut(1000);
        }
    });


    });
});

$(document).ready(function() {
    $(document).on('click', '#ASSIGNPAGES', function() {

        var categoryid = $('#CATEGORYID').val();
        var pageid = [];

        var selectedOptionElements = $(".selected");
        var assingedPageSelectElement = $('#ASSIGNEDPAGEID');
        var notAssingedPageSelectElement = $('#NOTASSIGNEDPAGEID');
        
        if(selectedOptionElements.length !== 0) {
            
            $(selectedOptionElements).each(function() {

                pageid.push(this.value)
            });
        }

        $.ajax({
                type: "POST",
                url: "categories/add",
                dataType: "json",
                data: {
                    id: categoryid,
                    pageid: pageid
            },
                success: function(data) {
        
                    if(selectedOptionElements.length !== 0) {
            
                        $(selectedOptionElements).each(function() {
            
                            this.classList.remove('selected');

                            if(this.parentNode.id === 'NOTASSIGNEDPAGEID') {
            
                                this.classList.remove('notAssingedPage');
                                this.classList.add('assingedPage');

                                assingedPageSelectElement.append(this);

                            } else if (this.parentNode.id === 'ASSIGNEDPAGEID') {

                                this.classList.remove('assingedPage');
                                this.classList.add('notAssingedPage');

                                notAssingedPageSelectElement.append(this);
                            }
                        });
                    }

                    //$('.MESSAGE').html('Updated successfully!').fadeIn(10).fadeOut(1000);
            },
                error: function(xhr, status, error) {

                    console.log('failed123')

                    //$('.MESSAGE').html('Oops, something went wrong!').fadeIn(10).fadeOut(1000);
            }
        });
    });
});