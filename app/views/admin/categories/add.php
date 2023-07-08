<div class="container">

    <?php if(!empty($assingedSubCategoryTitle) && $assingedSubCategoryTitle !== null) { ?>
        <div class="assingedSubCategory color-white">

            <p>Category is assinged!</p>


            <p class="categoryTitle">Title: <span class="title"><?php echo $assingedSubCategoryTitle; ?><span></p>
        </div>
    <?php } ?>


   
    
        <form action="" method="POST">

        <div class="modalFormContainer">

            <select id="ASSIGNEDPAGEID" name="pageid" multiple>

                <?php if(!empty($assignedPages) && $assignedPages !== null) { ?>

                    <?php foreach($assignedPages as $assignedPage) { ?>

                        <option class="assingedPage" value="<?php echo $assignedPage['id']; ?>"><?php  echo $assignedPage['title']; ?></option>

                    <?php } ?>
                
                <?php } else { ?>

                    <p>No assigned pages yet.<p>

                <?php } ?>

            </select>

        </div>

        <div class="modalFormContainer">

            <select id="NOTASSIGNEDPAGEID" name="pageid" multiple>

                <?php foreach($notAssingedPages as $notAssignedPage) { ?>

                    <option class="notAssingedPage" value="<?php echo $notAssignedPage['id']; ?>"><?php  echo $notAssignedPage['title']; ?></option>

                <?php } ?>

            </select>

        </div>

        <input type="hidden" id="CATEGORYID" value="<?php echo $id; ?>"/>
    </form>



    <a id="ASSIGNPAGES" class="button">Assign pages</a>
    



    <form action="" method="POST">

        <select id="CATEGORIES" name="title" multiple>

        <?php foreach($categories as $category) { ?>

            <option class="category" value="<?php echo $category['id']; ?>"><?php  echo $category['title']; ?></option>

        <?php } ?>

</select>                



    </form>


    <a id="ASSIGNCATEGORY" class="button">Assign category</a>
            
            <a id="BACK" class="button">Back</a>
</div>
