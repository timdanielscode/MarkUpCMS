<div class="container">



        <form action="" method="POST">

                <select id="NOTASSIGNEDPAGEID" name="pageid" multiple>


                <?php foreach($notAssingedPages as $notAssignedPage) { ?>

                    <option  value="<?php echo $notAssignedPage['id']; ?>"><?php  echo $notAssignedPage['title']; ?></option>

                <?php } ?>


                </select>

                    <input type="hidden" id="CATEGORYID" value="<?php echo $id; ?>"/>
            </form>





</div>
