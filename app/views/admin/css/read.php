<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/css.css");
    $this->stylesheet("/assets/css/sidebar.css");
    
    $this->stylesheet("/assets/css/codemirror/codemirror.css");
    $this->stylesheet("/assets/css/codemirror/monokai.css");
    $this->script("/assets/js/codemirror/codemirror.js");
    $this->script("/assets/js/codemirror/css.js");
    $this->script("/assets/js/codemirror/closebrackets.js");

    $this->script('/assets/js/ajax.js');
    $this->script("/assets/js/fullscreen.js");
    $this->script("/assets/js/zoom.js");

    $this->include("headerClose");
    $this->include('navbar');
?>

    
        <div class="row">
            <div class="col10 col9-L">
            <div class="read-container">
                <form action="update" method="POST" class="form-code" id="editorForm">
                    <div class="form-parts">
                        <input name="filename" type="text" id="filename" placeholder="Filename" value="<?php echo $file['file_name']; ?>">
                        <textarea name="code" id="code"><?php echo $code; ?></textarea>
                    </div>
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
                            <a href="/admin/css" class="button darkBlueButton">Back</a>
                        </div>
                        <div class="buttonContainer">
                            <a href="#" id="codeEditorFullScreen" class="button darkButton margin-r-10">Full screen</a>
                            <a href="#" id="codeEditorZoomIn" class="button darkButton margin-r-10">+</a>
                            <a href="#" id="codeEditorZoomOut" class="button darkButton">-</a>
                        </div>
                        <span class="text">File: </span>
                        <span class="data"><?php echo $file['file_name'] . $file['extension']; ?></span>
                    </div>
                </div>
            </div>
        </div>
   

        <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            theme: "monokai",
            lineNumbers: true,
            autoCloseBrackets: true,
            tabSize: 2
        });
    </script>
    

<?php 
    $this->include('footer');
?>