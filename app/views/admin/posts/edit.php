<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>

<?php 
    $this->include('headerOpen');  
    $this->stylesheet("/assets/css/codemirror/codemirror.css");
    $this->script("/assets/js/codemirror/codemirror.js");
    $this->script("/assets/js/codemirror/closetag.js");
    $this->script("/assets/js/codemirror/xml.js");
    $this->stylesheet("/assets/css/codemirror/bespin.css"); //ayu-mirage, lesser-dark, railscasts, seti
    $this->script("/assets/js/codemirror/htmlmixed.js");
    $this->script('/assets/js/ajax.js');
    $this->script('/assets/js/fullscreen.js');
    $this->title("IndependentCMS");
    $this->include("headerClose");
    $this->include('navbar');
    
?>

    
<?php if(Session::exists("csrf")) { ?>
        <div class="my-5 w-75 mx-auto"><?php echo Alert::display("warning", "csrf"); ?></div>
    <?php Session::delete('csrf'); } ?>

    <div class="containerPost">
    <div class="row">
        <div class="col10">
            <form action="" method="POST" class="form-code">
                <div class="form-parts">
                    <input name="title" id="title" value="<?php echo $post['title']; ?>">
                    <div class="error-messages">
                        <?php echo Errors::get($rules, 'title'); ?>
                    </div>    
                    <input name="slug" id="slug" type="text" value="<?php echo $post['slug']; ?>">
                    <div class="error-messages">
                        <?php echo Errors::get($rules, 'slug'); ?>
                    </div>
                </div>
                <textarea name="body" type="body" id="code"><?php echo $post['body']; ?></textarea>
                <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
            </form>
        </div>
        <div class="col2">
            <div id="postSidebar">
                <ul class="postSidebarButtons">
                    <li>
                        <a href="/admin/posts" class="button">Back</a>
                    </li>
                    <li>
                    <label for="submit" class="button">Update</label>
                    </li>
                    <li>
                    <a href="/admin/posts/<?php echo $post['id']; ?>/preview" class="button">Preview</a>
                    </li>
                    <li>
                    <a href="#" id="codeEditorFullScreen" class="button">Full screen</a>
                    </li>
                </ul>
                    	
                        
                        

            </div>
        </div>
    </div>
</div>
<script>
        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            theme: "bespin",
            lineNumbers: true,
            mode: 'text/html',
            autoCloseTags: true,
            tabSize: 2
        });
        editor.setSize('95%', "75vh");
    
    </script>
<?php 
    $this->include('footer');
?>