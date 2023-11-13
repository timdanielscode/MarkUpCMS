<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use core\Alert; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/widget.css");
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
                <form id="editorForm" action="/admin/widgets/<?php echo $widget['id']; ?>/update" method="POST" class="form-code">
                    <div class="form-parts">
                        <input type="text" autofocus name="title" id="title" value="<?php echo $widget['title']; ?>">
                        <?php if(!empty(Errors::get($rules, 'title')) && Errors::get($rules, 'title') !== null) { ?>
                            <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                                <span><?php echo Errors::get($rules, 'title'); ?></span>
                            </div>    
                        <?php } ?>  
                    </div>
                    <textarea name="content" type="content" id="code"><?php echo htmlentities($widget['content']); ?></textarea>
                    <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                    <input type="hidden" name="token" value="<?php Csrf::token('add'); ?>" />
                </form>
            </div>
        </div>
        <div class="col2 col2-L col3-L col4-S">
            <div id="sidebar" class="width-25">
                <div class="sidebarContainer">
                    <div class="mainButtonContainer">
                        <label for="submit" class="button greenButton margin-r-10">Update</label>
                        <a href="/admin/widgets/<?php echo $widget['id']; ?>/read" class="button blueButton margin-r-10">Read</a>
                        <a href="/admin/widgets" class="button darkBlueButton">Back</a>
                    </div>
                    <div class="buttonContainer margin-b-50">
                        <a href="#" id="codeEditorFullScreen" class="button darkButton margin-r-10">Full screen</a>
                        <a href="#" id="codeEditorZoomIn" class="button darkButton margin-r-10">+</a>
                        <a href="#" id="codeEditorZoomOut" class="button darkButton">-</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            theme: "monokai",
            lineNumbers: true,
            mode: 'text/html',
            tabSize: 2
        });
    </script>
<?php 
    $this->include('footer');
?>