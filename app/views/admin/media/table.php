<?php header('Content-Type: application/json; charset=utf-8'); ?>

<?php if(!empty($allMedia) && $allMedia !== null) { ?>
    <?php foreach($allMedia as $media) { ?>
        <tr id="<?php echo $media["id"]; ?>">

            <td>
                <?php echo $media["id"]; ?>
            </td>
                <td class="width-25">
                    <a href="#<?php echo $media['media_filename']; ?>" data-role="edit" data-id="<?php echo $media['id']; ?>" id="mediaTitle-<?php echo $media['id']; ?>" class="mediaEdit font-weight-500"><?php echo $media["media_title"]; ?></a> |
                    <a href="#<?php echo $media['media_filename']; ?>" data-role="edit" data-id="<?php echo $media['id']; ?>" class="mediaEdit font-weight-300">Edit</a> |
                    <a href="#<?php echo $media['media_filename']; ?>" class="mediaPreview font-weight-300" data-id="<?php echo $media['id']; ?>">Read</a> |
                    <a href="/admin/media/<?php echo $media["id"]; ?>/delete" class="font-weight-300 color-red">Remove</a>
                </td>
            <td class="width-10">

                    <?php if($media['media_filetype'] == 'image/png' || $media['media_filetype']  == 'image/webp' || $media['media_filetype']  == 'image/gif' || $media['media_filetype']  == 'image/jpeg' || $media['media_filetype']  == 'image/svg+xml') { ?>
                        <a href="#<?php echo $media['media_filename']; ?>" class="mediaPreview font-weight-300" data-id="<?php echo $media['id']; ?>"><img src="/website/assets/img/<?php echo $media['media_filename']; ?>" id="imageSmall"></a>
                    <?php } else if ($type == 'application/pdf') { ?>  
                        <a href="#<?php echo $media['media_filename']; ?>" class="mediaPreview font-weight-300" data-id="<?php echo $media['id']; ?>"><iframe src="/website/assets/application/<?php echo $media['media_filename']; ?>" id="pdfSmall"></iframe></a>
                    <?php } else if ($type == 'video/mp4' || $type == 'video/quicktime') { ?>
                        <a href="#<?php echo $media['media_filename']; ?>" class="mediaPreview font-weight-300" data-id="<?php echo $media['id']; ?>"><video src="/website/assets/video/<?php echo $media['media_filename']; ?>" id="imageSmall"></video></a>
                    <?php } ?>
            </td>
            <td class="width-25">
                <span class="font-weight-400" id="mediaPath-<?php echo $media['id']; ?>">
                        <?php if($media['media_filetype'] == 'image/png' || $media['media_filetype']  == 'image/webp' || $media['media_filetype']  == 'image/gif' || $media['media_filetype']  == 'image/jpeg' || $media['media_filetype']  == 'image/svg+xml') { ?>
                            <?php echo '/website/assets/img/' . $media["media_filename"]; ?>
                        <?php } else if($type == 'video/mp4' || $type == 'video/quicktime') { ?>
                            <?php echo '/website/assets/video/' . $media["media_filename"] ?>
                        <?php } else if($type == 'application/pdf') { ?>
                            <?php echo '/website/assets/application/' . $media["media_filename"] ?>
                        <?php } ?>
                </span>
                <?php if($media["media_filename"] !== "-") { ?>
                <form>
                    <input class="mediaFilename" name="filename" id="filename-<?php echo $media['id']; ?>" type="text" value="<?php echo $media["media_filename"]; ?>"/>
                    <div id="message-<?php echo $media['id'] ?>"></div>
                </form>
                <?php } else { ?>
                    <span class="font-weight-500"><?php echo $media["media_filename"]; ?></span>
                <?php } ?>
            </td>
            <td class="width-10">
                <a data-role="update" id="update" class="button" data-id="<?php echo $media['id']; ?>">Update</a>
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
            <td class="width-10">
                <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $media["date_created_at"] . " " . $media["time_created_at"]; ?></span><br>
                <span>Updated:</span> <span class="font-weight-300"><?php echo $media["date_updated_at"] . " " . $media["time_updated_at"]; ?></span>
            </td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr id="<?php echo $media["id"]; ?>">
        <td class="width-25">-</td>
        <td class="width-10">-</td>
        <td class="width-25">-</td>
        <td class="width-10">-</td>
        <td class="width-10"></td>
        <td class="width-10">-</td>
        <td class="width-10">-</td>
    </tr>
<?php } ?>
