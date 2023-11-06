<?php use core\Session; ?>
<?php use core\Alert; ?>
<?php use validation\Get; ?>
<?php use core\Csrf; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/modal.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/index.css");
    $this->stylesheet("/assets/css/categories.css");
    $this->stylesheet("/assets/css/pagination.css");

    $this->script('/assets/js/ajax.js', true);
    $this->script('/assets/js/categories/modal.js', true);
    $this->script('/assets/js/categories/add.js', true);
    $this->script('/assets/js/categories/slug.js', true);
    $this->script('/assets/js/categories/delete.js', true);
    
    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">

    <?php Alert::message('failed'); ?>
    <?php Alert::message('success'); ?>

    <div class="headerContainer">
        <h1>Categories</h1><span class="badge categories"><?php echo $count; ?></span>
    </div>
    <?php if(Session::get('user_role') === 'admin') { ?><a class="create" id="create">Create</a> <span class="deleteSeparator">|</span> <form action="/admin/categories/delete" method="POST" class="indexDeleteForm"><input type="submit" value="Delete" class="delete" onclick="return confirm('Are you sure?')"/><input type="hidden" name="deleteIds" id="deleteIds" value=""/><input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" /></form><?php } ?>
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
                <?php if(Session::get('user_role') === 'admin') { ?><th></th><?php } ?>
                <th>Author</th>
                <th>Date and time</th>
            </tr>
        </thead>
        <tbody id="categoryTableBody">

<?php if(!empty($categories) && $categories !== null) { ?>
    <?php foreach($categories as $category) { ?>
        <tr>

                <td>
                    <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $category['id']; ?>" <?php if(Session::get('user_role') === 'normal') { echo 'disabled'; } ?>/>
                </td>
                <?php if(Session::get('user_role') === 'admin') { ?>
                <td class="width-20">
                    <a data-id="<?php echo $category['id']; ?>" data-title="<?php echo $category["title"]; ?>" data-description="<?php echo $category["category_description"]; ?>" class="edit font-weight-300"><?php echo $category["title"]; ?></a> |
                    <a data-id="<?php echo $category['id']; ?>" data-title="<?php echo $category["title"]; ?>" data-description="<?php echo $category["category_description"]; ?>" class="edit font-weight-300">Edit</a> |
                    <a href="#" data-role="add" data-id="<?php echo $category['id']; ?>" class="add font-weight-300">Apply</a>
                    </td>
                <?php } else { ?>
                    <td class="width-30">
                    <span class="removed font-weight-500"><?php echo $category["title"]; ?></span> |
                    <a href="#" data-role="add" data-id="<?php echo $category['id']; ?>" class="add font-weight-300">Apply</a>
                </td>
                <?php } ?>
                <?php if(Session::get('user_role') === 'admin') { ?>
                <td class="width-30">
                    <form>
                        <input class="categorySlug" name="slug" id="slug-<?php echo $category['id']; ?>" type="text" value="<?php echo substr($category['slug'], 1); ?>"/>
                            <div id="message-<?php echo $category['id'] ?>"></div>
                    </form>
                </td>
                <?php } else { ?>
                <td class="width-30">
                    <?php echo $category['slug']; ?>
                </td>
                <?php } ?>
                <?php if(Session::get('user_role') === 'admin') { ?>
                    <td class="width-10">
                        <a data-role="update" id="update" data-id="<?php echo $category['id']; ?>" class="button">Update</a>
                    </td>
                <?php } ?>
                <td class="width-25">
                    <?php echo $category['author']; ?>
                </td>
                <td class="width-15">
                    <span class="padding-b-2 bold">Created:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($category["created_at"]) ); ?> <?php echo date("H:i:s", strtotime($category["created_at"]) ); ?></span><br>
                    <span class="bold">Updated:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($category["updated_at"]) ); ?> <?php echo date("H:i:s", strtotime($category["updated_at"]) ); ?></span>
                </td> 
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td>-</td>
        <td class="width-20">-</td>
        <td class="width-30">-</td>
        <td class="width-10"></td>
        <td class="width-25">-</td>
        <td class="width-15">-</td>
    </tr>

<?php } ?>
        </tbody>
    </table>

    <?php if(!empty($numberOfPages) && count($numberOfPages) > 1) { ?>
        <nav id="pagination" class="pagination">
        <ul>
                <?php 
                
                    foreach($numberOfPages as $page) {

                        if(!empty(Get::validate([get('search')])) ) {

                            echo '<li class="page-item"><a href="/admin/categories?search=' . Get::validate([get('search')]) . '&page='.$page.'">'.$page.'</a></li>';
                        } else {
                            echo '<li class="page-item"><a href="/admin/categories?page='.$page.'">'.$page.'</a></li>';
                        }
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