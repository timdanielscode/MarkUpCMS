<?php use core\Session; ?>
<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/navbar.css");

    $this->include('navbar');
?>

<?php 
    $this->include('footer');
?>