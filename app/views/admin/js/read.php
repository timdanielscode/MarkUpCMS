<!-- 
    - to show contents of js file
 --> 

<?php $this->include('openHeadTag'); ?> 
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/js.css"); ?>
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
    <?php $this->stylesheet("/assets/css/codemirror/codemirror.css"); ?>
    <?php $this->stylesheet("/assets/css/codemirror/shadowfox.css"); ?>
    <?php $this->script("/assets/js/codemirror/codemirror.js"); ?>
    <?php $this->script("/assets/js/codemirror/css.js"); ?>
    <?php $this->script("/assets/js/codemirror/closebrackets.js"); ?>
    <?php $this->script('/assets/js/ajax.js'); ?>
    <?php $this->script("/assets/js/fullscreen.js"); ?>
    <?php $this->script("/assets/js/zoom.js"); ?>
<?php $this->include("closeHeadTagAndOpenBodyTag"); ?>

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col9-L">
    <div class="read-container">
        <form action="" method="POST" class="form-code" id="editorForm">
            <div class="form-parts">
                <input name="filename" type="text" id="filename" placeholder="Filename" value="<?php echo $jsFile['file_name']; ?>">
                <textarea name="code" id="code"><?php echo $code; ?></textarea>
            </div>
        </form>
        </div>
    </div>
    <div class="col2 col3-L">
        <div id="sidebar" class="width-25-L">
            <div class="sidebarContainer">
                <div class="mainButtonContainer">
                    <a href="/admin/js" class="button darkBlueButton">Back</a>
                </div>
                <div class="buttonContainer">
                    <a href="#" id="codeEditorFullScreen" class="button darkButton margin-r-10">Full screen</a>
                    <a href="#" id="codeEditorZoomIn" class="button darkButton margin-r-10">+</a>
                    <a href="#" id="codeEditorZoomOut" class="button darkButton">-</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- to use CodeMirror text editor to have a better ux -->
<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        theme: "shadowfox",
        lineNumbers: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        tabSize: 2
    });
</script>
    
<?php $this->include('footer'); ?>