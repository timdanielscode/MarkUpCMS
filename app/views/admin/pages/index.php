<!-- 
    - to show an overview of pages, by default not thrashed. Or thrashed or on search value (by a dividing amount to not have to scroll down a lot)
    -
    - FOR TYPE OF ADMIN USER
    -
    - to create a selection of pages to move to/from thrashcan or to permanently remove
--> 

<?php $this->include('openHeadTag'); ?>
    <?php $this->title('Pages overview page'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/index.css"); ?>
    <?php $this->stylesheet("/assets/css/pagination.css"); ?>
    <?php $this->script("/assets/js/checkbox/Checkbox.js", true); ?>
    <?php $this->script("/assets/js/checkbox/main.js", true); ?>
<?php $this->include('closeHeadTagAndOpenBodyTag'); ?>

<?php $this->include('navbar'); ?>

<div class="index-container">
    <?php core\Alert::message('success'); ?>
    <div class="headerContainer">
        <h1>Pages</h1><span class="badge pages"><?php echo $count; ?></span>
    </div>
    <?php if(core\Session::get('user_role') === 1) { ?>
        <a href="/admin/pages/create" class="create">Create</a> 
        <span class="deleteSeparator">|</span>
        <form action="/admin/pages/delete" method="POST" class="indexDeleteForm">
            <input type="submit" class="delete" value="<?php if($search === 'Thrashcan') { echo 'Delete permanently'; } else { echo 'Delete'; } ?>" <?php if($search === 'Thrashcan') { echo 'onclick="return confirm(' . "'Are you sure?'" . ');"'; } ?>/>
            <input type="hidden" name="deleteIds" id="deleteIds" value=""/>
        </form> | 
        <?php } ?>
        <form action="" method="GET" class="thrashcanForm">
            <input type="submit" name="search" value="Thrashcan"/>
        </form>
        <?php if($search === 'Thrashcan' && core\Session::get('user_role') === 1) { ?> | 
            <form action="/admin/pages/recover" method="POST" class="recoverForm">
                <input type="submit" class="recover" value="Recover"/>
                <input type="hidden" name="recoverIds" id="recoverIds" value=""/>
            </form> 
        <?php } ?>
    <form action="" method="GET" class="searchForm">
        <input type="text" name="search" placeholder="Search" id="search">
    </form>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Slug</th>
                <th>Category</th>
                <th>Author</th>
                <th>Seo</th>
                <th>Date and time</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($pages) && $pages !== null) { ?>
                <?php foreach($pages as $page) { ?>
                    <tr>
                    <td>
                        <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $page['id']; ?>" <?php if(core\Session::get('user_role') !== 1) { echo 'disabled'; } ?>/>
                    </td>
                    <?php if($page['removed'] !== 1 && core\Session::get('user_role') === 1) { ?>
                    <td class="width-25">
                        <a href="/admin/pages/<?php echo $page['id']; ?>/edit" class="font-weight-500"><?php echo $page[1]; ?></a> |
                        <a href="/admin/pages/<?php echo $page['id']; ?>/edit" class="font-weight-300">Edit</a> |
                        <a href="/admin/pages/<?php echo $page['id']; ?>/read" class="font-weight-300">Read</a>
                    </td>
                    <?php } else { ?>
                    <td class="width-25">
                        <span class="removed font-weight-500"><?php echo $page[1]; ?></span> |
                        <a href="/admin/pages/<?php echo $page['id']; ?>/read" class="font-weight-300">Read</a>
                    </td>
                    <?php } ?>
                    <?php if($page['removed'] === 1) { ?>
                    <td class="width-20">
                        <span class="removed">TEMPORARILY UNSET</span>
                    </td>
                    <?php } else { ?>
                        <td class="width-20">
                        <a href="<?php echo $page['slug']; ?>"><?php echo $page['slug']; ?></a>
                    </td>
                    <?php } ?>
                    <td class="width-15">
                        <?php if(!empty($page['categoryId']) ) { ?>
                            <a href="/admin/categories/<?php echo $page['categoryId']; ?>/apply"><?php echo $page[10]; ?></a>
                        <?php } else { ?>-<?php } ?>
                    </td>
                    <td class="width-15">
                        <?php echo $page['author']; ?>
                    </td>
                    <td class="width-10">
                        <?php if(!empty($page['metaTitle']) && !empty($page['metaDescription'])) { echo '<span class="font-weight-300">ok</span>'; } else {echo '<span class="font-weight-300">-</span>'; } ?>
                    </td>
                    <td class="width-15">
                        <span class="padding-b-2 bold">Created:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($page["created_at"]) ); ?> <?php echo date("H:i:s", strtotime($page["created_at"]) ); ?></span><br>
                        <span class="bold">Updated:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($page["updated_at"]) ); ?> <?php echo date("H:i:s", strtotime($page["updated_at"]) ); ?></span>
                    </td> 
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td>-</td>
                    <td class="width-20">-</td>
                    <td class="width-30">-</td>
                    <td class="width-15">-</td>
                    <td class="width-15">-</td>
                    <td class="width-10">-</td>
                    <td class="width-10">-</td>
                </tr>
            <?php } ?>    
        </tbody>
    </table>
    <?php if($numberOfPages !== null && count($numberOfPages) > 1) { ?>
        <nav class="pagination">
            <ul>
                <?php 
                    foreach($numberOfPages as $page) {

                        if(!empty($search) && $search !== null) {
                            echo '<li class="page-item"><a href="/admin/pages?search=' . $search . '&page='.$page.'">'.$page.'</a></li>';
                        } else {
                            echo '<li class="page-item"><a href="/admin/pages?page='.$page.'">'.$page.'</a></li>';
                        }
                    }  
                ?>
            </ul>
        </nav>
    <?php } ?>
</div>

<?php $this->include('footer'); ?>