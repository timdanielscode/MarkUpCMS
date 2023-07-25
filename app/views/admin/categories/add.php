<div class="row">
    <div class="col8">
        <span class="categorySlugAdd">Slug: </span><div id="SUBCATEGORYSLUGCONTAINER"><div id="CATEGORYSLUG" class="listedItem"><?php echo $slug; ?></div><?php if(!empty($assingedSubCategories) && $assingedSubCategories !== null) { ?><?php foreach($assingedSubCategories as $assingedSubCategory) { ?><div id="LISTEDCATEGORY-<?php echo $assingedSubCategory['id']; ?>" class="listedItem"><?php echo $assingedSubCategory['slug']; ?></div><?php } ?><?php } ?></div>
    </div>
    <div class="col4">
        <a id="BACK" class="button">Close</a>
    </div>
</div>

        <div class="row">
            <div class="col6">
                <form action="" method="POST">
                <label>Assigned categories: </label>
                    <select id="ASSINGEDSUBCATEGORYID" name="subcategoryid" multiple>
                        
                        <?php if(!empty($assingedSubCategories) && $assingedSubCategories !== null) { ?>
                            <?php foreach($assingedSubCategories as $assingedSubCategory) { ?>           
                                <option class="assingedSubCategory" value="<?php echo $assingedSubCategory['id']; ?>">          
                                    <?php echo $assingedSubCategory['title']; ?>
                                </option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <select id="NOTASSINGEDSUBCATEGORYID" name="subcategoryid" multiple>
                    <?php foreach($notAssingedSubs as $notAssingedSub) { ?>
                        <option class="notAssingedSubCategory" value="<?php echo $notAssingedSub['id']; ?>">
                            <?php  echo $notAssingedSub['title']; ?>
                        </option>
                    <?php } ?>
                    </select>  
                    <a id="ASSIGNCATEGORY" class="button">Apply</a>
                </form>
            </div>
            <div class="col6">
            <form action="" method="POST">
            <label>Assigned pages: <span class="slugMessage">*Page slug must be unique</span></label>
                <select id="ASSIGNEDPAGEID" name="pageid" multiple>
                    <?php if(!empty($assignedPages) && $assignedPages !== null) { ?>
                        <?php foreach($assignedPages as $assignedPage) { ?>
                            <option class="assingedPage" value="<?php echo $assignedPage['id']; ?>"><?php  echo $assignedPage['title']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <select id="NOTASSIGNEDPAGEID" name="pageid" multiple>
                    <?php foreach($notAssingedPages as $notAssignedPage) { ?>
                        <option class="notAssingedPage" value="<?php echo $notAssignedPage['id']; ?>"><?php  echo $notAssignedPage['title']; ?></option>
                    <?php } ?>
                </select>
                <input type="hidden" id="CATEGORYID" value="<?php echo $id; ?>"/>
                <a id="ASSIGNPAGES" class="button">Apply</a>
                </form>
            </div>
        </div>
        <div id="PAGEMESSAGE"></div>
        <div id="CATEGORYMESSAGE"></div>
   
    
    



    
            
            

