<?php use core\Csrf; ?>             
<?php use validation\Errors; ?>
        

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
    $this->stylesheet("/assets/css/installation.css");

    $this->include('headerClose');
?>

<div class="container">
<h1>Database setup</h1>    
<p>Database connection and necessary tables will be created!</p>
<form action="" method="POST">
    <div class="form-parts">
    <label for="host">Name of server host: </label>
    <input type="text" name="host"/>
</div>
  <div class="form-parts">                
    <label for="database">Name of database:</label>                
    <input type="text" name="database"/>                     
  </div>           
  <div class="form-parts">                
    <label for="database">Database username:</label>                
    <input type="text" name="username"/>                     
  </div>   
  <div class="form-parts">                
    <label for="database">Database password:</label>                
    <input type="password" name="password"/>                     
  </div>       
  <div class="form-parts">                
    <label for="database">Retype password:</label>                
    <input type="password" name="retypePassword"/>                     
  </div>              
  <div class="form-parts">                
    <button type="submit" name="submit">Setup database</button>                 
    <input type="hidden" name="token" value="<?php Csrf::token("add"); ?>"/>
  </div>                
</form> 
</div>


<?php $this->include("footer"); ?>