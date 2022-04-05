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
                <tr>
                    <th>Title</th>
                    <th>File</th>
                    <th>Filename</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th class="width-10">Date</th>
                </tr>
            </thead>
            <tbody>
                
                

            <?php foreach($allMedia as $media) { ?>
                
                    <tr id="<?php echo $media['id']; ?>">
                        <?php if($media["media_title"] !== "not found") {?>
                        <td class="width-30">
                            <a href="/admin/media/<?php echo $media['id']; ?>/edit" class="font-weight-500"><?php echo $media['media_title']; ?></a> |
                            <a href="/admin/media/<?php echo $media['id']; ?>/edit" class="font-weight-300">Edit</a> |
                            <a href="/admin/media/<?php echo $media['id']; ?>/preview" class="font-weight-300">Preview</a> |
                            <a href="/admin/media/<?php echo $media['id']; ?>/delete" class="font-weight-300 color-red">Remove</a>
                            
                        </td>
                        <?php } else { ?>
                        <td>
                            <span class="font-weight-500"><?php echo $media['media_title']; ?></span>
                        </td>
                        <?php } ?>
                        <td class="width-10">
                            <?php $type = $media['media_filetype']; 
                            if($type == 'image/png' || $type  == 'image/webp' || $type  == 'image/gif' || $type  == 'image/jpeg' || $type  == 'image/svg+xml') { ?>
                            <?php echo '<img src="/website/assets/img/'. $media['media_filename'] .'" id="imageSmall">'; ?>
                            <?php } else if ($type == 'application/pdf') {     
                                echo '<iframe src="/website/assets/application/'. $media['media_filename'] .'" id="pdfSmall"></iframe>';
                            } else if ($type == 'video/mp4' || $type == 'video/quicktime') {
                                echo '<video src="/website/assets/video/'. $media['media_filename'] .'" id="imageSmall"></video>';
                            }
                            ?>
                        </td>
                        <td class="width-15">
                            <span class="font-weight-400">
                                <?php 
                                    $type = $media['media_filetype']; 
                                    if($type == 'image/png' || $type  == 'image/webp' || $type  == 'image/gif' || $type  == 'image/jpeg' || $type  == 'image/svg+xml') { 
                                        echo '/website/assets/img/';
                                    } else if($type == 'video/mp4' || $type == 'video/quicktime') {
                                        echo '/website/assets/video/'; 
                                    } else if($type == 'application/pdf') {
                                        echo '/website/assets/application/'; 
                                    }
                                ?>
                            </span><br>
                            <span class="font-weight-500">
                                <!--<form>-->
                                <!--<input name="filename" id="filename" type="text" value="<?php //echo $media['media_filename']; ?>"/>-->
                                
                                <!--<a data-role="update" data-id="<?php //echo $media['id']; ?>">update</a>-->
                                <!--<button type="button" id="update" name="submit" data-id="<?php //echo $media['id']; ?>">update</button>-->
                                <!--<input type="hidden" name="token" value="<?php //Csrf::token('add');?>" />-->
                                <!--<input id="id" value="<?php //echo $media['id']; ?>">-->
                                <!--<div class="myClass"></div>-->
                                <!--<input class="filename_id" type="text" value="<?php //echo $media['id']; ?>"/>-->
                                
                                <!--</form>-->
                            </span>
                        </td>
                        <td class="width-10">
                            <span class="font-weight-500"><?php echo $media['media_filetype']; ?></span>
                        </td>
                        <td class="width-10">
                            <span class="font-weight-400">
                                <?php 
                                    $filesize = $media['media_filesize'] / 1000000;
                                    $filesize = number_format((float)$filesize, 2, '.', '');
                                    echo $filesize;
                                ?>
                            </span>
                            <span class="font-weight-500"> 
                                mb
                            </span>
                        </td>
                        <td class="width-15">
                            <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $media["date_created_at"] . " " . $media["time_created_at"]; ?></span><br>
                            <span>Updated:</span> <span class="font-weight-300"><?php echo $media["date_updated_at"] . " " . $media["time_updated_at"]; ?></span>
                        </td>
                    </tr>
                <?php } ?>




            </tbody>
        </table>


        <table class="tablePosts margin-y-20">
            <thead>
                <th>id</th>
                <th>filename</th>
            </thead>
            <tbody id="mydata">
   
            </tbody>


        </table>
        




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
            $(document).ready(function() {

                $.ajax({
                    type: "GET",
                    url: "media/fetch-data",
                    //dataType: "json",
                    dataType: "html",
                    success: function (data) {
                       
                        $('#mydata').html(data);
                    }

                })
            })

            $(document).ready(function() {
                $(document).on('click', 'a[data-role=update]', function() {

                    var id = $(this).data('id');
                    var filename = $("#filename-"+id).val();
 
                        $.ajax({
                            type: "POST",
                            url: "media",
                            dataType: "json",
                            data: {
                                id: id,
                                filename: filename
                        },
                            success: function(data) {
                        },
                            error: function(xhr, status, error) {
                                alert("Something went wrong!");
                        }
                    });

                });
            });

        </script>
<?php 
    $this->include('footer');
?>