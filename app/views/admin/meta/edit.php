<!-- 
    -
    - FOR TYPE OF ADMIN USER
    -
    - to change the title value to submit and update
    - to change the contents value to submit and update
    - to click on the 'import all' button to submit and include on every page 
    - to click on the 'export all' buttom to submit and export on every page
    - to have an overview of pages where meta content is included and to create a selection to submit and include meta
    - to have an overview of pages where meta content is not included and to create a selection to submit and remove meta
--> 

<?php $this->include('openHeadTag'); ?>  
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/cdn.css"); ?>
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
            <form id="editorForm" action="/admin/meta/<?php echo $cdn['id']; ?>/update" method="POST" class="form-code">
                <div class="form-parts">
                    <input name="title" type="text" id="title" placeholder="Title" value="<?php echo $cdn['title']; ?>">
                    <?php if(!empty(validation\Errors::get($rules, 'title')) && validation\Errors::get($rules, 'title') !== null) { ?>
                        <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                            <span><?php echo validation\Errors::get($rules, 'title'); ?></span>
                        </div>    
                    <?php } ?> 
                    <textarea name="content" id="content"><?php echo $cdn['content']; ?></textarea>
                </div>
                <div class="form-parts">
                    <button name="submit" id="submit" type="submit" class="display-none" value="submit">Create</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col2 col2-L col3-L col4-S">
        <div id="sidebar" class="width-25">
            <div class="sidebarContainer">
                <div class="mainButtonContainer">
                    <label for="submit" class="button greenButton margin-r-10">Update</label>
                    <a href="/admin/meta" class="button blueButton">Back</a>
                </div>
                <div class="buttonContainer">
                    <a href="#" id="codeEditorFullScreen" class="button darkButton margin-r-10">Full screen</a>
                    <a href="#" id="codeEditorZoomIn" class="button darkButton margin-r-10">+</a>
                    <a href="#" id="codeEditorZoomOut" class="button darkButton">-</a>
                </div>
                <div class="buttonContainer">
                    <form action="/admin/meta/<?php echo $cdn['id']; ?>/import-all" method="POST">
                        <input type="submit" name="submit" value="Import all" class="button lightButton margin-t-10 margin-r-10" onclick="return confirm('Are you sure?');"/>
                    </form>
                    <form action="/admin/meta/<?php echo $cdn['id']; ?>/export-all" method="POST">
                        <input type="submit" name="submit" value="Export all" class="button lightButton margin-t-10" onclick="return confirm('Are you sure?');"/>
                    </form>
                </div>
                <form class="cdnForm" action="/admin/meta/<?php echo $cdn['id']; ?>/export-pages" method="POST">
                    <label>Imported on: </label>
                    <select name="pages[]" multiple>
                        <?php foreach($importedPages as $importedPage) { ?>
                            <option value="<?php echo $importedPage['id']; ?>"><?php echo $importedPage['title']; ?></option>
                        <?php } ?>
                    </select>
                    <input type="submit" name="submit" value="Export" class="button blueButton"/>
                </form>
                <form class="cdnForm" action="/admin/meta/<?php echo $cdn['id']; ?>/import-pages" method="POST">
                    <label>Other pages: </label>
                    <select name="pages[]" multiple>
                        <?php foreach($pages as $page) { ?>
                            <option value="<?php echo $page['id']; ?>"><?php echo $page['title']; ?></option>
                        <?php } ?>
                    </select>
                    <input type="submit" name="submit" value="Import" class="button greenButton"/>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- to use CodeMirror text editor to have a better ux -->
<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("content"), {
        theme: "monokai",
        lineNumbers: true,
        mode: 'text/html',
        tabSize: 2
    });
</script>
    
<?php $this->include('footer'); ?>