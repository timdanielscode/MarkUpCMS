<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="con">
<div class="row postHeaderContainer">
        <h1>Assets</h1>
        <a class="button postsButton margin-t-20" href="/admin/posts/create">Add new</a>
    </div>
    <div class="postContainterCount">
        <span>All</span>
        <span>(<?php //echo $count; ?>)</span>
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
                
                <?php foreach($assets as $asset) { ?>
                    <tr>
                        <?php if($post["title"] !== "not found") {?>
                        <td class="width-50">
                            <a href="/admin/posts/<?php echo $post['id']; ?>/edit" class="font-weight-500"><?php echo $post['title']; ?></a> |
                            <a href="/admin/posts/<?php echo $post['id']; ?>/edit" class="font-weight-300">Edit</a> |
                            <a href="/admin/posts/<?php echo $post['id']; ?>/preview" class="font-weight-300">Preview</a> |
                            <a href="/admin/posts/<?php echo $post['id']; ?>/delete" class="font-weight-300 color-red">Remove</a>
                        </td>
                        <?php } else { ?>
                        <td class="width-50">
                            <span class="font-weight-500"><?php echo $post['title']; ?></span>
                        </td>
                        <?php } ?>
                        <td class="width-15">
                            <?php echo $post['author']; ?>
                        </td>
                        <td class="width-15">
                            <a href="/admin/posts/<?php echo $post['id']; ?>/meta/edit" class="font-weight-300">Edit</a> 
                            <span class="display-block padding-y-2">Status: </span><?php if(!empty($post['metaTitle']) ) { echo '<span class="font-weight-300">ok</span>'; } else {echo '<span class="font-weight-300">-</span>'; } ?>
                        </td>
                        <td class="width-15">
                            <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $post["date_created_at"] . " " . $post["time_created_at"]; ?></span><br>
                            <span>Updated:</span> <span class="font-weight-300"><?php echo $post["date_updated_at"] . " " . $post["time_updated_at"]; ?></span>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
</div>
<?php 
    $this->include('footer');
?>