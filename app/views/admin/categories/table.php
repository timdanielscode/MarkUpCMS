<?php header('Content-Type: application/json; charset=utf-8'); ?>

<?php foreach($categories as $category) { ?>
                    <tr>
                        <?php if($category["title"] !== "not found" && $category["title"] !== "no category created") {?>
                        <td class="width-50">
                            <a href="/admin/categories/<?php echo $category['id']; ?>/edit" class="font-weight-500"><?php echo $category['title']; ?></a> |
                            <a href="/admin/categories/<?php echo $category['id']; ?>/edit" class="font-weight-300">Edit</a> |
                            <a href="/admin/categories/<?php echo $category['id']; ?>/preview" class="font-weight-300">Preview</a> |
                            <a href="/admin/categories/<?php echo $category['id']; ?>/delete" class="font-weight-300 color-red">Remove</a>
                        </td>
                        <?php } else { ?>
                        <td class="width-50">
                            <span class="font-weight-500"><?php echo $category['title'];?></span>
                        </td>
                        <?php } ?>
                        <td>
                            <span class="font-weight-500"><?php echo $category['slug'];?></span>
                        </td>
                        <td class="width-15">
                            <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $category["date_created_at"] . " " . $category["time_created_at"]; ?></span><br>
                            <span>Updated:</span> <span class="font-weight-300"><?php echo $category["date_updated_at"] . " " . $category["time_updated_at"]; ?></span>
                        </td>
                    </tr>
                <?php } ?>
