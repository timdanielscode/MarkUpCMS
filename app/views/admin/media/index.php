<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>
<?php use parts\Pagination; ?>

<?php 
    $this->include('headerOpen');  
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
                    <th>Author</th>
                    <th>Meta</th>
                    <th class="width-10">Date</th>
                </tr>
            </thead>
            <tbody>
                
                <?php foreach($allMedia as $media) { ?>
                    <tr>
                        <?php if($media["media_title"] !== "not found") {?>
                        <td class="width-50">
                            <a href="/admin/media/<?php echo $media['id']; ?>/edit" class="font-weight-500"><?php echo $media['media_title']; ?></a> |
                            <a href="/admin/media/<?php echo $media['id']; ?>/edit" class="font-weight-300">Edit</a> |
                            <a href="/admin/media/<?php echo $media['id']; ?>/preview" class="font-weight-300">Preview</a> |
                            <a href="/admin/media/<?php echo $media['id']; ?>/delete" class="font-weight-300 color-red">Remove</a>
                        </td>
                        <?php } else { ?>
                        <td class="width-50">
                            <span class="font-weight-500"><?php echo $media['media_title']; ?></span>
                        </td>
                        <?php } ?>
                        <td class="width-15">
                            <a href="/admin/media/<?php echo $media['id']; ?>/meta/edit" class="font-weight-300">Edit</a> 
                            <span class="display-block padding-y-2">Status: </span><?php if(!empty($media['metaTitle']) ) { echo '<span class="font-weight-300">ok</span>'; } else {echo '<span class="font-weight-300">-</span>'; } ?>
                        </td>
                        <td class="width-15">
                            <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $media["date_created_at"] . " " . $media["time_created_at"]; ?></span><br>
                            <span>Updated:</span> <span class="font-weight-300"><?php echo $media["date_updated_at"] . " " . $media["time_updated_at"]; ?></span>
                        </td>
                    </tr>
                <?php } ?>
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

<?php 
    $this->include('footer');
?>