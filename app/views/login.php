<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/login.css");

    $this->include('headerClose');
?>
  
<div class="container">
 <span>Login</span>
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
        <?php echo $failedLoginMessage; ?>
      </div>                
    </div>                
    <div class="form-parts">                
      <button type="submit" name="submit">Sign in</button>                 
      <input type="hidden" name="token" value="<?php Csrf::token("add"); ?>"/>
    </div>                
  </form>    
</div>