<?php use core\Csrf; ?>             
<?php use validation\Errors; ?>
                
<h1>Register</h1>    
<p>Welcome, create an admin account to build your website!</p>

<form action="" method="POST">
  <div class="form-parts">
    <label for="username">Username:</label>
    <input type="text" name="username"/>
    <div class="form-rules">
      <?php echo Errors::get($rules, "username"); ?>
    </div>                
  </div>                
  <div class="form-parts">                
    <label for="email">Email:</label>                
    <input type="email" name="email"/>                
    <div class="form-rules">                
      <?php echo Errors::get($rules, "email"); ?>                
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
    <label for="retypePassword">Retype password:</label>                
    <input type="password" name="retypePassword"/>                
    <div class="form-rules">                
      <?php echo Errors::get($rules, "retypePassword"); ?>                
    </div>                
  </div>                
  <div class="form-parts">                
    <button type="submit" name="submit">Create account</button>                 
    <input type="hidden" name="token" value="<?php Csrf::token("add"); ?>"/>
  </div>                
</form> 