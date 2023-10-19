<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/sidebar.css");
    $this->stylesheet("/assets/css/leftSidebar.css");
    $this->stylesheet("/assets/css/dashboard.css");
    
    $this->script("/assets/js/dashboard/Progress.js", true);
    $this->script("/assets/js/dashboard/main.js", true);
    $this->script("/assets/js/navbar/Navbar.js", true);
    $this->script("/assets/js/navbar/main.js", true);
    $this->script("/assets/js/dashboard/settings.js", true);
    $this->script("/assets/js/sidebar.js", true);

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
        <a href="/admin/settings" id="settings"><img src="/assets/img/settings.png"/></a>
        <?php if(Session::exists('username') === true) { ?>
        <div class="profileContainer">
            <span id="profileIcon" class="profileIcon <?php if(Session::get('user_role') === 'admin') { echo 'admin'; } else { echo 'normal';} ?>"><?php echo substr(Session::get('username'), 0, 1); ?></span>
            <ul id="profileDropdown">
            <span class="triangle"></span>
                <span class="profileIcon"><?php echo substr(Session::get('username'), 0, 1); ?></span>
                <li class="text-center username"><?php echo Session::get('username'); ?> <span>(<?php if(Session::get('user_role') !== 'admin') { echo 'non admin'; } else { echo Session::get('user_role'); } ?></span>)</li>
                <li><a href="/admin/profile/<?php echo Session::get('username'); ?>">Profile</a></li>
                <li class="/logout"><a href="/logout">Sign out</a></li>
            </ul>
        </div>
    <?php } ?>
    </nav>
    <div id="progressInfoItem"></div>
    <div class="row">
        <div class=" col2 col3-L">

            <?php $this->include('leftSidebar'); ?>

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
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col8">
                        <div class="cardContainerSecondRow float-right">
                            <div class="container">
                                <span class="header">Media <span class="small">(total)</span></span>
                                <span class="amount"><?php echo count($media); ?></span>
                                <div class="stopMousemoveEvent"></div>
                                <div class="grouped media">
                                    <div class="layer"></div>
                                    <span class="label">.jpeg</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypeJpg; ?>" max="<?php echo count($media); ?>"></progress>
                                </div>
                                <div class="grouped media">
                                    <div class="layer"></div>
                                    <span class="label">.png</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypePng; ?>" max="<?php echo count($media); ?>"></progress>
                                </div>
                                <div class="grouped media">
                                    <div class="layer"></div>
                                    <span class="label">.webp</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypeWebp; ?>" max="<?php echo count($media); ?>"></progress>
                                </div>
                                <div class="grouped media">
                                    <div class="layer"></div>
                                    <span class="label">.gif</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypeGif; ?>" max="<?php echo count($media); ?>"></progress>
                                </div>
                                <div class="grouped media">
                                    <div class="layer"></div>
                                    <span class="label">.svg</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypeSvg; ?>" max="<?php echo count($media); ?>"></progress>
                                </div>
                                <div class="grouped media">
                                    <div class="layer"></div>
                                    <span class="label">.mp4</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypeMp4; ?>" max="<?php echo count($media); ?>"></progress>
                                </div>
                                <div class="grouped media">
                                    <div class="layer"></div>
                                    <span class="label">.pdf</span>
                                    <progress class="bar blue" value="<?php echo $numberOfMediaFiletypePdf; ?>" max="<?php echo count($media); ?>"></progress>
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