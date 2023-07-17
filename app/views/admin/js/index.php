<?php 
    $this->include('headerOpen'); 
    
    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/index.css");
    $this->stylesheet("/assets/css/pagination.css");

    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">
    <div class="headerAndButtonContainer">
        <h1>Js</h1>
        <a class="button postsButton margin-t-20" href="/admin/js/create">Add new</a>
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
                    <th>Filename</th>
                    <th class="width-10">Date</th>
                </tr>
            </thead>
            <tbody>
                
                <?php foreach($jsFiles as $jsFile) { ?>
                    <tr>
                        <?php if($jsFile["file_name"] !== "not found" && $jsFile["file_name"] !== "no js file created yet") {?>
                        <td class="width-50">
                            <a href="/admin/js/<?php echo $jsFile['id']; ?>/edit" class="font-weight-500"><?php echo $jsFile['file_name'] . $jsFile['extension']; ?></a> |
                            <a href="/admin/js/<?php echo $jsFile['id']; ?>/edit" class="font-weight-300">Edit</a> |
                            <a href="/admin/js/<?php echo $jsFile['id']; ?>/read" class="font-weight-300">Read</a> |
                            <a href="/admin/js/<?php echo $jsFile['id']; ?>/delete" class="font-weight-300 color-red">Remove</a>
                        </td>
                        <?php } else { ?>
                        <td class="width-50">
                            <span class="font-weight-500"><?php echo $jsFile['file_name'];?></span>
                        </td>
                        <?php } ?>
                        <td class="width-15">
                            <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $jsFile["date_created_at"] . " " . $jsFile["time_created_at"]; ?></span><br>
                            <span>Updated:</span> <span class="font-weight-300"><?php echo $jsFile["date_updated_at"] . " " . $jsFile["time_updated_at"]; ?></span>
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
    </div>
<?php 
    $this->include('footer');
?>