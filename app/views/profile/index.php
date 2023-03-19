<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar');
?>

<?php use core\Session; ?>

<a href="/logout">Logout</a>

<h1>User details</h1>

<?php if(Session::get("logged_in") === true) { ?>    

  <p><span>Username:</span> <span><?php echo Session::get("username"); ?></span></p>
  
  <p><span>Role type:</span> <span><?php echo Session::get("user_role"); ?></span></p>

<?php } ?>


<?php 
    $this->include('footer');
?>