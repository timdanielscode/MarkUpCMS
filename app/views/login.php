<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>

<?php 
    $this->include('header'); 
    $this->include('navbar'); 
?>

<div class="container my-179">

    <?php if(Session::exists("registered")) { ?>
        <div class="my-5 w-75 mx-auto"><?php echo Alert::display("success", "registered"); ?></div>
    <?php Session::delete('registered'); } ?>

    <?php if(Session::exists("auth_failed")) { ?>
        <div class="my-5 w-75 mx-auto"><?php echo Alert::display("warning", "auth_failed"); ?></div>
    <?php Session::delete('auth_failed'); } ?>

    <?php if(Session::exists("csrf")) { ?>
        <div class="my-5 w-75 mx-auto"><?php echo Alert::display("warning", "csrf"); ?></div>
    <?php Session::delete('csrf'); } ?>

    <form method="POST" action="" class="d-block w-50 mx-auto">
    <h1 class="text-color-sec mb-5">Login</h1>
        <div class="form-group">
            <label for="username">Username:</label>
            <input name="username" value="<?php echo post('username') ?>" type="text" id="username" class="form-control <?php Errors::addValidClass($errors, 'username'); ?>">
            <div class="invalid-feedback text-color-thr"><?php echo Errors::get($errors, 'username'); ?></div>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input name="password" type="password" id="password" class="form-control <?php Errors::addValidClass($errors, 'password'); ?>">
            <div class="invalid-feedback text-color-thr"><?php echo Errors::get($errors, 'password'); ?></div>
        </div>
        <div class="form-group">
            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
            <button name="submit" type="submit" class="mt-3 btn bg-color-sec text-white btn-lg">Login</button>
        </div>
    </form>
</div>

<?php 
    $this->include('footer');
?>