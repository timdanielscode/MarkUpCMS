<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use validation\Errors; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/sidebar.css");
    $this->stylesheet("/assets/css/users.css");

    $this->script("/assets/js/navbar.js", true);

    $this->include('headerClose');
    $this->include('navbar');
?>

<div class="edit-container">
    <div class="row">
        <div class="col10 col9-L">

            <h1>Details</h1>
            
                <form action="/profile/<?php echo Session::get('username'); ?>/update" method="POST" class="usersEditForm">
                    <div class="form-parts">
                        <label for="username">Username:</label>
                        <input name="f_username" type="text" id="username" value="<?php echo $user['username']; ?>">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'f_username'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <label for="email">Email:</label>
                        <input name="email" type="email" id="email" value="<?php echo $user["email"]; ?>">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'email'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <input type="hidden" name="id" value="<?php echo $user["id"]; ?>"> 
                        <button name="submit" type="submit" class="display-none" id="submit"></button>
                        <input type="hidden" name="token" value="<?php echo Csrf::token('add');?>" />
                    </div>
                </form>
        </div>
        <div class="col2 col3-L">
            <div id="sidebar" class="width-25-L">
                <div class="sidebarContainer">
                    <div class="mainButtonContainer">
                        <label for="submit" class="button">Update</label>
                        <a href="/admin/users" class="button">Back</a>
                    </div>
                    <span class="text">Username:</span>
                    <span class="data"><?php echo $user['username']; ?></span>
                    <span class="text">Email:</span>
                    <span class="data"><?php echo $user['email']; ?></span>
                    <span class="text">Role:</span>
                    <span class="data"><?php echo $user['name']; ?></span>
                    <?php if(Session::get('user_role') === 'admin') { ?>
                        <form action="/profile/<?php echo Session::get('username'); ?>/update-role" method="POST" class="profileUpdateRoleForm">
                            <div class="form-parts">
                                <label for="role">Role:</label>
                                <select name="role" multiple>
                                    <option value="1">Normal</option>
                                    <option value="2">Admin</option>
                                </select>
                                <div class="error-messages">
                                    <?php echo Errors::get($rules, 'role'); ?>
                                </div>
                            </div>
                            <div class="form-parts">
                                <button name="submit" type="submit" id="submit" class="button updateRoleButton">Update</button>
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>"/>
                                <input type="hidden" name="token" value="<?php echo Csrf::token('add');?>" />
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    $this->include('footer');
?>