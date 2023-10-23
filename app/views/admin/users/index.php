<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use extensions\Pagination; ?>
<?php use validation\Get; ?>
<?php use core\Alert; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/index.css");
    $this->stylesheet("/assets/css/users.css");
    $this->stylesheet("/assets/css/pagination.css");

    $this->script("/assets/js/delete.js", true);
    $this->script("/assets/js/recover.js", true);

    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">
    <?php Alert::message('success'); ?>
    <div class="headerContainer">
        <h1>Users</h1>
            <span class="badge pages"><?php echo $count; ?></span>
    </div>

    <a href="/admin/users/create" class="create">Create</a> <span class="deleteSeparator">|</span> <form action="/admin/users/delete" method="POST" class="indexDeleteForm"><input type="submit" class="delete" value="<?php if(Get::validate([get('search')]) === 'Thrashcan') { echo 'Delete permanently'; } else { echo 'Delete'; } ?>"/><input type="hidden" name="deleteIds" id="deleteIds" value=""/></form> | <form action="" method="GET" class="thrashcanForm"><input type="submit" name="search" value="Thrashcan"/></form><?php if(Get::validate([get('search')]) === 'Thrashcan') { ?> | <form action="/admin/users/recover" method="POST" class="recoverForm"><input type="submit" class="recover" value="Recover"/><input type="hidden" name="recoverIds" id="recoverIds" value=""/></form> <?php } ?>
    <form action="" method="GET" class="searchForm">
        <input type="text" name="search" placeholder="Search" id="search">
        <input type="submit" name="submit" value="Search" class="button">
    </form>
    <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($allUsers) && $allUsers !== null) { ?>
                    <?php foreach($allUsers as $user) { ?>
                        <tr>
                            <td>
                                <?php if($user['name'] === 'normal') { ?>
                                    <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $user['id']; ?>"/>
                                <?php } else { ?>
                                    <input class="deleteCheckbox" type="checkbox" disabled/>
                                <?php } ?>
                            </td>
                            <td class="width-20">
                            <?php if($user['name'] === 'admin' || $user['removed'] === 1) { ?>
                                <?php echo $user['username'] . ' | <span class="isAdmin">admin</span>'; ?>
                            <?php } else { ?>
                                <a href="/admin/users/<?php echo $user['username']; ?>/edit" class="font-weight-500"><?php echo $user['username']; ?></a>
                            <?php } ?>
                            <td class="width-65">
                                <?php echo $user['email']; ?>
                            </td>
                            <td class="width-15">
                                <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $user["created_at"]; ?></span><br>
                                <span>Updated:</span> <span class="font-weight-300"><?php echo $user["updated_at"]; ?></span>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>

                    <tr>
                        <td>-</td>
                        <td class="width-20">-</td>
                        <td class="width-20">-</td>
                        <td class="width-50">-</td>
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
                        echo '<li class="page-item"><a href="/admin/users?page='.$page.'">'.$page.'</a></li>';
                    }  
                ?>
            </ul>
        </nav>
        <?php } ?>
</div>

<?php 
    $this->include('footer');
?>