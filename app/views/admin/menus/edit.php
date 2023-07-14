<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/menu.css");
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
        <div class="col10">
            <form action="update" method="POST" class="form-code">
                <div class="form-parts">
                    <input type="text" autofocus name="title" id="title" value="<?php echo $menu['title']; ?>">
                    <div class="error-messages">
                        <?php echo Errors::get($rules, 'title'); ?>
                    </div>    
                </div>
                <textarea name="content" type="content" id="code"><?php echo $menu['content']; ?></textarea>
                <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
            </form>
        </div>
        <div class="col2">
            <div id="postSidebar">
                <div class="containerFirstPostButtons">
                    <a href="/admin/menus" class="button">Back</a>
                    <label for="submit" class="button">Update</label>
                </div>
                
                <span id="menuPosition">Position: <?php echo $menu['position']; ?></span>

                <ul class="postSidebarButtons">
                    <li>
                        <a href="/admin/menus/<?php echo $post['id']; ?>/read" class="button">Preview</a>
                    </li>
                    <li>
                        <a href="#" id="codeEditorFullScreen" class="button">Full screen</a>
                    </li>
                </ul>
                <form action="update" method="POST">
                    <select name="position">
                        <option value="top">Top</option>
                        <option value="bottom">Bottom</option>
                    </select>
                    <input type="submit" name="submitPosition" value="update"/>
                </form>
                <form action="update" method="POST">
                    <input type="number" name="ordering" min="1" max="10">
                    <input type="submit" name="submitOrdering" value="update"/>
                </form>
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