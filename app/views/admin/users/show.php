<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar');
?>

<div class="con">
    
    <h1 class="my-5 text-color-pri"><?php echo $current[0]['username']; ?></h1>
    <table class="table table-striped mt-3 w-100">
        <thead>
            <tr>
                <th>#</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($current as $value) { ?>
                <tr>
                    <td>
                        Id
                    </td>
                    <td>
                        <?php echo $value['id']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Username
                    </td>
                    <td>
                        <?php echo $value['username']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Email
            </td>
                    <td>
                        <?php echo $value['email']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Role
                    </td>
                    <td>
                        <?php echo $value['name']; ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a class="button margin-t-50" href="/admin/users">Back</a>
</div>

<?php 
    $this->include('footer');
?>


