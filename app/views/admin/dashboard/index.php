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

<div class="main-container">
<nav id="IndependentCMSMainNavbar" class="navbarSmall">
    <ul>
        <li><a href="/admin/posts">Logo</a></li>
    </ul>
            <div class="profileContainer">
            <span id="profileIcon" class="profileIcon">t</span>
            
            <ul id="profileDropdown">
            <span class="triangle"></span>
                <span class="profileIcon">t</span>
                <li class="text-center username">testuser</li>
                <li><a href="/admin/profile/testuser">Profile</a></li>
                <li><a href="/admin/dashboard">Dashboard</a></li>
                <li><a href="#">Settings</a></li>
                <li class="/logout"><a href="/logout">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="row">
        <div class=" col2 col3-L">
            <div id="sidebar" class="width-25-L">
                <div class="sidebarContainer">
                    <nav id="navigationMenu">
                        <ul id="dropdownItems">
                            <li class="dropdownItem">Pages</li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/posts/create">Add new</a></li>
                                <li><a href="/admin/posts">Table overview</a></li>
                            </ul>
                            <li class="dropdownItem">Categories</li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/categories/create">Add new</a></li>
                                <li><a href="/admin/categories">Table overview</a></li>
                            </ul>
                            <li class="dropdownItem">Menus</li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/menus/create">Add new</a></li>
                                <li><a href="/admin/menus">Table overview</a></li>
                            </ul>
                            <li class="dropdownItem">Css</li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/css/create">Add new</a></li>
                                <li><a href="/admin/css">Table overview</a></li>
                            </ul>
                            <li class="dropdownItem">Js</li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/js/create">Add new</a></li>
                                <li><a href="/admin/js">Table overview</a></li>
                            </ul>
                            <li class="dropdownItem">Media</a></li>
                            <ul class="dropdown display-none">
                                <li><a href="/admin/media/create">Upload new files</a></li>
                                <li><a href="/admin/media">Table overview</a></li>
                            </ul>
                            <span class="sidebarSeparation"></span>
                            <li class="dropdownItem">Users</a></li>
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
                                    <span class="newestText">Last created: </span>
                                    <a href="/admin/posts/<?php echo $titleOfLastCreatedPage['id']; ?>/read"><span class="lastCreatedPage"><?php echo $titleOfLastCreatedPage['title']; ?></span></a>
                                    <span class="label">Content: <?php echo count($contentAppliedPages); ?></span>
                                    <progress class="bar" value="<?php echo count($contentAppliedPages); ?>" max="<?php echo count($pages); ?>"></progress>
                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Menus <span class="small">(total)</span></span>
                                    <span class="amount"><?php echo count($menus); ?></span>
                                    <span class="label3">Position: <?php echo count($positionAppliedMenus); ?></span>
                                    <progress class="bar3" value="<?php echo count($positionAppliedMenus); ?>" max="<?php echo count($menus); ?>"></progress>
                                    <span class="label2">Ordering: <?php echo count($orderingAppliedMenus); ?></span>
                                    <progress class="bar2" value="<?php echo count($orderingAppliedMenus); ?>" max="<?php echo count($menus); ?>"></progress>
                                    <span class="label">Content: <?php echo count($contentAppliedMenus); ?></span>
                                    <progress class="bar" value="<?php echo count($contentAppliedMenus); ?>" max="<?php echo count($menus); ?>"></progress>
                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Css <span class="small">(total)</span></span>
                                    <span class="amount"><?php echo count($css); ?></span>
                                    <span class="label2">Linked: <?php echo $numberOfLinkedCss; ?></span>
                                    <progress class="bar2" value="<?php echo $numberOfLinkedCss; ?>" max="<?php echo count($css); ?>"></progress>
                                    <span class="label">Content: <?php echo count($contentAppliedCss); ?></span>
                                    <progress class="bar" value="<?php echo count($contentAppliedCss); ?>" max="<?php echo count($css); ?>"></progress>
                                </div>
                            </div>
                            <div class="col2-4">
                                <div class="cardContainer">
                                    <span class="header">Js <span class="small">(total)</span></span>
                                    <span class="amount"><?php echo count($js); ?></span>
                                    <span class="label2">Included: <?php echo $numberOfIncludedJs; ?></span>
                                    <progress class="bar2" value="<?php echo $numberOfIncludedJs; ?>" max="<?php echo count($menus); ?>"></progress>
                                    <span class="label">Content: <?php echo count($contentAppliedJs); ?></span>
                                    <progress class="bar" value="<?php echo count($contentAppliedJs); ?>" max="<?php echo count($js); ?>"></progress>
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
                                    <progress class="bar" value="<?php echo $numberOfAppliedMetaTitle; ?>" max="<?php echo $numberOfPages; ?>"></progress>
                                    <span class="value"><?php echo $numberOfAppliedMetaTitle; ?></span>
                                </div>
                                <div class="grouped">
                                    <span class="label seo">Meta Description</span>
                                    <progress class="bar" value="<?php echo $numberOfAppliedMetaDescription; ?>" max="<?php echo $numberOfPages; ?>"></progress>
                                    <span class="value"><?php echo $numberOfAppliedMetaDescription; ?></span>
                                </div>
                                <div class="grouped">
                                    <span class="label seo">Meta Keywords</span>
                                    <progress class="bar" value="<?php echo $numberOfAppliedMetaKeywords; ?>" max="<?php echo $numberOfPages; ?>"></progress>
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
                                    <progress class="bar" value="<?php echo $numberOfMediaFiletypeJpg; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypeJpg; ?></span>
                                </div>
                                <div class="grouped media">
                                    <span class="label">.png</span>
                                    <progress class="bar" value="<?php echo $numberOfMediaFiletypePng; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypePng; ?></span>
                                </div>
                                <div class="grouped media">
                                    <span class="label">.webp</span>
                                    <progress class="bar" value="<?php echo $numberOfMediaFiletypeWebp; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypeWebp; ?></span>
                                </div>
                                <div class="grouped media">
                                    <span class="label">.gif</span>
                                    <progress class="bar" value="<?php echo $numberOfMediaFiletypeGif; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypeGif; ?></span>
                                </div>
                                <div class="grouped media">
                                    <span class="label">.svg</span>
                                    <progress class="bar" value="<?php echo $numberOfMediaFiletypeSvg; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypeSvg; ?></span>
                                </div>
                                <div class="grouped media">
                                    <span class="label">.mp4</span>
                                    <progress class="bar" value="<?php echo $numberOfMediaFiletypeMp4; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypeMp4; ?></span>
                                </div>
                                <div class="grouped media">
                                    <span class="label">.pdf</span>
                                    <progress class="bar" value="<?php echo $numberOfMediaFiletypePdf; ?>" max="<?php echo count($media); ?>"></progress>
                                    <span class="value"><?php echo $numberOfMediaFiletypePdf; ?></span>
                                </div>
                                <div class="mediaTextContainer"> 
                                    <span class="mediaText">Free space server: <?php echo $numberOfServerFreeSpace . 'G'; ?></span>
                                    <span class="mediaText">Max upload size: <?php echo ini_get('upload_max_filesize'); ?></span>
                                    <span class="mediaText">Total uploaded: <?php echo $numberOfMediaTotalUploadedSize . "M"; ?></span>
                                </div>
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