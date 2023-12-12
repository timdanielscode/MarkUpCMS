<!-- to navigate through pages -->

<?php $category = database\DB::try()->getLastId('categories')->first(); ?>

<nav id="navbar" class="navbarSmall">
    <a href="/admin/dashboard"><img id="logo" src="/assets/img/logo.png"/></a>
    <ul class="navbarItems">
        <li><a href="/admin/posts">Pages</a></li>
        <li><a href="/admin/categories<?php if(!empty($category) && $category !== null) { echo '/' . $category['id']; } ?>/apply">Categories</a></li>
        <li><a href="/admin/menus">Menus</a></li>
        <li><a href="/admin/widgets">Widgets</a></li>
        <li><a href="/admin/css">Css</a></li>
        <li><a href="/admin/js">Js</a></li>
        <li><a href="/admin/meta">Meta</a></li>
        <li><a href="/admin/media">Media</a></li>
    </ul>
</nav>