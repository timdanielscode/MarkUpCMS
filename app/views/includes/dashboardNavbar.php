<!--
    - to navigate to dashboard and settings pages
    - to search for specific pages 
    - to open/close profile dropdown menu to navigate to profile details page and to loggout
-->

<?php $userId = database\DB::try()->select('id')->from('users')->where('username', '=', core\Session::get('username'))->first(); ?>

<nav id="navbar">
    <a href="/admin/dashboard"><img id="logo" src="/assets/img/logo.png"></a>
    <form target="_blank" action="/admin/pages" method="GET">
        <img id="searchIcon" src="/assets/img/search.png"/>
        <input type="text" name="search" placeholder="Pages" id="search">
        <input type="hidden" name="submit" value="search">
    </form>
    <?php if(core\Session::get('user_role') === 1) { ?>
        <a href="/admin/settings" id="settings">
            <img src="/assets/img/settings.png"/>
        </a>
    <?php } ?>
    <?php if(core\Session::exists('username') === true) { ?>
    <div class="profileContainer">
        <span id="profileIcon" class="profileIcon <?php if(core\Session::get('user_role') === 1) { echo 'admin'; } else { echo 'normal';} ?>"><?php echo substr(core\Session::get('username'), 0, 1); ?></span>
        <ul id="profileDropdown">
            <span class="triangle"></span>
            <span class="profileIcon"><?php echo substr(core\Session::get('username'), 0, 1); ?></span>
            <li class="text-center username"><?php echo core\Session::get('username'); ?> <span>(<?php if(core\Session::get('user_role') !== 1) { echo 'normal'; } else { echo 'admin'; } ?></span>)</li>
            <li><a href="/admin/profile/<?php echo $userId['id']; ?>">Profile</a></li>
            <li class="/logout"><a href="/logout">Sign out</a></li>
        </ul>
    </div>
    <?php } ?>
</nav>