<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen'); 
    
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/categories.css");
    $this->stylesheet("/assets/css/sidebar.css");

    $this->stylesheet("/assets/css/codemirror/codemirror.css");
    $this->script("/assets/js/codemirror/codemirror.js");
    $this->script("/assets/js/codemirror/closetag.js");
    $this->script("/assets/js/codemirror/xml.js");
    $this->stylesheet("/assets/css/codemirror/monokai.css"); //ayu-mirage, lesser-dark, railscasts, seti
    $this->script("/assets/js/codemirror/htmlmixed.js");
    $this->script('/assets/js/ajax.js');
    $this->script('/assets/js/fullscreen.js');
    $this->title("IndependentCMS");
    $this->include("headerClose");
    $this->include('navbar');
?>
    
    <div class="create-container">
        <div class="row">
            <div class="col10 col9-L">
                <form action="" method="POST" class="form-code">
                    <div class="form-parts">
                        <input type="text" name="title" id="title" placeholder="Title" autofocus>
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'title'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <textarea type="text" name="description" id="description"></textarea>
                        <div class="error-messages">
                            <?php echo Errors::get($rules, 'description'); ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <div class="containerCategoryPagesCheckbox">
                            <?php foreach($pages as $page) { ?>
                                <div class="row">
                                    <div class="col6">
                                        <label><?php echo $page['title']; ?></label>
                                    </div>
                                    <div class="col6">
                                        <input name="page[]" type="checkbox" value="<?php echo $page['id'] ?>">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-parts">
                        <button name="submit" id="submit" type="submit" class="display-none">Create</button>
                        <input type="hidden" name="token" value="<?php Csrf::token('add');?>" />
                    </div>
                </form>
            </div>
            <div class="col2 col3-L">
                <div id="sidebar" class="width-25-L">
                    <div class="sidebarContainer">
                        <div class="mainButtonContainer">
                            <label for="submit" class="button">Create</label>
                            <a href="/admin/menus" class="button">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
    $this->include('footer');
?>