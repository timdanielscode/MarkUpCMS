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

    $this->script("/assets/js/navbar.js", true);
    $this->script("/assets/js/dashboard/sidebar.js", true);

    $this->title("IndependentCMS");
    $this->include("headerClose");
?>
    <nav id="navbar">
        <a href="/admin/dashboard"><img id="logo" src="/assets/img/logo.png"></a>
        <form target="_blank" action="/admin/posts/" method="GET">
            <img id="searchIcon" src="/assets/img/search.png"/>
            <input type="text" name="search" placeholder="Pages" id="search">
            <input type="hidden" name="submit" value="search">
        </form>
        <a href="#" id="settings">Settings</a>
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
            <div id="sidebar" class="width-25-L">
                <div class="sidebarContainer">
                    <nav id="navigationMenu">
                        <ul id="dropdownItems">
                            <li class="dropdownItem"><img src="/assets/img/file.png"/>Pages</li>
                            <ul class="dropdown display-none">
                                <a href="/admin/posts/create"><li>Add new <img class="add" src="/assets/img/add.png"/></li></a>
                                <a href="/admin/posts"><li>Table overview</li></a>
                                <a href="/admin/categories"><li>Category overview</li></a>
                                <?php if(!empty($titleOfLastCreatedPage) && $titleOfLastCreatedPage !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/posts/<?php echo $titleOfLastCreatedPage['id']; ?>/read"><li class="nestedItem">Read page</li></a>
                                        <a href="/admin/posts/<?php echo $titleOfLastCreatedPage['id']; ?>/edit"><li class="nestedItem">Edit page</li></a>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedPage) && $idOfLastUpdatedPage !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/posts/<?php echo $idOfLastUpdatedPage['id']; ?>/read"><li class="nestedItem">Read page</li></a>
                                        <a href="/admin/posts/<?php echo $idOfLastUpdatedPage['id']; ?>/edit"><li class="nestedItem">Edit page</li></a>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/footer.png"/>Menus</li>
                            <ul class="dropdown display-none">
                                <a href="/admin/menus/create"><li>Add new <img class="add" src="/assets/img/add.png"/></li></a>
                                <a href="/admin/menus"><li>Table overview</li></a>
                                <?php if(!empty($idOfLastCreatedMenu) && $idOfLastCreatedMenu !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/menus/<?php echo $idOfLastCreatedMenu['id']; ?>/read"><li class="nestedItem">Read menu</li></a>
                                        <a href="/admin/menus/<?php echo $idOfLastCreatedMenu['id']; ?>/edit"><li class="nestedItem">Edit menu</li></a>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedMenu) && $idOfLastUpdatedMenu !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/menus/<?php echo $idOfLastUpdatedMenu['id']; ?>/read"><li class="nestedItem">Read menu</li></a>
                                        <a href="/admin/menus/<?php echo $idOfLastUpdatedMenu['id']; ?>/edit"><li class="nestedItem">Edit menu</li></a>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/menu.png"/>Widgets</li>
                            <ul class="dropdown display-none">
                                <a href="/admin/widgets/create"><li>Add new <img class="add" src="/assets/img/add.png"/></li></a>
                                <a href="/admin/widgets"><li>Table overview</li></a>
                                <?php if(!empty($idOfLastCreatedWidget) && $idOfLastCreatedWidget !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/widgets/<?php echo $idOfLastCreatedWidget['id']; ?>/read"><li class="nestedItem">Read menu</li></a>
                                        <a href="/admin/widgets/<?php echo $idOfLastCreatedWidget['id']; ?>/edit"><li class="nestedItem">Edit menu</li></a>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedWidget) && $idOfLastUpdatedWidget !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/widgets/<?php echo $idOfLastUpdatedWidget['id']; ?>/read"><li class="nestedItem">Read menu</li></a>
                                        <a href="/admin/widgets/<?php echo $idOfLastUpdatedWidget['id']; ?>/edit"><li class="nestedItem">Edit menu</li></a>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/css.png"/>Css</li>
                            <ul class="dropdown display-none">
                                <a href="/admin/css/create"><li>Add new <img class="add" src="/assets/img/add.png"/></li></a>
                                <a href="/admin/css"><li>Table overview</li></a>
                                <?php if(!empty($idOfLastCreatedCss) && $idOfLastCreatedCss !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/css/<?php echo $idOfLastCreatedCss['id']; ?>/read"><li class="nestedItem">Read file</li></a>
                                        <a href="/admin/css/<?php echo $idOfLastCreatedCss['id']; ?>/edit"><li class="nestedItem">Edit file</li></a>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedCss) && $idOfLastUpdatedCss !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/css/<?php echo $idOfLastUpdatedCss['id']; ?>/read"><li class="nestedItem">Read file</li></a>
                                        <a href="/admin/css/<?php echo $idOfLastUpdatedCss['id']; ?>/edit"><li class="nestedItem">Edit file</li></a>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/technology.png"/>Js</li>
                            <ul class="dropdown display-none">
                                <a href="/admin/js/create"><li>Add new <img class="add" src="/assets/img/add.png"/></li></a>
                                <a href="/admin/js"><li>Table overview</li></a>
                                <?php if(!empty($idOfLastCreatedJs) && $idOfLastCreatedJs !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/js/<?php echo $idOfLastCreatedJs['id']; ?>/read"><li class="nestedItem">Read file</li></a>
                                        <a href="/admin/js/<?php echo $idOfLastCreatedJs['id']; ?>/edit"><li class="nestedItem">Edit file</li></a>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedJs) && $idOfLastUpdatedJs !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/js/<?php echo $idOfLastUpdatedJs['id']; ?>/read"><li class="nestedItem">Read file</li></a>
                                        <a href="/admin/js/<?php echo $idOfLastUpdatedJs['id']; ?>/edit"><li class="nestedItem">Edit file</li></a>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/image.png"/>Media</a></li>
                            <ul class="dropdown display-none">
                                <a href="/admin/media/create"><li>Upload new files <img class="arrowUp" src="/assets/img/up-arrow.png"/></li></a>
                                <a href="/admin/media"><li>Table overview</li></a>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/multiple-users-silhouette.png"/>Users</a></li>
                            <ul class="dropdown display-none">
                                <a href="/admin/users/create"><li>Add new <img class="add" src="/assets/img/add.png"/></li></a>
                                <a href="/admin/users"><li>Table overview</li></a>
                            </ul>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col10 col9-L">
            <div class="settingsContainer">
            <h1>Settings</h1>
            <form action="/admin/settings/update-slug" method="POST" class="updateLoginSlugForm">
                <div class="formParts">
                    <label>Login slug:</label>
                    <input type="text" name="slug" value="<?php if(!empty($currentLoginSlug) && $currentLoginSlug !== null) { echo $currentLoginSlug['slug']; } else { echo 'login'; } ?>"/>
                </div>
            </form>
            </div>

            </div>
        </div>
    </div>

<?php 
    $this->include('footer');
?>
            
        


                    

