<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/page.css");
    $this->stylesheet("/assets/css/sidebar.css");

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

<div class="edit-container">
    <div class="row">
        <div class="col10 col9-L">
            <form action="update" method="POST">
                <div class="form-parts">
                    <input type="text" autofocus name="title" id="title" value="<?php if(!empty($data['title'] )) { echo $data['title']; } ?>">
                    <div class="error-messages">
                        <?php echo Errors::get($rules, 'title'); ?>
                    </div>    
                </div>
                <textarea name="body" type="body" id="code"><?php if(!empty($data['body'] )) { echo $data['body']; } ?></textarea>
                <button name="submit" id="submit" type="submit" class="hiddenButton">Update</button>
                <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
            </form>
        </div>
        <div class="col2 col3-L">
            <div id="sidebar" class="width-25-L">
                <div class="sidebarContainer">
                    <div class="mainButtonContainer">
                        <label for="submit" class="button">Update</label>
                        <a href="/admin/posts" class="button">Back</a>
                    </div>
                    <div class="readAndFullScreenContainer">
                        <a target="_blank" href="/admin/posts/<?php echo $data['id']; ?>/read" class="button">Read</a>
                        <a href="#" id="codeEditorFullScreen" class="button">Full screen</a>
                    </div>
                    <span class="text">Slug:</span>
                    <span class="fullSlug"><?php if(!empty($data['slug']) && $data['slug'] !== null) { echo $data['slug']; } ?></span>
                    <form class="updateSlugForm" action="update" method="POST">
                        <div class="form-parts">
                            <input type="text" name="postSlug" id="slug" value="<?php if(!empty($postSlug )) { echo $postSlug; } ?>">
                        </div>
                        <input type="hidden" name="slug" value="<?php if(!empty($data['slug']) && $data['slug'] !== null) { echo $data['slug']; } ?>">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'slug'); ?>
                        </div>
                        <input class="updateSlugButton" type="submit" name="updateSlug" value="Update"/>
                    </form>
                    
                    <span class="text">Category: </span>
                        <?php if(!empty($categories) && $categories !== null) { ?>
                            <form class="AddCategory" action="update" method="POST">
                                <select name="categories" multiple>
                                    <?php foreach($categories as $category) { ?>
                                        <option value="<?php echo $category['id']; ?>">    
                                            <?php echo $category['title']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <div class="AssingCategoryContainer">
                                    <input class="assignCategoryButton" type="submit" name="submitCategory" value="Assign"/>
                                </div>
                                
                            </form>
                        <?php } else { ?>

                            <span class="categoryTitle"><?php echo $category['title']; ?></span>

                            <form action="update" method="POST">

                                <input class="detachCategoryButton" type="submit" name="removeCategory" value="Detach"/>
                            </form>

                        <?php } ?>
          
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