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
    $this->stylesheet("/assets/css/modal.css");
    $this->stylesheet("/assets/css/index.css");
    $this->stylesheet("/assets/css/pagination.css");
    $this->stylesheet("/assets/css/media.css");

    $this->script('/assets/js/ajax.js');
    $this->script('/assets/js/media/index/read.js');
    $this->script('/assets/js/media/index/delete.js', true);
    $this->script('/assets/js/media/index/update/filename.js', true);
    $this->script('/assets/js/media/index/update/description.js', true);

    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">
    <div class="index"><?php Alert::message('success'); ?></div>
    <div class="headerContainer">
        <h1>Media</h1><span class="badge media"><?php echo $count; ?></span>
    </div>
    <a href="/admin/media/create" class="create">Switch layout</a> <?php if(Session::get('user_role') === 'admin') { ?><span class="deleteSeparator">|</span> <form action="/admin/media/delete" method="POST" class="indexDeleteForm"><input type="submit" class="delete" value="Delete"/><input type="hidden" name="deleteIds" id="deleteIds" value=""/><input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" /></form><?php } ?>
    <form action="" method="GET" class="searchForm">
        <input type="text" name="search" placeholder="Search" id="search">
        <input id="searchValue" type="hidden" name="submit" value="<?php if(!empty($search) && $search !== null) { echo $search; } ?>">
        <input type="submit" name="submit" value="Search" class="button">
    </form>

    <div id="MEDIAREAD"></div>

    <table>
        <thead>
            <th>#</th>
            <th>File</th>
            <th>Filename</th>
            <th></th>
            <th>Description</th>
            <th></th>
            <th>Type</th>
            <th>Size</th>
            <th>Date and time</th>
        </thead>
        <tbody id="mediaTableBody">

<?php if(!empty($allMedia) && $allMedia !== null) { ?>
    <?php foreach($allMedia as $media) { ?>
        <tr id="<?php echo $media["id"]; ?>">

            <td>
                <input class="deleteCheckbox" type="checkbox" name="delete" value="<?php echo $media['id']; ?>" <?php if(Session::get('user_role') === 'normal') { echo 'disabled'; } ?>/>
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
                <?php if(Session::get('user_role') === 'admin') { ?>
                <form action="/admin/media/update/filename" method="POST">
                    <input class="mediaFilename" name="filename" id="filename-<?php echo $media['id']; ?>" type="text" value="<?php echo $media["media_filename"]; ?>"/>
                    <div class="margin-t-10" id="MESSAGE-<?php echo $media['id'] ?>"></div>
                </form>
                <?php } else { ?>
                    <span class="font-weight-500 display-block"><?php echo $media["media_filename"]; ?></span>
                <?php } ?>
            </td>
            <td class="width-10">
                <?php if(Session::get('user_role') === 'admin') { ?><a data-role="update" data-folder="<?php echo $media['media_folder']; ?>" id="update" class="button" value="<?php echo $media['id']; ?>" data-folder="<?php echo $media['media_folder']; ?>">Update</a><?php } ?>
            </td>
            <td class="width-15">
                <?php if(Session::get('user_role') === 'admin') { ?>
                    <textarea id="description-<?php echo $media['id']; ?>" class="updateDescription" value="<?php echo $media['media_description']; ?>"><?php echo $media['media_description']; ?></textarea>
                <?php } else { ?>
                    <?php echo $media['media_description']; ?>
                <?php } ?>
                <div class="margin-t-10" id="MESSAGE-DESCRIPTION-<?php echo $media['id']; ?>"></div>
            </td>
            <td class="width-10 text-center">
                <?php if(Session::get('user_role') === 'admin') { ?><a data-role="update-description" id="update-description" class="button" value="<?php echo $media['id']; ?>">Update</a><?php } ?>
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
        <td>-</td>
        <td class="width-10">-</td>
        <td class="width-20">-</td>
        <td class="width-10"></td>
        <td class="width-15">-</td>
        <td class="width-10"></td>
        <td class="width-10">-</td>
        <td class="width-10">-</td>
        <td class="width-15">-</td>
    </tr>
<?php } ?>

        </tbody>

    </table>

    <div id="modal" class="display-none">
        <div class="mediaModalFormContainer">
            <form id="mediaModelForm">

            </form>
        </div>
        <a href="#" id="updateMediaModal" class="button">Update</a>
        <a href="#" id="mediaModalClose" class="button">Close</a>
    </div>

    <?php if($numberOfPages !== null && count($numberOfPages) > 1) { ?>
            <nav class="pagination">
            <ul>
                <?php 
                
                    foreach($numberOfPages as $page) {

                        if(!empty($search) ) {

                            echo '<li class="page-item"><a href="/admin/media?search=' . $search . '&page='.$page.'">'.$page.'</a></li>';
                        } else {
                            echo '<li class="page-item"><a href="/admin/media?page='.$page.'">'.$page.'</a></li>';
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