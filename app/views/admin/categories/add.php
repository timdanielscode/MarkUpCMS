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

</div>
