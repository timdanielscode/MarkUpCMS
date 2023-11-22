<?php use validation\Get; ?>
<?php use core\Session; ?>
<?php use core\Alert; ?>

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
    <?php Alert::message('success'); ?>
    <div class="headerContainer">
        <h1>Css</h1><span class="badge css"><?php echo $count; ?></span>
    </div>
    <?php if(Session::get('user_role') === 'admin') { ?><a href="/admin/css/create" class="create">Create</a> <span class="deleteSeparator">|</span> <form action="/admin/css/delete" method="POST" class="indexDeleteForm"><input type="submit" class="delete" value="<?php if($search === 'Thrashcan') { echo 'Delete permanently'; } else { echo 'Delete'; } ?>" <?php if($search === 'Thrashcan') { echo 'onclick="return confirm(' . "'Are you sure?'" . ');"'; } ?>/><input type="hidden" name="deleteIds" id="deleteIds"/></form> | <?php } ?><form action="" method="GET" class="thrashcanForm"><input type="submit" name="search" value="Thrashcan"/></form><?php if($search === 'Thrashcan') { ?><?php if(Session::get('user_role') === 'admin') { ?> | <form action="/admin/css/recover" method="POST" class="recoverForm"><input type="submit" class="recover" value="Recover"/><input type="hidden" name="recoverIds" id="recoverIds" value=""/></form> <?php } } ?>
    <form action="" method="GET" class="searchForm">
        <input type="text" name="search" placeholder="Search" id="search">
        <input type="submit" name="submit" value="Search" class="button">
    </form>
    <table>
        
            <thead>
                <tr>
                    <th>#</th>
                    <th>Filename</th>
                    <th>Author</th>
                    <th>Date and time</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($cssFiles) && $cssFiles !== null) { ?>
                    <?php foreach($cssFiles as $cssFile) { ?>
                        <tr>
                            <td>
                            <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $cssFile['id']; ?>" <?php if(Session::get('user_role') === 'normal') { echo 'disabled'; } ?>/>
                            </td>
                            <?php if($cssFile['removed'] !== 1 && Session::get('user_role') === 'admin') { ?>
                            <td class="width-25">
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/edit" class="font-weight-500"><?php echo $cssFile['file_name'] . $cssFile['extension']; ?></a> |
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/edit" class="font-weight-300">Edit</a> |
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/read" class="font-weight-300">Read</a>
                            </td>
                            <?php } else { ?>
                                <td class="width-25">
                                <span class="font-weight-500 removed"><?php echo $cssFile['file_name'] . $cssFile['extension']; ?></span> |
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/read" class="font-weight-300">Read</a>
                            </td>
                            <?php } ?>
                            <td class="width-60">
                                <?php echo $cssFile['author']; ?>
                            </td>
                            <td class="width-15">
                                <span class="padding-b-2 bold">Created:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($cssFile["created_at"]) ); ?> <?php echo date("H:i:s", strtotime($cssFile["created_at"]) ); ?></span><br>
                                <span class="bold">Updated:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($cssFile["updated_at"]) ); ?> <?php echo date("H:i:s", strtotime($cssFile["updated_at"]) ); ?></span>
                            </td> 
                        </tr>
                    <?php } ?>
                <?php } else { ?>

                        <tr>
                            <td>-</td>
                            <td class="width-25">-</td>
                            <td class="width-60">-</td>
                            <td class="width-15">-</td>
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

                        echo '<li class="page-item"><a href="/admin/css?search=' . $search . '&page='.$page.'">'.$page.'</a></li>';
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