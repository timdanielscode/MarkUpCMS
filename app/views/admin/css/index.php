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
        <h1>Css</h1><span class="badge css"><?php echo $count; ?></span>
    </div>
    <a href="/admin/css/create" class="create">Create</a> <span class="deleteSeparator">|</span> <form action="/admin/css/delete" method="POST" class="indexDeleteForm"><input type="submit" class="delete" value="Delete"/><input type="hidden" name="deleteIds" id="deleteIds"/></form> | <form action="" method="GET" class="thrashcanForm"><input type="submit" name="search" value="Thrashcan"/></form>
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
                <?php if(!empty($cssFiles) && $cssFiles !== null) { ?>
                    <?php foreach($cssFiles as $cssFile) { ?>
                        <tr>
                            <td>
                            <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $cssFile['id']; ?>"/>
                            </td>
                            <?php if($cssFile['removed'] !== 1) { ?>
                            <td class="width-85">
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/edit" class="font-weight-500"><?php echo $cssFile['file_name'] . $cssFile['extension']; ?></a> |
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/edit" class="font-weight-300">Edit</a> |
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/read" class="font-weight-300">Read</a>
                            </td>
                            <?php } else { ?>
                                <td class="width-85">
                                <span class="font-weight-500 removed"><?php echo $cssFile['file_name'] . $cssFile['extension']; ?></span> |
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/read" class="font-weight-300">Read</a> |
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/recover" class="font-weight-300">Recover</a> |
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/delete" class="font-weight-300 color-red">Delete permanently</a>
                            </td>
                            <?php } ?>
                            <td class="width-15">
                                <span class="padding-b-2 bold">Created:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($cssFile["created_at"]) ); ?> <?php echo date("H:i:s", strtotime($cssFile["created_at"]) ); ?></span><br>
                                <span class="bold">Updated:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($cssFile["updated_at"]) ); ?> <?php echo date("H:i:s", strtotime($cssFile["updated_at"]) ); ?></span>
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

                        echo '<li class="page-item"><a href="/admin/css?search=' . get('search') . '&page='.$page.'">'.$page.'</a></li>';
                    } else {
                        echo '<li class="page-item"><a href="/admin/css?page='.$page.'">'.$page.'</a></li>';
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