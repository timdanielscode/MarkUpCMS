<!-- 
    - FOR TYPE OF ADMIN USER
    -
    - to add a filename value (required), to submit and create a new js file
    - to add a content value (js script code)
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
<?php $this->include("closeHeadTagAndOpenBodyTag"); ?>

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col10-L- col9-L col8-S">
        <div class="create-container">
            <form action="store" method="POST" class="form-code" id="editorForm">
                <div class="form-parts">
                    <input name="filename" type="text" id="filename" placeholder="Filename without extension" value="<?php if(!empty($filename) ) { echo $filename; } ?>" autofocus>
                    <?php if(!empty(validation\Errors::get($rules, 'filename')) && validation\Errors::get($rules, 'filename') !== null) { ?>
                        <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                            <span><?php echo validation\Errors::get($rules, 'filename'); ?></span>
                        </div>    
                    <?php } ?> 
                    <textarea name="code" id="code"><?php if(!empty($code) ) { echo $code; } ?></textarea>
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
                    <label for="submit" class="button greenButton margin-r-10">Create</label>
                    <a href="/admin/js" class="button blueButton">Back</a>
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