<div class="container">

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

        <select id="ASSINGEDSUBCATEGORYID" name="subcategoryid" multiple>

            <?php foreach($assingedSubCategories as $assingedSubCategory) { ?>
                            
                <option class="assingedSubCategory" value="<?php echo $assingedSubCategory['id']; ?>">
                                
                    <?php echo $assingedSubCategory['title']; ?>

                </option>

            <?php } ?>
        </select>
    
        <select id="NOTASSINGEDSUBCATEGORYID" name="subcategoryid" multiple>

        <?php foreach($notAssingedSubs as $notAssingedSub) { ?>

            <option class="notAssingedSubCategory" value="<?php echo $notAssingedSub['id']; ?>">

                <?php  echo $notAssingedSub['title']; ?>
                
            </option>

        <?php } ?>

</select>                



    </form>


    <a id="ASSIGNCATEGORY" class="button">Assign category</a>
            
            <a id="BACK" class="button">Back</a>
</div>
