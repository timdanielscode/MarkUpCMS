
<?php if(!empty($categories) && $categories !== null) { ?>
    <?php foreach($categories as $category) { ?>
        <tr>

                <td>
                    <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $category['id']; ?>"/>
                </td>
                <td class="width-20">
                    <a data-id="<?php echo $category['id']; ?>" data-id="<?php echo $category['id']; ?>" class="edit font-weight-300" id="TABLE-TITLE-<?php echo $category['id']; ?>"><?php echo $category["title"]; ?></a> |
                    <a data-id="<?php echo $category['id']; ?>" class="edit font-weight-300" class="edit">Edit</a> |
                    <a href="#" data-role="add" data-id="<?php echo $category['id']; ?>" class="add font-weight-300">Apply</a> |
                    <a href="#<?php echo $category['title']; ?>" class="read font-weight-300" data-id="<?php echo $category['id']; ?>">Read</a>
                </td>
                <td class="width-20">
                    <form>
                        <input class="categorySlug" name="slug" id="slug-<?php echo $category['id']; ?>" type="text" value="<?php echo substr($category['slug'], 1); ?>"/>
                            <div id="message-<?php echo $category['id'] ?>"></div>
                    </form>
                </td>
                <td class="width-50">
                    <a data-role="update" id="update" data-id="<?php echo $category['id']; ?>" class="padding-6">Update</a>
                </td>
                <td class="width-10">
                    <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $category["date_created_at"] . " " . $category["time_created_at"]; ?></span><br>
                    <span>Updated:</span> <span class="font-weight-300"><?php echo $category["date_updated_at"] . " " . $category["time_updated_at"]; ?></span>
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