<?php 
    $this->include('header');
    $this->include('navbar');
?>

<div class="container">
    
    <h1 class="my-5 text-color-pri"><?php echo $current[0]['username']; ?></h1>
    <table class="table table-striped mt-3 w-100">
        <thead>
            <tr>
                <th scope="col" class="w-25">#</th>
                <th scope="col">Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($current as $value) { ?>
                <tr>
                    <th>
                        Id
                    </th>
                    <th>
                        <?php echo $value['id']; ?>
                    </th>
                </tr>
                <tr>
                    <th>
                        Username
                    </th>
                    <th>
                        <?php echo $value['username']; ?>
                    </th>
                </tr>
                <tr>
                    <th>
                        Email
                    </th>
                    <th>
                        <?php echo $value['email']; ?>
                    </th>
                </tr>
                <tr>
                    <th>
                        Role
                    </th>
                    <th>
                        <?php echo $value['name']; ?>
                    </th>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a class="btn bg-color-sec text-white" href="/admin/users">Back</a>
</div>

<?php 
    $this->include('footer');
?>


