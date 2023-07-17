<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/users.css");
    $this->stylesheet("/assets/css/sidebar.css");

    $this->include('headerClose');
    $this->include('navbar');
?>

<div class="read-container">
    <div class="row">
        <div class="col10 col9-L">


            <h1><?php echo $current['username']; ?></h1>


            <table>
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
        </div>
        <div class="col2 col3-L">
            <div id="sidebar" class="width-25-L">
            <div class="sidebarContainer">
                    <div class="mainButtonContainer">
                <a class="button" href="/admin/users">Back</a>
</div>
</div>
            </div>

        </div>

    </div>

    
</div>

<?php 
    $this->include('footer');
?>


