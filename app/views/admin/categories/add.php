<div class="container">

    <div class="row">

        <div class="col6">

            <?php foreach($assignedPages as $assignedPage) { ?>

                <p><?php  echo $assignedPage['title']; ?><p>

            <?php } ?>

        </div>

        <div class="col6">

            <form action="" method="POST">

                <select name="notAssinged" multiple>


                <?php foreach($notAssingedPages as $notAssignedPage) { ?>

                    <option value="<?php echo $notAssingedPage['id']; ?>"><?php  echo $notAssignedPage['title']; ?></option>

                <?php } ?>


                </select>


            </form>



        </div>


    </div>




</div>
