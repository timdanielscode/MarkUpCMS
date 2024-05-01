<!-- 
    - to change login slug value to submit and update
--> 

<?php $this->include('openHeadTag'); ?>  
    <?php $this->title('Settings page'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?> 
    <?php $this->stylesheet("/assets/css/navbar.css"); ?> 
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?> 
    <?php $this->stylesheet("/assets/css/leftSidebar.css"); ?> 
    <?php $this->stylesheet("/assets/css/dashboard.css"); ?> 
    <?php $this->stylesheet("/assets/css/settings.css"); ?> 
    <?php $this->script("/assets/js/sidebar/left/Sidebar.js", true); ?>
    <?php $this->script("/assets/js/sidebar/left/main.js", true); ?>
    <?php $this->script("/assets/js/navbar/Navbar.js", true); ?> 
    <?php $this->script("/assets/js/navbar/main.js", true); ?> 
<?php $this->include("closeHeadTagAndOpenBodyTag"); ?> 

<?php $this->include("dashboardNavbar"); ?>

<div class="row">
    <div class="col2 col3-L">
        <?php $this->include('leftSidebar'); ?>
    </div>
    <div class="col10 col9-L">
        <div class="settingsContainer">
        <h1>Settings</h1>
        <form action="/admin/settings/update-slug" method="POST" class="updateLoginSlugForm">
            <div class="formParts">
                <label>Login slug:</label>
                <input type="text" name="slug" value="<?php if(!empty($currentLoginSlug) && $currentLoginSlug !== null) { echo substr($currentLoginSlug['slug'], 1); } else { echo 'login'; } ?>"/>
                <?php if(!empty(validation\Errors::get($rules, 'slug')) && validation\Errors::get($rules, 'slug') !== null) { ?>
                    <div class="error-messages margin-t-10 font-size-14">
                        <span><?php echo validation\Errors::get($rules, 'slug'); ?></span>
                    </div>    
                <?php } ?>  
                <input type="submit" name="submit" value="Update" class="button greenButton margin-t-10"/>
            </div>
        </form>
        </div>
        </div>
    </div>
</div>

<?php $this->include('footer'); ?>
            
        


                    

