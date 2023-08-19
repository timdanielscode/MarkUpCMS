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
    $this->script("/assets/js/recover.js", true);

    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">
    <div class="headerContainer">
        <h1>Widgets</h1><span class="badge menus"><?php echo $count; ?></span>
    </div>
    <a href="/admin/menus/create" class="create">Create</a> <span class="deleteSeparator">|</span> <form action="/admin/menus/delete" method="POST" class="indexDeleteForm"><input type="submit" class="delete" value="<?php if(get('search') === 'Thrashcan') { echo 'Delete permanently'; } else { echo 'Delete'; } ?>"/><input type="hidden" name="deleteIds" id="deleteIds"/></form> | <form action="" method="GET" class="thrashcanForm"><input type="submit" name="search" value="Thrashcan"/></form><?php if(get('search') === 'Thrashcan') { ?> | <form action="/admin/menus/recover" method="POST" class="recoverForm"><input type="submit" class="recover" value="Recover"/><input type="hidden" name="recoverIds" id="recoverIds" value=""/></form> <?php } ?>
    <form action="" method="GET" class="searchForm">
        <input type="text" name="search" placeholder="Search" id="search">
        <input type="submit" name="submit" value="Search" class="button">
    </form>
    <table>
        
            <thead>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date and time</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($widgets) && $widgets !== null) { ?>
                    <?php foreach($widgets as $widget) { ?>
                        <tr>
                            <td>
                                <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $widget['id']; ?>"/>
                            </td>
                            <td class="width-5">
                                <?php echo $widget['id']; ?>
                            </td>
                            <?php if($widget['removed'] !== 1) { ?>
                                <td class="width-60">
                                    <a href="/admin/menus/<?php echo $widget['id']; ?>/edit" class="font-weight-500"><?php echo $widget['title']; ?></a> |
                                    <a href="/admin/menus/<?php echo $widget['id']; ?>/edit" class="font-weight-300">Edit</a> |
                                    <a href="/admin/menus/<?php echo $widget['id']; ?>/read" class="font-weight-300">Read</a>
                                </td>
                            <?php } else { ?>
                                <td class="width-60">
                                    <span class="removed font-weight-500"><?php echo $widget['title']; ?></span> |
                                    <a href="/admin/menus/<?php echo $widget['id']; ?>/read" class="font-weight-300">Read</a>
                                </td>
                            <?php } ?>

                            <td class="width-20">
                                <?php echo $widget['author']; ?>
                            </td>
                            <td class="width-15">
                                <span class="padding-b-2 bold">Created:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($widget["created_at"]) ); ?> <?php echo date("H:i:s", strtotime($widget["created_at"]) ); ?></span><br>
                                <span class="bold">Updated:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($widget["updated_at"]) ); ?> <?php echo date("H:i:s", strtotime($widget["updated_at"]) ); ?></span>
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