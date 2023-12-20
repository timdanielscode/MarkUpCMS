<!-- 
    - to show overview of media files and folders
    - to navigate through folders by clicking on folders
    - to navigate through folders by clicking on crumpath and to show current directory
    - to select a file to show more details and or deselect file if already selected
    - to show file with a bigger width by clicking on file after selection or read button to have a better view of file
    - to get a different overview by adjusting file and folder width sizes using a slider bar
    - to search for specific media files
    - to filter media files on file type
    -
    - FOR TYPE OF ADMIN USER
    -
    - to create a selection of files to submit and upload new files
    - to create a selection of files to submit and remove files
    - to apply changes of filename value to submit and update
    - to apply changes of description value to submit and update
    - to add a folder value to submit and create a new folder or delete a folder (depending if exists)
--> 

<?php $this->include('openHeadTag'); ?>  
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/media.css"); ?>
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
    <?php $this->stylesheet("/assets/css/pagination.css"); ?>
    <?php $this->script('/assets/js/media/Sidebar.js', true); ?>
    <?php $this->script('/assets/js/media/FileContainer.js', true); ?>
    <?php $this->script('/assets/js/media/ReadImageContainer.js', true); ?>
    <?php $this->script('/assets/js/media/Ranger.js', true); ?>
    <?php $this->script('/assets/js/media/main.js', true); ?>
<?php $this->include("closeHeadTagAndOpenBodyTag"); ?>

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col10-L- col9-L col8-S">
            <?php if(!empty($folder) && $folder !== 'website/assets') {
                
                    echo '<div class="crumPath">';

                    $folderParts = explode('/', $folder); 
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
        <?php if(core\Session::get('user_role') === 'admin') { ?>
            <form action="/admin/media/folder?<?php echo "folder=" . $folder; ?>" method="POST" class="folderForm">
                <input type="text" placeholder="Folder" name="P_folder">
                <input type="submit" name="submitFolder" value="Folder &plusmn"/>
                <?php if(!empty(validation\Errors::get($rules, 'P_folder')) && validation\Errors::get($rules, 'P_folder') !== null) { ?>
                    <div class="error-messages font-size-14 folder">
                        <span><?php echo validation\Errors::get($rules, 'P_folder'); ?></span>
                    </div>    
                <?php } ?> 
            </form>
        <?php } ?>
        
        <?php core\Alert::message('success'); ?>

        <div class="readImageContainer display-none"></div>
        <div class="filesContainer">
            <div class="row flex-center">
                <?php if(!empty($folders) && $folders !== null && empty($search) && $types === false ) { 
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
                            <input class="deleteSelection <?php if(core\Session::get('user_role') === 'normal') { echo 'display-none-important'; } ?>" type="checkbox"/>
                        </div>
                <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div class="rangerContainer">
            <input type="range" min="50" max="500" value="100" id="ranger">
        </div>
    </div>
    <div class="col2 col2-L col3-L col4-S">
        <div id="sidebar" class="width-25">
            <div class="sidebarContainer">
                <div class="mainButtonContainer">
                    <label for="submit" class="button greenButton margin-r-10 <?php if(core\Session::get('user_role') === 'normal') { echo 'display-none-important'; } ?>">Upload</label>
                    <form action="/admin/media/delete?<?php echo "folder=" . $folder; ?>" method="POST" class="deleteForm display-none">
                        <input id="selectedFiles" type="hidden" name="deleteIds" value=""/>
                        <input type="submit" name="submitDelete" class="button redButton margin-r-10" value="Delete"/>
                    </form>
                    <a href="#" class="button read blueButton display-none-important">Read</a>
                    <a href="#" class="button close blueButton display-none-important">Close</a>
                    <a href="/admin/posts" class="button back blueButton">Back</a>
                </div>
                    <form action="" method="POST" class="uploadFileForm <?php if(core\Session::get('user_role') === 'normal') { echo 'display-none-important'; } ?>" enctype="multipart/form-data">
                        <div class="form-parts">
                            <label>Description: </label>
                            <textarea name="media_description" type="text" id="media_description" autofocus placeholder="Optional"></textarea>
                            <?php if(!empty(validation\Errors::get($rules, 'file')) && validation\Errors::get($rules, 'file') !== null) { ?>
                                <div class="error-messages margin-tm-10 font-size-14">
                                    <span><?php echo validation\Errors::get($rules, 'file'); ?></span>
                                </div>    
                            <?php } ?> 
                            <?php if(!empty(validation\Errors::get($rules, 'media_description')) && validation\Errors::get($rules, 'media_description') !== null) { ?>
                                <div class="error-messages margin-tm-10 font-size-14">
                                    <span><?php echo validation\Errors::get($rules, 'media_description'); ?></span>
                                </div>    
                            <?php } ?> 
                        </div>
                        <div class="form-parts">
                            <input name="file[]" type="file" multiple="true" id="file" class="display-none">
                        </div>
                        <div class="form-parts">
                            <button name="submit" id="submit" type="submit" class="display-none <?php if(core\Session::get('user_role') === 'normal') { echo 'display-none-important'; } ?>"></button>
                        </div>
                    </form>
                <div class="buttonContainer">
                <label for="file" class="button darkButton margin-t-10 <?php if(core\Session::get('user_role') === 'normal') { echo 'display-none-important'; } ?>">Select files</label>
                </div>
                <div class="totalContainer">
                    <span class="text">Total: </span>
                    <span class="data"><?php echo count($files); ?></span>
                </div>
                <form action="" method="GET" class="searchFormCreate">
                    <label for="#search">Search: </label>
                    <input type="text" name="search" placeholder="Search"/>
                </form>
                <form action="/admin/media" method="GET" class="filterForm">
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
                    <input type="submit" class="button blueButton margin-t-10 margin-r-10" name="filter" value="Apply"/>
                </form>
                <div class="fileInfoContainer display-none">
                    <div id="currentFile"></div>
                    <div class="infoContainer">
                        <div class="infoPart">
                        <span class="infoText">File:</span>
                            <span class="infoData" id="currentFolderFilename"></span>
                            <form action="/admin/media/update/filename" method="POST">
                                <input id="currentFilename" type="text" name="filename"/>
                                <input type="hidden" name="id" id="update" value=""/>
                                <?php if(core\Session::get('user_role') === 'admin') { ?>
                                    <input type="submit" name="submit" value="Update" class="button darkBlueButton margin-t-10"/>
                                <?php } ?>
                            </form>
                            <span id="currentFolder"></span>
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
                            <form action="/admin/media/update/description" method="POST">
                                <span class="infoText">Description: </span>
                                <textarea type="text" name="description" class="infoData" id="currentDescription"></textarea>
                                <input type="hidden" name="id" value="" id="updateDescription"/>
                                <?php if(core\Session::get('user_role') === 'admin') { ?>
                                    <input type="submit" name="submit" value="Update" class="button darkBlueButton margin-t-10"/>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->include('footer'); ?>