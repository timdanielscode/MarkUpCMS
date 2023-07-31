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
    $this->script('/assets/js/media/table.js');
    $this->script('/assets/js/media/edit.js');
    $this->script('/assets/js/media/read.js');

    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">

    <h1>Media library</h1>
    <div class="countContainer">
        <span>All</span>
        <span>(<?php echo $count; ?>)</span> | <a href="/admin/media/create">Upload</a>
    </div>
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search" id="search">
        <input id="searchValue" type="hidden" name="submit" value="<?php if(!empty($search) && $search !== null) { echo $search; } ?>">
    </form>

    <div id="mediaPreview"></div>

    <table>
        <thead>
            <th>#</th>
            <th>Title</th>
            <th>File</th>
            <th>Filename</th>
            <th></th>
            <th>Type</th>
            <th>Size</th>
            <th>Date</th>
        </thead>
        <tbody id="mediaTableBody">
   
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

    <?php if(!empty($numberOfPages) && count($numberOfPages) > 1) { ?>
        <nav id="pagination" class="pagination">
            <ul>
                <?php 
                    foreach($numberOfPages as $page) {

                        echo '<li class="page-item"><a class="PAGE" id="'. $page. '">'.$page.'</a></li>';
                    }  
                ?>
            </ul>
        </nav>
    <?php } ?>
    </div>
<?php 
    $this->include('footer');
?>