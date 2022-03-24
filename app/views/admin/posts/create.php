<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>

<?php 
    $this->include('headerOpen');  
    $this->title("IndependentCMS");
    $this->script("https://cdn.tiny.cloud/1/yjrgki0oubi33qi9ebe57t1lz8lw9nbe3xbnfrv5893n4oqb/tinymce/5/tinymce.min.js");
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
                        <input name="title" type="title" id="title" autofocus>
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'title'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <textarea name="body" type="body" id="body" class="empty" rows="5" cols="50"></textarea>
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'body'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                        <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                    </div>
                </form>
            </div>
            <div class="col2">
                <div id="postSidebar">
                    <a href="/admin/posts" class="button back">Back</a>
                    <label for="submit" class="button create">Create</label>
                </div>
            </div>
        </div>
    </div>


    

<?php 
    $this->include('footer');
?>