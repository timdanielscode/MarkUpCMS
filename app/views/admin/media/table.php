<?php header('Content-Type: application/json; charset=utf-8'); ?>

<?php if(!empty($allMedia) && $allMedia !== null) { ?>
    <?php foreach($allMedia as $media) { ?>
        <tr id="<?php echo $media["id"]; ?>">

            <td>
                <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $media['id']; ?>"/>
            </td>
            <td class="width-10">

                    <?php if($media['media_filetype'] == 'image/png' || $media['media_filetype']  == 'image/webp' || $media['media_filetype']  == 'image/gif' || $media['media_filetype']  == 'image/jpeg' || $media['media_filetype']  == 'image/svg+xml') { ?>
                        <a href="#<?php echo $media['media_filename']; ?>" class="mediaRead font-weight-300" data-id="<?php echo $media['id']; ?>"><img src="<?php echo "/" . $media['media_folder'] . '/' . $media['media_filename']; ?>" id="imageSmall" class="image"></a>
                    <?php } else if ($media['media_filetype'] == 'application/pdf') { ?>  
                        <a href="#<?php echo $media['media_filename']; ?>" class="mediaRead font-weight-300" data-id="<?php echo $media['id']; ?>"><img src="/assets/img/pdf.png" class="pdfImage" data-src="<?php echo '/' . $media['media_folder'] . '/' . $media['media_filename']; ?>"></a>
                    <?php } else if ($media['media_filetype'] == 'video/mp4' || $media['media_filetype'] == 'video/quicktime') { ?>
                        <a href="#<?php echo $media['media_filename']; ?>" class="mediaRead font-weight-300" data-id="<?php echo $media['id']; ?>"><video src="<?php echo "/" . $media['media_folder'] . '/' . $media['media_filename']; ?>" id="imageSmall" class="video"></video></a>
                    <?php } ?>
            </td>
            <td class="width-20">
                <span class="font-weight-400" id="mediaPath-<?php echo $media['id']; ?>">
                        <?php if($media['media_filetype'] == 'image/png' || $media['media_filetype']  == 'image/webp' || $media['media_filetype']  == 'image/gif' || $media['media_filetype']  == 'image/jpeg' || $media['media_filetype']  == 'image/svg+xml') { ?>
                            <?php echo "/" . $media['media_folder'] . '/' . $media["media_filename"]; ?>
                        <?php } else if($media['media_filetype'] == 'video/mp4' || $media['media_filetype'] == 'video/quicktime') { ?>
                            <?php echo "/" . $media['media_folder'] . '/' . $media["media_filename"] ?>
                        <?php } else if($media['media_filetype'] == 'application/pdf') { ?>
                            <?php echo "/" . $media['media_folder'] . '/' . $media["media_filename"] ?>
                        <?php } ?>
                </span>
                <?php if($media["media_filename"] !== "-") { ?>
                <form>
                    <input class="mediaFilename" name="filename" id="filename-<?php echo $media['id']; ?>" type="text" value="<?php echo $media["media_filename"]; ?>"/>
                    <div class="margin-t-10" id="MESSAGE-<?php echo $media['id'] ?>"></div>
                </form>
                <?php } else { ?>
                    <span class="font-weight-500"><?php echo $media["media_filename"]; ?></span>
                <?php } ?>
            </td>
            <td class="width-10">
                <a data-role="update" data-folder="<?php echo $media['media_folder']; ?>" id="update" class="button" value="<?php echo $media['id']; ?>" data-folder="<?php echo $media['media_folder']; ?>">Update</a>
            </td>
            <td class="width-15">
                <?php if(!empty($media['media_description']) && $media['media_description'] !== null) { ?>

                    <textarea id="description-<?php echo $media['id']; ?>" class="updateDescription" value="<?php echo $media['media_description']; ?>"><?php echo $media['media_description']; ?></textarea>
                <?php } else { ?>
                    <textarea id="description-<?php echo $media['id']; ?>" class="updateDescription" value="<?php echo $media['media_description']; ?>"><?php echo '-'; ?></textarea>
                <?php } ?>
                <div class="margin-t-10" id="MESSAGE-DESCRIPTION-<?php echo $media['id']; ?>"></div>
            </td>
            <td class="width-10 text-center">
                <a data-role="update-description" id="update-description" class="button" value="<?php echo $media['id']; ?>">Update</a>
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
                <span class="padding-b-2 bold">Created:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($media["created_at"]) ); ?> <?php echo date("H:i:s", strtotime($media["created_at"]) ); ?></span><br>
                <span class="bold">Updated:</span> <span class="font-weight-300"><?php echo date("d/m/Y", strtotime($media["updated_at"]) ); ?> <?php echo date("H:i:s", strtotime($media["updated_at"]) ); ?></span>
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
