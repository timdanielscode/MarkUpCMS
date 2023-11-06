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

    <div class="row">
        <div class="col10">
            <form action="" method="POST" class="metaDataForm">   
                <label>Meta title</label>
                <input name="metaTitle" type="text" value="<?php echo $post['metaTitle']; ?>" autofocus>
                <label>Meta description</label>    
                <textarea name="metaDescription" type="text"><?php echo $post['metaDescription']; ?></textarea>
                <button name="meta" id="meta" type="submit">Update</button>
                <input type="hidden" name="tokenMeta" value="<?php Csrf::token('add');?>" />
            </form>
        </div>
        <div class="col2">
            <div id="postSidebar">
                <a href="/admin/posts" class="button back">Back</a>
            </div>
        </div>
    </div>

<?php 
    $this->include('footer');
?>