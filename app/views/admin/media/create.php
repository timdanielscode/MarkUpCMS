<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>

<?php 
    $this->include('headerOpen');  
    $this->title("IndependentCMS");
    $this->include("headerClose");
    $this->include('navbar');
    
?>

    
<?php if(Session::exists("csrf")) { ?>
        <div class="my-5 w-75 mx-auto"><?php echo Alert::display("warning", "csrf"); ?></div>
    <?php Session::delete('csrf'); } ?>

    <div class="containerPost">
        <div class="row">
            <div class="col10">
                <form action="" method="POST" class="">
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
                        <button name="submit" id="submit" type="submit" class="display-none">Upload</button>
                        <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                    </div>
                </form>
            </div>
            <div class="col2">
                <div id="postSidebar">
                    <a href="/admin/posts" class="button back">Back</a>
                    <label for="submit" class="button create">Upload</label>
                </div>
            </div>
        </div>
    </div>


    

<?php 
    $this->include('footer');
?>