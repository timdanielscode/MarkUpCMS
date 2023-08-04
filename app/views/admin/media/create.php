<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/media.css");
    $this->stylesheet("/assets/css/sidebar.css");
    $this->stylesheet("/assets/css/pagination.css");

    
    $this->script('/assets/js/media/selection.js', true);
    $this->script('/assets/js/media/ranger.js', true);
    $this->script('/assets/js/media/info.js', true);
    

    $this->title("IndependentCMS");
    $this->include("headerClose");
    $this->include('navbar');
?>
 

        <div class="row">
            <div class="col10 col9-L">
                <div class="crumPath">
                    <?php if(!empty(get('folder'))) {

                        $folderParts = explode('/', get('folder')); 
                        unset($folderParts[0]);
                        $link = implode('/', $folderParts);

                        echo '<span>/</span>';
                        echo '<span>website</span>';

                        foreach($folderParts as $part) { 

                            $regex = "/\/". $part . "\/.*/";
                            preg_match($regex, "/" . implode('/', $folderParts) . "/", $match);
                            echo '<span class="separator">/</span>';
                            echo '<a class="folderPath" href="?folder=website' . str_replace($match, "", "/" . implode('/', $folderParts) . '/') . "/" . $part . '">' . $part . '</a>';
                        } 
                    } ?>
                </div>
                <form action="" method="POST" class="folderForm">
                    <input type="text" name="P_folder">
                    <input type="submit" name="submitFolder" value="Folder"/>
                </form>
                <div class="filesContainer">
                    <div class="row flex-center">
                        <?php if(!empty($folders) && $folders !== null) { 
                            foreach($folders as $folder) { 
                                if($folder !== 'website/assets/css' && $folder !== 'website/assets/js') {
                                    $folderParts = explode('/', $folder);
                                    unset($folderParts[0]);
                                    unset($folderParts[1]);
                                    $folderWithoutWebsiteAndAssets = implode('/', $folderParts);
                                    if(!empty($folderWithoutWebsiteAndAssets) && $folderWithoutWebsiteAndAssets !== null) {
                                        echo '<a href="?folder='.$folder.'">';
                                            echo '<div class="fileContainer folder">';
                                                echo '<img class="file folder" src="/assets/img/folder.png"/>';
                                                echo '<span class="folderText">' . $folderParts[array_key_last($folderParts)] . '</span>';
                                            echo '</div>';
                                        echo '</a>';
                                    } 
                                } 
                            } 
                        } ?>
                        <?php if(!empty($files) && $files !== null) { ?>
                            <?php foreach($files as $file) { ?>
                                <?php switch ($file['media_filetype']) {
                                        case 'image/png':
                                        case 'image/webp':
                                        case 'image/gif':
                                        case 'image/jpeg':
                                        case 'image/svg+xml':

                                            $type = 'img';
                                        break;
                                        case 'video/mp4':
                                        case 'video/quicktime':
                                            
                                            $type = 'video';
                                        break;  
                                        case 'application/pdf':

                                            $type = 'application';
                                        break;
                                        default:

                                            $type = 'image/png';
                                        break;
                                    }
                                ?>
                                <div class="fileContainer">
                                    <?php if($type === 'img') { ?>
                                    <img class="file mediaFile" data-id="<?php echo $file['id']; ?>" data-filename="<?php echo $file['media_filename']; ?>"  data-folder="<?php echo $file['media_folder']; ?>" data-filetype="<?php echo $file['media_filetype']; ?>" data-filesize="<?php echo $file['media_filesize']; ?>" src="<?php if(!empty(get('folder')) ) { echo '/' . get('folder') . '/'; } else { echo '/website/assets/'; } echo $file['media_filename']; ?>" loading="lazy">
                                    <?php } else if($type === 'video') { ?>
                                        <video class="file mediaFile" data-id="<?php echo $file['id']; ?>" data-filename="<?php echo $file['media_filename']; ?>" data-folder="<?php echo $file['media_folder']; ?>" data-filetype="<?php echo $file['media_filetype']; ?>" data-filesize="<?php echo $file['media_filesize']; ?>" src="<?php if(!empty(get('folder')) ) { echo '/' . get('folder') . '/'; } else { echo '/website/assets/'; } echo $file['media_filename']; ?>" controls></video>
                                    <?php } else if($type === 'application') { ?>
                                        <iframe class="file mediaFile" data-id="<?php echo $file['id']; ?>" data-filename="<?php echo $file['media_filename']; ?>" data-folder="<?php echo $file['media_folder']; ?>" data-filetype="<?php echo $file['media_filetype']; ?>" data-filesize="<?php echo $file['media_filesize']; ?>" src="<?php if(!empty(get('folder')) ) { echo '/' . get('folder') . '/'; } else { echo '/website/assets/'; } echo $file['media_filename']; ?>"></iframe>
                                    <?php } ?>
                                    <input class="deleteSelection" type="checkbox"/> 
                                    <!--<div class="layer"><span class="mediaTitle"><?php //echo $file['media_filetype']; ?></span></div>-->
                                </div>
                        <?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="rangerContainer">
                    <input type="range" min="50" max="500" value="100" id="ranger">
                </div>
            </div>
            <div class="col2 col3-L">
                <div id="sidebar" class="width-25-L">
                    <div class="sidebarContainer">
                        <div class="mainButtonContainer">
                            <label for="submit" class="button">Upload</label>
                            <a href="/admin/media" class="button">Back</a>
                        </div>
                        <form action="" method="POST" class="uploadFileForm" enctype="multipart/form-data">
                            <!--<div class="form-parts">
                                <label>Title *</label>
                                <input name="media_title" type="text" id="media_title" autofocus>
                                <div class="error-messages">
                                    <?php //echo Errors::get($rules, 'media_title'); ?>
                                    <?php //echo Errors::get($rules, 'file'); ?>
                                </div>
                            </div>
                            <div class="form-parts">
                                <label>Description </label>
                                <textarea name="media_description" type="text" id="media_description" autofocus></textarea>
                                <div class="error-messages">
                                    <?php //echo Errors::get($rules, 'media_description'); ?>
                                </div>
                            </div>-->
                            <div class="form-parts">
                                <input name="file[]" type="file" multiple="true" id="file" class="display-none">
                            </div>
                            <div class="form-parts">
                                <button name="submit" id="submit" type="submit" class="display-none">Upload</button>
                                <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                            </div>
                        </form>
                        <div class="buttonContainer">
                            <label for="file" class="button">Select files</label>
                            <form action="" method="POST" class="deleteForm">
                                <input id="selectedFiles" type="hidden" name="files" value="[]"/>
                                <input type="submit" name="submitDelete" class="button" value="Delete"/>
                            </form>
                        </div>
                        <!--<form action="" method="POST" class="createFolderForm">
                            <input type="text" name="newFolder">
                            <input type="submit" name="submitNewFolder" class="button" value="Create folder"/>
                        </form>-->
                        <!--<form action="" method="POST" class="deleteFolderForm">
                            <input type="text" name="deleteFolder">
                            <input type="submit" name="submitDeleteFolder" class="button" value="Delete folder"/>
                        </form>-->



                        <!--<span class="text">Total: </span>
                        <span class="data"><?php //cho count($files); ?></span>
                        <span class="text">Max upload size:</span>
                        <span class="data"><?php //echo ini_get('upload_max_filesize'); ?></span>
                        <span class="text">Search: </span>
                        <form action="/admin/media/create" method="GET" class="searchFormCreate">
                            <input type="text" name="search" placeholder="Search"/>
                        </form>
                        <span class="text">Filter:</span>
                        <form action="/admin/media/create" method="GET" class="filterForm">
                            <select name="type[]" multiple>
                                <option value="png">Png</option>
                                <option value="jpeg">Jpeg</option>
                                <option value="gif">Gif</option>
                                <option value="webp">Webp</option>
                                <option value="svg">Svg</option>
                                <option value="video">Video</option>
                                <option value="pdf">Pdf</option>
                            </select>
                            <input type="submit" class="button" name="filter" value="Filter"/>
                        </form>-->

                        <div class="fileInfoContainer display-none">
                            <div id="currentImage"></div>
                            <span class="text">File:</span>
                            <span id="currentFile"></span>
                            <span id="currentFilename"></span>
                            <span class="text">Type: </span>
                            <span id="currentFiletype"></span>
                            <span class="text">Size: </span>
                            <span id="currentFilesize"></span>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
<?php 
    $this->include('footer');
?>