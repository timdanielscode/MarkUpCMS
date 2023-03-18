<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar');
?>

<div class="con">

    <form action="" method="POST" class="">
    <h1 class="text-color-sec mb-5">Register</h1>
    <div class="form-parts">
            <label for="email">Username:</label>
            <input name="username" type="username" id="username" value="<?php echo post('username'); ?>">
            <div class="error-messages">
                <?php echo Errors::get($rules, 'username'); ?>
            </div>
        </div>
        <div class="form-parts">
            <label for="email">Email:</label>
            <input name="email" type="email" id="email" value="<?php echo post('email'); ?>">
            <div class="error-messages">
                <?php echo Errors::get($rules, 'email'); ?>
            </div>
        </div>
        <div class="form-parts">
            <label for="password">Password:</label>
            <input name="password" type="password" id="password" value="<?php echo post('password'); ?>">
            <div class="error-messages">
                <?php echo Errors::get($rules, 'password'); ?>
            </div>
        </div>
        <div class="form-parts">
            <label for="password_confirm">Retype password:</label>
            <input name="password_confirm" type="password" id="password_confirm" value="<?php echo post('password_confirm'); ?>">
            <div class="error-messages">
                <?php echo Errors::get($rules, 'password_confirm'); ?>
            </div>
        </div>
        <div class="form-parts">
            <label for="role">User Role:</label>
            <select name="role" id="role">
                <option>Normal</option>
                <option>Admin</option>
            </select>
            <div class="error-messages">
                <?php echo Errors::get($rules, 'role'); ?>
            </div>
        </div>
        <div class="form-parts">
            <button name="submit" type="submit">Register</button>
            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
        </div>
    </form>
</div>

<?php 
    $this->include('footer');
?>