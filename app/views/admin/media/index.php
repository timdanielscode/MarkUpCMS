<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use extensions\Pagination; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/index.css");
    $this->stylesheet("/assets/css/pagination.css");
    $this->stylesheet("/assets/css/media.css");

    $this->script('/assets/js/ajax.js');
    $this->script('/assets/js/media.js');
    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">
    <div class="headerAndButtonContainer">
        <h1>Media</h1>
        <a class="button" href="/admin/media/create">Add new</a>
    </div>
    <div class="countContainer">
        <span>All</span>
        <span>(<?php echo $count; ?>)</span>
    </div>
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search" id="search">
        <input type="hidden" name="submit" value="search">
    </form>

    <div id="mediaPreview"></div>

    <table>
        <thead>
            <th>Title</th>
            <th>File</th>
            <th>Filename</th>
            <th>Type</th>
            <th>Size</th>
            <th>Date</th>
        </thead>
        <tbody id="mydata">
   
        </tbody>

    </table>

    <div id="modal" class="display-none">
        <div class="mediaModalFormContainer">
            <form id="mediaModelForm">

            </form>
        </div>
        <a href="#" id="updateMediaModal" class="button">Update</a>
        <a href="#" id="mediaModalClose" class="button">Close</a>
    </div>


        <?php if(count($numberOfPages) > 1) { ?>
            <nav class="paginationPosts">
                <ul class="pagination">
                    <li class="page-item previous"><a href="/admin/medias?back=1">Previous</a></li>
                    <?php 
                        foreach($numberOfPages as $page) {
                            echo '<li class="page-item"><a href="/admin/medias?page='.$page.'">'.$page.'</a></li>';
                       }  
                    ?>
                    <li class="page-item next"><a href="/admin/medias?next=1">Next</a></li>
                </ul>
            </nav>
        <?php } ?>
    </div>
<?php 
    $this->include('footer');
?>