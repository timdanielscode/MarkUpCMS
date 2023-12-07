<!-- 
    - to have an overview of categories (by a dividing amount to not have to scroll down a lot)
    - to search for specific categories
    - to have an overview of assinged and not assinged sub categories per category (after clicking on the 'apply' link)
    - to have an overview of assinged pages and not assinged pages per category (after clicking on the 'apply' link)
    -
    - FOR TYPE OF ADMIN USER
    -
    - to change the title value and submit to update (after clicking on the edit link)
    - to add or change the description value to submit and update (after clicking on the edit link)
    - to create a selection of categories to submit and remove
-->

<?php $this->include('openHeadTag'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/modal.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/index.css"); ?>
    <?php $this->stylesheet("/assets/css/categories.css"); ?>
    <?php $this->stylesheet("/assets/css/pagination.css"); ?>
    <?php $this->script('/assets/js/categories/index/Modal.js', true); ?>
    <?php $this->script('/assets/js/categories/index/main.js', true); ?>
    <?php $this->script('/assets/js/categories/index/delete.js', true); ?>
<?php $this->include('closeHeadTagAndOpenBodyTag'); ?>

<?php $this->include('navbar'); ?>

<div class="index-container">

    <?php core\Alert::message('failed'); ?>
    <?php core\Alert::message('success'); ?>

    <div class="headerContainer">
        <h1>Categories</h1><span class="badge categories"><?php echo $count; ?></span>
    </div>
    <?php if(core\Session::get('user_role') === 'admin') { ?>
        <a class="create" id="create">Create</a> <span class="deleteSeparator">|</span>
        <form action="/admin/categories/delete" method="POST" class="indexDeleteForm">
            <input type="submit" value="Delete" class="delete" onclick="return confirm('Are you sure?')"/>
            <input type="hidden" name="deleteIds" id="deleteIds" value=""/>
        </form>
    <?php } ?>
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
                <th>Author</th>
                <th>Date and time</th>
            </tr>
        </thead>
        <tbody id="categoryTableBody">
            <?php if(!empty($categories) && $categories !== null) { ?>
                <?php foreach($categories as $category) { ?>
                <tr>
                    <td>
                        <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $category['id']; ?>" <?php if(core\Session::get('user_role') === 'normal') { echo 'disabled'; } ?>/>
                    </td>
                    <?php if(core\Session::get('user_role') === 'admin') { ?>
                    <td class="width-30">
                        <a data-id="<?php echo $category['id']; ?>" data-title="<?php echo $category["title"]; ?>" data-description="<?php echo $category["category_description"]; ?>" class="edit font-weight-300"><?php echo $category["title"]; ?></a> |
                        <a data-id="<?php echo $category['id']; ?>" data-title="<?php echo $category["title"]; ?>" data-description="<?php echo $category["category_description"]; ?>" class="edit font-weight-300">Edit</a> |
                        <a href="/admin/categories/<?php echo $category['id']; ?>/apply" class="edit font-weight-300">Apply</a>
                    </td>
                    <?php } else { ?>
                    <td class="width-30">
                        <span class="removed font-weight-500"><?php echo $category["title"]; ?></span> |
                        <a href="/admin/categories/<?php echo $category['id']; ?>/apply" class="edit font-weight-300">Apply</a>
                    </td>
                    <?php } ?>
                    <td class="width-30">
                        <?php echo $category['slug']; ?>
                    </td>
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

                        if(!empty($search) ) {
                            echo '<li class="page-item"><a href="/admin/categories?search=' . $search . '&page='.$page.'">'.$page.'</a></li>';
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

<?php $this->include('footer'); ?>