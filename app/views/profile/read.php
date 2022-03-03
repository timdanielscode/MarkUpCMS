<?php use parts\Session; ?>
<?php use parts\Alert; ?>

<?php 
    $this->include('header');
    $this->include('navbar');
?>

<div class="custom-container my-179">
    <?php if(Session::exists("updated")) { ?>
        <div class="my-3 w-75 mx-auto"><?php echo Alert::display("success", "updated"); ?></div>
    <?php Session::delete('updated'); } ?>

    <div class="justify-content-center">
        <div class="mx-auto w-50">
            <h1 class="text-left text-color-sec my-5"><?php echo $user['username']; ?></h1>
        </div>
        <table class="table table-bordered mx-auto w-50">

            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Id</th>
                    <th><?php echo $user['id']; ?></th>
                </tr>
                <tr>
                    <th>Username</th>
                    <th><?php echo $user['username']; ?></th>
                </tr>
                <tr>
                    <th>Email</th>
                    <th><?php echo $user['email']; ?></th>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="mx-auto w-50">
        <a href="/profile/<?php echo $user['username']; ?>/edit" class="mt-3 btn bg-color-sec text-white btn-lg">Edit</a>
    </div>
</div>

<?php 
    $this->include('footer');
?>