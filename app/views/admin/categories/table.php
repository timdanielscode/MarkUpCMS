<?php header('Content-Type: application/json; charset=utf-8'); ?>

<?php foreach($categories as $category) { ?>
    <tr>
        <?php if($category["title"] !== "not found" && $category["title"] !== "no category created") {?>
            <td class="width-50">
                <a href="#<?php echo $category['title']; ?>" data-role="edit" data-id="<?php echo $category['id']; ?>" id="categoryTitle-<?php echo $category['id']; ?>" class="mediaEdit font-weight-500"><?php echo $category["title"]; ?></a> |
                <a href="#" data-role="edit" data-id="<?php echo $category['id']; ?>" class="edit font-weight-300">Edit</a> |
                <a href="#" data-role="add" data-id="<?php echo $category['id']; ?>" class="add font-weight-300">Add</a> |
                <a href="#<?php echo $category['title']; ?>" class="read font-weight-300" data-id="<?php echo $category['id']; ?>">Read</a> |
                <a href="/admin/categories/<?php echo $category['id']; ?>/delete" class="font-weight-300 color-red">Remove</a>
            </td>
        <?php } else { ?>
            <td class="width-50">
                <span class="font-weight-500"><?php echo $category['title'];?></span>
            </td>
        <?php } ?>
            <td>
                <form>
                    <input class="mediaFilename" name="slug" id="slug-<?php echo $category['id']; ?>" type="text" value="<?php echo $category["slug"]; ?>"/>
                        <a data-role="update" id="update" data-id="<?php echo $category['id']; ?>">update</a>
                        <div id="message-<?php echo $category['id'] ?>"></div>
                </form>
            </td>
            <td class="width-15">
                <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $category["date_created_at"] . " " . $category["time_created_at"]; ?></span><br>
                <span>Updated:</span> <span class="font-weight-300"><?php echo $category["date_updated_at"] . " " . $category["time_updated_at"]; ?></span>
            </td>
    </tr>
<?php } ?>
