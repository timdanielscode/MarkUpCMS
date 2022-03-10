<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>

<?php 
    $this->include('header');  
    $this->include('navbar');
?>

<div class="con">
    
<?php if(Session::exists("csrf")) { ?>
        <div class="my-5 w-75 mx-auto"><?php echo Alert::display("warning", "csrf"); ?></div>
    <?php Session::delete('csrf'); } ?>



    <div id="wysiwg">
        <button id="pTag">p</button>
    </div>


    <form action="" method="POST" class="">
    <h1 class="text-color-sec mb-5">Add post</h1>
    <div class="form-parts">
            <label for="title">Title:</label>
            <input name="title" type="title" id="title" value="<?php echo post('title'); ?>">
            <div class="error-messages">
                <?php echo Errors::get($rules, 'title'); ?>
            </div>
        </div>

        <div class="form-parts">
            <label for="body">Body:</label>
            <textarea name="body" type="body" id="body" rows="5" cols="50"></textarea>
            <div class="error-messages">
                <?php echo Errors::get($rules, 'body'); ?>
            </div>
        </div>
        <div class="form-parts">
            <button name="submit" type="submit">Create</button>
            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
        </div>
    </form>
</div>

<?php 
    $this->include('footer');
?>