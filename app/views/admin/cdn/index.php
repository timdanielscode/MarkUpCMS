<?php use validation\Get; ?>

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
        <h1>Cdn</h1><span class="badge cdn"><?php echo $count; ?></span>
    </div>
    <a href="/admin/cdn/create" class="create">Create</a> <span class="deleteSeparator">|</span> <form action="/admin/cdn/delete" method="POST" class="indexDeleteForm"><input type="submit" class="delete" value="<?php if(Get::validate([get('search')]) === 'Thrashcan') { echo 'Delete permanently'; } else { echo 'Delete'; } ?>"/><input type="hidden" name="deleteIds" id="deleteIds" value=""/></form> | <form action="" method="GET" class="thrashcanForm"><input type="submit" name="search" value="Thrashcan"/></form><?php if(Get::validate([get('search')]) === 'Thrashcan') { ?> | <form action="/admin/cdn/recover" method="POST" class="recoverForm"><input type="submit" class="recover" value="Recover"/><input type="hidden" name="recoverIds" id="recoverIds" value=""/></form> <?php } ?>
    <form action="" method="GET" class="searchForm">
        <input type="text" name="search" placeholder="Search" id="search">
        <input type="submit" name="submit" value="Search" class="button">
    </form>
    <table>
        
            <thead>
                <tr>
                    <th></th>
                    <th>Title</th>
                    <th>Date and time</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($cdns) && $cdns !== null) { ?>
                    <?php foreach($cdns as $cdn) { ?>
                        <tr>
                            <td>
                            <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $cdn['id']; ?>"/>
                            </td>
                            <?php if($cdn['removed'] !== 1) { ?>
                            <td class="width-85">
                                <a href="/admin/cdn/<?php echo $cdn['id']; ?>/edit" class="font-weight-500"><?php echo $cdn['title']; ?></a> |
                                <a href="/admin/cdn/<?php echo $cdn['id']; ?>/edit" class="font-weight-300">Edit</a> |
                                <a href="/admin/cdn/<?php echo $cdn['id']; ?>/read" class="font-weight-300">Read</a>
                            </td>
                            <?php } else { ?>
                                <td class="width-85">
                                <span class="font-weight-500"><?php echo $cdn['title']; ?></span> |
                                <a href="/admin/cdn/<?php echo $cdn['id']; ?>/read" class="font-weight-300">Read</a>
                            </td>
                            <?php } ?>
                            <td class="width-15">
                                <span class="padding-b-2 bold">Created:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($cdn["created_at"]) ); ?> <?php echo date("H:i:s", strtotime($cdn["created_at"]) ); ?></span><br>
                                <span class="bold">Updated:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($cdn["updated_at"]) ); ?> <?php echo date("H:i:s", strtotime($cdn["updated_at"]) ); ?></span>
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