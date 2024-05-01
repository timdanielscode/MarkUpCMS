<!-- 
    - FOR TYPE OF ADMIN USER
    -
    - to add a username value (required), email value (required), password value (required) and select a role (required) to submit and store a new user
--> 

<?php $this->include('openHeadTag'); ?>  
    <?php $this->title('Users create page'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?> 
    <?php $this->stylesheet("/assets/css/navbar.css"); ?> 
    <?php $this->stylesheet("/assets/css/users.css"); ?> 
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?> 
<?php $this->include('closeHeadTagAndOpenBodyTag'); ?> 

<?php $this->include('navbar'); ?> 

<div class="row">
    <div class="col10 col10-L- col9-L col8-S">
        <div class="create-container">
        <h1 class="mb-5">Add a new user</h1>
            <form action="store" method="POST" class="usersCreateForm">
                <div class="form-parts">
                    <label for="username">Username:</label>
                    <input name="f_username" type="username" id="username" value="<?php if(!empty($username) ) { echo $username; } ?>">
                    <div class="error-messages">
                        <?php echo validation\Errors::get($rules, 'f_username'); ?>
                    </div>
                </div>
                <div class="form-parts">
                    <label for="email">Email:</label>
                    <input name="email" type="email" id="email" value="<?php if(!empty($email) ) { echo $email; } ?>">
                    <div class="error-messages">
                        <?php echo validation\Errors::get($rules, 'email'); ?>
                    </div>
                </div>
                <div class="form-parts">
                    <label for="password">Password:</label>
                    <input name="password" type="password" id="password">
                    <div class="error-messages">
                        <?php echo validation\Errors::get($rules, 'password'); ?>
                    </div>
                </div>
                <div class="form-parts">
                    <label for="retypePassword">Retype password:</label>
                    <input name="retypePassword" type="password" id="retypePassword">
                    <div class="error-messages">
                        <?php echo validation\Errors::get($rules, 'retypePassword'); ?>
                    </div>
                </div>
                <div class="form-parts">
                    <label for="role">Role:</label>
                    <select name="role" id="role">
                        <option value="null" selected="selected">Normal</option>
                        <option value="1">Admin</option>
                    </select>
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

<?php $this->include('footer'); ?>