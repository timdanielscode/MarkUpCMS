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
            <h1>Change password: </h1>
            
                <form action="/admin/profile/<?php echo $user['username']; ?>/change-password" method="POST" class="usersEditForm">
                    <div class="form-parts">
                        <label for="password">Password:</label>
                        <input name="password" type="password" id="password">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'password'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <label for="retypePassword">Retype password:</label>
                        <input name="retypePassword" type="password" id="retypePassword">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'retypePassword'); ?>
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
                    <div class="mainButtonContainer">
                        <label for="submit" class="button greenButton margin-r-10">Update</label>
                        <a href="/admin/profile/<?php echo Session::get('username'); ?>" class="button darkBlueButton">Back</a>
                    </div>
                    <span class="text margin-t-50">Username:</span>
                    <span class="data"><?php echo $user['username']; ?></span>
                    <span class="text">Email:</span>
                    <span class="data"><?php echo $user['email']; ?></span>
                    <span class="text">Role:</span>
                    <span class="data"><?php echo $user['name']; ?></span>
                </div>
            </div>
        </div>
   
</div>

<?php 
    $this->include('footer');
?>
