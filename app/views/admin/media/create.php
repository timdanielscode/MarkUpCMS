<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/media.css");
    $this->stylesheet("/assets/css/sidebar.css");

    $this->title("IndependentCMS");
    $this->include("headerClose");
    $this->include('navbar');
?>


    <div class="create-container">
        <div class="row">
            <div class="col10 col9-L">
                <h1>Upload media files</h1>
                <form action="" method="POST" class="uploadFileForm" enctype="multipart/form-data">
                    <div class="form-parts">
                        <input name="media_title" type="text" id="media_title" placeholder="Title" autofocus>
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'media_title'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <textarea name="media_description" type="text" id="media_description" placeholder="Description" autofocus></textarea>
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'media_description'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <input name="file[]" type="file" multiple="true" id="file" class="display-none">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'file'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <button name="submit" id="submit" type="submit" class="display-none">Upload</button>
                        <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                    </div>
                </form>
            </div>
            <div class="col2 col3-L">
                <div id="sidebar" class="width-25-L">
                    <div class="sidebarContainer">
                        <div class="mainButtonContainer">
                            <label for="submit" class="button">Upload</label>
                            <a href="/admin/media" class="button">Back</a>
                        </div>
                        <div class="buttonContainer">
                            <label for="file" class="button">Select files to upload</label>
                        </div>
                        <span class="text">Max upload size:</span>
                        <span class="data"><?php echo ini_get('upload_max_filesize'); ?></span>
                        <span class="text">Allowed file types: </span>
                        <span class="data">.jpeg (image/jpeg)</span>
                        <span class="data">.png (image/png)</span>
                        <span class="data">.webp (image/webp)</span>
                        <span class="data">.giff (image/gif)</span>
                        <span class="data">.svg (image/svg+xml)</span>
                        <span class="data">.mp4 (video/mp4)</span>
                        <span class="data">.mov (video/quicktime)</span>
                        <span class="data">.pdf (application/pdf)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
    $this->include('footer');
?>