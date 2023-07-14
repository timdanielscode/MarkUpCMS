<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use extensions\Pagination; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/index.css");
    $this->stylesheet("/assets/css/pagination.css");

    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">
    <div class="headerAndButtonContainer">
        <h1>Pages</h1>
        <a class="button " href="/admin/posts/create">Add new</a>
    </div>
    <div class="countContainer">
        <span>All</span>
        <span>(<?php echo $count; ?>)</span>
    </div>
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search" id="search">
        <input type="hidden" name="submit" value="search">
    </form>
    <table>
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
                    category
                </td>
                <td class="width-15">
                    <?php echo $post['author']; ?>
                </td>
                <td class="width-15">
                    <a href="/admin/posts/<?php echo $post['id']; ?>/meta/edit" class="font-weight-300">Edit</a> 
                    <?php if(!empty($post['metaTitle']) ) { echo '<span class="font-weight-300">ok</span>'; } else {echo '<span class="font-weight-300">-</span>'; } ?>
                </td>
                <td class="width-15">
                    <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $post["date_created_at"] . " " . $post["time_created_at"]; ?></span><br>
                    <span>Updated:</span> <span class="font-weight-300"><?php echo $post["date_updated_at"] . " " . $post["time_updated_at"]; ?></span>
                </td> 
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php if($numberOfPages !== null && count($numberOfPages) > 1) { ?>
    <nav class="pagination">
        <ul>
            <?php 
                foreach($numberOfPages as $page) {
                    echo '<li class="page-item"><a href="/admin/posts?page='.$page.'">'.$page.'</a></li>';
                }  
            ?>
        </ul>
    </nav>
<?php } ?>

</div>



<?php 
    $this->include('footer');
?>