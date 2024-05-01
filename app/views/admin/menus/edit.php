<!-- 
    - FOR TYPE OF ADMIN USER
    - 
    - to change the title value to submit and update
    - to change the contents value to submit and update
    - to select a position to submit and update
    - to select a ordering to submit and update
--> 

<?php $this->include('openHeadTag'); ?> 
    <?php $this->title('Menus edit page'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/menu.css"); ?>
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
    <?php $this->script("/assets/js/navbar/Navbar.js", true); ?>
    <?php $this->script("/assets/js/navbar/main.js", true); ?>
    <?php $this->script("/assets/js/sidebar/Editor.js", true); ?>
    <?php $this->script("/assets/js/sidebar/Section.js", true); ?>
    <?php $this->script("/assets/js/sidebar/Button.js", true); ?>
    <?php $this->script("/assets/js/sidebar/main.js", true); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css" integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js" integrity="sha512-8RnEqURPUc5aqFEN04aQEiPlSAdE0jlFS/9iGgUyNtwFnSKCXhmB6ZTNl7LnDtDWKabJIASzXrzD0K+LYexU9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/xml/xml.min.js" integrity="sha512-LarNmzVokUmcA7aUDtqZ6oTS+YXmUKzpGdm8DxC46A6AHu+PQiYCUlwEGWidjVYMo/QXZMFMIadZtrkfApYp/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/monokai.min.css" integrity="sha512-R6PH4vSzF2Yxjdvb2p2FA06yWul+U0PDDav4b/od/oXf9Iw37zl10plvwOXelrjV2Ai7Eo3vyHeyFUjhXdBCVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php $this->include("closeHeadTagAndOpenBodyTag"); ?>

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col10-L- col9-L col8-S">
        <div class="edit-container">
            <?php core\Alert::message('success'); ?>
            <form id="editorForm" action="/admin/menus/<?php echo $menu['id']; ?>/update" method="POST" class="form-code">
                <div class="form-parts">
                    <input type="text" autofocus name="title" id="title" value="<?php echo $menu['title']; ?>">
                    <?php if(!empty(validation\Errors::get($rules, 'title')) && validation\Errors::get($rules, 'title') !== null) { ?>
                        <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                            <span><?php echo validation\Errors::get($rules, 'title'); ?></span>
                        </div>    
                    <?php } ?>  
                </div>
                <textarea name="content" type="content" id="code"><?php echo $menu['content']; ?></textarea>
                <button name="submit" id="submit" type="submit" class="display-none"></button>
            </form>
        </div>
    </div>
    <div class="col2 col2-L col3-L col4-S">
        <div id="sidebar" class="width-25">
            <div class="sidebarContainer">
                <div class="mainButtonContainer">
                    <label for="submit" class="button greenButton margin-r-10">Update</label>
                    <a href="/admin/menus/<?php echo $menu['id']; ?>/read" class="button darkBlueButton margin-r-10">Read</a>
                    <a href="/admin/menus" class="button blueButton">Back</a>
                </div>
                <div class="buttonContainer margin-b-50">
                    <a href="#" id="codeEditorFullScreen" class="button lightButton margin-r-10">Full screen</a>
                    <a href="#" id="codeEditorZoomIn" class="button lightButton margin-r-10">+</a>
                    <a href="#" id="codeEditorZoomOut" class="button lightButton">-</a>
                </div>
                <form action="/admin/menus/<?php echo $menu['id']; ?>/update-position" method="POST" class="updatePositionForm">
                    <label class="margin-b-20">Position:</label>
                    <span class="data"><?php echo $menu['position']; ?></span>
                    <select name="position" multiple>
                        <option value="top">Top</option>
                        <option value="bottom">Bottom</option>
                        <option value="unset">Unset</option>
                    </select>
                    <input type="submit" name="submit" class="button darkBlueButton" value="Update"/>
                </form>
                <span class="text ordering">Ordering: </span>
                <span class="data ordering"><?php if(!empty($menu['ordering']) && $menu['ordering'] !== null) { echo $menu['ordering']; } else { echo 'Unset'; } ?></span>
                <form action="/admin/menus/<?php echo $menu['id']; ?>/update-ordering" method="POST" class="updateNumberForm">
                    <input dir="rtl" type="number" name="ordering" min="1" max="99" value="1">
                    <input type="submit" name="submit" class="button darkBlueButton" value="Update"/>
                </form>
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