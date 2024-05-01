<!--
    - to show a 404 page
-->

<?php $this->include('openHeadTag'); ?>
    <?php $this->title('404'); ?>
    <?php $this->stylesheet("/assets/css/style.css"); ?>
    <?php $this->stylesheet("/assets/css/navbar.css"); ?>
    <?php $this->stylesheet("/assets/css/404.css"); ?>
<?php $this->include('closeHeadTagAndOpenBodyTag'); ?>

<?php if(core\Session::exists('logged_in') && core\Session::exists('logged_in') === true) { ?>
    <?php $this->include('navbar'); ?>
<?php } ?>

<h1>404</h1>

<?php $this->include('footer'); ?>
