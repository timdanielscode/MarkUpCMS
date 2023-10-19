<?php use core\Session; ?>

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
    $this->script('/assets/js/categories/index/delete.js', true);
    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">

    <div class="headerContainer">
        <h1>Categories</h1><span class="badge categories"><?php echo $count; ?></span>
    </div>
    <?php if(Session::get('user_role') === 'admin') { ?><a class="create">Create</a> <span class="deleteSeparator">|</span> <form action="/admin/categories/delete" method="POST" class="indexDeleteForm"><input type="submit" value="Delete" class="delete" onclick="return confirm('Are you sure?')"/><input type="hidden" name="deleteIds" id="deleteIds" value=""/></form><?php } ?>
    <form action="" method="GET" class="searchForm">
        <input type="text" name="search" placeholder="Search" id="search">
        <input id="searchValue" type="hidden" name="submit" value="<?php if(!empty($search) && $search !== null) { echo $search; } ?>">
        <input type="submit" name="submit" value="Search" class="button">
    </form>
    <table>
        
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Slug</th>
                <th></th>
                <th>Author</th>
                <th>Date and time</th>
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