<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/navbar.css");

    $this->include('headerClose');
    $this->include('navbar');
?>
    <div class="margin-t-50"></div>
    <?php echo html_entity_decode($menu["content"]); ?>
