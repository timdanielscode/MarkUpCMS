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
        <h1>Js</h1><span class="badge js"><?php echo $count; ?></span>
    </div>
    <?php if(Session::get('user_role') === 'admin') { ?><a href="/admin/js/create" class="create">Create</a> <span class="deleteSeparator">|</span> <form action="/admin/js/delete" method="POST" class="indexDeleteForm"><input type="submit" class="delete" value="<?php if(Get::validate([get('search')]) === 'Thrashcan') { echo 'Delete permanently'; } else { echo 'Delete'; } ?>" <?php if(Get::validate([get('search')]) === 'Thrashcan') { echo 'onclick="return confirm(' . "'Are you sure?'" . ');"'; } ?>/><input type="hidden" name="deleteIds" id="deleteIds" value=""/></form> | <?php } ?><form action="" method="GET" class="thrashcanForm"><input type="submit" name="search" value="Thrashcan"/></form><?php if(Get::validate([get('search')]) === 'Thrashcan') { ?><?php if(Session::get('user_role') === 'admin') { ?> | <form action="/admin/js/recover" method="POST" class="recoverForm"><input type="submit" class="recover" value="Recover"/><input type="hidden" name="recoverIds" id="recoverIds" value=""/></form> <?php } } ?>
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
                <?php if(!empty($jsFiles) && $jsFiles !== null) { ?>
                    <?php foreach($jsFiles as $jsFile) { ?>
                        <tr>
                            <td>
                            <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $jsFile['id']; ?>" <?php if(Session::get('user_role') === 'normal') { echo 'disabled'; } ?>/>
                            </td>
                            <?php if($jsFile['removed'] !== 1 && Session::get('user_role') === 'admin') { ?>
                            <td class="width-25">
                                <a href="/admin/js/<?php echo $jsFile['id']; ?>/edit" class="font-weight-500"><?php echo $jsFile['file_name'] . $jsFile['extension']; ?></a> |
                                <a href="/admin/js/<?php echo $jsFile['id']; ?>/edit" class="font-weight-300">Edit</a> |
                                <a href="/admin/js/<?php echo $jsFile['id']; ?>/read" class="font-weight-300">Read</a>
                            </td>
                            <?php } else { ?>
                                <td class="width-25">
                                <span class="font-weight-500"><?php echo $jsFile['file_name'] . $jsFile['extension']; ?></span> |
                                <a href="/admin/js/<?php echo $jsFile['id']; ?>/read" class="font-weight-300">Read</a>
                            </td>
                            <?php } ?>
                            <td class="width-60">
                                <?php echo $jsFile['author']; ?>
                            </td>
                            <td class="width-15">
                                <span class="padding-b-2 bold">Created:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($jsFile["created_at"]) ); ?> <?php echo date("H:i:s", strtotime($jsFile["created_at"]) ); ?></span><br>
                                <span class="bold">Updated:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($jsFile["updated_at"]) ); ?> <?php echo date("H:i:s", strtotime($jsFile["updated_at"]) ); ?></span>
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

                    if(!empty(Get::validate([get('search')])) ) {

                        echo '<li class="page-item"><a href="/admin/js?search=' . Get::validate([get('search')]) . '&page='.$page.'">'.$page.'</a></li>';
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