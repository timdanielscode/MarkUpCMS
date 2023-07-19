<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/index.css");
    $this->stylesheet("/assets/css/pagination.css");

    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">
    <div class="headerAndButtonContainer">
        <h1>Css</h1>
        <a class="button" href="/admin/css/create">Add new</a>
    </div>
    <div class="countContainer">
        <span>All</span>
        <span>(<?php echo count($cssFiles); ?>)</span>
    </div>
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search" id="search">
        <input type="hidden" name="submit" value="search">
    </form>
    <table>
        
            <thead>
                <tr>
                    <th>#</th>
                    <th>Filename</th>
                    <th class="width-10">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($cssFiles) && $cssFiles !== null) { ?>
                    <?php foreach($cssFiles as $cssFile) { ?>
                        <tr>
                            <td>
                                <?php echo $cssFile['id']; ?>
                            </td>
                            <td class="width-90">
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/edit" class="font-weight-500"><?php echo $cssFile['file_name'] . $cssFile['extension']; ?></a> |
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/edit" class="font-weight-300">Edit</a> |
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/read" class="font-weight-300">Read</a> |
                                <a href="/admin/css/<?php echo $cssFile['id']; ?>/delete" class="font-weight-300 color-red">Remove</a>
                            </td>
                            <td>
                                <span class="padding-b-2">Created:</span> <span class="font-weight-300"><?php echo $cssFile["date_created_at"] . " " . $cssFile["time_created_at"]; ?></span><br>
                                <span>Updated:</span> <span class="font-weight-300"><?php echo $cssFile["date_updated_at"] . " " . $cssFile["time_updated_at"]; ?></span>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>

                        <tr>
                            <td>-</td>
                            <td>-</td>
                     
                        </tr>

                <?php } ?>
            </tbody>
        </table>
        <?php if($numberOfPages !== null && count($numberOfPages) > 1) { ?>
    <nav class="pagination">
        <ul>
            <?php 
                foreach($cssFiles as $cssFile) {
                    echo '<li class="page-item"><a href="/admin/posts?page='.$cssFile.'">'.$cssFile.'</a></li>';
                }  
            ?>
        </ul>
    </nav>
<?php } ?>
    </div>
<?php 
    $this->include('footer');
?>