<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use extensions\Pagination; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/index.css");
    $this->stylesheet("/assets/css/pagination.css");

    $this->script("/assets/js/delete.js", true);

    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">
    <div class="headerContainer">
        <h1>Menus</h1><span class="badge menus"><?php echo $count; ?></span>
    </div>
    <a href="/admin/menus/create" class="create">Create</a> <span class="deleteSeparator">|</span> <form action="/admin/menus/delete" method="POST" class="indexDeleteForm"><input type="submit" class="delete" value="Delete"/><input type="hidden" name="deleteIds" id="deleteIds"/></form> | <form action="" method="GET" class="thrashcanForm"><input type="submit" name="search" value="Thrashcan"/></form>
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search" id="search">
        <input type="hidden" name="submit" value="search">
    </form>
    <table>
        
            <thead>
                <tr>
                    <th></th>
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
                                <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $menu['id']; ?>"/>
                            </td>
                            <?php if($menu['removed'] !== 1) { ?>
                                <td class="width-25">
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/edit" class="font-weight-500"><?php echo $menu['title']; ?></a> |
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/edit" class="font-weight-300">Edit</a> |
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/read" class="font-weight-300">Read</a>
                                </td>
                            <?php } else { ?>
                                <td class="width-25">
                                    <span class="removed font-weight-500"><?php echo $menu['title']; ?></span> |
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/read" class="font-weight-300">Read</a> |
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/recover" class="font-weight-300">Recover</a> |
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/delete" class="font-weight-300 color-red">Delete permanently</a>
                                </td>
                            <?php } ?>

                            <?php if(!empty($menu['position']) ) { ?>
                            <td class="width-20">
                                <?php echo $menu['position']; ?>
                            </td>
                            <?php } else { ?>
                            <td class="width-20">
                                <span>unset</span>
                            </td>
                            <?php } ?>
                            <?php if(!empty($menu['ordering']) ) { ?>
                            <td class="width-20">
                                <?php echo $menu['ordering']; ?>
                            </td>
                            <?php } else { ?>
                            <td class="width-20">
                                <span>unset</span>
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

                        if(!empty(get('search')) ) {

                            echo '<li class="page-item"><a href="/admin/menus?search=' . get('search') . '&page='.$page.'">'.$page.'</a></li>';
                        } else {
                            echo '<li class="page-item"><a href="/admin/menus?page='.$page.'">'.$page.'</a></li>';
                        }
                    ?>
                </ul>
            </nav>
        <?php } ?>
</div>

<?php 
    $this->include('footer');
?>