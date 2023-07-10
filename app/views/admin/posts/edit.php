<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  
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

    <div class="containerPost">
    <div class="row">
        <div class="col10">
            <form action="update" method="POST" class="form-code">
                <div class="form-parts">
                    <input type="text" autofocus name="title" id="title" value="<?php if(!empty($data['title'] )) { echo $data['title']; } ?>">
                    <div class="error-messages">
                        <?php echo Errors::get($rules, 'title'); ?>
                    </div>    
                    <input type="text" name="postSlug" id="slug" value="<?php if(!empty($postSlug )) { echo $postSlug; } ?>">
                    <span><?php if(!empty($data['slug']) && $data['slug'] !== null) { echo $data['slug']; } ?></span>
                    <input type="hidden" name="slug" value="<?php if(!empty($data['slug']) && $data['slug'] !== null) { echo $data['slug']; } ?>">
                    <div class="error-messages">
                        <?php echo Errors::get($rules, 'slug'); ?>
                    </div>
                </div>
                <textarea name="body" type="body" id="code"><?php if(!empty($data['body'] )) { echo $data['body']; } ?></textarea>
                <button name="submit" id="submit" type="submit" class="">Update</button>
                <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
            </form>
        </div>
        <div class="col2">
            <div id="postSidebar">
                <div class="containerFirstPostButtons">
                    <a href="/admin/posts" class="button">Back</a>

                    <label for="submit" class="button">Update</label>

                </div>
                <ul class="postSidebarButtons">
                    <li>
                        <a href="/admin/posts/<?php echo $data['id']; ?>/preview" class="button">Preview</a>
                    </li>
                    <li>
                        <a href="#" id="codeEditorFullScreen" class="button">Full screen</a>
                    </li>
                    <select id="pageSelectMenu">
                        <option>    
                            test
                        </option>
                    </select>
                </ul>
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
        editor.setSize('95%', "75vh");
    
    </script>
<?php 
    $this->include('footer');
?>