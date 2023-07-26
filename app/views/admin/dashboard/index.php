<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/sidebar.css");
    $this->stylesheet("/assets/css/dashboard.css");

    $this->script("/assets/js/dashboard.js", true);

    $this->title("IndependentCMS");
    $this->include("headerClose");
?>

<div class="main-container">
    <div class="row">
        <div class=" col2 col3-L">
            <div id="sidebar" class="width-25-L">
                <div class="sidebarContainer">
                    <div class="profile">
                        <span>Hi, <?php echo Session::get('username'); ?><span>
                    </div>
                    <nav id="navigationMenu">
                        <ul id="dropdownItems">
                            <li class="dropdownItem"></span>Pages<span class="count"><?php echo count($pages); ?></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/posts">Table</a></li>
                                <li><a href="/admin/posts/create">New page</a></li>
                            </ul>
                            <li class="dropdownItem">Menus<span class="count"><?php echo count($menus); ?></span></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/menus">Table</a></li>
                                <li><a href="/admin/menus/create">New menu</a></li>
                            </ul>
                            <li class="dropdownItem">Categories<span class="count"><?php echo count($categories); ?></span></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/categories">Table</a></li>
                                <li><a href="/admin/categories/create">New category</a></li>
                            </ul>
                            <li class="dropdownItem">Css<span class="count"><?php echo count($css); ?></span></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/css">Table</a></li>
                                <li><a href="/admin/css/create">New stylesheet</a></li>
                            </ul>
                            <li class="dropdownItem">Js<span class="count"><?php echo count($js); ?></span></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/js">Table</a></li>
                                <li><a href="/admin/js/create">New script</a></li>
                            </ul>
                            <li class="dropdownItem">Media<span class="count"><?php echo count($media); ?></span></a></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/media">Table</a></li>
                                <li><a href="/admin/media/create">Upload files</a></li>
                            </ul>
                            <li class="dropdownItem">Users<span class="count"><?php echo count($users); ?></span></a></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/users">Table</a></li>
                                <li><a href="/admin/users/create">New user</a></li>
                            </ul>
                            <li class="dropdownItem">Profile</a></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/profile/<?php echo Session::get('username'); ?>">Manage</a></li>
                            </ul>
                            <a href="/logout" class="button">Logout</a>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col10 col9-L">
            <div class="chartContainer">
                <div class="row">
                    <div class="col6">
                        <div class="row">
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Pages</span>
                                    <span class="amount"><?php echo count($pages); ?></span>
                                    <span class="label">Content applied: <?php echo count($contentAppliedPages); ?></span>
                                    <progress class="bar" value="<?php echo count($contentAppliedPages); ?>" max="<?php echo count($pages); ?>"></progress>
                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Menus</span>
                                    <span class="amount"><?php echo count($menus); ?></span>
                                    <span class="label">Content applied: <?php echo count($contentAppliedMenus); ?></span>
                                    <progress class="bar" value="<?php echo count($contentAppliedMenus); ?>" max="<?php echo count($menus); ?>"></progress>
                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Css</span>
                                    <span class="amount"><?php echo count($css); ?></span>
                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Js</span>
                                    <span class="amount"><?php echo count($js); ?></span>
                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Users</span>
                                    <span class="amount"><?php echo count($users); ?></span>
                                    <div class="chartContainerUsers">
                                        <span id="authors" value="<?php print_r($chartUserRoles); ?>"></span>
                                        <span id="numberOfNormalUsers" value="<?php echo $chartNumberOfNormalUsers; ?>"></span>
                                        <span id="numberOfAdminUsers" value="<?php echo $chartNumberOfAdminUsers; ?>"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col6">
    
                    </div>
                    <div class="col6">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    $this->include('footer');
?>