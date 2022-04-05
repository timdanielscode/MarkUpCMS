<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>

<?php 
    $this->include('headerOpen');  
    $this->stylesheet("/assets/css/codemirror/codemirror.css");
    $this->stylesheet("/assets/css/codemirror/gruvbox-dark.css");
    $this->script("/assets/js/codemirror/codemirror.js");
    $this->script("/assets/js/codemirror/css.js");
    $this->script("/assets/js/codemirror/closebrackets.js");
    $this->title("IndependentCMS");
    $this->include("headerClose");
    $this->include('navbar');
?>
    
<?php if(Session::exists("csrf")) { ?>
        <div class="my-5 w-75 mx-auto"><?php echo Alert::display("warning", "csrf"); ?></div>
    <?php Session::delete('csrf'); } ?>

    <div class="containerPost">
        <div class="row">
            <div class="col10">
                <form action="" method="POST" class="form-code">
                    <div class="form-parts">
                        <input name="title" type="title" id="title" placeholder="Title" autofocus>
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'title'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <textarea name="body" type="body" id="code"></textarea>
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
                    <a href="/admin/posts" class="button back">Back</a>
                    <label for="submit" class="button create">Create</label>
                </div>
            </div>
        </div>
    </div>
    <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            theme: "gruvbox-dark",
            lineNumbers: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            tabSize: 2
        });
        editor.setSize('95%', "75vh");
    
    </script>
    

<?php 
    $this->include('footer');
?>