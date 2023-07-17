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
            
        </div>
        <div class="col2 col3-L">
            <div id="sidebar" class="width-25-L">
                <div class="sidebarContainer">
                    <div class="mainButtonContainer">
                        <a class="button" href="/admin/users">Back</a>
                    </div>
                    <span class="text">Username: </span>
                    <span class="data"><?php echo $current['username']; ?></span>
                    <span class="text">Email: </span>
                    <span class="data"><?php echo $current['email']; ?></span>
                    <span class="text">Role: </span>
                    <span class="data"><?php echo $current['name']; ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    $this->include('footer');
?>


