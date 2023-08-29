<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use extensions\Pagination; ?>
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

<?php 
    Session::delete('updateJs');
    Session::delete('updateMeta');
    Session::delete('updateCategory');
    Session::delete('updateCss');
?>

<div class="index-container">

    <div class="headerContainer">
        <h1>Pages</h1><span class="badge pages"><?php echo $count; ?></span>
    </div>

    <a href="/admin/posts/create" class="create">Create</a> <span class="deleteSeparator">|</span> <form action="/admin/posts/delete" method="POST" class="indexDeleteForm"><input type="submit" class="delete" value="<?php if(Get::validate([get('search')]) === 'Thrashcan') { echo 'Delete permanently'; } else { echo 'Delete'; } ?>"/><input type="hidden" name="deleteIds" id="deleteIds" value=""/></form> | <form action="" method="GET" class="thrashcanForm"><input type="submit" name="search" value="Thrashcan"/></form><?php if(Get::validate([get('search')]) === 'Thrashcan') { ?> | <form action="/admin/posts/recover" method="POST" class="recoverForm"><input type="submit" class="recover" value="Recover"/><input type="hidden" name="recoverIds" id="recoverIds" value=""/></form> <?php } ?>
    <form action="" method="GET" class="searchForm">
        <input type="text" name="search" placeholder="Search" id="search">
        <input type="submit" name="submit" value="Search" class="button">
    </form>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Slug</th>
                <th>Category</th>
                <th>Author</th>
                <th>Meta</th>
                <th>Date and time</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($posts) && $posts !== null) { ?>
                <?php foreach($posts as $post) { ?>
                    <tr>
                    <td>
                        <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $post['id']; ?>"/>
                    </td>
                    <?php if($post['removed'] !== 1) { ?>
                    <td class="width-25">
                        <a href="/admin/posts/<?php echo $post['id']; ?>/edit" class="font-weight-500"><?php echo $post[1]; ?></a> |
                        <a href="/admin/posts/<?php echo $post['id']; ?>/edit" class="font-weight-300">Edit</a> |
                        <a href="/admin/posts/<?php echo $post['id']; ?>/read" class="font-weight-300">Read</a>
                    </td>
                    <td class="width-20">
                        <a href="<?php echo $_SERVER['HTTP_HOST'] . $post['slug']; ?>"><?php echo $post['slug']; ?></a>
                    </td>
                    <?php } else { ?>
                    <td class="width-25">
                        <span class="removed font-weight-500"><?php echo $post[1]; ?></span> |
                        <a href="/admin/posts/<?php echo $post['id']; ?>/read" class="font-weight-300">Read</a>
                    </td>
                    <td class="width-20">
                        <span class="removed">TEMPORARILY UNSET</span>
                    </td>
                    <?php } ?>
                    <td class="width-15">
                        <?php if(!empty($post[11]) ) { echo $post[11]; } else { echo '-'; } ?>
                    </td>
                    <td class="width-15">
                        <?php echo $post['author']; ?>
                    </td>
                    <td class="width-10">
                        <?php if(!empty($post['metaTitle']) && !empty($post['metaDescription'])) { echo '<span class="font-weight-300">ok</span>'; } else {echo '<span class="font-weight-300">-</span>'; } ?>
                    </td>
                    <td class="width-15">
                        <span class="padding-b-2 bold">Created:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($post["created_at"]) ); ?> <?php echo date("H:i:s", strtotime($post["created_at"]) ); ?></span><br>
                        <span class="bold">Updated:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($post["updated_at"]) ); ?> <?php echo date("H:i:s", strtotime($post["updated_at"]) ); ?></span>
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

                    if(!empty(Get::validate([get('search')])) ) {

                        echo '<li class="page-item"><a href="/admin/posts?search=' . Get::validate([get('search')]) . '&page='.$page.'">'.$page.'</a></li>';
                    } else {
                        echo '<li class="page-item"><a href="/admin/posts?page='.$page.'">'.$page.'</a></li>';
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