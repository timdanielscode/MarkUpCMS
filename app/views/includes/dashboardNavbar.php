<!--
    - to navigate to dashboard and settings pages
    - to search for specific pages 
    - to open/close profile dropdown menu to navigate to profile details page and to loggout
-->
<nav id="navbar">
    <a href="/admin/dashboard"><img id="logo" src="/assets/img/logo.png"></a>
    <form target="_blank" action="/admin/posts" method="GET">
        <img id="searchIcon" src="/assets/img/search.png"/>
        <input type="text" name="search" placeholder="Pages" id="search">
        <input type="hidden" name="submit" value="search">
    </form>
    <?php if(core\Session::get('user_role') === 'admin') { ?>
        <a href="/admin/settings" id="settings">
            <img src="/assets/img/settings.png"/>
        </a>
    <?php } ?>
    <?php if(core\Session::exists('username') === true) { ?>
    <div class="profileContainer">
        <span id="profileIcon" class="profileIcon <?php if(core\Session::get('user_role') === 'admin') { echo 'admin'; } else { echo 'normal';} ?>"><?php echo substr(core\Session::get('username'), 0, 1); ?></span>
        <ul id="profileDropdown">
            <span class="triangle"></span>
            <span class="profileIcon"><?php echo substr(core\Session::get('username'), 0, 1); ?></span>
            <li class="text-center username"><?php echo core\Session::get('username'); ?> <span>(<?php if(core\Session::get('user_role') !== 'admin') { echo 'non admin'; } else { echo core\Session::get('user_role'); } ?></span>)</li>
            <li><a href="/admin/profile/<?php echo core\Session::get('username'); ?>">Profile</a></li>
            <li class="/logout"><a href="/logout">Sign out</a></li>
        </ul>
    </div>
    <?php } ?>
</nav>