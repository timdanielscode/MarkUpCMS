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
                    <nav id="navigationMenu">
                        <ul id="dropdownItems">
                            <li class="dropdownItem">Pages</li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/posts">Table</a></li>
                                <li><a href="/admin/posts/create">New page</a></li>
                            </ul>
                            <li class="dropdownItem">Menus</li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/menus">Table</a></li>
                                <li><a href="/admin/menus/create">New menu</a></li>
                            </ul>
                            <li class="dropdownItem">Categories</li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/categories">Table</a></li>
                                <li><a href="/admin/categories/create">New category</a></li>
                            </ul>
                            <li class="dropdownItem">Css</li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/css">Table</a></li>
                                <li><a href="/admin/css/create">New stylesheet</a></li>
                            </ul>
                            <li class="dropdownItem">Js</li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/js">Table</a></li>
                                <li><a href="/admin/js/create">New script</a></li>
                            </ul>
                            <li class="dropdownItem">Media</a></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/media">Table</a></li>
                                <li><a href="/admin/media/create">Upload files</a></li>
                            </ul>
                            <li class="dropdownItem">Users</a></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/users">Table</a></li>
                                <li><a href="/admin/users/create">New user</a></li>
                            </ul>
                            <li class="dropdownItem">Profile</a></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/posts">Details</a></li>
                                <li><a href="/admin/posts">Change role</a></li>
                                <li><a href="/admin/posts">Change password</a></li>
                                <li><a href="/admin/posts">Remove account</a></li>
                            </ul>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col10 col9-L">

        </div>
    </div>
</div>

<?php 
    $this->include('footer');
?>