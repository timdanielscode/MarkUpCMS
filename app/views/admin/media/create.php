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

    $this->script('/assets/js/ajax.js');
    $this->script('/assets/js/media/create.js', true);
    $this->title("IndependentCMS");
    $this->include("headerClose");
    $this->include('navbar');
?>


    <div class="create-container">
        <div class="row">
            <div class="col10 col9-L">
                <input type="range" min="125" max="500" value="150" id="ranger">

                <div class="filesContainer">
                    <?php if(!empty($files) && $files !== null) { ?>
                    <div class="row flex-center">

                        <?php foreach($files as $file) { ?>

                            <?php 
                                switch ($file['media_filetype']) {

                                    case 'image/png':
                                    case 'image/webp':
                                    case 'image/gif':
                                    case 'image/jpeg':
                                    case 'image/svg+xml':

                                        $type = 'img';
                                        $path = "/website/assets/img/";
                                    break;
                                    case 'video/mp4':
                                    case 'video/quicktime':
                                        
                                        $type = 'video';
                                        $path = "/website/assets/video/";
                                    break;  
                                    case 'application/pdf':

                                        $type = 'application';
                                        $path = "/website/assets/application/";
                                    break;
                                    default:

                                        $path = '';
                                    break;
                                }
                            ?>
                            
                            <div class="fileInfoContainer display-none">
                                <span class="">Title: </span>
                                <p><?php echo $file['media_title']; ?></p>
                                <?php if(!empty($file['media_description']) ) { ?>
                                    <span class="">Description: </span>
                                    <p><?php echo $file['media_description']; ?></p>
                                <?php } ?>
                                <span class="">Type:</span>
                                <p><?php echo $file['media_filetype']; ?></p>
                                <span class="">Size:</span>
                                <?php 
                                    $filesize = $file['media_filesize'] / 1000000;
                                    $filesize = number_format((float)$filesize, 2, '.', '');
                                ?>
                                <p class=""><?php echo $filesize . 'M'; ?></p>
                                <span class="filename">File: </span>
                                <p class="filenamePath"><?php echo $path . $file['media_filename']; ?></p>
                                <a href="/admin/media?search=<?php echo $file['media_filename']; ?>" class="button">Edit</a>
                            </div>
                            <div class="fileContainer">
                                <?php if($type === 'img') { ?>
                                <img class="file" src="<?php echo $path . $file['media_filename']; ?>">
                                <?php } else if($type === 'video') { ?>
                                    <video class="file" src="<?php echo $path . $file['media_filename']; ?>" controls></video>
                                <?php } else if($type === 'application') { ?>
                                    <iframe class="file" src="<?php echo $path . $file['media_filename']; ?>"></iframe>
                                <?php } ?>
                                <div class="layer"><span class="mediaTitle"><?php echo $file['media_filetype']; ?></span></div>
                            </div>
                                    
                        <?php } ?>

                    </div>
                    <?php } else { ?>
                        <span>No files found.</span>
                    <?php } ?>
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
                            <div class="form-parts">
                                <label>Title *</label>
                                <input name="media_title" type="text" id="media_title" autofocus>
                                <div class="error-messages">
                                    <?php echo Errors::get($rules, 'media_title'); ?>
                                    <?php echo Errors::get($rules, 'file'); ?>
                                </div>
                            </div>
                            <div class="form-parts">
                                <label>Description </label>
                                <textarea name="media_description" type="text" id="media_description" autofocus></textarea>
                                <div class="error-messages">
                                    <?php echo Errors::get($rules, 'media_description'); ?>
                                </div>
                            </div>
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
                        </div>
                        <span class="text">Max upload size:</span>
                        <span class="data"><?php echo ini_get('upload_max_filesize'); ?></span>
                        <span class="text">Filters:</span>
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
    $this->include('footer');
?>