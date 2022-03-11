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


        <div class="form-parts">
            <textarea name="body" id="body" rows="5" cols="50"><?php echo $post['body']; ?></textarea>
            <div class="error-messages">
                <?php echo Errors::get($rules, 'body'); ?>
            </div>
        </div>
        <button name="submit" id="submit" type="submit" class="display-none">Create</button>
        <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
    </form>
</div>
<div class="col2">
    <div id="postSidebar">
    <div class="row">
        <div class="col6">
            <a href="/admin/posts" class="button margin-t-50">Back</a>
        </div>  
        <div class="col6">
            <label for="submit" class="button margin-t-50">Submit</label>
        </div>
    </div>

    <form action="" method="POST" class="margin-t-50">
        <button name="submit" id="submit" type="submit">Code</button>
        <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
    </form>


    <div id="wysiwg" class="margin-t-50">
        <button onclick="clickHandler('p')" id="pTag" value="p">p</button>
        <button onclick="clickHandler('div')" id="pTag" value="div">div</button>
        <button onclick="clickHandler('h1')" id="pTag" value="h1">h1</button>
        <button onclick="clickHandler('h2')" id="pTag" value="h2">h2</button>
        <button onclick="clickHandler('h3')" id="pTag" value="h3">h3</button>
    </div>
    </div>
</div>
</div>

<?php 
    $this->include('footer');
?>