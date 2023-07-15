<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use validation\Errors; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/users.css");
    $this->stylesheet("/assets/css/sidebar.css");

    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="edit-container">
    <div class="row">
        <div class="col10 col9-L">

            <h1><?php echo $user['username']; ?><span class="pl-3"><?php echo $user['name']; ?></span></h1>
            
                <form action="update" method="POST" class="d-block m-auto">
                    <div class="form-parts">
                        <label for="username">Username:</label>
                        <input name="f_username" type="text" id="username" value="<?php echo $user['username']; ?>">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'f_username'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <button name="submit" type="submit" class="button">Update</button>
                        <input type="hidden" name="token" value="<?php echo Csrf::token('add');?>" />
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
                        <button name="submit" type="submit" class="button">Update</button>
                        <input type="hidden" name="token" value="<?php echo Csrf::token('add');?>" />
                    </div>
                </form>

                <form action="update" method="POST" class="">
                    <div class="form-parts">
                        <label for="role">User Role:</label>
                        <select name="role" id="role">
                        <option value="1">Normal</option>
                        <option value="2">Admin</option>
                        </select>
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'role'); ?>
                        </div>
                        <div class="form-parts">
                        <input type="hidden" name="id" value="<?php echo $user["id"]; ?>"> 
                        <button name="submit" type="submit" class="button">Update</button>
                        <input type="hidden" name="token" value="<?php echo Csrf::token('add');?>" />
                    </div>
                    </div>
                </form>
</div>
        <div class="col2 col3-L">
            <div id="sidebar" class="width-25-L">
                <a href="/admin/css" class="button back">Back</a>
                <label for="submit" class="button update">Update</label>
            </div>
        </div>
    </div>
</div>

<?php 
    $this->include('footer');
?>
