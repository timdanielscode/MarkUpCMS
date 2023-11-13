<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use core\Alert; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/cdn.css");
    $this->stylesheet("/assets/css/sidebar.css");

    $this->script("/assets/js/fullscreen.js", true);
    $this->script("/assets/js/zoom.js", true);

    $this->stylesheet("/assets/css/codemirror/codemirror.css");
    $this->script("/assets/js/codemirror/codemirror.js");
    $this->script("/assets/js/codemirror/xml.js");
    $this->stylesheet("/assets/css/codemirror/monokai.css");

    $this->include("headerClose");
    $this->include('navbar');
?>

        <div class="row">
            <div class="col10 col10-L- col9-L col8-S">
                <div class="edit-container">
                <?php Alert::message('success'); ?>
                    <form id="editorForm" action="/admin/cdn/<?php echo $cdn['id']; ?>/update" method="POST" class="form-code">
                        <div class="form-parts">
                            <input name="title" type="text" id="title" placeholder="Title" value="<?php echo $cdn['title']; ?>">
                            <?php if(!empty(Errors::get($rules, 'title')) && Errors::get($rules, 'title') !== null) { ?>
                                <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                                    <span><?php echo Errors::get($rules, 'title'); ?></span>
                                </div>    
                            <?php } ?> 
                            <textarea name="content" id="content"><?php echo $cdn['content']; ?></textarea>
                        </div>
                        <div class="form-parts">
                            <button name="submit" id="submit" type="submit" class="display-none" value="submit">Create</button>
                            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col2 col2-L col3-L col4-S">
                <div id="sidebar" class="width-25">
                    <div class="sidebarContainer">
                        <div class="mainButtonContainer">
                            <label for="submit" class="button greenButton margin-r-10">Update</label>
                            <a href="/admin/cdn" class="button blueButton">Back</a>
                        </div>
                        <div class="buttonContainer">
                            <a href="#" id="codeEditorFullScreen" class="button darkButton margin-r-10">Full screen</a>
                            <a href="#" id="codeEditorZoomIn" class="button darkButton margin-r-10">+</a>
                            <a href="#" id="codeEditorZoomOut" class="button darkButton">-</a>
                        </div>
                        <div class="buttonContainer">
                            <form action="/admin/cdn/<?php echo $cdn['id']; ?>/import-all" method="POST">
                                <input type="submit" name="submit" value="Import all" class="button lightButton margin-t-10 margin-r-10" onclick="return confirm('Are you sure?');"/>
                                <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                            </form>
                            <form action="/admin/cdn/<?php echo $cdn['id']; ?>/export-all" method="POST">
                                <input type="submit" name="submit" value="Export all" class="button lightButton margin-t-10" onclick="return confirm('Are you sure?');"/>
                                <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                            </form>
                        </div>
                        <form class="cdnForm" action="/admin/cdn/<?php echo $cdn['id']; ?>/export-pages" method="POST">
                            <select name="pages[]" multiple>
                                <?php foreach($importedPages as $importedPage) { ?>
                                    <option value="<?php echo $importedPage['id']; ?>"><?php echo $importedPage['title']; ?></option>
                                <?php } ?>
                            </select>
                            <input type="submit" name="submit" value="Export" class="button blueButton"/>
                            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                        </form>
                        <form class="cdnForm" action="/admin/cdn/<?php echo $cdn['id']; ?>/import-pages" method="POST">
                            <select name="pages[]" multiple>
                                <?php foreach($pages as $page) { ?>
                                    <option value="<?php echo $page['id']; ?>"><?php echo $page['title']; ?></option>
                                <?php } ?>
                            </select>
                            <input type="submit" name="submit" value="Import" class="button greenButton"/>
                            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("content"), {
            theme: "monokai",
            lineNumbers: true,
            mode: 'text/html',
            tabSize: 2
        });
    </script>
    

<?php 
    $this->include('footer');
?>