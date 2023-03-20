<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use extensions\Pagination; ?>

<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="con">
    
    <?php

        if(Session::exists('create')) {
            echo Session::get('create');
            Session::delete('create');
        }
        if(Session::exists('delete')) {

            echo Session::get('delete');
            Session::delete('delete');
        }


    ?>




    </div>
    <div class="row postHeaderContainer">
        <h1>Posts</h1>
        <a class="button postsButton margin-t-20" href="/admin/posts/create">Add new</a>
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
                    <th>Category</th>
                    <th>Author</th>
                    <th>Meta</th>
                    <th class="width-10">Date</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach($posts as $post) { ?>
                    <tr>
                        <?php if($post["title"] !== "not found") {?>
                        <td class="width-40">
                            <a href="/admin/posts/<?php echo $post['id']; ?>/edit" class="font-weight-500"><?php echo $post['title']; ?></a> |
                            <a href="/admin/posts/<?php echo $post['id']; ?>/edit" class="font-weight-300">Edit</a> |
                            <a href="/admin/posts/<?php echo $post['id']; ?>/read" class="font-weight-300">Preview</a> |
                            <a href="/admin/posts/<?php echo $post['id']; ?>/delete" class="font-weight-300 color-red">Remove</a>
                        </td>
                        <?php } else { ?>
                        <td class="width-40">
                            <span class="font-weight-500"><?php echo $post['title']; ?></span>
                        </td>
                        <?php } ?>
                        <td class="">
                            <?php /*if($post['ctitle'] !== null) {
                                echo $post['ctitle']; 
                            } else {
                                echo '-';
                            }
                                */
                            
                            // category title
                            echo 'category title';    
                            ?>
                        </td>
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
        <?php if(count($numberOfPages) > 1) { ?>
            <nav class="paginationPosts">
                <ul class="pagination">
                    <li class="page-item previous"><a href="/admin/posts?back=1">Previous</a></li>
                    <?php 
                        foreach($numberOfPages as $page) {
                            echo '<li class="page-item"><a href="/admin/posts?page='.$page.'">'.$page.'</a></li>';
                        }  
                    ?>
                    <li class="page-item next"><a href="/admin/posts?next=1">Next</a></li>
                </ul>
            </nav>
        <?php } ?>

<?php 
    $this->include('footer');
?>