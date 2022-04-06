<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>
<?php use parts\Pagination; ?>

<?php 
    $this->include('headerOpen');  
    $this->script('/assets/js/ajax.js');
    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="con">

    <?php if(parts\Session::exists("updated")) { ?>
        <div class="margin-t-50"><?php echo parts\Alert::display("success", "updated"); ?></div>
    <?php parts\Session::delete('updated');  } ?>

    <?php if(Session::exists("registered")) { ?>
        <div class="margin-t-50"><?php echo Alert::display("warning", "registered"); ?></div>
    <?php Session::delete('registered'); } ?>

    </div>
    <div class="row postHeaderContainer">
        <h1>Media</h1>
        <a class="button mediasButton margin-t-20" href="/admin/media/create">Add new</a>
    </div>
    <div class="postContainterCount">
        <span>All</span>
        <span>(<?php echo $count; ?>)</span>
        <div id="navbarSearch">
            <form action="" method="GET">
                <input type="text" name="search" placeholder="Search" id="search">
                <input type="hidden" name="submit" value="search">
            </form>
        </div>
    </div>

    <table class="tablePosts margin-y-20">
        <thead>
            <th>Title</th>
            <th>File</th>
            <th>Filename</th>
            <th>Type</th>
            <th>Size</th>
            <th>Date</th>
        </thead>
        <tbody id="mydata">
   
        </tbody>

    </table>
    
    <div id="modal" class="display-none">
        <div class="mediaModalFormContainer">
            <form id="mediaModelForm">
                
            </form>
        </div>
        <a href="#" id="updateMediaModal" class="button">Update</a>
        <a href="#" id="mediaModalClose" class="button">Exit</a>
    </div>




        <?php if(count($numberOfPages) > 1) { ?>
            <nav class="paginationPosts">
                <ul class="pagination">
                    <li class="page-item previous"><a href="/admin/medias?back=1">Previous</a></li>
                    <?php 
                        foreach($numberOfPages as $page) {
                            echo '<li class="page-item"><a href="/admin/medias?page='.$page.'">'.$page.'</a></li>';
                       }  
                    ?>
                    <li class="page-item next"><a href="/admin/medias?next=1">Next</a></li>
                </ul>
            </nav>
        <?php } ?>
        <script>

            $(document).on('click', '.mediaEdit', function() {

                var modal = $('#modal');
                modal.addClass('display-block'); 

                var id = $(this).data('id');
                var filename = $('.mediaEdit').val();

                $(document).ready(function() {

                    $.ajax({
                        type: "GET",
                        url: "media/media-modal-fetch?id="+id,
                        dataType: "html",
                        success: function (data) {

                            $('#mediaModelForm').html(data);
                        },

                        error: function(xhr, status, error) {
                            alert('oeps');
                        }

                    });
                });
            });
            
            $(document).on('click', '#mediaModalClose', function() {
                var modal = $('#modal');
                modal.removeClass('display-block'); 
            });


            
            $(document).ready(function() {
                $(document).on('click', '#updateMediaModal', function() {
                   
                    var id = $('#mediaModalId').val();
                    var mediaModalTitle = $('#mediaModalTitle').val();
                    var mediaModalDescription = $('#mediaModalDescription').val();

                    $.ajax({
                            type: "POST",
                            url: "media",
                            dataType: "json",
                            data: {
                                id: id,
                                title: mediaModalTitle,
                                description: mediaModalDescription
                        },
                            success: function(data) {
                            $('#mediaTitle-'+id).text(data.title);
                        },
                            error: function(xhr, status, error) {
                                alert('oeps');
                        }
                    });

                });
            });

            $(document).ready(function() {

                $.ajax({
                    type: "GET",
                    url: "media/fetch-data",
                    dataType: "html",
                    success: function (data) {
                       
                        $('#mydata').html(data);
                    }

                });
            });

            $(document).ready(function() {
                $(document).on('click', 'a[data-role=update]', function() {

                    var id = $(this).data('id');
                    var filename = $("#filename-"+id).val();
                    var message = $("#message-"+id); 

                        $.ajax({
                            type: "POST",
                            url: "media",
                            dataType: "json",
                            data: {
                                id: id,
                                filename: filename
                        },
                            success: function(data) {
                                
                            message.html("Updated successfully!");
                            message.addClass('message'); 
                        },
                            error: function(xhr, status, error) {

                            message.html("Oops, something went wrong!");
                            message.addClass('message'); 
                        }
                    });

                });
            });

        </script>
<?php 
    $this->include('footer');
?>