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

    <div class="containerCss">
        <div class="row">
            <div class="col10">
                <form action="" method="POST" class="">
                    <div class="form-parts">
                        <input name="filename" type="text" id="title" placeholder="Filename" value="<?php echo $cssFile['file_name']; ?>">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'filename'); ?>
                        </div>
                        <textarea name="content" id="content" onkeydown="insertTab(this, event);"><?php echo $content; ?></textarea>
                    </div>
                    <div class="form-parts">
                        <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                        <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                    </div>
                </form>
            </div>
            <div class="col2">
                <div id="postSidebar">
                    <a href="/admin/css" class="button back">Back</a>
                    <label for="submit" class="button update">Update</label>
                </div>
            </div>
        </div>
    </div>


    

<?php 
    $this->include('footer');
?>