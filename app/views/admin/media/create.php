<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use validation\Get; ?>
<?php use core\Alert; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/media.css");
    $this->stylesheet("/assets/css/sidebar.css");
    $this->stylesheet("/assets/css/pagination.css");

    $this->script('/assets/js/media/create/Sidebar.js', true);
    $this->script('/assets/js/media/create/FileContainer.js', true);
    $this->script('/assets/js/media/create/ReadImageContainer.js', true);
    $this->script('/assets/js/media/create/Ranger.js', true);
    $this->script('/assets/js/media/create/main.js', true);
    
    $this->script('/assets/js/ajax.js');
    $this->script('/assets/js/media/create/update/filename.js');
    $this->script('/assets/js/media/create/update/description.js');

    $this->include("headerClose");
    $this->include('navbar');
?>
 

        <div class="row">
            <div class="col10 col9-L">

                    <?php if(!empty(Get::validate([get('folder')])) && Get::validate([get('folder')]) !== 'website/assets') {

                        echo '<div class="crumPath">';

                        $folderParts = explode('/', Get::validate([get('folder')])); 
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
                        echo '</div>';
                    } else { ?>
      
                        <div class="crumPath"><span>/website</span><span class="separator">/</span><a class="folderPath" href="?folder=website/assets">assets</a></div>

                    <?php } ?>
                <?php if(Session::get('user_role') === 'admin') { ?>
                    <form action="" method="POST" class="folderForm">
                        <input type="text" placeholder="Folder" name="P_folder">
                        <input type="submit" name="submitFolder" value="Folder &plusmn"/>
                        <?php if(!empty(Errors::get($rules, 'P_folder')) && Errors::get($rules, 'P_folder') !== null) { ?>
                            <div class="error-messages font-size-14 folder">
                                <span><?php echo Errors::get($rules, 'P_folder'); ?></span>
                            </div>    
                        <?php } ?> 
                        <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                    </form>
                <?php } ?>
                <?php Alert::message('success'); ?>
                <div class="readImageContainer display-none"></div>
                <div class="filesContainer">
                    <div class="row flex-center">
                        <?php if(!empty($folders) && $folders !== null && empty(get('search') ) && empty(get('filter') )) { 
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
                                    <img class="file mediaFile imgFile" data-id="<?php echo $file['id']; ?>" data-filename="<?php echo $file['media_filename']; ?>"  data-folder="<?php echo $file['media_folder']; ?>" data-filetype="<?php echo $file['media_filetype']; ?>" data-filesize="<?php echo $file['media_filesize']; ?>" src="<?php echo '/' . $file['media_folder'] . '/' . $file['media_filename']; ?>" data-description="<?php if(!empty($file['media_description']) && $file['media_description'] !== null) { echo $file['media_description']; } else { echo '-'; } ?>" loading="lazy">
                                    <?php } else if($type === 'video') { ?>
                                        <video class="file mediaFile videoFile" data-id="<?php echo $file['id']; ?>" data-filename="<?php echo $file['media_filename']; ?>" data-folder="<?php echo $file['media_folder']; ?>" data-filetype="<?php echo $file['media_filetype']; ?>" data-filesize="<?php echo $file['media_filesize']; ?>" src="<?php echo '/' . $file['media_folder'] . '/' . $file['media_filename']; ?>" data-description="<?php if(!empty($file['media_description']) && $file['media_description'] !== null) { echo $file['media_description']; } else { echo '-'; } ?>" loading="lazy"></video>
                                    <?php } else if($type === 'application') { ?>
                                        <img class="file mediaFile pdfFile" data-id="<?php echo $file['id']; ?>" data-filename="<?php echo $file['media_filename']; ?>" data-folder="<?php echo $file['media_folder']; ?>" data-filetype="<?php echo $file['media_filetype']; ?>" data-filesize="<?php echo $file['media_filesize']; ?>" src="/assets/img/pdf.png" data-description="<?php if(!empty($file['media_description']) && $file['media_description'] !== null) { echo $file['media_description']; } else { echo '-'; } ?>" loading="lazy"></iframe>
                                    <?php } ?>
                                    <input class="deleteSelection <?php if(Session::get('user_role') === 'normal') { echo 'display-none-important'; } ?>" type="checkbox"/>
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
                            <label for="submit" class="button greenButton margin-r-10 <?php if(Session::get('user_role') === 'normal') { echo 'display-none-important'; } ?>">Upload</label>
                            <form action="" method="POST" class="deleteForm display-none">
                                <input id="selectedFiles" type="hidden" name="files" value=""/>
                                <input type="submit" name="submitDelete" class="button redButton margin-r-10" value="Delete"/>
                                <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                            </form>
                            <a href="#" class="button read blueButton display-none-important">Read</a>
                            <a href="#" class="button close blueButton display-none-important">Close</a>
                            <a href="/admin/media" class="button back darkBlueButton">Back</a>
                        </div>
                            <form action="" method="POST" class="uploadFileForm <?php if(Session::get('user_role') === 'normal') { echo 'display-none-important'; } ?>" enctype="multipart/form-data">
                                <div class="form-parts">
                                    <label>Description: </label>
                                    <textarea name="media_description" type="text" id="media_description" autofocus placeholder="Optional"></textarea>
                                    <?php if(!empty(Errors::get($rules, 'file')) && Errors::get($rules, 'file') !== null) { ?>
                                        <div class="error-messages margin-tm-10 font-size-14">
                                            <span><?php echo Errors::get($rules, 'file'); ?></span>
                                        </div>    
                                    <?php } ?> 
                                    <?php if(!empty(Errors::get($rules, 'media_description')) && Errors::get($rules, 'media_description') !== null) { ?>
                                        <div class="error-messages margin-tm-10 font-size-14">
                                            <span><?php echo Errors::get($rules, 'media_description'); ?></span>
                                        </div>    
                                    <?php } ?> 
                                </div>
                                <div class="form-parts">
                                    <input name="file[]" type="file" multiple="true" id="file" class="display-none">
                                </div>
                                <div class="form-parts">
                                    <button name="submit" id="submit" type="submit" class="display-none <?php if(Session::get('user_role') === 'normal') { echo 'display-none-important'; } ?>">Upload</button>
                                    <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                                </div>
                            </form>
                        
                        <div class="buttonContainer">
                        <label for="file" class="button darkButton margin-t-10 <?php if(Session::get('user_role') === 'normal') { echo 'display-none-important'; } ?>">Select files</label>
                        </div>
                        <div class="totalContainer">
                            <span class="text">Total: </span>
                            <span class="data"><?php echo count($files); ?></span>
                        </div>
                        <form action="" method="GET" class="searchFormCreate">
                            <label for="#search">Search: </label>
                            <input type="text" name="search" placeholder="Search"/>
                        </form>
                        <form action="/admin/media/create" method="GET" class="filterForm">
                            <label>Filter: </label>
                            <select name="type[]" multiple>
                                <option value="png">Png</option>
                                <option value="jpeg">Jpeg</option>
                                <option value="gif">Gif</option>
                                <option value="webp">Webp</option>
                                <option value="svg">Svg</option>
                                <option value="video">Video</option>
                                <option value="pdf">Pdf</option>
                            </select>
                            <input type="submit" class="button greenButton margin-t-10 margin-r-10" name="filter" value="Apply"/><input type="submit" class="button blueButton margin-t-10" name="applied-filter" value="Unset"/>
                        </form>
                        <div class="fileInfoContainer display-none">
                            <div id="currentFile"></div>
                            <div class="infoContainer">
                                <div class="infoPart">
                                    <span class="infoText">File:</span>
                                    <span class="infoData" id="currentFolderFilename"></span>
                                    <span id="currentFolder"></span>
                                    <input id="currentFilename" type="text" name="filename" value=""/>
                                    <div id="MESSAGE"></div>
                                    <?php if(Session::get('user_role') === 'admin') { ?><a data-role="update" id="update" class="button greenButton margin-t-10 width-50-px">Update</a><?php } ?>
                                </div>
                                <div class="infoPart">
                                    <span class="infoText">Type: </span>
                                    <span class="infoData" id="currentFiletype"></span>
                                </div>
                                <div class="infoPart">
                                    <span class="infoText">Size: </span>
                                    <span class="infoData" id="currentFilesize"></span>
                                </div>
                                <div class="infoPart">
                                    <span class="infoText">Description: </span>
                                    <textarea type="text" name="description" class="infoData" id="currentDescription"></textarea>
                                    <div id="MESSAGE-DESCRIPTION"></div>
                                    <?php if(Session::get('user_role') === 'admin') { ?><a data-role="update-description" id="updateDescription" class="button greenButton margin-t-10 width-50-px">Update</a><?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
<?php 
    $this->include('footer');
?>