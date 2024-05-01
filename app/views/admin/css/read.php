<!-- 
    - to show contents of css file
 --> 

 <?php $this->include('openHeadTag'); ?> 
    <?php $this->title('Css read page'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?> 
    <?php $this->stylesheet("/assets/css/navbar.css"); ?> 
    <?php $this->stylesheet("/assets/css/css.css"); ?> 
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?> 
    <?php $this->script("/assets/js/navbar/Navbar.js", true); ?>
    <?php $this->script("/assets/js/navbar/main.js", true); ?>
    <?php $this->script("/assets/js/sidebar/Editor.js", true); ?>
    <?php $this->script("/assets/js/sidebar/Section.js", true); ?>
    <?php $this->script("/assets/js/sidebar/Button.js", true); ?>
    <?php $this->script("/assets/js/sidebar/main.js", true); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css" integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/shadowfox.min.css" integrity="sha512-Xt1Gi99yJFMZ0ocjkdHqKWLjtb/l8pzJo5cCmHV2GvBFwJiOCLR2HXQBrcFmqY6i8+gXnRaKTGzoBVOl3YUwjw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js" integrity="sha512-8RnEqURPUc5aqFEN04aQEiPlSAdE0jlFS/9iGgUyNtwFnSKCXhmB6ZTNl7LnDtDWKabJIASzXrzD0K+LYexU9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/css/css.min.js" integrity="sha512-rQImvJlBa8MV1Tl1SXR5zD2bWfmgCEIzTieFegGg89AAt7j/NBEe50M5CqYQJnRwtkjKMmuYgHBqtD1Ubbk5ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/edit/closebrackets.min.js" integrity="sha512-tsjcYO5hFvViRssxiM7Jhd8601epWOx1He3Hl4yuI5dKKPxr43KxkOhc9GZeeqzlYJm9ABb7UPA9697NiqZZ7Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php $this->include("closeHeadTagAndOpenBodyTag"); ?> 

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col9-L">
    <div class="read-container">
        <form action="update" method="POST" class="form-code" id="editorForm">
            <div class="form-parts">
                <input name="filename" type="text" id="filename" placeholder="Filename" value="<?php echo $file['file_name']; ?>">
                <textarea name="code" id="code"><?php echo $code; ?></textarea>
            </div>
        </form>
        </div>
    </div>
    <div class="col2 col3-L">
        <div id="sidebar" class="width-25-L">
            <div class="sidebarContainer">
                <div class="mainButtonContainer">
                    <a href="/admin/css" class="button blueButton">Back</a>
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
    autoCloseBrackets: true,
    tabSize: 2
});
</script>
    
<?php $this->include('footer'); ?>