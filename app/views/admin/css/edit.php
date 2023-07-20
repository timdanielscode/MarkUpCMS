<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/css.css");
    $this->stylesheet("/assets/css/sidebar.css");

    $this->stylesheet("/assets/css/codemirror/codemirror.css");
    $this->stylesheet("/assets/css/codemirror/rubyblue.css");
    $this->script("/assets/js/codemirror/codemirror.js");
    $this->script("/assets/js/codemirror/css.js");
    $this->script("/assets/js/codemirror/closebrackets.js");
    $this->title("IndependentCMS");
    $this->include("headerClose");
    $this->include('navbar');
?>

    <div class="edit-container">
        <div class="row">
            <div class="col10 col9-L">
                <form action="update" method="POST" class="form-code">
                    <div class="form-parts">
                        <input name="filename" type="text" id="filename" placeholder="Filename" value="<?php if(!empty($data['file_name'])) { echo $data['file_name']; } ?>">
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'filename'); ?>
                        </div>
                        <textarea name="code" id="code"><?php echo $data['code']; ?></textarea>
                    </div>
                    <div class="form-parts">
                        <button name="submit" id="submit" type="submit" class="display-none" value="submit">Create</button>
                        <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                    </div>
                </form>
            </div>
            <div class="col2 col3-L">
                <div id="sidebar" class="width-25-L">
                    <div class="sidebarContainer">
                        <div class="mainButtonContainer">
                            <label for="submit" class="button update">Update</label>
                            <a href="/admin/css" class="button back">Back</a>
                        </div>
                        <div class="buttonContainer">
                            <form action="update" method="POST">
                                <input type="submit" name="linkAll" value="Link on all"/>
                            </form>
                            <form action="update" method="POST">
                                <input type="submit" name="removeAll" value="Unlink all"/>
                            </form>
                        </div>
                        <span class="text">File: </span>
                        <span class="data"><?php echo $data['file_name'] . $data['extension']; ?></span>
                        <form action="update" method="POST" class="removeCssForm">
                            <label>Linked on: </label>
                            <select name="pages[]" multiple>
                                <?php foreach($data['assingedPages'] as $page) { ?>
                                    <option value="<?php echo $page['id']; ?>"><?php echo $page['title']; ?></option>
                                <?php } ?>
                            </select>
                            <input type="submit" name="removePage" value="Unlink"/>
                        </form>
                        <form action="update" method="POST" class="linkCssForm">
                            <label>Other pages: </label>
                            <select name="pages[]" multiple>
                                <?php foreach($data['pages'] as $page) { ?>
                                    <option value="<?php echo $page['id']; ?>"><?php echo $page['title']; ?></option>
                                <?php } ?>
                            </select>
                            <input type="submit" name="updatePage" value="Link"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            theme: "rubyblue",
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