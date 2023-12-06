<!-- 
    - to change the new password value to submit and update (after successful authentication)
-->

<?php $this->include('openHeadTag'); ?>  
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/users.css"); ?>
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
<?php $this->include('closeHeadTagAndOpenBodyTag'); ?>

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col10-L- col9-L col8-S">
    <div class="edit-container">
        <h1 class="margin-b-30">Update password</h1>
            <form action="/admin/profile/<?php echo $user['username']; ?>/change-password" method="POST" class="usersEditForm">
                <div class="form-parts">
                    <label for="currentPassword">Current password: </label>
                    <input id="currentPassword" type="password" name="password">
                    <input type="hidden" name="username" value="<?php echo core\Session::get('username'); ?>">
                    <div class="error-messages">
                        <?php echo validation\Errors::get($rules, 'password'); ?>
                    </div>
                </div>
                <div class="form-parts">
                    <label for="newPassword">New password:</label>
                    <input id="newPassowrd" name="newPassword" type="password">
                    <div class="error-messages">
                        <?php echo validation\Errors::get($rules, 'newPassword'); ?>
                    </div>
                </div>
                <div class="form-parts">
                    <label for="retypePassword">Retype password:</label>
                    <input id="retypePassword" name="retypePassword" type="password">
                    <div class="error-messages">
                        <?php echo validation\Errors::get($rules, 'retypePassword'); ?>
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
                <div class="mainButtonContainer">
                    <label for="submit" class="button greenButton margin-r-10">Update</label>
                    <a href="/admin/profile/<?php echo core\Session::get('username'); ?>" class="button darkBlueButton">Back</a>
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

<?php $this->include('footer'); ?>
