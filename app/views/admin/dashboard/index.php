<?php use parts\Session; ?>
<?php 
    $this->include('header');
    $this->include('navbar');
?>
<div class="container">
<h1>Welcome <?php echo Session::get('username'); ?></h1>
</div>
<?php 
    $this->include('footer');
?>