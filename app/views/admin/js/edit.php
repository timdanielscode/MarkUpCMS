<!-- 
    - FOR TYPE OF ADMIN USER
    - 
    - to change the filename value to submit and update
    - to change the contents value to submit and update
    - to click on 'include on all' button to submit and include js file on every page
    - to click on 'remove all' button to submit and exclude js file on every page
    - to have an overview of pages where js file is included and to create a selection to submit and exclude js file
    - to have an overview of pages where js file is not included and to create a selection to submit and include js file
-->

<?php $this->include('openHeadTag'); ?>   
    <?php $this->title('Js edit page'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/js.css"); ?>
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
    <?php $this->script("/assets/js/navbar/Navbar.js", true); ?>
    <?php $this->script("/assets/js/navbar/main.js", true); ?>
    <?php $this->script("/assets/js/sidebar/Editor.js", true); ?>
    <?php $this->script("/assets/js/sidebar/Section.js", true); ?>
    <?php $this->script("/assets/js/sidebar/Button.js", true); ?>
    <?php $this->script("/assets/js/sidebar/main.js", true); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css" integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/shadowfox.min.css" integrity="sha512-Xt1Gi99yJFMZ0ocjkdHqKWLjtb/l8pzJo5cCmHV2GvBFwJiOCLR2HXQBrcFmqY6i8+gXnRaKTGzoBVOl3YUwjw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js" integrity="sha512-8RnEqURPUc5aqFEN04aQEiPlSAdE0jlFS/9iGgUyNtwFnSKCXhmB6ZTNl7LnDtDWKabJIASzXrzD0K+LYexU9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/css/css.min.js" integrity="sha512-rQImvJlBa8MV1Tl1SXR5zD2bWfmgCEIzTieFegGg89AAt7j/NBEe50M5CqYQJnRwtkjKMmuYgHBqtD1Ubbk5ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/edit/closebrackets.min.js" integrity="sha512-tsjcYO5hFvViRssxiM7Jhd8601epWOx1He3Hl4yuI5dKKPxr43KxkOhc9GZeeqzlYJm9ABb7UPA9697NiqZZ7Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php $this->include("closeHeadTagAndOpenBodyTag"); ?>

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col10-L- col9-L col8-S">
        <div class="edit-container">
        <?php core\Alert::message('success'); ?>
            <form id="editorForm" action="/admin/js/<?php echo $data['id']; ?>/update" method="POST" class="form-code">
                <div class="form-parts">
                    <input name="filename" type="text" id="filename" placeholder="Filename" value="<?php echo $data['file_name']; ?>">
                    <?php if(!empty(validation\Errors::get($rules, 'filename')) && validation\Errors::get($rules, 'filename') !== null) { ?>
                        <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
                            <span><?php echo validation\Errors::get($rules, 'filename'); ?></span>
                        </div>    
                    <?php } ?> 
                    <textarea name="code" id="code"><?php echo $data['code']; ?></textarea>
                </div>
                <div class="form-parts">
                    <button name="submit" id="submit" type="submit" class="display-none" value="submit"></button>
                </div>
            </form>
        </div>
    </div>
    <div class="col2 col2-L col3-L col4-S">
        <div id="sidebar" class="width-25">
            <div class="sidebarContainer">
                <div class="mainButtonContainer">
                    <label for="submit" class="button greenButton margin-r-10">Update</label>
                    <a href="/admin/js" class="button blueButton">Back</a>
                </div>
                <div class="buttonContainer">
                    <a href="#" id="codeEditorFullScreen" class="button lightButton margin-r-10">Full screen</a>
                    <a href="#" id="codeEditorZoomIn" class="button lightButton margin-r-10">+</a>
                    <a href="#" id="codeEditorZoomOut" class="button lightButton">-</a>
                </div>
                <div class="buttonContainer">
                    <form action="/admin/js/<?php echo $data['id']; ?>/include-all" method="POST">
                        <input type="submit" name="submit" class="button darkButton margin-r-10" value="Include on all" onclick="return confirm('Are you sure?');"/>
                    </form>
                    <form action="/admin/js/<?php echo $data['id']; ?>/remove-all" method="POST">
                        <input type="submit" name="submit" class="button darkButton" value="Remove all" onclick="return confirm('Are you sure?');"/>
                    </form>
                </div>
                <form action="/admin/js/<?php echo $data['id']; ?>/remove-pages" method="POST" class="removeJsForm margin-t-50">
                    <label>Included: </label>
                    <select name="pages[]" multiple>
                        <?php foreach($data['assingedPages'] as $page) { ?>
                            <option value="<?php echo $page['id']; ?>"><?php echo $page['title']; ?></option>
                        <?php } ?> 
                    </select>
                    <input type="submit" name="submit" class="button darkBlueButton" value="Exclude"/>
                </form>   
                <form action="/admin/js/<?php echo $data['id']; ?>/include-pages" method="POST" class="includeJsForm">
                    <label>Pages: </label>
                    <select name="pages[]" multiple>
                        <?php foreach($data['pages'] as $page) { ?>
                            <option value="<?php echo $page['id']; ?>"><?php echo $page['title']; ?></option>
                        <?php } ?> 
                    </select>
                    <input type="submit" name="submit" class="button darkBlueButton" value="Include"/>
                </form>   
            </div>
        </div>
    </div>
</div>
    
<!-- to use CodeMirror text editor to have a better ux -->
<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        theme: "shadowfox",
        lineNumbers: true,
        autoCloseBrackets: true,
        tabSize: 2
    });
</script>
    
<?php $this->include('footer'); ?>