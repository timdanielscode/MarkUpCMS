<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/sidebar.css");
    $this->stylesheet("/assets/css/leftSidebar.css");
    $this->stylesheet("/assets/css/dashboard.css");
    $this->stylesheet("/assets/css/settings.css");

    $this->script("/assets/js/navbar/Navbar.js", true);
    $this->script("/assets/js/navbar/main.js", true);
    $this->script("/assets/js/sidebar.js", true);

    $this->include("headerClose");
?>
    <nav id="navbar">
        <a href="/admin/dashboard"><img id="logo" src="/assets/img/logo.png"></a>
        <form target="_blank" action="/admin/posts/" method="GET">
            <img id="searchIcon" src="/assets/img/search.png"/>
            <input type="text" name="search" placeholder="Pages" id="search">
            <input type="hidden" name="submit" value="search">
        </form>
        <a href="#" id="settings"><img src="/assets/img/settings.png"/></a>
        <?php if(Session::exists('username') === true) { ?>
        <div class="profileContainer">
            <span id="profileIcon" class="profileIcon <?php if(Session::get('user_role') === 'admin') { echo 'admin'; } else { echo 'normal';} ?>"><?php echo substr(Session::get('username'), 0, 1); ?></span>
            <ul id="profileDropdown">
            <span class="triangle"></span>
                <span class="profileIcon"><?php echo substr(Session::get('username'), 0, 1); ?></span>
                <li class="text-center username"><?php echo Session::get('username'); ?> <span>(<?php echo Session::get('user_role'); ?></span>)</li>
                <li><a href="/admin/profile/<?php echo Session::get('username'); ?>">Profile</a></li>
                <li class="/logout"><a href="/logout">Sign out</a></li>
            </ul>
        </div>
    <?php } ?>
    </nav>
    <div id="progressInfoItem"></div>
    <div class="row">
        <div class=" col2 col3-L">

            <?php $this->include('leftSidebar'); ?>

        </div>
        <div class="col10 col9-L">
            <div class="settingsContainer">
            <h1>Settings</h1>
            <form action="/admin/settings/update-slug" method="POST" class="updateLoginSlugForm">
                <div class="formParts">
                    <label>Login slug:</label>
                    <input type="text" name="slug" value="<?php if(!empty($currentLoginSlug) && $currentLoginSlug !== null) { echo substr($currentLoginSlug['slug'], 1); } else { echo 'login'; } ?>"/>
                    <?php if(!empty(Errors::get($rules, 'slug')) && Errors::get($rules, 'slug') !== null) { ?>
                        <div class="error-messages margin-t-10 font-size-14">
                            <span><?php echo Errors::get($rules, 'slug'); ?></span>
                        </div>    
                    <?php } ?>  
                    <input type="submit" name="submit" value="Update" class="button greenButton margin-t-10"/>
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
            
        


                    

