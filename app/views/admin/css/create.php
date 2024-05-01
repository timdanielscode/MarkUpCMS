<!--     
    - FOR TYPE OF ADMIN USER
    -
    - to add a filename value (required), to submit and create a new css file
    - to add a content value (css style)
--> 
<?php $this->include('openHeadTag'); ?>
    <?php $this->title('Css create page'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/css.css"); ?>
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
    <?php $this->script("/assets/js/navbar/Navbar.js", true); ?>
    <?php $this->script("/assets/js/navbar/main.js", true); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css" integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/shadowfox.min.css" integrity="sha512-Xt1Gi99yJFMZ0ocjkdHqKWLjtb/l8pzJo5cCmHV2GvBFwJiOCLR2HXQBrcFmqY6i8+gXnRaKTGzoBVOl3YUwjw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js" integrity="sha512-8RnEqURPUc5aqFEN04aQEiPlSAdE0jlFS/9iGgUyNtwFnSKCXhmB6ZTNl7LnDtDWKabJIASzXrzD0K+LYexU9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/css/css.min.js" integrity="sha512-rQImvJlBa8MV1Tl1SXR5zD2bWfmgCEIzTieFegGg89AAt7j/NBEe50M5CqYQJnRwtkjKMmuYgHBqtD1Ubbk5ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/edit/closebrackets.min.js" integrity="sha512-tsjcYO5hFvViRssxiM7Jhd8601epWOx1He3Hl4yuI5dKKPxr43KxkOhc9GZeeqzlYJm9ABb7UPA9697NiqZZ7Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php $this->include("closeHeadTagAndOpenBodyTag"); ?>

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col10-L- col9-L col8-S">
        <div class="create-container">
            <form action="store" method="POST" class="form-code" id="editorForm">
                <div class="form-parts">
                    <input name="filename" type="text" id="filename" value="<?php if(!empty($filename)) { echo $filename; } ?>" placeholder="Filename without extension" autofocus>
                    <?php if(!empty(validation\Errors::get($rules, 'filename')) && validation\Errors::get($rules, 'filename') !== null) { ?>
                        <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                            <span><?php echo validation\Errors::get($rules, 'filename'); ?></span>
                        </div>    
                    <?php } ?>  
                    <textarea name="code" id="code"><?php if(!empty($code)) { echo $code; } ?></textarea>
                </div>
                <div class="form-parts">
                    <button name="submit" id="submit" type="submit" class="display-none"></button>
                </div>
            </form>
        </div>
    </div>
    <div class="col2 col2-L col3-L col4-S">
        <div id="sidebar" class="width-25">
            <div class="sidebarContainer">
                <div class="mainButtonContainer">
                    <label for="submit" class="button darkBlueButton margin-r-10">Create</label>
                    <a href="/admin/css" class="button blueButton">Back</a>
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