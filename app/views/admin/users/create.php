<?php use validation\Errors; ?>
<?php use core\Session; ?>

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
        <div class="col10 col10-L- col9-L col8-S">
            <div class="create-container">
            <h1 class="mb-5">Add a new user</h1>
                <form action="store" method="POST" class="usersCreateForm">
                    <div class="form-parts">
                        <label for="username">Username:</label>
                        <input name="f_username" type="username" id="username" value="<?php if(!empty($username) ) { echo $username; } ?>">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'f_username'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <label for="email">Email:</label>
                        <input name="email" type="email" id="email" value="<?php if(!empty($email) ) { echo $email; } ?>">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'email'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <label for="password">Password:</label>
                        <input name="password" type="password" id="password">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'password'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <label for="password_confirm">Retype password:</label>
                        <input name="password_confirm" type="password" id="password_confirm">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'password_confirm'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <label for="role">Role:</label>
                        <select name="role" id="role">
                            <option>Normal</option>
                            <option>Admin</option>
                        </select>
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'role'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <button id="submit" name="submit" type="submit" class="display-none"></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col2 col2-L col3-L col4-S">
            <div id="sidebar" class="width-25">
                <div class="sidebarContainer">
                    <div class="mainButtonContainer">
                        <label for="submit" class="button greenButton margin-r-10">Store</label>
                        <a href="/admin/users" class="button blueButton">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php 
    $this->include('footer');
?>