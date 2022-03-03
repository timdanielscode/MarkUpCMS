<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>

<?php 
    $this->include('header');
    $this->include('navbar');
?>
       
<div class="custom-container my-179">
    <?php if(parts\Session::exists("Csrf_token_message")) { ?>
        <div class="my-3 w-75 mx-auto"><?php echo parts\Alert::display("warning", "Csrf_token_message"); ?></div>
    <?php parts\Session::delete('Csrf_token_message'); } ?>

    <form action="" method="POST" class="d-block mx-auto w-50">
        <h1 class="my-5 text-color-sec"><?php echo $user['username']; ?></h1>
        <div class="form-group">
            <label for="username">Username:</label>
            <input name="username" type="text" id="username" class="form-control <?php Errors::addValidClass($rules, 'username'); ?>" value="<?php echo $user["username"]; ?>">
            <div class="invalid-feedback text-color-thr">
                <?php echo Errors::get($rules, 'username'); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input name="email" type="email" id="email" class="form-control <?php Errors::addValidClass($rules, 'email'); ?>" value="<?php echo $user["email"]; ?>">
            <div class="invalid-feedback text-color-thr">
                <?php echo Errors::get($rules, 'email'); ?>
            </div>
        </div>
        <div class="form-group">
            <button name="submit" type="submit" class="mt-3 btn bg-color-sec text-white btn-lg">Update</button>
            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
        </div>
    </form>
</div>

<?php 
    $this->include('footer');
?>