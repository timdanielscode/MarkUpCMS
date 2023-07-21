<?php use core\Session; ?>

<nav id="IndependentCMSMainNavbar" class="navbarSmall">
    <ul>
        <li><a href="/admin/posts">Pages</a></li>
        <li><a href="/admin/users">Users</a></li>
        <li><a href="/admin/css">Css</a></li>
        <li><a href="/admin/js">Js</a></li>
        <li><a href="/admin/menus">Menus</a></li>
        <li><a href="/admin/media">Media</a></li>
        <li><a href="/admin/categories">Categories</a></li>
    </ul>
    <?php if(Session::exists('username') === true) { ?>
        <div class="profileContainer">
            <span id="profileIcon" class="profileIcon"><?php echo substr(Session::get('username'), 0, 1); ?></span>
            <ul id="profileDropdown">
                <span class="profileIcon"><?php echo substr(Session::get('username'), 0, 1); ?></span>
                <li class="text-center username"><?php echo Session::get('username'); ?></li>
                <li><a href="/profile/<?php echo Session::get('username'); ?>">Profile</a></li>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Settings</a></li>
                <li class="/logout"><a href="/logout">Logout</a></li>
            </ul>
        </div>
    <?php } ?>
</nav>