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
            <form action="" method="POST">
                    <label>Meta title</label>    
                    <input name="metaTitle" type="text" value="<?php echo $post['metaTitle']; ?>">
                    <label>Meta description</label>    
                    <textarea name="metaDescription" type="text"><?php echo $post['metaDescription']; ?></textarea>
                    <button name="meta" id="meta" type="submit">Update</button>
                    <input type="hidden" name="tokenMeta" value="<?php Csrf::token('add');?>" />
                </form>
        </div>
        <div class="col2">
            <div id="postSidebar">
                <a href="/admin/posts" class="button margin-t-50">Back</a>
                <label for="submit" class="button margin-t-50">Submit</label>
                <a href="/admin/posts/<?php echo $post['id']; ?>/preview" class="button margin-t-50">Preview</a>
                <form action="" method="POST">
                    <label>Slug</label>    
                    <input name="slug" type="text" value="<?php echo $post['slug']; ?>">
                    <div class="error-messages">
                        <?php echo Errors::get($rules, 'slug'); ?>
                    </div>
                    <button name="submitSlug" id="submitSlug" type="submit">Update</button>
                    <input type="hidden" name="tokenSlug" value="<?php Csrf::token('add');?>" />
                </form>
            </div>
        </div>
    </div>

<?php 
    $this->include('footer');
?>