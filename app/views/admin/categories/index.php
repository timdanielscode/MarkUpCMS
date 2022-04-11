<?php 
    $this->include('headerOpen');  
    $this->script('/assets/js/ajax.js');
    $this->script('/assets/js/category.js');
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
                    <th>Slug</th>
                    <th class="width-10">Date</th>
                </tr>
            </thead>
            <tbody id="categoryTableBody">
                
            </tbody>
        </table>

        <div id="categoriesPreview"></div>

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