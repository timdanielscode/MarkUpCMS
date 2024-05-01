<!-- 
    - FOR TYPE OF ADMIN USER AND OF TYPE OF NORMAL USER
    -
    - to have an overview of user details
    - to change username value to submit and update
    - to change email value to submit and update
    - to select type of role to submit and update
--> 

<?php $this->include('openHeadTag'); ?>   
    <?php $this->title('Users edit page'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/users.css"); ?>
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
<?php $this->include('closeHeadTagAndOpenBodyTag'); ?>

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col10-L- col9-L col8-S">
    <div class="edit-container">
    <?php core\Alert::message('success'); ?>
    <h1 class="margin-b-30">Update details</h1>
            <form action="/admin/users/<?php echo $user['id']; ?>/update" method="POST" class="usersEditForm">
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
                    <button name="submit" type="submit" id="submit" class="display-none"></button>
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>"/>
                </div>
            </form>
            </div>
    </div>
    <div class="col2 col2-L col3-L col4-S">
        <div id="sidebar" class="width-25">
            <div class="sidebarContainer">
                <div class="mainButtonContainer margin-b-50">
                    <label for="submit" class="button greenButton margin-r-10">Update</label>
                    <a href="/admin/users" class="button darkBlueButton">Back</a>
                </div>
                <span class="text">Username:</span>
                <span class="data"><?php echo $user['username']; ?></span>
                <span class="text">Email:</span>
                <span class="data"><?php echo $user['email']; ?></span>
                <form action="/admin/users/<?php echo $user['id']; ?>/update-role" method="POST" class="profileUpdateRoleForm">
                    <div class="form-parts">
                        <label for="role">Update role:</label>
                        <select name="role" multiple>
                            <option value="2" selected>Admin</option>
                        </select>
                    </div>
                    <div class="form-parts">
                        <button name="submit" type="submit" class="button greenButton margin-t-10" onclick="return confirm('Are you sure?');">Update</button>
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->include('footer'); ?>
