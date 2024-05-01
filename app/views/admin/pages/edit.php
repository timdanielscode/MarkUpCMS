<!-- 
    - FOR TYPE OF ADMIN
    -
    - to change the slug value to submit and update
    - to change the contents value to submit and update
    - to have an overview of exluded metas to create a selection to submit selection to include metas
    - to have an overview of included metas and to create a selection to submit selection to exclude metas
    - to have an overview of applicable widgets and to create a selection to submit selection to make widgets inapplicable on page
    - to have an overview of inapplicable widgets and to create a selection to submit selection to make widgets applicable on page
    - to have an overview of not assinged categories to select a category to submit to assign to page
    - to show assinged category and to submit to detach from page
    - to add a meta title and to change meta title if already added to submit and update
    - to add a meta description and to change meta description if already added to submit and update
    - to add meta keywords and to change meta keywords if already added to submit and update
    - to have an overview of linked css files and to create a selection to submit selection to unlink on page
    - to have an overview of unlinked css files on page and to create a selection of css files to submit selection to link on page
    - to have an overview of selection of not included js files and to create a selection to submit selection to include on page
    - to have an overview of selection of included js files on page and to create a selection to submit selection to exclude on page
--> 

<?php $this->include('openHeadTag'); ?>  
    <?php $this->title('Pages edit page'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/page.css"); ?>
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
    <?php $this->script("/assets/js/navbar/Navbar.js", true); ?>
    <?php $this->script("/assets/js/navbar/main.js", true); ?>
    <?php $this->script("/assets/js/sidebar/Editor.js", true); ?>
    <?php $this->script("/assets/js/sidebar/Section.js", true); ?>
    <?php $this->script("/assets/js/sidebar/Button.js", true); ?>
    <?php $this->script("/assets/js/sidebar/main.js", true); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css" integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js" integrity="sha512-8RnEqURPUc5aqFEN04aQEiPlSAdE0jlFS/9iGgUyNtwFnSKCXhmB6ZTNl7LnDtDWKabJIASzXrzD0K+LYexU9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/xml/xml.min.js" integrity="sha512-LarNmzVokUmcA7aUDtqZ6oTS+YXmUKzpGdm8DxC46A6AHu+PQiYCUlwEGWidjVYMo/QXZMFMIadZtrkfApYp/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/monokai.min.css" integrity="sha512-R6PH4vSzF2Yxjdvb2p2FA06yWul+U0PDDav4b/od/oXf9Iw37zl10plvwOXelrjV2Ai7Eo3vyHeyFUjhXdBCVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php $this->include("closeHeadTagAndOpenBodyTag"); ?>

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col10-L- col9-L col8-S">
        <div class="edit-container">
            <?php core\Alert::message('success'); ?>
            <form id="editorForm" action="/admin/pages/<?php echo $data['id']; ?>/update" method="POST">
                <div class="form-parts">
                    <input type="text" autofocus name="title" id="title" value="<?php echo $data['title']; ?>">
                    <?php if(!empty(validation\Errors::get($rules, 'title')) && validation\Errors::get($rules, 'title') !== null) { ?>
                        <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                            <span><?php echo validation\Errors::get($rules, 'title'); ?></span>
                        </div>    
                    <?php } ?>
                </div>
                <textarea name="body" id="code"><?php echo $data['body']; ?></textarea>
                <button name="submit" id="submit" type="submit" class="hiddenButton" value="submit">Update</button>
            </form>
        </div>
    </div>
    <div class="col2 col2-L col3-L col4-S">
        <div id="sidebar" class="right" class="width-25">
            <div class="sidebarContainer">
                <div class="mainButtonContainer">
                    <label for="submit" class="button greenButton margin-r-10">Update</label>
                    <a href="/admin/pages/<?php echo $data['id']; ?>/read" target="_blank" class="button darkBlueButton margin-r-10">Read</a>
                    <a href="/admin/pages" class="button blueButton">Back</a>
                </div>
                <div class="buttonContainer">
                    <a href="#" id="codeEditorFullScreen" class="button lightButton margin-r-10">Full screen</a>
                    <a href="#" id="codeEditorZoomIn" class="button lightButton margin-r-10">+</a>
                    <a href="#" id="codeEditorZoomOut" class="button lightButton">-</a>
                </div>
                <div class="buttonContainer">
                    <a id="slugButton" class="button margin-r-10 <?php if(core\Session::exists('slug') === true ) { echo 'lightButton'; } else { echo 'darkButton'; } ?>">Page slug</a>
                    <a id="categoryButton" class="button <?php if(core\Session::exists('category') === true ) { echo 'lightButton'; } else { echo 'darkButton'; } ?>">Categories</a>
                </div>
                <div class="buttonContainer">
                    <a id="cdnButton" class="button margin-r-10 <?php if(core\Session::exists('cdn') === true ) { echo 'lightButton'; } else { echo 'darkButton'; } ?>">Metas</a>
                    <a id="jsButton" class="button margin-r-10 <?php if(core\Session::exists('js') === true ) { echo 'lightButton'; } else { echo 'darkButton'; } ?>">Scripts</a>
                    <a id="cssButton" class="button margin-r-10 <?php if(core\Session::exists('css') === true ) { echo 'lightButton'; } else { echo 'darkButton'; } ?>">Css</a>
                </div>
                <div class="buttonContainer">
                    <a id="widgetButton" class="button margin-r-10 <?php if(core\Session::exists('widget') === true ) { echo 'lightButton'; } else { echo 'darkButton'; } ?>">Widgets</a>
                    <a id="metaButton" class="button <?php if(core\Session::exists('meta') === true ) { echo 'lightButton'; } else { echo 'darkButton'; } ?>">Seo</a>
                </div>
                <div id="slug" class="<?php if(core\Session::exists('slug') === false ) { echo 'display-none'; } ?>">
                    <span class="text">Slug:</span>
                    <span class="fullSlug"><?php echo $data['slug']; ?></span>
                    <form class="updateSlugForm" action="/admin/pages/<?php echo $data['id']; ?>/update-slug" method="POST">
                        <div class="form-parts">
                            <input type="text" name="pageSlug" id="slug" value="<?php echo substr($data['pageSlug'], 1); ?>">
                            <?php if(!empty(validation\Errors::get($rules, 'pageSlug')) && validation\Errors::get($rules, 'pageSlug') !== null) { ?>
                                <div class="error-messages">
                                    <?php echo validation\Errors::get($rules, 'pageSlug'); ?>
                                </div>   
                            <?php } ?>
                        </div>
                        <input type="hidden" name="slug" value="<?php echo $data['slug']; ?>">
                        <input class="button darkBlueButton margin-t-10" type="submit" name="submit" value="Update"/>
                    </form>
                </div>
                <div id="cdn" class="<?php if(core\Session::exists('cdn') === false ) { echo 'display-none'; } ?>">
                    <form action="/admin/pages/<?php echo $data['id']; ?>/export-cdns" method="POST" class="cdnForm margin-t-50">
                        <label>Imported: </label>
                        <select name="cdns[]" multiple>
                            <?php foreach($data['exportCdns'] as $cdn) { ?>
                            <option value="<?php echo $cdn['id']; ?>">
                                <?php echo $cdn['title']; ?>
                            </option>
                            <?php } ?>
                            <input type="submit" name="submit" value="Export" class="button darkBlueButton margin-t-20"/>
                        </select>
                    </form>
                    <form action="/admin/pages/<?php echo $data['id']; ?>/import-cdns" method="POST" class="cdnForm margin-t-20">
                        <label>Metas: </label>
                        <select name="cdns[]" multiple>
                            <?php foreach($data['importCdns'] as $cdn) { ?>
                            <option value="<?php echo $cdn['id']; ?>">
                                <?php echo $cdn['title']; ?>
                            </option>
                            <?php } ?>
                            <input type="submit" name="submit" value="Import" class="button darkBlueButton margin-t-20"/>
                        </select>
                    </form>
                </div>
                <div id="widget" class="<?php if(core\Session::exists('widget') === false ) { echo 'display-none'; } ?>">
                    <div id="applyWidget"></div>
                    <form class="widgetForm" action="/admin/pages/<?php echo $data['id']; ?>/remove-widget" method="POST">
                        <label>Applicable: </label>
                        <select id="applicableWidgetSelect" name="widgets[]" multiple>
                            <?php foreach($data['applicableWidgets'] as $widget) { ?>
                                <option value="<?php echo $widget['id']; ?>">    
                                    <?php echo $widget['title']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input class="button darkBlueButton margin-t-20" type="submit" name="submit" value="Make it inapplicable"/>
                    </form>
                    <form class="widgetForm margin-t-20" action="/admin/pages/<?php echo $data['id']; ?>/add-widget" method="POST">
                        <label>Widgets: </label>
                        <select name="widgets[]" multiple>
                            <?php foreach($data['inapplicableWidgets'] as $widget) { ?>
                                <option value="<?php echo $widget['id']; ?>">    
                                    <?php echo $widget['title']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input class="button darkBlueButton margin-t-20" type="submit" name="submit" value="Make it applicable"/>
                    </form>
                </div>
                <div id="category" class="<?php if(core\Session::exists('category') === false ) { echo 'display-none'; } ?>">
                        <?php if(!empty($data['categories']) && $data['categories'] !== null) { ?>
                            <span class="text">Categories: </span>
                            <form class="AddCategory" action="/admin/pages/<?php echo $data['id']; ?>/assign-category" method="POST">
                                <select name="categories" multiple>
                                    <?php foreach($data['categories'] as $category) { ?>
                                        <option value="<?php echo $category['id']; ?>">    
                                            <?php echo $category['title']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <div class="error-messages">
                                    <?php echo validation\Errors::get($rules, 'categories'); ?>
                                </div>  
                                <div class="AssingCategoryContainer">
                                    <input class="button darkBlueButton margin-t-20" type="submit" name="submit" value="Assign"/>
                                </div> 
                            </form>
                        <?php } else { ?>
                            <span class="text">Category: </span>
                            <span class="categoryTitle"><?php echo $data['category']['title']; ?></span>
                            <form action="/admin/pages/<?php echo $data['id']; ?>/detach-category" method="POST">
                                <input type="hidden" name="slug" value="<?php echo $data['slug']; ?>"/>
                                <div class="error-messages">
                                    <?php echo validation\Errors::get($rules, 'submit'); ?>
                                </div>   
                                <input class="button darkBlueButton margin-t-10" type="submit" name="submit" value="Detach" onclick="return confirm('Are you sure?');"/>
                            </form>
                    <?php } ?>
                </div>
                <form id="metaForm" class="updateMetaDataForm <?php if(core\Session::exists('meta') === false ) { echo 'display-none'; } ?>"action="/admin/pages/<?php echo $data['id']; ?>/update-metadata" method="POST">
                    <div class="form-parts">
                        <label for="metaTitle">Meta title: </label>
                        <input id="metaTitle" type="text" name="metaTitle" value="<?php echo $data['metaTitle']; ?>" placeholder="Title"/>
                        <?php if(!empty(validation\Errors::get($rules, 'metaTitle')) && validation\Errors::get($rules, 'metaTitle') !== null) { ?>
                            <div class="error-messages font-size-12 margin-b-5">
                                <?php echo validation\Errors::get($rules, 'metaTitle'); ?>
                            </div>   
                        <?php } ?>
                    </div>
                    <div class="form-parts">
                        <label for="metaDescription">Meta description: </label>
                        <textarea id="metaDescription" name="metaDescription" placeholder="Description"><?php echo $data['metaDescription']; ?></textarea>
                        <?php if(!empty(validation\Errors::get($rules, 'metaDescription')) && validation\Errors::get($rules, 'metaDescription') !== null) { ?>
                            <div class="error-messages font-size-12 margin-b-5">
                                <?php echo validation\Errors::get($rules, 'metaDescription'); ?>
                            </div>   
                        <?php } ?> 
                    </div>
                    <div class="form-parts">
                        <label for="metaKeywords">Meta keywords: </label>
                        <textarea id="metaKeywords" name="metaKeywords" placeholder="Keywords separated with a comma"><?php echo $data['metaKeywords']; ?></textarea>
                        <?php if(!empty(validation\Errors::get($rules, 'metaKeywords')) && validation\Errors::get($rules, 'metaKeywords') !== null) { ?>
                            <div class="error-messages font-size-12 margin-b-5">
                                <?php echo validation\Errors::get($rules, 'metaKeywords'); ?>
                            </div>   
                        <?php } ?>  
                    </div>
                    <input type="submit" name="submit" class="button darkBlueButton margin-t-10" value="Update"/>
                </form>
                <form id="linkedCssFiles" class="linkedCssFilesForm <?php if(core\Session::exists('css') === false ) { echo 'display-none'; } ?>" action="/admin/pages/<?php echo $data['id']; ?>/unlink-css" method="POST">
                    <label for="linkedFiles">Linked:</label>
                    <select id="linkedFiles" name="linkedCssFiles[]" multiple>
                        <?php foreach($data['linkedCssFiles'] as $linkedCssFile) { ?>
                            <option value="<?php echo $linkedCssFile['id']; ?>">
                                <?php echo $linkedCssFile['file_name'] . $linkedCssFile['extension']; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <input type="submit" name="submit" class="button darkBlueButton margin-y-20" value="Unlink"/>
                </form>
                <form id="cssFiles" class="cssFilesForm <?php if(core\Session::exists('css') === false ) { echo 'display-none'; } ?>" action="/admin/pages/<?php echo $data['id']; ?>/link-css" method="POST">
                    <label for="cssFilesSelect">Css files:</label>
                    <select id="cssFilesSelect" name="cssFiles[]" multiple>
                        <?php foreach($data['notLinkedCssFiles'] as $notLinkedCssFile) { ?>
                            <option value="<?php echo $notLinkedCssFile['id']; ?>">
                                <?php echo $notLinkedCssFile['file_name'] . $notLinkedCssFile['extension']; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <input type="submit" class="button darkBlueButton margin-t-20" name="submit" value="Link"/>
                </form>
                <form id="linkedJsFiles" class="linkedJsFilesForm margin-t-50 <?php if(core\Session::exists('js') === false ) { echo 'display-none'; } ?>" action="/admin/pages/<?php echo $data['id'] ?>/remove-js" method="POST">
                    <label for="linkedJsFiles">Included:</label>
                    <select id="linkedJsFiles" name="linkedJsFiles[]" multiple>
                        <?php foreach($data['linkedJsFiles'] as $linkedJsFile) { ?>
                            <option value="<?php echo $linkedJsFile['id']; ?>">
                                <?php echo $linkedJsFile['file_name'] . $linkedJsFile['extension']; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <input type="submit" name="submit" class="button darkBlueButton margin-t-20" value="Exclude"/>
                </form>
                <form id="jsFiles" class="jsFilesForm margin-t-20 <?php if(core\Session::exists('js') === false ) { echo 'display-none'; } ?>" action="/admin/pages/<?php echo $data['id']; ?>/include-js" method="POST">
                    <label for="jsFilesSelect">Js files:</label>
                        <select id="jsFilesSelect" name="jsFiles[]" multiple>
                            <?php foreach($data['notLinkedJsFiles'] as $notLinkedJsFile) { ?>
                                <option value="<?php echo $notLinkedJsFile['id']; ?>">
                                    <?php echo $notLinkedJsFile['file_name'] . $notLinkedJsFile['extension']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    <input type="submit" name="submit" class="button darkBlueButton margin-t-20" value="Include"/>
                </form> 
            </div>
        </div>
    </div>
</div>

<!-- to use CodeMirror text editor to have a better ux -->
<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        theme: "monokai",
        lineNumbers: true,
        mode: 'text/html',
        tabSize: 2
    });
</script>

<?php $this->include('footer'); ?>