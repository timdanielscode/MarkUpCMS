<?php use validation\Errors; ?>
<?php use core\Csrf; ?>

<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar'); 
?>

<div class="container my-179">


<form action="" method="POST">
  <div class="form-parts">                
    <label for="username">Username:</label>                
    <input type="text" name="username"/>                
    <div class="form-rules">                
      <?php echo Errors::get($rules, "username"); ?>                
    </div>                
  </div>                
  <div class="form-parts">                
    <label for="password">Password:</label>                
    <input type="password" name="password"/>                
    <div class="form-rules">                
      <?php echo Errors::get($rules, "password"); ?>                
    </div>                
  </div>                
  <div class="form-parts">                
    <button type="submit" name="submit">Login</button>                 
    <input type="hidden" name="token" value="<?php Csrf::token("add"); ?>"/>
  </div>                
</form>    
</div>

<?php 
    $this->include('footer');
?>