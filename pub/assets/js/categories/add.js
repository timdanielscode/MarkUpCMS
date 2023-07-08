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

        if(this.classList.add('notAssingedPageSelected') ) {

            this.classList.add('notAssingedPageSelected');
        } else {
            this.classList.add('notAssingedPageSelected');
        }
    });
});

$(document).ready(function() {
    $(document).on('click', '.assingedPage', function() {

        if(this.classList.add('assingedPageSelected') ) {

            this.classList.add('assingedPageSelected');
        } else {
            this.classList.add('assingedPageSelected');
        }
    });
});


$(document).ready(function() {
    $(document).on('click', '#UPDATE', function() {

        var categoryid = $('#CATEGORYID').val();

        var pageid = [];

        var notAssingedPageSelected = $(".notAssingedPageSelected");
        var assingedPageSelected = $(".assingedPageSelected");

        var type = "";


        if(notAssingedPageSelected.length !== 0) {

            $(notAssingedPageSelected).each(function() {

            
                type = "NOTASSIGNED";
                pageid.push(this.value)
    
            });
        }

        if(assingedPageSelected.length !== 0) {

            $(assingedPageSelected).each(function() {

            
                type = "ASSIGNED";
                pageid.push(this.value)
    
            });
        }

        var assingedPageSelectElement = $('#ASSIGNEDPAGEID');
        var notAssingedPageSelectElement = $('#NOTASSIGNEDPAGEID');



        $.ajax({
                type: "POST",
                url: "categories/add",
                dataType: "json",
                data: {
                    id: categoryid,
                    pageid: pageid
            },
                success: function(data) {
        

                    if(type === "NOTASSIGNED") {



                        console.log('notAssinged')

                        $(notAssingedPageSelected).each(function() {



                            assingedPageSelectElement.append(this);

                            this.classList.add('assingedPage');
                            this.classList.remove('notAssingedPage');
                            this.classList.remove('notAssingedPageSelected');
                        });

                        
                    } else {

                        console.log('assinged')

                        $(assingedPageSelected).each(function() {



                            notAssingedPageSelectElement.append(this);

                            this.classList.remove('assingedPageSelected');
                            this.classList.remove('assingedPage');
                            this.classList.add('notAssingedPage');
                        });

                    }

                    //$('.MESSAGE').html('Updated successfully!').fadeIn(10).fadeOut(1000);
            },
                error: function(xhr, status, error) {

                    console.log('failed')

                    //$('.MESSAGE').html('Oops, something went wrong!').fadeIn(10).fadeOut(1000);
            }
        });
    });
});