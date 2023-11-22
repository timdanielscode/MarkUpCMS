<?php use core\Csrf; ?>             
<?php use validation\Errors; ?>
        

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/installation.css");

    $this->include('headerClose');
?>


<div class="container">
<h1>Register</h1>    
<p>Welcome, create an admin account to build your website!</p>
<form action="" method="POST">
  <div class="form-parts">
    <label for="username">Username:</label>
    <input type="text" name="username"/>
    <div class="error-messages margin-b-10 margin-tm-10 font-size-14">
      <span><?php echo Errors::get($rules, "username"); ?></span>
    </div>                
  </div>                
  <div class="form-parts">                
    <label for="email">Email:</label>                
    <input type="email" name="email"/>                
    <div class="error-messages margin-b-10 margin-tm-10 font-size-14">              
      <span><?php echo Errors::get($rules, "email"); ?></span>                
    </div>                
  </div>                
  <div class="form-parts">                
    <label for="password">Password:</label>                
    <input type="password" name="password"/>                
    <div class="error-messages margin-b-10 margin-tm-10 font-size-14">               
      <span><?php echo Errors::get($rules, "password"); ?></span>                
    </div>                
  </div>                
  <div class="form-parts">                
    <label for="retypePassword">Retype password:</label>                
    <input type="password" name="retypePassword"/>                
    <div class="error-messages margin-b-10 margin-tm-10 font-size-14">              
      <span><?php echo Errors::get($rules, "retypePassword"); ?></span>                
    </div>                
  </div>                
  <div class="form-parts">                
    <button type="submit" name="submit">Create account</button>                 
    <input type="hidden" name="token" value="<?php Csrf::token(); ?>"/>
  </div>                
</form> 
</div>


<?php $this->include("footer"); ?>