<!-- 
    - to have an overview of assinged and not assinged sub categories per category (after clicking on the 'apply' link in table overview)
    - to have an overview of assinged pages and not assinged pages per category (after clicking on the 'apply' link in table overview)
    -
    - FOR TYPE OF ADMIN USER
    -
    - to create a selection of sub categories to submit and assign or detach from/to category (after clicking on the 'apply' link in table overview)
    - to create a selection of pages to submit and assign or detach from/to category (after clicking on the 'apply' link in table overview)
    - to change the category slug value to submit and update
-->

<?php $this->include('openHeadTag'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/categories.css"); ?>
    <?php $this->stylesheet("/assets/css/pagination.css"); ?>
    <?php $this->stylesheet("/assets/css/sidebar.css"); ?>
    <?php $this->script('/assets/js/categories/apply/Category.js', true); ?>
    <?php $this->script('/assets/js/categories/apply/Page.js', true); ?>
    <?php $this->script('/assets/js/categories/apply/main.js', true); ?>
    <?php $this->script('/assets/js/categories/main.js', true); ?>
<?php $this->include('closeHeadTagAndOpenBodyTag'); ?>

<?php $this->include('navbar'); ?>

<div class="row">
    <div class="col10 col10-L- col9-L col8-S">
        <div class="edit-container">
            <div id="slug"><?php if(!empty($assingedSubCategories) && $assingedSubCategories !== null) { ?><?php foreach($assingedSubCategories as $assingedSubCategory) { ?><div class="listedItem"><?php echo $assingedSubCategory['slug']; ?></div><?php } ?><?php } ?><div class="listedItem"><?php echo $slug; ?></div></div>
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
                        <input id="submitCategories" type="submit" name="submit" class="display-none"/>
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
                            <input id="submitPages" type="submit" name="submit" class="display-none"/>
                    </form>
                </div>
            </div>  
        </div>
    </div>
    <div class="col2 col2-L col3-L col4-S">
        <div id="sidebar" class="width-25">
            <div class="sidebarContainer">
                <div class="mainButtonContainer">
                <?php if(core\Session::get('user_role') === 'admin') { ?>
                    <label for="submitCategories" class="button greenButton margin-r-10">Categories</label>
                    <label for="submitPages" class="button blueButton margin-r-10">Pages</label>
                <?php } ?>
                    <a href="/admin/categories" class="button darkBlueButton margin-r-10">Back</a>
                </div>
                <form action="/admin/categories/slug" method="POST" id="slugForm">
                    <label>Slug: </label>
                    <input type="text" name="slug" value="<?php echo ltrim($slug, '/'); ?>"/>
                    <input type="hidden" name="id" value="<?php echo $categoryId; ?>" />
                    <input type="submit" name="submitSlug" value="Update" class="button lightButton"/>
                </form>
                <span class="title">Categories: </span>
                <table>
                    <?php foreach($categories as $category){ ?>
                        <tr>
                            <td><a href="/admin/categories/<?php echo $category['id']; ?>/apply"><?php echo $category['title']; ?></a></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->include('footer'); ?>