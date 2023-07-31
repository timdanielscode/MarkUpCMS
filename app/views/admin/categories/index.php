<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/index.css");
    $this->stylesheet("/assets/css/categories.css");
    $this->stylesheet("/assets/css/pagination.css");

    $this->script('/assets/js/ajax.js', true);
    $this->script('/assets/js/categories/table.js', true);
    $this->script('/assets/js/categories/modal.js', true);
    $this->script('/assets/js/categories/edit.js', true);
    $this->script('/assets/js/categories/create.js', true);
    $this->script('/assets/js/categories/read.js', true);
    $this->script('/assets/js/categories/add.js', true);
    $this->script('/assets/js/categories/slug.js', true);
    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">
    <h1>Category</h1>
    <div class="countContainer">
        <span>All</span>
        <span>(<?php echo $count; ?>)</span> | <a class="create">Create</a>
    </div>
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search" id="search">
        <input id="searchValue" type="hidden" name="submit" value="<?php if(!empty($search) && $search !== null) { echo $search; } ?>">
    </form>
    <table>
        
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Slug</th>
                <th></th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody id="categoryTableBody">
                
        </tbody>
    </table>

    <?php if(!empty($numberOfPages) && count($numberOfPages) > 1) { ?>
        <nav id="pagination" class="pagination">
            <ul>
                <?php 
                    foreach($numberOfPages as $page) {

                        echo '<li class="page-item"><a class="PAGE" id="'. $page. '">'.$page.'</a></li>';
                    }  
                ?>
            </ul>
        </nav>
    <?php } ?>



    <div id="modal" class="display-none">
        <div class="container">
            <div id="modalForm">

            </div>
        </div>
    </div>
</div>
<?php 
    $this->include('footer');
?>