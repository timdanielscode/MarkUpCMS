<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar');
?>

<div class="con">
    
    <h1 class="my-5 text-color-pri"><?php echo $current['username']; ?></h1>
    <table class="table table-striped mt-3 w-100">
        <thead>
            <tr>
                <th>#</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
           
            <tr>
                <td>
                    Id
                </td>
                <td>
                    <?php echo $current['id']; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Username
                </td>
                <td>
                    <?php echo $current['username']; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Email
                </td>
                <td>
                    <?php echo $current['email']; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Role
                </td>
                <td>
                    <?php echo $current['name']; ?>
                </td>
            </tr>
        </tbody>
    </table>
    <a class="button margin-t-50" href="/admin/users">Back</a>
</div>

<?php 
    $this->include('footer');
?>


