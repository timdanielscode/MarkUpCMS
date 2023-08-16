<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
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
    
 
        <div class="row">
            <div class="col10 col9-L">
                <div class="edit-container">
                    <form action="store" method="POST" class="form-code" id="editorForm">
                        <div class="form-parts">
                            <input type="text" name="title" type="title" id="title" value="<?php echo post('title'); ?>" placeholder="Title" autofocus>
                            <?php if(!empty(Errors::get($rules, 'title')) && Errors::get($rules, 'title') !== null) { ?>
                                <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                                    <span><?php echo Errors::get($rules, 'title'); ?></span>
                                </div>    
                            <?php } ?>
                        </div>
                        <textarea type="text" name="body" type="body" id="code"></textarea>
                        <div class="form-parts">
                            <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col2 col3-L">
                <div id="sidebar" class="width-25-L">
                    <div class="sidebarContainer">
                        <div class="mainButtonContainer">
                            <label for="submit" class="button greenButton margin-r-10">Create</label>
                            <a href="/admin/posts" class="button blueButton">Back</a>
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