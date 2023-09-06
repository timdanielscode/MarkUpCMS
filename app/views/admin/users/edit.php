<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use validation\Errors; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/users.css");
    $this->stylesheet("/assets/css/sidebar.css");

    $this->include('headerClose');
    $this->include('navbar');
?>

    <div class="row">
        <div class="col10 col9-L">
        <div class="edit-container">
        <h1>Details: </h1>
                <form action="/admin/users/<?php echo $user['username']; ?>/update" method="POST" class="usersEditForm">
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
                        <button name="submit" type="submit" id="submit" class="display-none">Update</button>
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>"/>
                        <input type="hidden" name="token" value="<?php echo Csrf::token('add');?>" />
                    </div>
                </form>
                </div>
        </div>
        <div class="col2 col3-L">
            <div id="sidebar" class="width-25-L">
                <div class="sidebarContainer">
                    <div class="mainButtonContainer margin-b-50">
                        <label for="submit" class="button greenButton margin-r-10">Update</label>
                        <a href="/admin/users" class="button darkBlueButton">Back</a>
                    </div>
                    <span class="text">Username:</span>
                    <span class="data"><?php echo $user['username']; ?></span>
                    <span class="text">Email:</span>
                    <span class="data"><?php echo $user['email']; ?></span>
                    <span class="text">Role:</span>
                    <span class="data"><?php echo $user['name']; ?></span>
                    <form action="/admin/users/<?php echo $user['username']; ?>/update-role" method="POST" class="profileUpdateRoleForm">
                        <div class="form-parts">
                            <label for="role">Update role:</label>
                            <select name="role" multiple>
                                <option value="2" selected>Admin</option>
                            </select>
                        </div>
                        <div class="form-parts">
                            <button name="submit" type="submit" class="button greenButton margin-t-10">Update</button>
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>"/>
                            <input type="hidden" name="token" value="<?php echo Csrf::token('add');?>" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
 
</div>

<?php 
    $this->include('footer');
?>
