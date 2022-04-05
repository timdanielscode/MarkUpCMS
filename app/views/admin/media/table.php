<?php header('Content-Type: application/json; charset=utf-8'); ?>

<?php foreach($allMedia as $media) { ?>
    <tr>
        <?php if($media["media_title"] !== "not found") { ?>
            <td class="width-30">
                <a href="/admin/media/<?php echo $media["id"]; ?>/edit" class="font-weight-500"><?php echo $media["media_title"]; ?></a> |
                <a href="/admin/media/<?php echo $media["id"]; ?>/edit" class="font-weight-300">Edit</a> |
                <a href="/admin/media/<?php echo $media["id"]; ?>/preview" class="font-weight-300">Preview</a> |
                <a href="/admin/media/<?php echo $media["id"]; ?>/delete" class="font-weight-300 color-red">Remove</a>
            </td>
        <?php } else { ?>
            <td>
                <span class="font-weight-500"><?php echo $media["media_title"]; ?></span>
            </td>
        <?php } ?>
        <td class="width-10">
            <?php $type = $media['media_filetype']; ?>
            <?php if($type == 'image/png' || $type  == 'image/webp' || $type  == 'image/gif' || $type  == 'image/jpeg' || $type  == 'image/svg+xml') { ?>
                <img src="/website/assets/img/<?php echo $media['media_filename']; ?>" id="imageSmall">
            <?php } else if ($type == 'application/pdf') { ?>  
                <iframe src="/website/assets/application/<?php echo $media['media_filename']; ?>" id="pdfSmall"></iframe>
            <?php } else if ($type == 'video/mp4' || $type == 'video/quicktime') { ?>
                <video src="/website/assets/video/<?php echo $media['media_filename']; ?>" id="imageSmall"></video>
            <?php } ?>
        </td>
        <td>
            <span class="font-weight-400">
                <?php $type = $media['media_filetype']; ?>
                    <?php if($type == 'image/png' || $type  == 'image/webp' || $type  == 'image/gif' || $type  == 'image/jpeg' || $type  == 'image/svg+xml') { ?>
                        <?php echo '/website/assets/img/'; ?>
                    <?php } else if($type == 'video/mp4' || $type == 'video/quicktime') { ?>
                        <?php echo '/website/assets/video/'; ?>
                    <?php } else if($type == 'application/pdf') { ?>
                        <?php echo '/website/assets/application/'; ?>
                    <?php } ?>
            </span>
            <form>
                <input class="mediaFilename" name="filename" id="filename-<?php echo $media['id']; ?>" type="text" value="<?php echo $media["media_filename"]; ?>"/>
                <a data-role="update" id="update" data-id="<?php echo $media['id']; ?>">update</a>
                <div id="message-<?php echo $media['id'] ?>"></div>
            </form>
        </td>
        <td class="width-10">
            <span class="font-weight-500"><?php echo $media['media_filetype']; ?></span>
        </td>
        <td class="width-10">
            <span class="font-weight-400">
                <?php 
                    $filesize = $media['media_filesize'] / 1000000;
                    $filesize = number_format((float)$filesize, 2, '.', '');
                    echo $filesize;
                ?>
            </span>
            <span class="font-weight-500"> 
                mb
            </span> 
        </td>
        <td class="width-15">
            <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $media["date_created_at"] . " " . $media["time_created_at"]; ?></span><br>
            <span>Updated:</span> <span class="font-weight-300"><?php echo $media["date_updated_at"] . " " . $media["time_updated_at"]; ?></span>
        </td>
    </tr>
<?php } ?>
