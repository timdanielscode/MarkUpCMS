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
                <form action="" method="POST" class="form-code">
                    <div class="form-parts">
                        <input type="text" autofocus name="title" type="title" id="title" placeholder="Title" autofocus>
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'title'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <textarea type="text" name="body" type="body" id="code"></textarea>
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'body'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                        <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                    </div>
                </form>
            </div>
            <div class="col2">
                <div id="postSidebar">
                    <div class="containerFirstPostButtons">
                        <a href="/admin/posts" class="button">Back</a>
                        <label for="submit" class="button">Create</label>
                    </div>
                    <ul class="postSidebarButtons">
                        <li>
                            <a href="#" id="codeEditorFullScreen" class="button">Full screen</a>
                        </li>
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