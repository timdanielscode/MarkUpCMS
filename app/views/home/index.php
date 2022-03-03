<?php
    $this->include('header');
    $this->include('navbar');
?>
<div class="container">
    <h1 class="mb-3 mt-5">Framework demo</h1>
    <p>With this demo you can look and feel how the framework functions.</p>
    <h2 class="mb-3 mt-5">Installation proccess</h2>
    <p>After you've downloaded or cloned the respository, you should rename the config-example.ini to config.ini. This is the file that holds the configuration values for setting up the database connection. The file path of config.ini is located in the /config/database/ folder. Next, add your database credentials to the config.ini. You can always visit <a href="https://independentphp.com/" target="_blank">independentphp.com</a> for more information about the installation.</p>
    <h2 class="mb-3 mt-5">Login & register system</h2>
    <p>The demo comes with a login/register system. You can simply make use of this functionality after you've added the correct tables and columns to the database. To make this process a bit easier, you can simply look for the file named as 0001_import.sql. The file path of 0001_import.sql is located in the /database/import/ folder. When you open this file you will see the sql statements which you can import to the database.</p>
</div>

<?php 
    $this->include('footer');
?>
