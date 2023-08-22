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
        <h1>Menus</h1><span class="badge forms"></span>
    </div>
    <a href="/admin/forms/create" class="create">Create</a> <span class="deleteSeparator">|</span> <form action="/admin/menus/delete" method="POST" class="indexDeleteForm"><input type="submit" class="delete" value="<?php if(get('search') === 'Thrashcan') { echo 'Delete permanently'; } else { echo 'Delete'; } ?>"/><input type="hidden" name="deleteIds" id="deleteIds"/></form> | <form action="" method="GET" class="thrashcanForm"><input type="submit" name="search" value="Thrashcan"/></form><?php if(get('search') === 'Thrashcan') { ?> | <form action="/admin/menus/recover" method="POST" class="recoverForm"><input type="submit" class="recover" value="Recover"/><input type="hidden" name="recoverIds" id="recoverIds" value=""/></form> <?php } ?>
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
                <?php if(!empty($forms) && $forms !== null) { ?>
                    <?php foreach($forms as $form) { ?>
                        <tr>
                            <td>
                                <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $form['id']; ?>"/>
                            </td>
                            <?php if($form['removed'] !== 1) { ?>
                                <td class="width-25">
                                    <a href="/admin/forms/<?php echo $form['id']; ?>/edit" class="font-weight-500"><?php echo $form['title']; ?></a> |
                                    <a href="/admin/forms/<?php echo $form['id']; ?>/edit" class="font-weight-300">Edit</a> |
                                    <a href="/admin/forms/<?php echo $form['id']; ?>/read" class="font-weight-300">Read</a>
                                </td>
                            <?php } else { ?>
                                <td class="width-25">
                                    <span class="removed font-weight-500"><?php echo $form['title']; ?></span> |
                                    <a href="/admin/forms/<?php echo $form['id']; ?>/read" class="font-weight-300">Read</a>
                                </td>
                            <?php } ?>
                            <td class="width-15">
                                <span class="padding-b-2 bold">Created:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($form["created_at"]) ); ?> <?php echo date("H:i:s", strtotime($form["created_at"]) ); ?></span><br>
                                <span class="bold">Updated:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($form["updated_at"]) ); ?> <?php echo date("H:i:s", strtotime($form["updated_at"]) ); ?></span>
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
</div>

<?php 
    $this->include('footer');
?>