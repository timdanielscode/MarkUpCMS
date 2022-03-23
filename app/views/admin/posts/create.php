<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>

<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');  
    $this->include('navbar');
?>

    
<?php if(Session::exists("csrf")) { ?>
        <div class="my-5 w-75 mx-auto"><?php echo Alert::display("warning", "csrf"); ?></div>
    <?php Session::delete('csrf'); } ?>



    <!--<div id="wysiwg">
        <button onclick="clickHandler('p')" id="pTag" value="p">p</button>
        <button onclick="clickHandler('div')" id="pTag" value="div">div</button>
        <button onclick="clickHandler('h1')" id="pTag" value="h1">h1</button>
        <button onclick="clickHandler('h2')" id="pTag" value="h2">h2</button>
        <button onclick="clickHandler('h3')" id="pTag" value="h3">h3</button>
    </div>-->


    <div class="row">
        <div class="col10">
    <form action="" method="POST" class="">
        <div class="form-parts">
            <label for="title">Title:</label>
            <input name="title" type="title" id="title">
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
            <button name="submit" type="submit">Create</button>
            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
        </div>
        <div class="empty"></div>
    </form>
    </div>
    <div class="col2">


    <div class="empty">
        <div class="fill" draggable="true"></div>
    </div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
</div>
</div>


    

<?php 
    $this->include('footer');
?>