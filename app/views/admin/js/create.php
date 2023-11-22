<?php use validation\Errors; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/js.css");
    $this->stylesheet("/assets/css/sidebar.css");

    $this->stylesheet("/assets/css/codemirror/codemirror.css");
    $this->stylesheet("/assets/css/codemirror/shadowfox.css");
    $this->script("/assets/js/codemirror/codemirror.js");
    $this->script("/assets/js/codemirror/css.js");
    $this->script("/assets/js/codemirror/closebrackets.js");

    $this->include("headerClose");
    $this->include('navbar');
?>

    
        <div class="row">
            <div class="col10 col10-L- col9-L col8-S">
                <div class="create-container">
                    <form action="store" method="POST" class="form-code" id="editorForm">
                        <div class="form-parts">
                            <input name="filename" type="text" id="filename" placeholder="Filename without extension" value="<?php if(!empty($filename) ) { echo $filename; } ?>" autofocus>
                            <?php if(!empty(Errors::get($rules, 'filename')) && Errors::get($rules, 'filename') !== null) { ?>
                                <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                                    <span><?php echo Errors::get($rules, 'filename'); ?></span>
                                </div>    
                            <?php } ?> 
                            <textarea name="code" id="code"></textarea>
                        </div>
                        <div class="form-parts">
                            <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col2 col2-L col3-L col4-S">
                <div id="sidebar" class="width-25">
                    <div class="sidebarContainer">
                        <div class="mainButtonContainer">
                            <label for="submit" class="button greenButton margin-r-10">Create</label>
                            <a href="/admin/js" class="button blueButton">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
    <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            theme: "shadowfox",
            lineNumbers: true,
            autoCloseBrackets: true,
            tabSize: 2
        });
    </script>

<?php 
    $this->include('footer');
?>