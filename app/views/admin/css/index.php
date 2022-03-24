<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="row postHeaderContainer">
        <h1>Css</h1>
        <a class="button postsButton margin-t-20" href="/admin/css/create">Add new</a>
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
                    <th>Filename</th>
                    <th class="width-10">Date</th>
                </tr>
            </thead>
            <tbody>
                
                <?php foreach($cssFiles as $cssFile) { ?>
                    <tr>
                        <?php if($cssFile["file_name"] !== "not found") {?>
                        <td class="width-50">
                            <a href="/admin/css/<?php echo $cssFile['id']; ?>/edit" class="font-weight-500"><?php echo $cssFile['file_name'] . $cssFile['extension']; ?></a> |
                            <a href="/admin/css/<?php echo $cssFile['id']; ?>/edit" class="font-weight-300">Edit</a> |
                            <a href="/admin/css/<?php echo $cssFile['id']; ?>/preview" class="font-weight-300">Preview</a> |
                            <a href="/admin/css/<?php echo $cssFile['id']; ?>/delete" class="font-weight-300 color-red">Remove</a>
                        </td>
                        <?php } else { ?>
                        <td class="width-50">
                            <span class="font-weight-500"><?php echo $cssFile['file_name'];?></span>
                        </td>
                        <?php } ?>
                        <td class="width-15">
                            <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $cssFile["date_created_at"] . " " . $cssFile["time_created_at"]; ?></span><br>
                            <span>Updated:</span> <span class="font-weight-300"><?php echo $cssFile["date_updated_at"] . " " . $cssFile["time_updated_at"]; ?></span>
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