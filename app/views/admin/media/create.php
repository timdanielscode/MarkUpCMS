<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

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
                <form action="" method="POST" class="" enctype="multipart/form-data">
                    <div class="form-parts">
                        <input name="media_title" type="text" id="media_title" placeholder="Title" autofocus>
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'media_title'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <textarea name="media_description" type="text" id="media_description" autofocus></textarea>
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'media_description'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <input name="file" type="file" multiple="true" id="file">
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
                <div id="sidebar">
                    <a href="/admin/posts" class="button back">Back</a>
                    <label for="submit" class="button create">Upload</label>
                </div>
            </div>
        </div>
    </div>
<?php 
    $this->include('footer');
?>