<!-- 
    - FOR TYPE OF ADMIN USER
    - 
    - to change the title value to submit and update
    - to change the contents value to submit and update
    - to select a position to submit and update
    - to select a ordering to submit and update
--> 

<?php $this->include('openHeadTag'); ?> 
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/menu.css"); ?>
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
    <?php $this->script("/assets/js/fullscreen.js", true); ?>
    <?php $this->script("/assets/js/zoom.js", true); ?>
    <?php $this->stylesheet("/assets/css/codemirror/codemirror.css"); ?>
    <?php $this->script("/assets/js/codemirror/codemirror.js"); ?>
    <?php $this->script("/assets/js/codemirror/xml.js"); ?>
    <?php $this->stylesheet("/assets/css/codemirror/monokai.css"); ?>
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
                    <a href="/admin/menus/<?php echo $menu['id']; ?>/read" class="button blueButton margin-r-10">Read</a>
                    <a href="/admin/menus" class="button darkBlueButton">Back</a>
                </div>
                <div class="buttonContainer margin-b-50">
                    <a href="#" id="codeEditorFullScreen" class="button darkButton margin-r-10">Full screen</a>
                    <a href="#" id="codeEditorZoomIn" class="button darkButton margin-r-10">+</a>
                    <a href="#" id="codeEditorZoomOut" class="button darkButton">-</a>
                </div>
                <span class="text">Current position: </span>
                <span class="data"><?php echo $menu['position']; ?></span>
                <form action="/admin/menus/<?php echo $menu['id']; ?>/update-position" method="POST" class="updatePositionForm">
                    <label>Position:</label>
                    <select name="position" multiple>
                        <option value="top">Top</option>
                        <option value="bottom">Bottom</option>
                        <option value="unset">Unset</option>
                    </select>
                    <input type="submit" name="submit" class="button greenButton" value="Update"/>
                </form>
                <span class="text margin-t-50">Current ordering: </span>
                <span class="data"><?php if(!empty($menu['ordering']) && $menu['ordering'] !== null) { echo $menu['ordering']; } else { echo 'Unset'; } ?></span>
                <span class="text">Ordering: </span>
                <form action="/admin/menus/<?php echo $menu['id']; ?>/update-ordering" method="POST" class="updateNumberForm">
                    <input dir="rtl" type="number" name="ordering" min="1" max="99" value="1">
                    <input type="submit" name="submit" class="button blueButton" value="Update"/>
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