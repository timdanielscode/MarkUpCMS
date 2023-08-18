<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/sidebar.css");
    $this->stylesheet("/assets/css/dashboard.css");
    

    $this->script("/assets/js/dashboard.js", true);
    $this->script("/assets/js/navbar.js", true);

    $this->title("IndependentCMS");
    $this->include("headerClose");
?>
    <nav id="navbar">
        <a href="/admin/dashboard"><img id="logo" src="/assets/img/logo.png"></a>

        <?php if(Session::exists('username') === true) { ?>
        <div class="profileContainer">
            <span id="profileIcon" class="profileIcon"><?php echo substr(Session::get('username'), 0, 1); ?></span>
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
    <div class="row">
        <div class=" col2 col3-L">
            <div id="sidebar" class="width-25-L">
                <div class="sidebarContainer">
                    <nav id="navigationMenu">
                        <ul id="dropdownItems">
                            <li class="dropdownItem"><img src="/assets/img/right-arrow.png"/>Pages</li>
                            <ul class="dropdown display-none">
                                <a href="/admin/posts/create"><li>Add new</li></a>
                                <a href="/admin/posts"><li>Table overview</li></a>
                                <?php if(!empty($titleOfLastCreatedPage) && $titleOfLastCreatedPage !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/posts/<?php echo $titleOfLastCreatedPage['id']; ?>/read"><li class="nestedItem">Read</li></a>
                                        <a href="/admin/posts/<?php echo $titleOfLastCreatedPage['id']; ?>/edit"><li class="nestedItem">Edit</li></a>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedPage) && $idOfLastUpdatedPage !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/posts/<?php echo $idOfLastUpdatedPage['id']; ?>/read"><li class="nestedItem">Read</li></a>
                                        <a href="/admin/posts/<?php echo $idOfLastUpdatedPage['id']; ?>/edit"><li class="nestedItem">Edit</li></a>
                                    </ul>
                                <?php } ?>
                            </ul>
                            
                            <li class="dropdownItem"><img src="/assets/img/right-arrow.png"/>Categories</li>
                            <ul class="dropdown display-none">
                                <a href="/admin/categories"><li>Table overview</li></a>
                                <?php if(!empty($titleOfLastCreatedCategory) && $titleOfLastCreatedCategory !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/categories?search=<?php echo $titleOfLastCreatedCategory['title']; ?>"><li class="nestedItem">Category row</li></a>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/right-arrow.png"/>Menus</li>
                            <ul class="dropdown display-none">
                                <a href="/admin/menus/create"><li>Add new</li></a>
                                <a href="/admin/menus"><li>Table overview</li></a>
                                <?php if(!empty($idOfLastCreatedMenu) && $idOfLastCreatedMenu !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/menus/<?php echo $idOfLastCreatedMenu['id']; ?>/read"><li class="nestedItem">Read</li></a>
                                        <a href="/admin/menus/<?php echo $idOfLastCreatedMenu['id']; ?>/edit"><li class="nestedItem">Edit</li></a>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedMenu) && $idOfLastUpdatedMenu !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/menus/<?php echo $idOfLastUpdatedMenu['id']; ?>/read"><li class="nestedItem">Read</li></a>
                                        <a href="/admin/menus/<?php echo $idOfLastUpdatedMenu['id']; ?>/edit"><li class="nestedItem">Edit</li></a>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/right-arrow.png"/>Css</li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/css/create">Add new</a></li>
                                <li><a href="/admin/css">Table overview</a></li>
                                <?php if(!empty($idOfLastCreatedCss) && $idOfLastCreatedCss !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/css/<?php echo $idOfLastCreatedCss['id']; ?>/read"><li class="nestedItem">Read</li></a>
                                        <a href="/admin/css/<?php echo $idOfLastCreatedCss['id']; ?>/edit"><li class="nestedItem">Edit</li></a>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedCss) && $idOfLastUpdatedCss !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/css/<?php echo $idOfLastUpdatedCss['id']; ?>/read"><li class="nestedItem">Read</li></a>
                                        <a href="/admin/css/<?php echo $idOfLastUpdatedCss['id']; ?>/edit"><li class="nestedItem">Edit</li></a>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/right-arrow.png"/>Js</li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/js/create">Add new</a></li>
                                <li><a href="/admin/js">Table overview</a></li>
                                <?php if(!empty($idOfLastCreatedJs) && $idOfLastCreatedJs !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last created</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/js/<?php echo $idOfLastCreatedJs['id']; ?>/read"><li class="nestedItem">Read</li></a>
                                        <a href="/admin/js/<?php echo $idOfLastCreatedJs['id']; ?>/edit"><li class="nestedItem">Edit</li></a>
                                    </ul>
                                <?php } ?>
                                <?php if(!empty($idOfLastUpdatedJs) && $idOfLastUpdatedJs !== null) { ?>
                                    <li class="dropdownItem nestedDropdownItem"><img src="/assets/img/right-arrow.png"/>Last updated</li>
                                    <ul class="dropdown display-none">
                                        <a href="/admin/js/<?php echo $idOfLastUpdatedJs['id']; ?>/read"><li class="nestedItem">Read</li></a>
                                        <a href="/admin/js/<?php echo $idOfLastUpdatedJs['id']; ?>/edit"><li class="nestedItem">Edit</li></a>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/right-arrow.png"/>Media</a></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/media/create">Upload new files</a></li>
                                <li><a href="/admin/media">Table overview</a></li>
                            </ul>
                            <li class="dropdownItem"><img src="/assets/img/right-arrow.png"/>Users</a></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/users/create">Add new</a></li>
                                <li><a href="/admin/users">Table overview</a></li>
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
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Pages <span class="small">(total)</span></span>
                                    <span class="amount"><?php echo count($pages); ?></span>
                                    <span class="label2">Content: <?php echo count($contentAppliedPages); ?></span>
                                    <progress class="bar2 darkBlue" value="<?php echo count($contentAppliedPages); ?>" max="<?php echo count($pages); ?>"></progress>
                                    <span class="label">Trashcan: <?php echo count($removedPages); ?></span>
                                    <progress class="bar red" value="<?php echo count($removedPages); ?>" max="<?php echo count($pages); ?>"></progress>

                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Menus <span class="small">(total)</span></span>
                                    <span class="amount"><?php echo count($menus); ?></span>
                                    <span class="label4">Ordering: <?php echo count($orderingAppliedMenus); ?></span>
                                    <progress class="bar4 darkBlue" value="<?php echo count($orderingAppliedMenus); ?>" max="<?php echo count($menus); ?>"></progress>
                                    <span class="label3">Position: <?php echo count($positionAppliedMenus); ?></span>
                                    <progress class="bar3 darkBlue" value="<?php echo count($positionAppliedMenus); ?>" max="<?php echo count($menus); ?>"></progress>
                                    <span class="label2">Content: <?php echo count($contentAppliedMenus); ?></span>
                                    <progress class="bar2 darkBlue" value="<?php echo count($contentAppliedMenus); ?>" max="<?php echo count($menus); ?>"></progress>
                                    <span class="label">Trashcan: <?php echo count($removedMenus); ?></span>
                                    <progress class="bar red" value="<?php echo count($removedMenus); ?>" max="<?php echo count($menus); ?>"></progress>
                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Css <span class="small">(total)</span></span>
                                    <span class="amount"><?php echo count($css); ?></span>
                                    <span class="label3">Linked: <?php echo $numberOfLinkedCss; ?></span>
                                    <progress class="bar3 darkBlue" value="<?php echo $numberOfLinkedCss; ?>" max="<?php echo count($css); ?>"></progress>
                                    <span class="label2">Content: <?php echo count($contentAppliedCss); ?></span>
                                    <progress class="bar2 darkBlue" value="<?php echo count($contentAppliedCss); ?>" max="<?php echo count($css); ?>"></progress>
                                    <span class="label">Trashcan: <?php echo count($removedCss); ?></span>
                                    <progress class="bar red" value="<?php echo count($removedCss); ?>" max="<?php echo count($css); ?>"></progress>
                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Js <span class="small">(total)</span></span>
                                    <span class="amount"><?php echo count($js); ?></span>
                                    <span class="label3">Included: <?php echo $numberOfIncludedJs; ?></span>
                                    <progress class="bar3 darkBlue" value="<?php echo $numberOfIncludedJs; ?>" max="<?php echo count($menus); ?>"></progress>
                                    <span class="label2">Content: <?php echo count($contentAppliedJs); ?></span>
                                    <progress class="bar2 darkBlue" value="<?php echo count($contentAppliedJs); ?>" max="<?php echo count($js); ?>"></progress>
                                    <span class="label">Trashcan: <?php echo count($removedJs); ?></span>
                                    <progress class="bar red" value="<?php echo count($removedJs); ?>" max="<?php echo count($js); ?>"></progress>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col4">
                        <div class="progressContainer">
                            <div class="container">
                                <span class="header">Seo <span class="small">(applied on pages)</span></span>
                                <div class="grouped first">
                                    <span class="label seo">Meta Title</span>
                                    <progress class="bar lightBlue" value="<?php echo $numberOfAppliedMetaTitle; ?>" max="<?php echo $numberOfPages; ?>"></progress>
                                    <span class="value"><?php echo $numberOfAppliedMetaTitle; ?></span>
                                </div>
                                <div class="grouped">
                                    <span class="label seo">Meta Description</span>
                                    <progress class="bar lightBlue" value="<?php echo $numberOfAppliedMetaDescription; ?>" max="<?php echo $numberOfPages; ?>"></progress>
                                    <span class="value"><?php echo $numberOfAppliedMetaDescription; ?></span>
                                </div>
                                <div class="grouped">
                                    <span class="label seo">Meta Keywords</span>
                                    <progress class="bar lightBlue" value="<?php echo $numberOfAppliedMetaKeywords; ?>" max="<?php echo $numberOfPages; ?>"></progress>
                                    <span class="value"><?php echo $numberOfAppliedMetaKeywords; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col8">
                        <div class="progressContainer float-right">
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
        </div>
    </div>

<?php 
    $this->include('footer');
?>