<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/page.css");
    $this->stylesheet("/assets/css/sidebar.css");

    $this->script("/assets/js/pages.js", true);

    $this->stylesheet("/assets/css/codemirror/codemirror.css");
    $this->script("/assets/js/codemirror/codemirror.js");
    $this->script("/assets/js/codemirror/closetag.js");
    $this->script("/assets/js/codemirror/xml.js");
    $this->stylesheet("/assets/css/codemirror/monokai.css"); //ayu-mirage, lesser-dark, railscasts, seti
    $this->script("/assets/js/codemirror/htmlmixed.js");
    $this->script('/assets/js/ajax.js');
    $this->script('/assets/js/fullscreen.js');
    $this->title("IndependentCMS");
    $this->include("headerClose");
    $this->include('navbar');
    
?>
    <div class="row">
        <div class="col10 col9-L">
            <div class="edit-container">
                <form id="editorForm" action="/admin/posts/<?php echo $data['id']; ?>/update" method="POST">
                    <div class="form-parts">
                        <input type="text" autofocus name="title" id="title" value="<?php if(!empty($data['title'] )) { echo $data['title']; } ?>">
                        <?php if(!empty(Errors::get($rules, 'title')) && Errors::get($rules, 'title') !== null) { ?>
                            <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                                <span><?php echo Errors::get($rules, 'title'); ?></span>
                            </div>    
                        <?php } ?>
                    </div>
                    <textarea name="body" id="code"><?php if(!empty($data['body'] )) { echo $data['body']; } ?></textarea>
                    <button name="submit" id="submit" type="submit" class="hiddenButton" value="submit">Update</button>
                    <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                </form>
            </div>
        </div>
        <div class="col2 col3-L">
            <div id="sidebar" class="width-25-L">
                <div class="sidebarContainer">
                    <div class="mainButtonContainer">
                        <label for="submit" class="button greenButton margin-r-10">Update</label>
                        <a href="/admin/posts/<?php if(!empty($data['id']) ) { echo $data['id']; } ?>/read" target="_blank" class="button blueButton margin-r-10">Read</a>
                        <a href="/admin/posts" class="button darkBlueButton">Back</a>
                    </div>
                    <div class="buttonContainer">
                        <a href="#" id="codeEditorFullScreen" class="button darkButton margin-r-10">Full screen</a>
                    </div>
                    <div class="buttonContainer">
                        <a id="metaButton" class="button lightButton margin-r-10">Meta data</a>
                        <a id="categoryButton" class="button lightButton">Categories</a>
                    </div>
                    <div class="buttonContainer">
                        <a id="cssButton" class="button lightButton margin-r-10">Css</a>
                        <a id="jsButton" class="button lightButton margin-r-10">Js</a>
                        <a id="widgetButton" class="button lightButton">Widgets</a>
                    </div>
                    <span class="text">Slug:</span>
                    <span class="fullSlug"><?php if(!empty($data['slug']) ) { echo $data['slug']; } ?></span>
                    <form class="updateSlugForm" action="/admin/posts/<?php echo $data['id']; ?>/update-slug" method="POST">
                        <div class="form-parts">
                            <input type="text" name="postSlug" id="slug" value="<?php if(!empty($data['postSlug'] )) { echo substr($data['postSlug'], 1); } ?>">
                            <?php if(!empty(Errors::get($rules, 'postSlug')) && Errors::get($rules, 'postSlug') !== null) { ?>
                                <div class="error-messages">
                                    <?php echo Errors::get($rules, 'postSlug'); ?>
                                </div>   
                            <?php } ?>
                        </div>
                        <input type="hidden" name="slug" value="<?php if(!empty($data['slug']) ) { echo $data['slug']; } ?>">
                        <input class="button greenButton margin-t-10" type="submit" name="submit" value="Update"/>
                        <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                    </form>
                    <div id="widget" class="<?php if(Session::exists('updateWidget') === false) { echo 'display-none'; } ?>">
                        <form class="widgetForm" action="/admin/posts/<?php echo $data['id']; ?>/remove-widget" method="POST">
                            <label>Applicable widgets: </label>
                            <select name="widgets[]" multiple>
                                <?php foreach($data['widgets'] as $widget) { ?>
                                    <option value="<?php echo $widget['id']; ?>">    
                                        <?php echo $widget['title']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <input class="button blueButton margin-t-20" type="submit" name="submit" value="Make it inapplicable"/>
                            <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                        </form>
                        <form class="widgetForm margin-t-20" action="/admin/posts/<?php echo $data['id']; ?>/add-widget" method="POST">
                            <label>Other widgets: </label>
                            <select name="widgets[]" multiple>
                                <?php foreach($data['widgets'] as $widget) { ?>
                                    <option value="<?php echo $widget['id']; ?>">    
                                        <?php echo $widget['title']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <input class="button greenButton margin-t-20" type="submit" name="submit" value="Make it applicable"/>
                            <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                        </form>
                    </div>
                    <div id="category" class="<?php if(Session::exists('updateCategory') === false) { echo 'display-none'; } ?>">
                        <span class="text">Category: </span>
                            <?php if(!empty($data['categories']) && $data['categories'] !== null) { ?>
                                <form class="AddCategory" action="/admin/posts/<?php echo $data['id']; ?>/assign-category" method="POST">
                                    <select name="categories" multiple>
                                        <?php foreach($data['categories'] as $category) { ?>
                                            <option value="<?php echo $category['id']; ?>">    
                                                <?php echo $category['title']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <div class="error-messages">
                                        <?php echo Errors::get($rules, 'categories'); ?>
                                    </div>  
                                    <div class="AssingCategoryContainer">
                                        <input class="button greenButton margin-t-20" type="submit" name="submit" value="Assign"/>
                                    </div> 
                                    <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                                </form>
                            <?php } else { ?>
                                <span class="categoryTitle"><?php echo $data['category']['title']; ?></span>
                                <form action="/admin/posts/<?php echo $data['id']; ?>/detach-category" method="POST">
                                    <div class="error-messages">
                                        <?php echo Errors::get($rules, 'submit'); ?>
                                    </div>   
                                    <input class="button redButton margin-t-10" type="submit" name="submit" value="Detach"/>
                                    <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                                </form>
                        <?php } ?>
                    </div>
                    <form id="metaForm" class="updateMetaDataForm <?php if(Session::exists('updateMeta') === false) { echo 'display-none'; } ?>" action="/admin/posts/<?php echo $data['id']; ?>/update-metadata" method="POST">
                        <div class="form-parts">
                            <label for="metaTitle">Meta title: </label>
                            <input id="metaTitle" type="text" name="metaTitle" value="<?php if(!empty($data['metaTitle']) && $data['metaTitle'] !== null) { echo $data['metaTitle']; } ?>" placeholder="Title"/>
                            <?php if(!empty(Errors::get($rules, 'metaTitle')) && Errors::get($rules, 'metaTitle') !== null) { ?>
                                <div class="error-messages font-size-12 margin-b-5">
                                    <?php echo Errors::get($rules, 'metaTitle'); ?>
                                </div>   
                            <?php } ?>
                        </div>
                        <div class="form-parts">
                            <label for="metaDescription">Meta description: </label>
                            <textarea id="metaDescription" name="metaDescription" placeholder="Description"><?php if(!empty($data['metaDescription']) && $data['metaDescription'] !== null) { echo $data['metaDescription']; } ?></textarea>
                            <?php if(!empty(Errors::get($rules, 'metaDescription')) && Errors::get($rules, 'metaDescription') !== null) { ?>
                                <div class="error-messages font-size-12 margin-b-5">
                                    <?php echo Errors::get($rules, 'metaDescription'); ?>
                                </div>   
                            <?php } ?> 
                        </div>
                        <div class="form-parts">
                            <label for="metaKeywords">Meta keywords: </label>
                            <textarea id="metaKeywords" name="metaKeywords" placeholder="Keywords separated with a comma"><?php if(!empty($data['metaKeywords']) && $data['metaKeywords'] !== null) { echo $data['metaKeywords']; } ?></textarea>
                            <?php if(!empty(Errors::get($rules, 'metaKeywords')) && Errors::get($rules, 'metaKeywords') !== null) { ?>
                                <div class="error-messages font-size-12 margin-b-5">
                                    <?php echo Errors::get($rules, 'metaKeywords'); ?>
                                </div>   
                            <?php } ?>  
                        </div>
                        <input type="submit" name="submit" class="button greenButton margin-t-10" value="Update"/>
                        <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                    </form>
                    <form id="linkedCssFiles" class="linkedCssFilesForm <?php if(Session::exists('updateCss') === false) { echo 'display-none'; } ?>" action="/admin/posts/<?php echo $data['id']; ?>/unlink-css" method="POST">
                        <label for="linkedFiles">Linked css files:</label>
                        <select id="linkedFiles" name="linkedCssFiles[]" multiple>
                            <?php foreach($data['linkedCssFiles'] as $linkedCssFile) { ?>
                                <option value="<?php echo $linkedCssFile['id']; ?>">
                                    <?php echo $linkedCssFile['file_name'] . $linkedCssFile['extension']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input type="submit" name="submit" class="button blueButton margin-y-20" value="Unlink"/>
                        <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                    </form>
                    <form id="cssFiles" class="cssFilesForm <?php if(Session::exists('updateCss') === false) { echo 'display-none'; } ?>" action="/admin/posts/<?php echo $data['id']; ?>/link-css" method="POST">
                        <label for="cssFilesSelect">Other css files:</label>
                        <select id="cssFilesSelect" name="cssFiles[]" multiple>
                            <?php foreach($data['notLinkedCssFiles'] as $notLinkedCssFile) { ?>
                                <option value="<?php echo $notLinkedCssFile['id']; ?>">
                                    <?php echo $notLinkedCssFile['file_name'] . $notLinkedCssFile['extension']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                        <input type="submit" class="button greenButton margin-t-20" name="submit" value="Link"/>
                    </form>
                    <form id="linkedJsFiles" class="linkedJsFilesForm margin-t-50 <?php if(Session::exists('updateJs') === false) { echo 'display-none'; } ?>" action="/admin/posts/<?php echo $data['id'] ?>/remove-js" method="POST">
                        <label for="linkedJsFiles">Linked js files:</label>
                        <select id="linkedJsFiles" name="linkedJsFiles[]" multiple>
                            <?php foreach($data['linkedJsFiles'] as $linkedJsFile) { ?>
                                <option value="<?php echo $linkedJsFile['id']; ?>">
                                    <?php echo $linkedJsFile['file_name'] . $linkedJsFile['extension']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                        <input type="submit" name="submit" class="button blueButton margin-t-20" value="Exclude"/>
                    </form>
                    <form id="jsFiles" class="jsFilesForm margin-t-20 <?php if(Session::exists('updateJs') === false) { echo 'display-none'; } ?>" action="/admin/posts/<?php echo $data['id']; ?>/include-js" method="POST">
                        <label for="jsFilesSelect">Other js files:</label>
                            <select id="jsFilesSelect" name="jsFiles[]" multiple>
                                <?php foreach($data['notLinkedJsFiles'] as $notLinkedJsFile) { ?>
                                    <option value="<?php echo $notLinkedJsFile['id']; ?>">
                                        <?php echo $notLinkedJsFile['file_name'] . $notLinkedJsFile['extension']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                        <input type="submit" name="submit" class="button greenButton margin-t-20" value="Include"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        theme: "monokai",
        lineNumbers: true,
        mode: 'text/html',
        autoCloseTags: true,
        tabSize: 2
    });
    editor.setSize('95%', "80vh");
</script>
<?php 
    $this->include('footer');
?>