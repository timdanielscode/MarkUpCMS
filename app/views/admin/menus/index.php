<!-- 
    - to show an overview of menus, by default not thrashed. Or thrashed or on search value (by a dividing amount to not have to scroll down a lot)
    -
    - FOR TYPE OF ADMIN USER
    -
    - to create a selection of menus to move to/from thrashcan or to permanently remove
--> 

<?php $this->include('openHeadTag'); ?>  
    <?php $this->title('Menus overview page'); ?>
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
        <h1>Menus</h1><span class="badge menus"><?php echo $count; ?></span>
    </div>
    <?php if(core\Session::get('user_role') === 1) { ?>
        <a href="/admin/menus/create" class="create">Create</a> 
        <span class="deleteSeparator">|</span> 
        <form action="/admin/menus/delete" method="POST" class="indexDeleteForm">
            <input type="submit" class="delete" value="<?php if($search === 'Thrashcan') { echo 'Delete permanently'; } else { echo 'Delete'; } ?>" <?php if($search === 'Thrashcan') { echo 'onclick="return confirm(' . "'Are you sure?'" . ');"'; } ?>/>
            <input type="hidden" name="deleteIds" id="deleteIds"/>
        </form> | 
        <?php } ?>
        <form action="" method="GET" class="thrashcanForm">
            <input type="submit" name="search" value="Thrashcan"/>
        </form>
        <?php if($search === 'Thrashcan' && core\Session::get('user_role') === 1) { ?> | 
            <form action="/admin/menus/recover" method="POST" class="recoverForm">
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
                    <th>Position</th>
                    <th>Ordering</th>
                    <th>Author</th>
                    <th>Date and time</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($menus) && $menus !== null) { ?>
                    <?php foreach($menus as $menu) { ?>
                        <tr>
                            <td>
                                <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $menu['id']; ?>" <?php if(core\Session::get('user_role') !== 1) { echo 'disabled'; } ?>/>
                            </td>
                            <?php if($menu['removed'] !== 1 && core\Session::get('user_role') === 1) { ?>
                                <td class="width-25">
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/edit" class="font-weight-500"><?php echo $menu['title']; ?></a> |
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/edit" class="font-weight-300">Edit</a> | 
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/read" class="font-weight-300">Read</a>
                                </td>
                            <?php } else { ?>
                                <td class="width-25">
                                    <span class="removed font-weight-500"><?php echo $menu['title']; ?></span> |
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/read" class="font-weight-300">Read</a>
                                </td>
                            <?php } ?>
                            <?php if(!empty($menu['position']) ) { ?>
                            <td class="width-20">
                                <?php echo $menu['position']; ?>
                            </td>
                            <?php } ?>
                            <?php if(!empty($menu['ordering']) ) { ?>
                            <td class="width-20">
                                <?php echo $menu['ordering']; ?>
                            </td>
                            <?php } else { ?>
                            <td class="width-20">
                                <span>-</span>
                            </td>
                            <?php } ?>
                            <td class="width-20">
                                <?php echo $menu['author']; ?>
                            </td>
                            <td class="width-15">
                                <span class="padding-b-2 bold">Created:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($menu["created_at"]) ); ?> <?php echo date("H:i:s", strtotime($menu["created_at"]) ); ?></span><br>
                                <span class="bold">Updated:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($menu["updated_at"]) ); ?> <?php echo date("H:i:s", strtotime($menu["updated_at"]) ); ?></span>
                            </td> 
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td>-</td>
                        <td class="width-30">-</td>
                        <td class="width-20">-</td>
                        <td class="width-20">-</td>
                        <td class="width-20">-</td>
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

                        if(!empty($search) ) {
                            echo '<li class="page-item"><a href="/admin/menus?search=' . $search . '&page='.$page.'">'.$page.'</a></li>';
                        } else {
                            echo '<li class="page-item"><a href="/admin/menus?page='.$page.'">'.$page.'</a></li>';
                        }
                    }  
                ?>
            </ul>
        </nav>
    <?php } ?>
</div>

<?php $this->include('footer'); ?>