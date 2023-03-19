<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar'); 
?>

<div class="container my-179">

    <form method="POST" action="" class="d-block w-50 mx-auto">
    <h1 class="text-color-sec mb-5">Login</h1>
        <div class="form-group">
            <label for="username">Username:</label>
            <input name="username" value="<?php echo post('username') ?>" type="text" id="username" class="form-control <?php //Errors::addValidClass($errors, 'username'); ?>">
            <div class="invalid-feedback text-color-thr"><?php echo Errors::get($errors, 'username'); ?></div>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input name="password" type="password" id="password" class="form-control <?php //Errors::addValidClass($errors, 'password'); ?>">
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