<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/cdn.css");
    $this->stylesheet("/assets/css/sidebar.css");

    $this->stylesheet("/assets/css/codemirror/codemirror.css");
    $this->stylesheet("/assets/css/codemirror/rubyblue.css");
    $this->script("/assets/js/codemirror/codemirror.js");
    $this->script("/assets/js/codemirror/css.js");
    $this->script("/assets/js/codemirror/closebrackets.js");

    $this->script('/assets/js/ajax.js');
    $this->script('/assets/js/fullscreen.js');

    $this->title("IndependentCMS");
    $this->include("headerClose");
    $this->include('navbar');
?>

    
        <div class="row">
            <div class="col10 col9-L">
                <div class="edit-container">
                    <form id="editorForm" action="/admin/cdn/<?php echo $cdn['id']; ?>/update" method="POST" class="form-code">
                        <div class="form-parts">
                            <input name="title" type="text" id="title" placeholder="Title" value="<?php echo $cdn['title']; ?>">
                            <textarea name="content" id="content"><?php echo $cdn['content']; ?></textarea>
                        </div>
                        <div class="form-parts">
                            <button name="submit" id="submit" type="submit" class="display-none" value="submit">Create</button>
                            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col2 col3-L">
                <div id="sidebar" class="width-25-L">
                    <div class="sidebarContainer">
                        <div class="mainButtonContainer">
                            <label for="submit" class="button greenButton margin-r-10">Update</label>
                            <a href="/admin/cdn" class="button blueButton">Back</a>
                        </div>
                        <div class="buttonContainer">
                            <a href="#" id="codeEditorFullScreen" class="button darkButton">Full screen</a>
                        </div>
                        <span class="text margin-t-50">File: </span>
                        <span class="data"><?php echo $cdn['title']; ?></span>
                        <form class="cdnForm" action="/admin/cdn/<?php echo $cdn['id']; ?>/export-pages" method="POST">
                            <select name="pages[]" multiple>
                                <?php foreach($importedPages as $importedPage) { ?>
                                    <option value="<?php echo $importedPage['id']; ?>"><?php echo $importedPage['title']; ?></option>
                                <?php } ?>
                            </select>
                        <input type="submit" name="export" value="Export" class="button blueButton"/>
                        </form>
                        <form class="cdnForm" action="/admin/cdn/<?php echo $cdn['id']; ?>/import-pages" method="POST">
                            <select name="pages[]" multiple>
                                <?php foreach($pages as $page) { ?>
                                    <option value="<?php echo $page['id']; ?>"><?php echo $page['title']; ?></option>
                                <?php } ?>
                            </select>
                        <input type="submit" name="import" value="Import" class="button greenButton"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    

    <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("content"), {
            theme: "rubyblue",
            lineNumbers: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            tabSize: 2
        });
    </script>
    

<?php 
    $this->include('footer');
?>