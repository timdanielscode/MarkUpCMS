<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>

<?php 
    $this->include('headerOpen');  
    $this->include("headerClose");
    $this->include('navbar');
    
?>

    
<?php if(Session::exists("csrf")) { ?>
        <div class="my-5 w-75 mx-auto"><?php echo Alert::display("warning", "csrf"); ?></div>
    <?php Session::delete('csrf'); } ?>

    <div class="containerPost">
    <div class="row">
        <div class="col10">
            <form action="" method="POST">
                <div class="form-parts">
                    <input name="media_title" id="title" value="<?php echo $media['media_title']; ?>">
                    <div class="error-messages">
                        <?php echo Errors::get($rules, 'title'); ?>
                    </div>    
                    <textarea name="media_description" id="media_description" type="text"><?php echo $media['media_description']; ?></textarea>
                    <div class="error-messages">
                        <?php echo Errors::get($rules, 'slug'); ?>
                    </div>
                </div>
                <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
            </form>
        </div>
        <div class="col2">
            <div id="postSidebar">
                <a href="/admin/posts" class="button back">Back</a>

                    	<label for="submit" class="button update">Update</label>

                        <a href="/admin/posts/<?php echo $post['id']; ?>/preview" class="button preview">Preview</a>

            </div>
        </div>
    </div>
</div>
<?php 
    $this->include('footer');
?>