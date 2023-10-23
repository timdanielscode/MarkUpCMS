<?php use database\DB; ?>
<?php use core\Session; ?>

<?php 

    $idOfLastCreatedPage = DB::try()->select('id, title')->from('pages')->where('removed', '!=', '1')->order('created_at')->desc()->first(); 
    $idOfLastUpdatedPage = DB::try()->select('id, title')->from('pages')->where('removed', '!=', '1')->order('updated_at')->desc()->first();
    $idOfLastCreatedMenu = DB::try()->select('id, title')->from('menus')->where('removed', '!=', '1')->order('created_at')->desc()->first();
    $idOfLastUpdatedMenu = DB::try()->select('id')->from('menus')->where('removed', '!=', '1')->order('updated_at')->desc()->first();
    $idOfLastCreatedWidget = DB::try()->select('id')->from('widgets')->where('removed', '!=', 1)->order('created_at')->desc()->first();
    $idOfLastUpdatedWidget = DB::try()->select('id')->from('widgets')->where('removed', '!=', 1)->order('updated_at')->desc()->first();
    $idOfLastCreatedCss = DB::try()->select('id')->from('css')->where('removed', '!=', '1')->order('created_at')->desc()->first();
    $idOfLastUpdatedCss = DB::try()->select('id')->from('css')->where('removed', '!=', '1')->order('updated_at')->desc()->first();
    $idOfLastCreatedJs = DB::try()->select('id')->from('js')->where('removed', '!=', '1')->order('created_at')->desc()->first();
    $idOfLastUpdatedJs = DB::try()->select('id')->from('js')->where('removed', '!=', '1')->order('updated_at')->desc()->first();

?>            
            
            <div id="sidebar" class="width-25-L">
                <div class="sidebarContainer">
                    <nav id="navigationMenu">
                        <ul id="dropdownItems">
                            <li class="dropdownItem"><img src="/assets/img/file.png"/>Pages</li>
                            <ul class="dropdown">
                                <?php if(Session::get('user_role') === 'admin') { ?>
                                <a href="/admin/posts/create"><li>Add new <img class="add" src="/assets/img/add.png"/></li></a>
                                <?php } ?>
                                <a href="/admin/posts"><li>Table overview</li></a>
                                <a href="/admin/categories"><li>Category overview</li></a>
                                <?php if(!empty($idOfLastCreatedPage) && $idOfLastCreatedPage !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/posts/<?php echo $idOfLastCreatedPage['id']; ?>/read"><li class="nestedItem">Read page</li></a>
                                        <?php if(Session::get('user_role') === 'admin') { ?>
                                        <a href="/admin/posts/<?php echo $idOfLastCreatedPage['id']; ?>/edit"><li class="nestedItem">Edit page</li></a>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedPage) && $idOfLastUpdatedPage !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/posts/<?php echo $idOfLastUpdatedPage['id']; ?>/read"><li class="nestedItem">Read page</li></a>
                                        <?php if(Session::get('user_role') === 'admin') { ?>
                                        <a href="/admin/posts/<?php echo $idOfLastUpdatedPage['id']; ?>/edit"><li class="nestedItem">Edit page</li></a>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/footer.png"/>Menus</li>
                            <ul class="dropdown display-none">
                                <?php if(Session::get('user_role') === 'admin') { ?>
                                <a href="/admin/menus/create"><li>Add new <img class="add" src="/assets/img/add.png"/></li></a>
                                <?php } ?>
                                <a href="/admin/menus"><li>Table overview</li></a>
                                <?php if(!empty($idOfLastCreatedMenu) && $idOfLastCreatedMenu !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/menus/<?php echo $idOfLastCreatedMenu['id']; ?>/read"><li class="nestedItem">Read menu</li></a>
                                        <?php if(Session::get('user_role') === 'admin') { ?>
                                        <a href="/admin/menus/<?php echo $idOfLastCreatedMenu['id']; ?>/edit"><li class="nestedItem">Edit menu</li></a>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedMenu) && $idOfLastUpdatedMenu !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/menus/<?php echo $idOfLastUpdatedMenu['id']; ?>/read"><li class="nestedItem">Read menu</li></a>
                                        <?php if(Session::get('user_role') === 'admin') { ?>
                                        <a href="/admin/menus/<?php echo $idOfLastUpdatedMenu['id']; ?>/edit"><li class="nestedItem">Edit menu</li></a>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/menu.png"/>Widgets</li>
                            <ul class="dropdown">
                                <?php if(Session::get('user_role') === 'admin') { ?>
                                <a href="/admin/widgets/create"><li>Add new <img class="add" src="/assets/img/add.png"/></li></a>
                                <?php } ?>
                                <a href="/admin/widgets"><li>Table overview</li></a>
                                <?php if(!empty($idOfLastCreatedWidget) && $idOfLastCreatedWidget !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/widgets/<?php echo $idOfLastCreatedWidget['id']; ?>/read"><li class="nestedItem">Read menu</li></a>
                                        <?php if(Session::get('user_role') === 'admin') { ?>
                                        <a href="/admin/widgets/<?php echo $idOfLastCreatedWidget['id']; ?>/edit"><li class="nestedItem">Edit menu</li></a>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedWidget) && $idOfLastUpdatedWidget !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/widgets/<?php echo $idOfLastUpdatedWidget['id']; ?>/read"><li class="nestedItem">Read menu</li></a>
                                        <?php if(Session::get('user_role') === 'admin') { ?>
                                        <a href="/admin/widgets/<?php echo $idOfLastUpdatedWidget['id']; ?>/edit"><li class="nestedItem">Edit menu</li></a>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/css.png"/>Css</li>
                            <ul class="dropdown display-none">
                                <?php if(Session::get('user_role') === 'admin') { ?>
                                <a href="/admin/css/create"><li>Add new <img class="add" src="/assets/img/add.png"/></li></a>
                                <?php } ?>
                                <a href="/admin/css"><li>Table overview</li></a>
                                <?php if(!empty($idOfLastCreatedCss) && $idOfLastCreatedCss !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/css/<?php echo $idOfLastCreatedCss['id']; ?>/read"><li class="nestedItem">Read file</li></a>
                                        <?php if(Session::get('user_role') === 'admin') { ?>
                                        <a href="/admin/css/<?php echo $idOfLastCreatedCss['id']; ?>/edit"><li class="nestedItem">Edit file</li></a>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedCss) && $idOfLastUpdatedCss !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/css/<?php echo $idOfLastUpdatedCss['id']; ?>/read"><li class="nestedItem">Read file</li></a>
                                        <?php if(Session::get('user_role') === 'admin') { ?>
                                        <a href="/admin/css/<?php echo $idOfLastUpdatedCss['id']; ?>/edit"><li class="nestedItem">Edit file</li></a>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/technology.png"/>Js</li>
                            <ul class="dropdown display-none">
                                <?php if(Session::get('user_role') === 'admin') { ?>
                                <a href="/admin/js/create"><li>Add new <img class="add" src="/assets/img/add.png"/></li></a>
                                <?php } ?>
                                <a href="/admin/js"><li>Table overview</li></a>
                                <?php if(!empty($idOfLastCreatedJs) && $idOfLastCreatedJs !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/js/<?php echo $idOfLastCreatedJs['id']; ?>/read"><li class="nestedItem">Read file</li></a>
                                        <?php if(Session::get('user_role') === 'admin') { ?>
                                        <a href="/admin/js/<?php echo $idOfLastCreatedJs['id']; ?>/edit"><li class="nestedItem">Edit file</li></a>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedJs) && $idOfLastUpdatedJs !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/js/<?php echo $idOfLastUpdatedJs['id']; ?>/read"><li class="nestedItem">Read file</li></a>
                                        <?php if(Session::get('user_role') === 'admin') { ?>
                                        <a href="/admin/js/<?php echo $idOfLastUpdatedJs['id']; ?>/edit"><li class="nestedItem">Edit file</li></a>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/image.png"/>Media</a></li>
                            <ul class="dropdown">
                                <?php if(Session::get('user_role') === 'admin') { ?>
                                <a href="/admin/media/create"><li>Upload new files <img class="arrowUp" src="/assets/img/up-arrow.png"/></li></a>
                                <?php } ?>
                                <a href="/admin/media"><li>Table overview</li></a>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/multiple-users-silhouette.png"/>Users</a></li>
                            <ul class="dropdown display-none">
                                <?php if(Session::get('user_role') === 'admin') { ?>
                                <a href="/admin/users/create"><li>Add new <img class="add" src="/assets/img/add.png"/></li></a>
                                <?php } ?>
                                <a href="/admin/users"><li>Table overview</li></a>
                            </ul>
                            <?php if(Session::get('user_role') === 'admin') { ?>
                            <a href="/admin/settings"><li class="dropdownItem"><img src="/assets/img/settingsBlack.png"/>Settings</li></a>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>