<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  
    $this->stylesheet("/assets/css/codemirror/codemirror.css");
    $this->stylesheet("/assets/css/codemirror/rubyblue.css");
    $this->script("/assets/js/codemirror/codemirror.js");
    $this->script("/assets/js/codemirror/css.js");
    $this->script("/assets/js/codemirror/closebrackets.js");
    $this->title("IndependentCMS");
    $this->include("headerClose");
    $this->include('navbar');
?>

    <div class="containerJs">
        <div class="row">
            <div class="col10">
                <form action="" method="POST" class="form-code">
                    <div class="form-parts">
                        <input name="filename" type="text" id="filename" placeholder="Filename" value="<?php echo $jsFile['file_name']; ?>">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'filename'); ?>
                        </div>
                        <textarea name="code" id="code"><?php echo $code; ?></textarea>
                    </div>
                    <div class="form-parts">
                        <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                        <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                    </div>
                </form>
            </div>
            <div class="col2">
                <div id="postSidebar">
                    <a href="/admin/css" class="button back">Back</a>
                    <label for="submit" class="button update">Update</label>
                </div>
            </div>
        </div>
    </div>

    <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            theme: "rubyblue",
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