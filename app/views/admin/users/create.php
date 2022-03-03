<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>

<?php 
    $this->include('header');  
    $this->include('navbar');
?>

<div class="container">
    
<?php if(Session::exists("csrf")) { ?>
        <div class="my-5 w-75 mx-auto"><?php echo Alert::display("warning", "csrf"); ?></div>
    <?php Session::delete('csrf'); } ?>

    <form action="" method="POST" class="d-block mx-auto w-50">
    <h1 class="text-color-sec mb-5">Register</h1>
    <div class="form-group">
            <label for="email">Username:</label>
            <input name="username" type="username" id="username" class="form-control <?php Errors::addValidClass($rules, 'username'); ?>" value="<?php echo post('username'); ?>">
            <small class="form-text text-muted">Use an unique username.</small>
            <div class="invalid-feedback text-color-thr">
                <?php echo Errors::get($rules, 'username'); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input name="email" type="email" id="email" class="form-control <?php Errors::addValidClass($rules, 'email'); ?>" value="<?php echo post('email'); ?>">
            <small class="form-text text-muted">Use an unique email.</small>
            <div class="invalid-feedback text-color-thr">
                <?php echo Errors::get($rules, 'email'); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input name="password" type="password" id="password" class="form-control <?php Errors::addValidClass($rules, 'password'); ?>" value="<?php echo post('password'); ?>">
            <div class="invalid-feedback text-color-thr">
                <?php echo Errors::get($rules, 'password'); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="password_confirm">Retype password:</label>
            <input name="password_confirm" type="password" id="password_confirm" class="form-control <?php Errors::addValidClass($rules, 'password_confirm'); ?>" value="<?php echo post('password_confirm'); ?>">
            <div class="invalid-feedback text-color-thr">
                <?php echo Errors::get($rules, 'password_confirm'); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="role">User Role:</label>
            <select name="role" class="form-control <?php Errors::addValidClass($rules, 'role'); ?>" id="role">
            <option>Normal</option>
            <option>Admin</option>
            </select>
            <div class="invalid-feedback text-color-thr">
                <?php echo Errors::get($rules, 'role'); ?>
            </div>
        </div>
        <div class="form-group">
            <button name="submit" type="submit" class="mt-3 btn bg-color-sec text-white btn-lg">Register</button>
            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
        </div>
    </form>
</div>

<?php 
    $this->include('footer');
?>