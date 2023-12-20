<!-- 
    - to have an overview of assinged and not assinged sub categories per category
    - to have an overview of assinged pages and not assinged pages per category
    -
    - FOR TYPE OF ADMIN USER
    -
    - to create a selection of sub categories to submit and assign or detach from/to category
    - to create a selection of pages to submit and assign or detach from/to category
    - to change the category slug value to submit and update
    - to change the category title value to submit and update
    - to change the category description value to submit and update
    - to create a selection of categories to submit and remove
    - to add a new category title to submit and create a new category 
-->

<?php $this->include('openHeadTag'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/categories.css"); ?>
    <?php $this->stylesheet("/assets/css/pagination.css"); ?>
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
    <?php $this->stylesheet("/assets/css/modal.css"); ?>
    <?php $this->script('/assets/js/categories/Modal.js', true); ?>
    <?php $this->script('/assets/js/categories/Category.js', true); ?>
    <?php $this->script('/assets/js/categories/Page.js', true); ?>
    <?php $this->script('/assets/js/categories/main.js', true); ?>
<?php $this->include('closeHeadTagAndOpenBodyTag'); ?>

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col10-L- col9-L col8-S">
        <div class="edit-container">

            <?php echo core\Alert::message('success'); ?>
            <?php echo core\Alert::message('failed'); ?>

            <?php if(!empty($categoryId) && $categoryId !== null ) { ?><div id="slug"><?php if(!empty($assingedSubCategories) && $assingedSubCategories !== null) { ?><?php foreach($assingedSubCategories as $assingedSubCategory) { ?><div class="listedItem"><?php echo $assingedSubCategory['slug']; ?></div><?php } ?><?php } ?><div class="listedItem">/</div><form action="/admin/categories/slug" method="POST" id="slugForm"><input type="text" name="slug" value="<?php echo ltrim($slug, '/'); ?>"/><input type="hidden" name="id" value="<?php echo $categoryId; ?>" /></form></div><?php } ?>
            <?php if(!empty($categoryId) && $categoryId !== null) { ?>
                <div class="row">
                    <div class="col6">
                        <form action="/admin/categories/addcategory" method="POST" id="category">
                            <label>Assigned categories: </label>
                            <select name="subcategoryid" class="assingedSubCategories" multiple>
                            <?php if(!empty($assingedSubCategories) && $assingedSubCategories !== null) { ?>
                                <?php foreach($assingedSubCategories as $assingedSubCategory) { ?>           
                                    <option class="assingedSubCategory" value="<?php echo $assingedSubCategory['id']; ?>">          
                                        <?php echo $assingedSubCategory['title']; ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                            </select>
                            <select name="subcategoryid" multiple>
                                <?php foreach($notAssingedSubs as $notAssingedSub) { ?>
                                    <option class="notAssingedSubCategory" value="<?php echo $notAssingedSub['id']; ?>">
                                        <?php echo $notAssingedSub['title']; ?>
                                    </option>
                                <?php } ?>
                            </select>  
                            <input type="hidden" id="categoryIds" name="categoryIds" value=""/>
                            <input type="hidden" name="id" value="<?php echo $categoryId; ?>"/>
                            <?php if(core\Session::get('user_role') === 'admin') { ?>
                                <input type="submit" name="submit" value="Apply" class="button darkBlueButton margin-t-20"/>
                            <?php } ?>
                        </form>
                    </div>
                    <div class="col6">
                        <form action="/admin/categories/addpage" method="POST" id="page">
                            <label>Assigned pages: </label>
                            <select name="pageid[]" class="assignedPages" multiple>
                                <?php if(!empty($assignedPages) && $assignedPages !== null) { ?>
                                    <?php foreach($assignedPages as $assignedPage) { ?>
                                        <option class="assingedPage" value="<?php echo $assignedPage['id']; ?>"><?php  echo $assignedPage['title']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                            <select name="pageid[]" multiple>
                                <?php foreach($notAssingedPages as $notAssignedPage) { ?>
                                    <option class="notAssingedPage" value="<?php echo $notAssignedPage['id']; ?>"><?php  echo $notAssignedPage['title']; ?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" id="pageIds" name="pageIds" value=""/>
                            <input type="hidden" name="id" value="<?php echo $categoryId; ?>"/>
                            <?php if(core\Session::get('user_role') === 'admin') { ?>
                                <input type="submit" name="submit" value="Apply" class="button darkBlueButton margin-t-20"/>
                            <?php } ?>
                        </form>
                    </div>
                </div> 
            <?php } ?> 
        </div>
    </div>
    <div class="col2 col2-L col3-L col4-S">
        <div id="sidebar" class="width-25">
            <div class="sidebarContainer">
                <div class="mainButtonContainer">
                    <?php if(core\Session::get('user_role') === 'admin') { ?>
                        <a class="create button greenButton margin-r-10" id="create" data-id="<?php if(!empty($categoryId) && $categoryId !== null) { echo $categoryId; } ?>">Create</a>
                        <a data-id="<?php echo $categoryId; ?>" data-title="<?php echo $title; ?>" data-description="<?php echo $description; ?>" class="edit button darkBlueButton margin-r-10">Edit</a>
                    <?php } ?>
                        <a href="/admin/posts" class="button blueButton">Back</a>
                </div>
                <span class="title">Categories: </span>
                <form class="searchForm" action="" method="GET">
                    <input type="text" name="search" value="" placeholder="Search"/>
                    <input type="hidden" name="submit" value="Search"/>
                </form>
                <?php if(!empty($categoryId) && $categoryId !== null ) { ?>
                    <table>
                        <?php foreach($categories as $category){ ?>
                            <tr>
                                <td><a href="/admin/categories/<?php echo $category['id']; ?>/apply" class="<?php if($category['id'] == $categoryId) { echo 'active'; } ?>"><?php echo $category['title']; ?></a></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <?php if(core\Session::get('user_role') === 'admin') { ?>
                        <a class="button darkBlueButton margin-t-20 width-50-px" id="delete">Delete</a>
                    <?php } ?>
                <?php } ?>
                <?php if(core\Session::get('user_role') === 'admin') { ?>
                    <form action="/admin/categories/delete" method="POST" class="display-none deleteForm">
                        <select name="deleteIds[]" multiple>
                            <?php foreach($categories as $category) { ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo $category['title']; ?></option>
                            <?php } ?>
                        </select>
                        <input type="submit" name="delete" value="Delete" class="button redButton margin-t-20" onclick="return confirm('Are you sure?');" />
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <div id="modal" class="display-none" data-id="<?php if(!empty($categoryId) && $categoryId !== null) { echo $categoryId; } ?>">
        <div class="container">
            <div id="modalForm">
        </div>
    </div>
</div>

<?php $this->include('footer'); ?>