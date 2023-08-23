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

    $this->script('/assets/js/ajax.js');
    $this->script('/assets/js/fullscreen.js');

    $this->title("IndependentCMS");
    $this->include("headerClose");
    $this->include('navbar');
?>

    
        <div class="row">
            <div class="col10 col9-L">
                <div class="edit-container">
                    <form id="editorForm" action="/admin/css/<?php echo $data['id'];?>/update" method="POST" class="form-code">
                        <div class="form-parts">
                            <input name="filename" type="text" id="filename" placeholder="Filename" value="<?php if(!empty($data['file_name'])) { echo $data['file_name']; } ?>">
                            <?php if(!empty(Errors::get($rules, 'filename')) && Errors::get($rules, 'filename') !== null) { ?>
                                <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                                    <span><?php echo Errors::get($rules, 'filename'); ?></span>
                                </div>    
                            <?php } ?> 
                            <textarea name="code" id="code"><?php echo $data['code']; ?></textarea>
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
                            <a href="/admin/css" class="button blueButton">Back</a>
                        </div>
                        <div class="buttonContainer">
                            <a href="#" id="codeEditorFullScreen" class="button darkButton">Full screen</a>
                        </div>
                        <div class="buttonContainer">
                            <form action="/admin/css/<?php echo $data['id']; ?>/link-all" method="POST">
                                <input type="submit" name="submit" class="button lightButton margin-r-10" value="Link on all"/>
                                <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                            </form>
                            <form action="/admin/css/<?php echo $data['id']; ?>/unlink-all" method="POST">
                                <input type="submit" name="submit" class="button lightButton" value="Unlink all"/>
                                <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                            </form>
                        </div>
                        <span class="text margin-t-50">File: </span>
                        <span class="data"><?php echo $data['file_name'] . $data['extension']; ?></span>
                        <form action="/admin/css/<?php echo $data['id']; ?>/unlink-pages" method="POST" class="removeCssForm">
                            <label>Linked on: </label>
                            <select name="pages[]" multiple>
                                <?php foreach($data['assingedPages'] as $page) { ?>
                                    <option value="<?php echo $page['id']; ?>"><?php echo $page['title']; ?></option>
                                <?php } ?>
                            </select>
                            <input type="submit" name="submit" class="button blueButton" value="Unlink"/>
                            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                        </form>
                        <form action="/admin/css/<?php echo $data['id']; ?>/link-pages" method="POST" class="linkCssForm">
                            <label>Other pages: </label>
                            <select name="pages[]" multiple>
                                <?php foreach($data['pages'] as $page) { ?>
                                    <option value="<?php echo $page['id']; ?>"><?php echo $page['title']; ?></option>
                                <?php } ?>
                            </select>
                            <input type="submit" name="submit" class="button greenButton" value="Link"/>
                            <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                        </form>
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
    </script>
    

<?php 
    $this->include('footer');
?>