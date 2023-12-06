<!-- 
    - to add a username and password to submit and authenticate to login
 --> 

<?php $this->include('openHeadTag'); ?>  
  <?php $this->stylesheet("/assets/css/style.css"); ?>
  <?php $this->stylesheet("/assets/css/login.css"); ?>
<?php $this->include('closeHeadTagAndOpenBodyTag'); ?>

<div class="container">
    <span>Login</span>
    <form action="" method="POST">
        <div class="form-parts">                
            <label for="username">Username:</label>              
            <input type="text" name="username"/>             
        <div class="form-rules">                
            <?php echo validation\Errors::get($rules, "username"); ?>                
        </div>                
        </div>                
        <div class="form-parts">                
            <label for="password">Password:</label>                
            <input type="password" name="password"/>
        <div class="form-rules">               
            <?php echo validation\Errors::get($rules, "password"); ?> 
            <?php echo $failedLoginMessage; ?>
        </div>                
        </div>                
        <div class="form-parts">             
            <button type="submit" name="submit">Sign in</button>             
            <input type="hidden" name="token" value="<?php core\Csrf::token(); ?>"/>
        </div>                
    </form>    
</div>