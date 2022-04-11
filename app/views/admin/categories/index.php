<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="row postHeaderContainer">
        <h1>Category</h1>
        <a class="button postsButton margin-t-20" href="/admin/categories/create">Add new</a>
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
                    <th class="width-10">Date</th>
                </tr>
            </thead>
            <tbody>
                
                <?php foreach($categories as $category) { ?>
                    <tr>
                        <?php if($category["title"] !== "not found" && $category["title"] !== "no category created") {?>
                        <td class="width-50">
                            <a href="/admin/categories/<?php echo $category['id']; ?>/edit" class="font-weight-500"><?php echo $category['title']; ?></a> |
                            <a href="/admin/categories/<?php echo $category['id']; ?>/edit" class="font-weight-300">Edit</a> |
                            <a href="/admin/categories/<?php echo $category['id']; ?>/preview" class="font-weight-300">Preview</a> |
                            <a href="/admin/categories/<?php echo $category['id']; ?>/delete" class="font-weight-300 color-red">Remove</a>
                        </td>
                        <?php } else { ?>
                        <td class="width-50">
                            <span class="font-weight-500"><?php echo $category['title'];?></span>
                        </td>
                        <?php } ?>
                        <td class="width-15">
                            <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $category["date_created_at"] . " " . $category["time_created_at"]; ?></span><br>
                            <span>Updated:</span> <span class="font-weight-300"><?php echo $category["date_updated_at"] . " " . $category["time_updated_at"]; ?></span>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php if(count($numberOfPages) > 1) { ?>
            <nav class="paginationPosts">
                <ul class="pagination">
                    <li class="page-item previous"><a href="/admin/categories?back=1">Previous</a></li>
                    <?php 
                        foreach($numberOfPages as $page) {
                            echo '<li class="page-item"><a href="/admin/categories?page='.$page.'">'.$page.'</a></li>';
                        }  
                    ?>
                    <li class="page-item next"><a href="/admin/categories?next=1">Next</a></li>
                </ul>
            </nav>
        <?php } ?>
<?php 
    $this->include('footer');
?>