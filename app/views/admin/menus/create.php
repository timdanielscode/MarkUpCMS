<!-- 
    - FOR TYPE OF ADMIN USER
    -
    - to add a title value (required), to submit and store a new menu
    - to add a content value (html markup)
--> 

<?php $this->include('openHeadTag'); ?>
    <?php $this->title('Menus create page'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/menu.css"); ?>
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
    <?php $this->script("/assets/js/navbar/Navbar.js", true); ?>
    <?php $this->script("/assets/js/navbar/main.js", true); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css" integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js" integrity="sha512-8RnEqURPUc5aqFEN04aQEiPlSAdE0jlFS/9iGgUyNtwFnSKCXhmB6ZTNl7LnDtDWKabJIASzXrzD0K+LYexU9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/xml/xml.min.js" integrity="sha512-LarNmzVokUmcA7aUDtqZ6oTS+YXmUKzpGdm8DxC46A6AHu+PQiYCUlwEGWidjVYMo/QXZMFMIadZtrkfApYp/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/monokai.min.css" integrity="sha512-R6PH4vSzF2Yxjdvb2p2FA06yWul+U0PDDav4b/od/oXf9Iw37zl10plvwOXelrjV2Ai7Eo3vyHeyFUjhXdBCVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php $this->include("closeHeadTagAndOpenBodyTag"); ?>

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col10-L- col9-L col8-S">
        <div class="create-container">
            <form id="editorForm" action="store" method="POST" class="form-code">
                <div class="form-parts">
                    <input type="text" autofocus name="title" type="title" id="title" value="<?php if(!empty($title) ) { echo $title; } ?>" placeholder="Title" autofocus>
                    <?php if(!empty(validation\Errors::get($rules, 'title')) && validation\Errors::get($rules, 'title') !== null) { ?>
                        <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                            <span><?php echo validation\Errors::get($rules, 'title'); ?></span>
                        </div>    
                    <?php } ?>
                </div>
                <div class="form-parts">
                    <textarea type="text" name="content" type="content" id="code"><?php if(!empty($content) ) { echo $content; } ?></textarea>
                    <div class="error-messages">
                        <?php echo validation\Errors::get($rules, 'content'); ?>
                    </div>
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
                    <a href="/admin/menus" class="button blueButton">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- to use CodeMirror text editor to have a better ux -->
<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        theme: "monokai",
        lineNumbers: true,
        mode: 'text/html',
        tabSize: 2
    });
</script>
    
<?php $this->include('footer'); ?>