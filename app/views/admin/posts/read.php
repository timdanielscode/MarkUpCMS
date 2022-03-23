<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar');
?>

    <?php echo html_entity_decode($post["body"]); ?>
