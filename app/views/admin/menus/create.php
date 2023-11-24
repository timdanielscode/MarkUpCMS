<?php use validation\Errors; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/menu.css");
    $this->stylesheet("/assets/css/sidebar.css");

    $this->stylesheet("/assets/css/codemirror/codemirror.css");
    $this->script("/assets/js/codemirror/codemirror.js");
    $this->script("/assets/js/codemirror/xml.js");
    $this->stylesheet("/assets/css/codemirror/monokai.css");

    $this->include("headerClose");
    $this->include('navbar');
?>


    
        <div class="row">
            <div class="col10 col10-L- col9-L col8-S">
                <div class="create-container">
                    <form id="editorForm" action="store" method="POST" class="form-code">
                        <div class="form-parts">
                            <input type="text" autofocus name="title" type="title" id="title" value="<?php if(!empty($title) ) { echo $title; } ?>" placeholder="Title" autofocus>
                            <?php if(!empty(Errors::get($rules, 'title')) && Errors::get($rules, 'title') !== null) { ?>
                                <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                                    <span><?php echo Errors::get($rules, 'title'); ?></span>
                                </div>    
                            <?php } ?>
                        </div>
                        <div class="form-parts">
                            <textarea type="text" name="content" type="content" id="code"><?php if(!empty($content) ) { echo $content; } ?></textarea>
                            <div class="error-messages">
                                <?php echo Errors::get($rules, 'content'); ?>
                            </div>
                        </div>
                        <div class="form-parts">
                            <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col2 col2-L col3-L col4-S">
                <div id="sidebar" class="width-25">
                    <div class="sidebarContainer">
                        <div class="mainButtonContainer">
                            <label for="submit" class="button greenButton margin-r-10">Create</label>
                            <a href="/admin/menus" class="button blueButton">Back</a>
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