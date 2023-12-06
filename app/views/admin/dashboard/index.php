<!--
    - to show an overview to optimize and get information about pages, menus, widgets, css, js, users and media 
    - pages: how many do have a meta title, description, keywords, content and are thrashed
    - menus: how many are ordered, have a position, have content and are trashed
    - widgets: how many do have content and are thrashed
    - css: how many are linked, have content and are thrashed
    - js: how many are included, have content and are thrashed
    - users: how many admins and normal users
    - media: how many jpeg, png, webp, gif, svg, mp4, pdf
-->

<?php $this->include('openHeadTag'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?> 
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
    <?php $this->stylesheet("/assets/css/leftSidebar.css"); ?>
    <?php $this->stylesheet("/assets/css/dashboard.css"); ?>
    <?php $this->script("/assets/js/dashboard/Progress.js", true); ?>
    <?php $this->script("/assets/js/dashboard/main.js", true); ?>
    <?php $this->script("/assets/js/navbar/Navbar.js", true); ?>
    <?php $this->script("/assets/js/navbar/main.js", true); ?>
    <?php $this->script("/assets/js/sidebar.js", true); ?>
<?php $this->include("closeHeadTagAndOpenBodyTag"); ?>

<?php $this->include("dashboardNavbar"); ?>

<div id="progressInfoItem"></div>
<div class="row">
    <div class="col2 col2-L col3-L col4-S">
        <?php $this->include('leftSidebar'); ?>
    </div>
    <div class="col10 col10-L- col9-L col8-S">
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

<?php $this->include('footer'); ?>