<!-- 
    - to show an overview of user details
    - to change the username value to submit and update
    - to change the email value to submit and update
    - to select a type of role to submit and update
    - to click on the delete account button to submit and delete account
--> 

<?php $this->include('openHeadTag'); ?>
    <?php $this->title('Profile page'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
    <?php $this->stylesheet("/assets/css/users.css"); ?>
    <?php $this->script("/assets/js/navbar.js", true); ?>
<?php $this->include('closeHeadTagAndOpenBodyTag'); ?>

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col10-L- col9-L col8-S">
    <div class="edit-container">
        <?php core\Alert::message('success'); ?>
        <h1 class="margin-b-30">Update details</h1>
            <form action="/admin/profile/<?php echo $id; ?>/update" method="POST" class="usersEditForm">
                <div class="form-parts">
                    <label for="username">Username:</label>
                    <input name="f_username" type="text" id="username" value="<?php echo $user['username']; ?>">
                    <div class="error-messages">
                        <?php echo validation\Errors::get($rules, 'f_username'); ?>
                    </div>
                </div>
                <div class="form-parts">
                    <label for="email">Email:</label>
                    <input name="email" type="email" id="email" value="<?php echo $user["email"]; ?>">
                    <div class="error-messages">
                        <?php echo validation\Errors::get($rules, 'email'); ?>
                    </div>
                </div>
                <div class="form-parts">
                    <input type="hidden" name="id" value="<?php echo $user["id"]; ?>"> 
                    <button name="submit" type="submit" class="display-none" id="submit"></button>
                </div>
            </form>
            </div>
    </div>
    <div class="col2 col2-L col3-L col4-S">
        <div id="sidebar" class="width-25">
            <div class="sidebarContainer">
                <div class="mainButtonContainer">
                    <label for="submit" class="button greenButton margin-r-10">Update</label>
                    <a href="/admin/dashboard" class="button blueButton">Back</a>
                </div>
                <div class="buttonContainer">
                    <a href="/admin/profile/<?php echo $id; ?>/change-password" class="button darkButton margin-r-10">Password</a>
                    <form action="/admin/profile/<?php echo $id; ?>/delete" method="POST" class="deleteAccountForm">
                        <input type="hidden" name="id" value="<?php echo $user["id"]; ?>"> 
                        <input type="submit" name="delete" class="button lightButton" value="Delete account" onclick="return confirm('Are you sure?');"/>
                    </form>
                </div>
                <span class="text">Username:</span>
                <span class="data"><?php echo $user['username']; ?></span>
                <span class="text">Email:</span>
                <span class="data"><?php echo $user['email']; ?></span>
                <?php if($user['type'] === 1) { ?>
                    <span class="text">Role:</span>
                    <span class="data"><?php echo $user['type']; ?></span>
                <?php } ?>
                <?php if(core\Session::get('user_role') === 1) { ?>
                    <form action="/admin/profile/<?php echo $id; ?>/update-role" method="POST" class="profileUpdateRoleForm">
                        <div class="form-parts">
                            <label for="role">Update role:</label>
                            <select name="role" multiple>
                                <option value="1" selected>Normal</option>
                            </select>
                            <div class="error-messages">
                                <?php echo validation\Errors::get($rules, 'role'); ?>
                            </div>
                        </div>
                        <div class="form-parts">
                            <input name="submit" type="submit" id="submit" class="button darkBlueButton margin-t-10" value="Update" onclick="return confirm('Are you sure?');">
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>"/>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php $this->include('footer'); ?>