<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>

<?php 
    $this->include('header');  
    $this->include('navbar');
?>

    
<?php if(Session::exists("csrf")) { ?>
        <div class="my-5 w-75 mx-auto"><?php echo Alert::display("warning", "csrf"); ?></div>
    <?php Session::delete('csrf'); } ?>


    <div class="row">
        <div class="col10">
            <form action="" method="POST">
                <div class="form-parts">
                    <input name="title" id="title" value="<?php echo $post['title']; ?>">
                    <div class="error-messages">
                        <?php echo Errors::get($rules, 'title'); ?>
                    </div>
                </div>
                <textarea name="body" id="body"><?php echo htmlspecialchars_decode($post['body']); ?></textarea>
                <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
            </form>
        </div>
        <div class="col2">
            <div id="postSidebar">
                <a href="/admin/posts" class="button margin-t-50">Back</a>
                <label for="submit" class="button margin-t-50">Submit</label>
            </div>
        </div>
    </div>

<?php 
    $this->include('footer');
?>