<?php use core\Session; ?>

<?php if(!empty($categories) && $categories !== null) { ?>
    <?php foreach($categories as $category) { ?>
        <tr>

                <td>
                    <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $category['id']; ?>" <?php if(Session::get('user_role') === 'normal') { echo 'disabled'; } ?>/>
                </td>
                <?php if(Session::get('user_role') === 'admin') { ?><td class="width-20">
                    <a data-id="<?php echo $category['id']; ?>" data-id="<?php echo $category['id']; ?>" class="edit font-weight-300" id="TABLE-TITLE-<?php echo $category['id']; ?>"><?php echo $category["title"]; ?></a> |
                    <a data-id="<?php echo $category['id']; ?>" class="edit font-weight-300" class="edit">Edit</a> |
                    <a href="#" data-role="add" data-id="<?php echo $category['id']; ?>" class="add font-weight-300">Apply</a> |
                    <a href="#<?php echo $category['title']; ?>" class="read font-weight-300" data-id="<?php echo $category['id']; ?>">Read</a>
                </td>
                <?php } else { ?>
                    <td class="width-20">
                        <?php echo $category['title'] . ' | '; ?>
                        <a href="#<?php echo $category['title']; ?>" class="read font-weight-300" data-id="<?php echo $category['id']; ?>">Read</a>
                    </td>
                <?php } ?>
                <td class="width-30">
                    <form>
                        <input class="categorySlug" name="slug" id="slug-<?php echo $category['id']; ?>" type="text" value="<?php echo substr($category['slug'], 1); ?>"/>
                            <div id="message-<?php echo $category['id'] ?>"></div>
                    </form>
                </td>
                <td class="width-10">
                    <a data-role="update" id="update" data-id="<?php echo $category['id']; ?>" class="button">Update</a>
                </td>
                <td class="widht-25">
                    <?php echo $category['author']; ?>
                </td>
                <td class="width-15">
                    <span class="padding-b-2 bold">Created:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($category["created_at"]) ); ?> <?php echo date("H:i:s", strtotime($category["created_at"]) ); ?></span><br>
                    <span class="bold">Updated:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($category["updated_at"]) ); ?> <?php echo date("H:i:s", strtotime($category["updated_at"]) ); ?></span>
                </td> 
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td>-</td>
        <td class="width-20">-</td>
        <td class="width-30">-</td>
        <td class="width-10">-</td>
        <td class="width-25">-</td>
        <td class="width-15">-</td>
    </tr>

<?php } ?>