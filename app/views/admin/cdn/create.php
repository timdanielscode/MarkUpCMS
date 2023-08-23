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
    $this->stylesheet("/assets/css/codemirror/gruvbox-dark.css");
    $this->script("/assets/js/codemirror/codemirror.js");
    $this->script("/assets/js/codemirror/css.js");
    $this->script("/assets/js/codemirror/closebrackets.js");

    $this->title("IndependentCMS");
    $this->include("headerClose");
    $this->include('navbar');
?>

   
        <div class="row">
            <div class="col10 col9-L">
                <div class="create-container">
                    <form id="editorForm" action="store" method="POST" class="form-code">
                        <div class="form-parts">
                            <input name="title" type="text" id="title" placeholder="Title" autofocus>
                            <?php if(!empty(Errors::get($rules, 'title')) && Errors::get($rules, 'title') !== null) { ?>
                                <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                                    <span><?php echo Errors::get($rules, 'title'); ?></span>
                                </div>    
                            <?php } ?> 
                            <textarea name="code" id="code"></textarea>
                        </div>
                        <div class="form-parts">
                            <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col2 col3-L">
                <div id="sidebar" class="width-25-L">
                    <div class="sidebarContainer">
                        <div class="mainButtonContainer">
                            <label for="submit" class="button greenButton margin-r-10">Create</label>
                            <a href="/admin/css" class="button blueButton">Back</a>
                        </div>
                        <div class="buttonContainer">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
    <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            theme: "gruvbox-dark",
            lineNumbers: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            tabSize: 2
        });
        editor.setSize('95%', "80vh");
    
    </script>

    

<?php 
    $this->include('footer');
?>