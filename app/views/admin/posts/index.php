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
        <h1>Posts</h1>
        <a class="button postsButton margin-t-20" href="/admin/posts/create">Add new</a>
    </div>
    <div class="postContainterCount">
        <span>All</span>
        <span>(<?php echo $count; ?>)</span>
    </div>
    <table class="tablePosts margin-y-20">
        
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($posts as $post) { ?>
                    <tr>
                        <td>
                            <a href="/admin/posts/<?php echo $post['id']; ?>/edit" class="font-weight-500"><?php echo $post['title']; ?></a> |
                            <a href="/admin/posts/<?php echo $post['id']; ?>/edit" class="font-weight-300" target="_blank">Edit</a> |
                            <a href="/admin/posts/<?php echo $post['id']; ?>/preview" class="font-weight-300" target="_blank">Preview</a> |
                            <a href="/admin/posts/<?php echo $post['id']; ?>/delete" class="font-weight-300 color-red">Remove</a>
                        </td>
                        <td>
                            <?php echo $post['author']; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <nav class="paginationPosts">
            <ul class="pagination">
                <li class="page-item"><a href="/admin/posts?back=1">Previous</a></li>
                <?php 
                    foreach($numberOfPages as $page) {
                        echo '<li><a href="/admin/posts?page='.$page.'">'.$page.'</a></li>';
                    }  
                ?>
                <li><a href="/admin/posts?next=1">Next</a></li>
            </ul>
        </nav>


<?php 
    $this->include('footer');
?>