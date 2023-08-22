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

    
    <div class="row">
        <div class="col10 col9-L">
            <div class="edit-container">
                <form id="editorForm" action="/admin/forms/<?php echo $form['id']; ?>/update" method="POST" class="form-code">
                    <div class="form-parts">
                        <input type="text" autofocus name="title" id="title" value="<?php echo $form['title']; ?>">
                    </div>
                    <textarea name="content" type="content" id="code"><?php echo $form['content']; ?></textarea>
                    <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                    <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                </form>
            </div>
        </div>
        <div class="col2 col3-L">
            <div id="sidebar" class="width-25-L">
                <div class="sidebarContainer">
                    <div class="mainButtonContainer">
                        <label for="submit" class="button greenButton margin-r-10">Update</label>
                        <a href="/admin/forms/<?php echo $menu['id']; ?>/read" class="button blueButton margin-r-10">Read</a>
                        <a href="/admin/forms" class="button darkBlueButton">Back</a>
                    </div>
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