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

        <div name="builder" id="builder" class="display-none"><?php echo $post['body']; ?></div>

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
    <div class="row">
        <div class="col6">
            <button onclick="toVisualBuilder();" class="button margin-t-50">Visual Builder</button>
        </div>  
        <div class="col6">
            <button  onclick="toBuilder();" class="button margin-t-50">Builder</button>
        </div>
    </div>

    
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