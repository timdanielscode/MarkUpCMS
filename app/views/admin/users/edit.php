<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use validation\Errors; ?>

<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="con">


<h1><?php echo $user['username']; ?><span class="pl-3"><?php echo $user['name']; ?></span></h1>
 
    <form action="update" method="POST" class="d-block m-auto">
        <div class="form-parts">
            <label for="username">Username:</label>
            <input name="f_username" type="text" id="username" value="<?php echo $user["username"]; ?>">
            <div class="error-messages">
                <?php echo Errors::get($rules, 'username'); ?>
            </div>
        </div>
        <div class="form-parts">
            <input type="hidden" name="id" value="<?php echo $user["id"]; ?>"> 
            <button name="submit" type="submit" class="button">Update</button>
            <input type="hidden" name="token" value="<?php echo CSRF::token('add');?>" />
        </div>
    </form>




    <form action="update" method="POST" class="">
        <div class="form-parts">
            <label for="email">Email:</label>
            <input name="email" type="email" id="email" value="<?php echo $user["email"]; ?>">
            <div class="error-messages">
                <?php echo Errors::get($rules, 'email'); ?>
            </div>
        </div>
        <div class="form-parts">
            <input type="hidden" name="id" value="<?php echo $user["id"]; ?>"> 
            <button name="submit" type="submit" class="button">Update</button>
            <input type="hidden" name="token" value="<?php echo CSRF::token('add');?>" />
        </div>
    </form>
    <form action="update" method="POST" class="">
        <div class="form-parts">
            <label for="role">User Role:</label>
            <select name="role" id="role">
            <option>Normal</option>
            <option>Admin</option>
            </select>
            <div class="error-messages">
                <?php echo Errors::get($rules, 'role'); ?>
            </div>
            <div class="form-parts">
            <input type="hidden" name="id" value="<?php echo $user["id"]; ?>"> 
            <button name="submit" type="submit" class="button">Update</button>
            <input type="hidden" name="token" value="<?php echo CSRF::token('add');?>" />
        </div>
        </div>
    </form>

</div>

<?php 
    $this->include('footer');
?>
