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

    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">
    <div class="headerAndButtonContainer">
        <h1>Menus</h1>
        <a class="button" href="/admin/menus/create">Add new</a>
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
                    <th>#</th>
                    <th>Title</th>
                    <th>Position</th>
                    <th>Ordering</th>
                    <th>Author</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($menus) && $menus !== null) { ?>
                    <?php foreach($menus as $menu) { ?>
                        <tr>
                            <td>
                                <?php echo $menu['id']; ?>
                            </td>
                            <?php if($menu['removed'] !== 1) { ?>
                                <td class="width-30">
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/edit" class="font-weight-500"><?php echo $menu['title']; ?></a> |
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/edit" class="font-weight-300">Edit</a> |
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/read" class="font-weight-300">Read</a> |
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/delete" class="font-weight-300 color-red">Remove</a>
                                </td>
                            <?php } else { ?>
                                <td class="width-30">
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/recover" class="font-weight-300 recover">Recover</a> |
                                    <span class="removed font-weight-500"><?php echo $menu['title']; ?></span> |
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/read" class="removed font-weight-300">Read</a> |
                                    <a href="/admin/menus/<?php echo $menu['id']; ?>/delete" class="font-weight-300 color-red">Remove</a>
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
                            <td class="width-10">
                                <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $menu["date_created_at"] . " " . $menu["time_created_at"]; ?></span><br>
                                <span>Updated:</span> <span class="font-weight-300"><?php echo $menu["date_updated_at"] . " " . $menu["time_updated_at"]; ?></span>
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