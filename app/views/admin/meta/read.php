<?php use validation\Errors; ?>
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

    $this->include("headerClose");
    $this->include('navbar');
?>

    
        <div class="row">
            <div class="col10 col9-L">
            <div class="read-container">
                <form action="" method="POST" class="form-code" id="editorForm">
                    <div class="form-parts">
                        <input name="title" type="text" id="title" placeholder="title" value="<?php echo $cdn['title']; ?>">
                        <textarea name="content" id="content"><?php echo $cdn['content']; ?></textarea>
                    </div>
                    <div class="form-parts">
                        <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                    </div>
                </form>
                </div>
            </div>
            <div class="col2 col3-L">
                <div id="sidebar" class="width-25-L">
                    <div class="sidebarContainer">
                        <div class="mainButtonContainer">
                            <a href="/admin/meta" class="button darkBlueButton">Back</a>
                        </div>
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