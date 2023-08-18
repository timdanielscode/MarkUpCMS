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
        <h1>js</h1><span class="badge js"><?php echo $count; ?></span>
    </div>
    <a href="/admin/js/create" class="create">Create</a> <span class="deleteSeparator">|</span> <form action="/admin/js/delete" method="POST" class="indexDeleteForm"><input type="submit" class="delete" value="Delete"/><input type="hidden" name="deleteIds" id="deleteIds" value=""/></form>
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search" id="search">
        <input type="hidden" name="submit" value="search">
    </form>
    <table>
        
            <thead>
                <tr>
                    <th></th>
                    <th>Filename</th>
                    <th>Date and time</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($jsFiles) && $jsFiles !== null) { ?>
                    <?php foreach($jsFiles as $jsFile) { ?>
                        <tr>
                            <td>
                            <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $jsFile['id']; ?>"/>
                            </td>
                            <?php if($jsFile['removed'] !== 1) { ?>
                            <td class="width-85">
                                <a href="/admin/js/<?php echo $jsFile['id']; ?>/edit" class="font-weight-500"><?php echo $jsFile['file_name'] . $jsFile['extension']; ?></a> |
                                <a href="/admin/js/<?php echo $jsFile['id']; ?>/edit" class="font-weight-300">Edit</a> |
                                <a href="/admin/js/<?php echo $jsFile['id']; ?>/read" class="font-weight-300">Read</a>
                            </td>
                            <?php } else { ?>
                                <td class="width-85">
                                <span class="font-weight-500"><?php echo $jsFile['file_name'] . $jsFile['extension']; ?></span> |
                                <a href="/admin/js/<?php echo $jsFile['id']; ?>/read" class="font-weight-300">Read</a> |
                                <a href="/admin/js/<?php echo $jsFile['id']; ?>/recover" class="font-weight-300">Recover</a> |
                                <a href="/admin/js/<?php echo $jsFile['id']; ?>/delete" class="font-weight-300 color-red">Delete permanently</a>
                            </td>
                            <?php } ?>
                            <td class="width-15">
                                <span class="padding-b-2 bold">Created:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($jsFile["created_at"]) ); ?> <?php echo date("H:i:s", strtotime($jsFile["created_at"]) ); ?></span><br>
                                <span class="bold">Updated:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($jsFile["updated_at"]) ); ?> <?php echo date("H:i:s", strtotime($jsFile["updated_at"]) ); ?></span>
                            </td> 
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                        <tr>
                            <td>-</td>
                            <td class="width-90">-</td>
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

                    if(!empty(get('search')) ) {

                        echo '<li class="page-item"><a href="/admin/js?search=' . get('search') . '&page='.$page.'">'.$page.'</a></li>';
                    } else {
                        echo '<li class="page-item"><a href="/admin/js?page='.$page.'">'.$page.'</a></li>';
                    }
                }  
            ?>
        </ul>
    </nav>
<?php } ?>
    </div>
<?php 
    $this->include('footer');
?>