<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/sidebar.css");
    $this->stylesheet("/assets/css/dashboard.css");
    
    $this->script("/assets/js/dashboard/settings.js", true);
    $this->script("/assets/js/dashboard/sidebar.js", true);
    $this->script("/assets/js/dashboard/amounts.js", true);
    $this->script("/assets/js/navbar.js", true);

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
            <div class="cardProgressContainer">
                <div class="row">
                    <div class="col6">
                        <div class="row">
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Pages <span class="total">(total) </span></span>
                                    <span class="amount"><?php echo count($pages); ?></span>
                                    <div class="stopMousemoveEvent"></div>
                                    <div class="progressContainer">
                                        
                                        <div class="layer"></div>
                                        <span class="label">Meta title</span>
                                        <progress class="bar lightBlue" value="<?php echo $numberOfAppliedMetaTitle; ?>" max="<?php echo $numberOfPages; ?>"></progress>
                                    </div>
                                    <div class="progressContainer">
                               
                                        <div class="layer"></div>
                                        <span class="label">Meta description</span>
                                        <progress class="bar lightBlue" value="<?php echo $numberOfAppliedMetaDescription; ?>" max="<?php echo $numberOfPages; ?>"></progress>
                                    </div>
                                    <div class="progressContainer">
                                  
                                        <div class="layer"></div>
                                        <span class="label">Meta keywords</span>
                                        <progress class="bar lightBlue" value="<?php echo $numberOfAppliedMetaKeywords; ?>" max="<?php echo $numberOfPages; ?>"></progress>
                                    </div>
                                    <div class="progressContainer">
                                   
                                        <div class="layer"></div>
                                        <span class="label">Content</span>
                                        <progress class="bar darkBlue" value="<?php echo count($contentAppliedPages); ?>" max="<?php echo count($pages); ?>"></progress>
                                    </div>
                                    <div class="progressContainer">
                                 
                                        <div class="layer"></div>
                                        <span class="label">Trashcan</span>
                                        <progress class="bar red" value="<?php echo count($removedPages); ?>" max="<?php echo count($pages); ?>"></progress>
                                    </div>
                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Menus <span class="total">(total)</span></span>
                                    <span class="amount"><?php echo count($menus); ?></span>
                                    <div class="stopMousemoveEvent"></div>
                                    <div class="progressContainer"></div>
                                    <div class="progressContainer">
                                        <div class="layer"></div>
                                        <span class="label">Ordering</span>
                                        <progress class="bar darkBlue" value="<?php echo count($orderingAppliedMenus); ?>" max="<?php echo count($menus); ?>"></progress>
                                    </div>
                                    <div class="progressContainer">
                                        <div class="layer"></div>
                                        <span class="label">Position</span>
                                        <progress class="bar darkBlue" value="<?php echo count($positionAppliedMenus); ?>" max="<?php echo count($menus); ?>"></progress>
                                    </div>
                                    <div class="progressContainer">

                                        <div class="layer"></div>
                                        <span class="label">Content</span>
                                        <progress class="bar darkBlue" value="<?php echo count($contentAppliedMenus); ?>" max="<?php echo count($menus); ?>"></progress>
                                    </div>
                                    <div class="progressContainer">
                                        <div class="layer"></div>
                                        <span class="label">Trashcan</span>
                                        <progress class="bar red" value="<?php echo count($removedMenus); ?>" max="<?php echo count($menus); ?>"></progress>
                                    </div>
                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Widgets <span class="total">(total)</span></span>
                                    <span class="amount"><?php echo count($widgets); ?></span>
                                    <div class="stopMousemoveEvent"></div>
                                    <div class="progressContainer"></div>
                                    <div class="progressContainer"></div>
                                    <div class="progressContainer"></div>
                                    <div class="progressContainer">

                                        <div class="layer"></div>
                                        <span class="label">Content</span>
                                        <progress class="bar darkBlue" value="<?php echo count($contentAppliedWidgets); ?>" max="<?php echo count($widgets); ?>"></progress>
                                    </div>
                                    <div class="progressContainer">
       
                                        <div class="layer"></div>
                                        <span class="label">Trashcan</span>
                                        <progress class="bar red" value="<?php echo count($removedWidgets); ?>" max="<?php echo count($widgets); ?>"></progress>
                                    </div>
                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Css <span class="total">(total)</span></span>
                                    <span class="amount"><?php echo count($css); ?></span>
                                    <div class="stopMousemoveEvent"></div>
                                    <div class="progressContainer"></div>
                                    <div class="progressContainer"></div>
                                    <div class="progressContainer">
                   
                                        <div class="layer"></div>
                                        <span class="label">Linked</span>
                                        <progress class="bar darkBlue" value="<?php echo $numberOfLinkedCss; ?>" max="<?php echo count($css); ?>"></progress>
                                    </div>
                                    <div class="progressContainer">
     
                                        <div class="layer"></div>
                                        <span class="label">Content</span>
                                        <progress class="bar darkBlue" value="<?php echo count($contentAppliedCss); ?>" max="<?php echo count($css); ?>"></progress>
                                    </div>
                                    <div class="progressContainer">
              
                                        <div class="layer"></div>
                                        <span class="label">Trashcan</span>
                                        <progress class="bar red" value="<?php echo count($removedCss); ?>" max="<?php echo count($css); ?>"></progress>
                                    </div>
                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Js <span class="total">(total)</span></span>
                                    <span class="amount"><?php echo count($js); ?></span>
                                    <div class="stopMousemoveEvent"></div>
                                    <div class="progressContainer"></div>
                                    <div class="progressContainer"></div>
                                    <div class="progressContainer">
 
                                        <div class="layer"></div>
                                        <span class="label">Included </span>
                                        <progress class="bar darkBlue" value="<?php echo $numberOfIncludedJs; ?>" max="<?php echo count($menus); ?>"></progress>
                                    </div>
                                    <div class="progressContainer">

                                        <div class="layer"></div>
                                        <span class="label">Content</span>
                                        <progress class="bar darkBlue" value="<?php echo count($contentAppliedJs); ?>" max="<?php echo count($js); ?>"></progress>
                                    </div>
                                    <div class="progressContainer">
      
                                        <div class="layer"></div>
                                        <span class="label">Trashcan</span>
                                        <progress class="bar red" value="<?php echo count($removedJs); ?>" max="<?php echo count($js); ?>"></progress>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col4">
                        <div class="cardContainerSecondRow">
                            <div class="container">
                            <span class="header">Users <span class="small">(total)</span></span>
                                    <span class="amount"><?php echo count($users); ?></span>
                                    <div class="containerUsers">
                                        <span id="authors" value="<?php print_r($chartUserRoles); ?>"></span>
                                        <span class="circleUsers" style="background: conic-gradient(#1888e1 0deg calc(3.6deg * <?php echo $percentageOfAdminUsers; ?>), #72b7ee 0deg calc(3.6deg * <?php echo $percentageOfNormalUsers; ?>));"><span class="innerCircle"></span></span>
                                        <span class="labelTypeOfAdmin">Admin: <?php echo $numberOfAdminUsers; ?> <span class="colorAdmin"></span></span>
                                        <span class="labelTypeOfNormal">Normal:  <?php echo $numberOfNormalUsers; ?> <span class="colorNormal"></span></span>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col8">
                        <div class="cardContainerSecondRow float-right">
                            <div class="container">
                                <span class="header">Media <span class="small">(total)</span></span>
                                <span class="amount"><?php echo count($media); ?></span>
                                <div class="grouped media">
                                    <span class="label">.jpeg</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypeJpg; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypeJpg; ?></span>
                                </div>
                                <div class="grouped media">
                                    <span class="label">.png</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypePng; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypePng; ?></span>
                                </div>
                                <div class="grouped media">
                                    <span class="label">.webp</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypeWebp; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypeWebp; ?></span>
                                </div>
                                <div class="grouped media">
                                    <span class="label">.gif</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypeGif; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypeGif; ?></span>
                                </div>
                                <div class="grouped media">
                                    <span class="label">.svg</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypeSvg; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypeSvg; ?></span>
                                </div>
                                <div class="grouped media">
                                    <span class="label">.mp4</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypeMp4; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypeMp4; ?></span>
                                </div>
                                <div class="grouped media">
                                    <span class="label">.pdf</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypePdf; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypePdf; ?></span>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
            <div id="settingsContainer" class="display-none">



                <form action="/admin/dashboard/update-login-slug" method="POST">

                <div class="formParts">
                    <label>Login slug:</label>
                    <input type="text" name="slug" value=""/>
                </div>


                </form>

            </div>
        </div>
    </div>

<?php 
    $this->include('footer');
?>