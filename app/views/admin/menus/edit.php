<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
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
        <div class="col10 col9-L">
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
        <div class="col2 col3-L">
            <div id="sidebar" class="width-25-L">
                <div class="sidebarContainer">
                    <div class="mainButtonContainer">
                        <label for="submit" class="button">Update</label>
                        <a href="/admin/menus" class="button">Back</a>
                    </div>
                    <div class="buttonContainer">
                        <a href="/admin/menus/<?php echo $menu['id']; ?>/read" class="button">Read</a>
                        <a href="#" id="codeEditorFullScreen" class="button">Full screen</a>
                    </div>
                    <span class="text">Current position: </span>
                    <span class="data"><?php echo $menu['position']; ?></span>
                    <span class="text">Ordering: </span>
                    <span class="data"><?php if(!empty($menu['ordering']) && $menu['ordering'] !== null) { echo $menu['ordering']; } else { echo 'Unset'; } ?></span>
                    <form action="/admin/menus/<?php echo $menu['id']; ?>/update-position" method="POST" class="updatePositionForm">
                        <label>Position:</label>
                        <select name="position" multiple>
                            <option value="top">Top</option>
                            <option value="bottom">Bottom</option>
                        </select>
                        <input type="submit" name="submitPosition" value="update"/>
                    </form>
                    <form action="/admin/menus/<?php echo $menu['id']; ?>/update-ordering" method="POST" class="updateNumberForm">
                        <input dir="rtl" type="number" name="ordering" min="1" max="99" value="1">
                        <input type="submit" name="submitOrdering" value="update"/>
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